<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<h2>Quotation List</h2>

<!-- ✅ ADD THIS BUTTON -->
<a href="<?= base_url('index.php/quotation/create') ?>" class="create-btn">
    + Create Quotation
</a>

<form id="deleteForm">

<table class="q-table">
<tr>
    <th><input type="checkbox" id="selectAll"></th> <!-- ✅ NEW -->
    <th>ID</th>
    <th>Client Name</th> <!-- NEW -->
    <th>Total</th>
    <th>Currency</th>
    <th>Status</th> <!-- NEW -->
    <th>Action</th>
</tr>

<?php foreach ($quotations as $q): ?>
<tr>

    <!-- ✅ CHECKBOX -->
    <td>
        <input type="checkbox" name="ids[]" value="<?= $q['id'] ?>">
    </td>

    <td><?= $q['quotation_id'] ?></td>
<td>
<?php 
$clientModel = new \App\Models\ClientModel();
$client = $clientModel->find($q['client_id']);

if (!empty($client) && is_array($client)) {
    echo ($client['first_name'] ?? '') . ' ' . ($client['last_name'] ?? '');
} else {
    echo 'N/A';
}
?>
</td>

    <td><?= $q['total_price'] ?></td>
    <td><?= $q['currency'] ?></td>

     <td><?= $q['status'] ?></td>

    <td>
        <div class="action-menu">
    <button class="action-btn">⋮</button>

    <div class="action-dropdown">
        <a href="<?= base_url('index.php/quotation/edit/'.$q['id']) ?>">Edit</a>

        <a href="<?= base_url('index.php/quotation/download/'.$q['id']) ?>">
            Download PDF
        </a>

        <a href="<?= base_url('index.php/quotation/delete/'.$q['id']) ?>"
           onclick="return confirm('Are you sure you want to delete this quotation?')">
            Delete
        </a>
    </div>
</div>
    </td>

</tr>
<?php endforeach; ?>

</table>

<br>

<!-- ✅ DELETE BUTTON -->
<button type="button" id="deleteSelected" class="create-btn">
    Delete Selected
</button>

</form>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Select All
    document.getElementById("selectAll").addEventListener("click", function () {
        document.querySelectorAll('input[name="ids[]"]').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Delete Selected
    document.getElementById("deleteSelected").addEventListener("click", function () {

        let ids = [];

        document.querySelectorAll('input[name="ids[]"]:checked').forEach(el => {
            ids.push(el.value);
        });

        if(ids.length === 0){
            alert("Select at least one quotation");
            return;
        }

        if(!confirm("Delete selected quotations?")) return;

        fetch("<?= base_url('quotation/deleteMultiple') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.text())
        .then(data => {
            console.log(data);
            location.reload();
        });

    });

});
</script>
<script>
document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();

        document.querySelectorAll('.action-dropdown').forEach(d => d.style.display = 'none');

        let dropdown = this.nextElementSibling;
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    });
});

document.addEventListener('click', function() {
    document.querySelectorAll('.action-dropdown').forEach(d => d.style.display = 'none');
});
</script>
<?= $this->endSection(); ?>
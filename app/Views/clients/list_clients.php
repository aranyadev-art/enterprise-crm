<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<a href="<?= base_url('clients/create') ?>" class="btn add-btn" style="margin-bottom:15px; display:inline-block;">
    + Add Client
</a>



<form id="deleteForm">

<table class="client-table">

<tr>
    <th><input type="checkbox" id="selectAll"></th> <!-- ✅ NEW -->
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>City</th>
    <th>Action</th>
</tr>

<?php foreach($clients as $client): ?>
<tr>

    <!-- ✅ CHECKBOX -->
    <td>
        <input type="checkbox" name="ids[]" value="<?= $client['id'] ?>">
    </td>

    <td><?= $client['id'] ?></td>
    <td><?= $client['first_name'].' '.$client['last_name'] ?></td>
    <td><?= $client['email'] ?></td>
    <td><?= $client['phone'] ?></td>
    <td><?= $client['city'] ?></td>

    <td>
        <a class="btn edit-btn" href="<?= base_url('client/edit/'.$client['id']) ?>">Edit</a>

        <a class="btn delete-btn" 
           href="<?= base_url('client/delete/'.$client['id']) ?>"
           onclick="return confirm('Are you sure?')">
           Delete
        </a>
    </td>

</tr>
<?php endforeach; ?>

</table>

<br>

<!-- ✅ DELETE BUTTON -->
<button type="button" id="deleteSelected" class="btn delete-btn">
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
            alert("Select at least one client");
            return;
        }

        if(!confirm("Delete selected clients?")) return;

        fetch("<?= base_url('client/deleteMultiple') ?>", {
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

<?= $this->endSection(); ?>
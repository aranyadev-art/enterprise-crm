<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<h2 class="fac-title">Factory List</h2>

<a href="<?= base_url('factory/create') ?>" class="fac-add-btn">
    + Add Entry
</a>

<div class="fac-table-container">
<table class="fac-table">
<tr>
    <th><input type="checkbox" id="selectAll"></th> 
    <th>ID</th>
    <th>Employee</th>
    <th>Order No</th>
    <th>Shipping No</th>
    <th>Metal</th>
    <th>Stone</th>
    <th>Final</th>
    <th>Status</th>
    <th>Date</th>
</tr>

<?php foreach($factory as $row): ?>
<tr data-id="<?= $row['id'] ?>">

     <td>
        <input type="checkbox" class="rowCheckbox" name="ids[]" value="<?= $row['id'] ?>">
    </td>
    <td><?= $row['id'] ?></td>
    <td><?= $row['employee_name'] ?></td>
    <td><?= $row['order_no'] ?></td>
    <td><?= $row['shipping_no'] ?></td>
    <td><?= $row['metal_weight'] ?></td>
    <td><?= $row['stone_ct_weight'] ?></td>
    <td><?= $row['final_weight'] ?></td>

    <td class="<?= $row['completed'] ? 'fac-status-done' : 'fac-status-pending' ?>">
        <?= $row['completed'] ? 'Done' : 'Pending' ?>
    </td>

    <td><?= $row['completed_date'] ?></td>
</tr>
<?php endforeach; ?>

</table>
<button type="button" class="ul-delete-all" id="deleteSelected">
    Delete Selected
</button>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.rowCheckbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    document.getElementById('deleteSelected').addEventListener('click', function () {

        let ids = [];

        document.querySelectorAll('input[name="ids[]"]:checked').forEach(el => {
            ids.push(el.value);
        });

        if (ids.length === 0) {
            alert("Please select at least one employee");
            return;
        }

        // ✅ ONLY ONE CONFIRM POPUP
        if (!confirm("Are you sure you want to delete selected employee(s)?")) return;

        fetch("<?= base_url('factory/deleteMultiple') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {

                // ✅ DIRECTLY REMOVE ROWS (NO SECOND ALERT)
                ids.forEach(id => {
                    let row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) {
                        row.remove();
                    }
                });

            } else {
                alert("Delete failed");
            }

        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong");
        });

    });

});
</script>
<?= $this->endSection(); ?>
<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<div class="cad-table-container">

    <h2 class="cad-title">All CAD Records</h2>

<a href="<?= base_url('sales') ?>" class="btn btn-secondary mb-3">
    ← Back to Sales
</a>
<a href="<?= base_url('index.php/cod/create') ?>" class="cad-btn-create">
    + Create New COD
</a>


<table class="cad-table">

<tr>
<th><input type="checkbox" id="selectAll"></th> <!-- ✅ NEW -->   
<th>CAD Code</th>
<th>Start</th>
<th>End</th>
<th>Duration</th>
<th>Design</th>
<th>CPX Sent</th>
<th>Comment</th>
</tr>

<?php foreach($records as $row){ ?>

<tr data-id="<?= $row['id'] ?>"> <!-- ✅ IMPORTANT -->

<td>
    <input type="checkbox" class="rowCheckbox" name="ids[]" value="<?= $row['id'] ?>">
</td>

<td><?= $row['cad_code'] ?></td>
<td><?= $row['start_time'] ?></td>
<td><?= $row['end_time'] ?></td>
<td><?= $row['duration'] ?></td>
<td>
<a href="<?= base_url('cod/download/' . $row['id']) ?>" class="cad-btn-download">
    Download
</a>
</td>

<td>
<?php if($row['cpx_sent']){ ?>
<span class="cad-badge-yes">Yes</span>
<?php } else { ?>
<span class="cad-badge-no">No</span>
<?php } ?>
</td>

<td><?= $row['comment'] ?></td>

</tr>

<?php } ?>

</table>



</div>

<button type="button" id="deleteSelected" class="cad-btn-delete">
    Delete Selected
</button>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Select All
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.rowCheckbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // Delete Selected
    document.getElementById('deleteSelected').addEventListener('click', function () {

        let ids = [];

        document.querySelectorAll('input[name="ids[]"]:checked').forEach(el => {
            ids.push(el.value);
        });

        if (ids.length === 0) {
            alert("Please select at least one record");
            return;
        }

        if (!confirm("Are you sure you want to delete selected records?")) return;

        fetch("<?= base_url('cod/deleteMultiple') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === "success") {

                ids.forEach(id => {
                    let row = document.querySelector(`tr[data-id="${id}"]`);
                    if (row) row.remove();
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
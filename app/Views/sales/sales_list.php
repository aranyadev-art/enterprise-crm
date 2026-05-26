<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<div class="sal-page">

<div class="sal-header">
    <h2 class="sal-title">Sales Management</h2>

    <a href="<?= base_url('sales/add') ?>" class="btn btn-primary">
        + Add Sales
    </a>
</div>

<!-- Search -->
<form method="get" action="<?= base_url('sales') ?>" class="sal-search-form">

    <input type="text"
           name="tracking"
           class="sal-input"
           placeholder="Search Tracking Number"
           value="<?= esc($_GET['tracking'] ?? '') ?>">

    <button type="submit" class="sal-btn-search">Search</button>

    <a href="<?= base_url('sales') ?>" class="sal-btn-reset">Reset</a>

</form>

<!-- Table -->
<div class="sal-table-container">

<table class="sal-table">

<thead>
<tr>
<th><input type="checkbox" id="selectAll"></th>
<th>ID</th>
<th>Client</th>
<th>CAD Designer</th>
<th>Quantity</th>
<th>Status</th>
<th>Tracking</th>
</tr>
</thead>

<tbody>

<?php if(!empty($sales)) { ?>
<?php foreach($sales as $row){ ?>

<tr>
<td>
    <input type="checkbox" name="ids[]" value="<?= $row['id'] ?>">
</td>

<td><?= $row['id'] ?></td>

<td>
<?= !empty($row['client_first']) 
    ? $row['client_first'].' '.$row['client_last'] 
    : 'N/A' ?>
</td>

<!-- ✅ CAD DESIGNER COLUMN -->
<td style="width:180px; height:180px;">

<?php 
$imagePath = 'uploads/'.$row['design_image'];
?>

<div style="width:180px; height:180px;">

    <?php if(!empty($row['design_image']) && file_exists(FCPATH.$imagePath)): ?>
        
        <a href="<?= base_url($imagePath) ?>" target="_blank" style="display:block; width:100%; height:100%;">
            
            <img src="<?= base_url($imagePath) ?>" 
                 style="width:100%; height:100%; object-fit:cover; border-radius:6px;">
        
        </a>

    <?php else: ?>
        <span style="color:#999;">No Image</span>
    <?php endif; ?>

</div>

</td>

<td><?= $row['quantity'] ?></td>

<td>
<?php if($row['status']=="Pending"){ ?>
    <span class="sal-badge warning">Pending</span>
<?php } else { ?>
    <span class="sal-badge success"><?= $row['status'] ?></span>
<?php } ?>
</td>

<td>
<span class="sal-badge primary">
<?= $row['tracking_number'] ?>
</span>
</td>


</tr>

<?php } ?>
<?php } else { ?>

<tr>
<td colspan="7" style="text-align:center;">No Sales Found</td>
</tr>

<?php } ?>

</tbody>

</table>

<br>

<button type="button" id="deleteSelected" class="sal-btn-reset">
    Delete Selected
</button>

</div>

</div>

<!-- ✅ SINGLE CLEAN SCRIPT -->
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
            alert("Select at least one sale");
            return;
        }

        if(!confirm("Delete selected sales?")) return;

        fetch("<?= base_url('sales/deleteMultiple') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.text())
        .then(data => {
            location.reload();
        });

    });

});
</script>

<?= $this->endSection(); ?>
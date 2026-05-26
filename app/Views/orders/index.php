<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<!-- Page CSS -->
<link rel="stylesheet" href="<?= base_url('css/order.css') ?>">

<h2>Orders</h2>

<div class="top-buttons">
    <a href="<?= base_url('orders/create') ?>" class="btn add-btn">
        + Create Order
    </a>
</div>

<table>

<tr>
    <th><input type="checkbox" id="selectAll"></th> <!-- ✅ ADD -->
    <th>ID</th>
    <th>Order No</th>
    <th>Sales Name</th>
    <th>CAD Name</th>
    <th>Client Name</th>
    <th>Quotation ID</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php if(!empty($orders)): ?>
    <?php foreach($orders as $order): ?>
        <tr data-id="<?= $order['id'] ?>"> <!-- ✅ IMPORTANT -->

            <td>
                <input type="checkbox" class="rowCheckbox" name="ids[]" value="<?= $order['id'] ?>">
            </td>
            <td><?= $order['id'] ?></td>
            <td><?= $order['order_number'] ?></td>
            <td><?= $order['sales_name'] ?></td>
            <td><?= $order['cad_name'] ?></td>
            <td><?= $order['client_name'] ?></td>
            <td><?= $order['quotation_id'] ?></td>
<td>
    <?php if($order['status'] == 'Completed'): ?>
        <span style="background:#28a745; color:white; padding:5px 10px; border-radius:5px;">
            ✅ Completed
        </span>

    <?php elseif($order['status'] == 'In Progress'): ?>
        <span style="background:#17a2b8; color:white; padding:5px 10px; border-radius:5px;">
            🔄 In Progress
        </span>

    <?php elseif ($order['status'] == 'Pending') :?>
        <span style="background:#6c757d; color:white; padding:5px 10px; border-radius:5px;">
            🕒 Pending
        </span>
    <?php endif; ?>
</td>   
 <td>
<?php if($order['status'] != 'Completed'): ?>
    <a class="btn edit-btn"
       href="<?= base_url('factory/create/'.$order['id']) ?>">
       Send to Factory
    </a>
<?php else: ?>
    <span style="color:gray;">Already Completed</span>
<?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="8">No Orders Found</td>
</tr>
<?php endif; ?>

</table>

<button type="button" id="deleteSelected" class="btn delete-btn">
    Delete Selected
</button>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Select All
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('.rowCheckbox').forEach(cb => {
            cb.checked = this.checked;
        });
    });

    // ✅ Delete Selected
    document.getElementById('deleteSelected').addEventListener('click', function () {

        let ids = [];

        document.querySelectorAll('input[name="ids[]"]:checked').forEach(el => {
            ids.push(el.value);
        });

        if (ids.length === 0) {
            alert("Please select at least one order");
            return;
        }

        if (!confirm("Are you sure you want to delete selected orders?")) return;

        fetch("<?= base_url('orders/deleteMultiple') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ ids: ids })
        })
        .then(res => res.text()) // ✅ FIXED
        .then(data => {
            console.log(data);
            location.reload(); // ✅ IMPORTANT
        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong");
        });

    });

});
</script>

<?= $this->endSection(); ?>
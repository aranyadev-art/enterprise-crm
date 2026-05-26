<?php
/** @var string $tracking_number */
/** @var string $gfj_no */
/** @var array $clients */
?>
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<form method="post" action="<?= base_url('shipping/store') ?>" class="shipping-form">

<!-- ROW 1 -->
<div class="shipment-box">
<!-- ROW 1 -->
<div class="shipment-line">
    <div class="shipment-field">
        <label>GFJ No</label>
        <input type="text" value="<?= $gfj_no ?>" readonly>
    </div>

    <div class="shipment-field">
        <label>Tracking No</label>
        <input type="text" value="<?= $tracking_number ?>" readonly>
    </div>
</div>

<!-- ROW 2 -->
<div class="shipment-line">
    <div class="shipment-field">
        <label>Product Name</label>
        <input type="text" name="product_name" placeholder="Enter product name">
    </div>

    <div class="shipment-field">
        <label>Sales Rep</label>
        <input type="text" name="sales_rep" placeholder="Enter Sales Rep Name">
    </div>

    <div class="shipment-field">
        <label>Client Name</label>
        <select name="client_id">
            <option value="">Select Client</option>

            <?php foreach($clients as $client): ?>
                <option value="<?= $client['id'] ?>">
                    <?= $client['first_name'] . ' ' . $client['last_name'] ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>
</div>


<!-- ROW 3 -->
<div class="shipment-line">
    <div class="shipment-field">
        <label>Final Quotation</label>
        <input type="text" name="final_quotation">
    </div>

    <div class="shipment-field">
        <label>System Type</label>
        <select name="system_type">
            <option value="">Select Type</option>
            <option value="1">Manual</option>
            <option value="2">Auto</option>
        </select>
    </div>
</div>
<!-- ROW 3 -->
<div class="shipment-line">
<div class="shipment-field">
    <label>Status</label>

    <select name="status" class="form-control">
        <option value="Pending">Pending</option>
        <option value="Packed">Packed</option>
        <option value="Dispatched">Dispatched</option>
        <option value="In Transit">In Transit</option>
        <option value="Delivered">Delivered</option>
    </select>
</div>
<div class="shipment-field">

    <div class="col-md-6">
        <label>Courier Name</label>
        <input type="text" name="courier_name" class="form-control">
    </div>

    <div class="col-md-6">
        <label>Dispatch Date</label>
        <input type="date" name="dispatch_date" class="form-control">
    </div>

</div>

</div>
<!-- ROW 4 -->
<div class="shipment-line">
    <div class="shipment-field shipment-wide">
        <label>Metal & Stone Details</label>
        <textarea name="metal_stone_details"></textarea>
    </div>
</div>

<!-- EMAIL -->
<div class="shipment-field">
    <label>Client Email</label>
    <input type="email" name="client_email" placeholder="Enter client email">
</div>

<!-- CHECKBOX -->
<div class="shipment-toggle">
    <input type="checkbox" id="send_email" name="send_email" value="1">
    <label for="send_email">
        Send Shipping Email 🚚
        <span class="hint">(Client will receive tracking details)</span>
    </label>
</div>

<!-- BUTTON -->
<button type="submit" class="shipment-submit">Save</button>

</form>

</div>


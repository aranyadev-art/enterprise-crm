<?php
/** @var array $quotations */
/** @var array $clients */
?>
<link rel="stylesheet" href="<?= base_url('css/order-create.css') ?>">
<h2>Create Order</h2> 

<div class="form-container">
<form method="post" action="<?= base_url('/orders/save') ?>">

<label>Sales Name:</label>
<input type="text" name="sales_name" required>

<label>CAD Name:</label>
<input type="text" name="cad_name" required>

<!-- Client Name -->
<label>Client</label>
<select name="client_name" id="clientSelect" required>
    <option value="">Select Client</option>
    <?php foreach ($clients as $c): ?>
        <option 
            value="<?= $c['first_name'] ?> <?= $c['last_name'] ?>"
            data-email="<?= $c['email'] ?>">
            <?= $c['first_name'] ?> <?= $c['last_name'] ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- ✅ NEW: Client Email -->
<label>Client Email:</label>
<input type="email" name="client_email" id="clientEmail" placeholder="Client email will appear here" readonly>

<!-- Quotation ID -->
<label>Quotation:</label>
<select name="quotation_id" required>
    <option value="">Select Quotation</option>
    <?php foreach ($quotations as $q): ?>
        <option value="<?= $q['id'] ?>">
            <?= $q['id'] ?> - <?= $q['client_name'] ?? 'No Name' ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Status -->
<label>Status:</label>
<select name="status" required>
    <option value="Pending">Pending</option>
    <option value="In Progress">In Progress</option>
    <option value="Completed">Completed</option>
</select>

<!-- ✅ NEW: Email Checkbox -->
<div class="checkbox-group">
    <label>
        <input type="checkbox" name="send_email" value="1">
        Send Email Notification 📩
    </label>
</div>

<button type="submit">Create Order</button>

</form>
</div>

<script>
document.getElementById('clientSelect').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    var email = selectedOption.getAttribute('data-email');

    document.getElementById('clientEmail').value = email ? email : '';
});
</script>
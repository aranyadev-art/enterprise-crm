<?php /** @var array $user */ ?>

<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<?php
// ✅ SAFE MODULE ACCESS (NO ERROR)
$access = [];

if (!empty($user['module_access'])) {
    $decoded = json_decode($user['module_access'], true);
    $access = is_array($decoded) ? $decoded : [];
}
?>

<!DOCTYPE html>
<html>

<head>
<title>User edit</title>
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>

<body>

<h2 class="usr-edit-title">Edit User</h2>

<form method="post" action="<?= base_url('user/update/'.$user['id']) ?>" class="usr-edit-form">

<div class="usr-field">
    <label>First Name</label>
    <input type="text" name="first_name" value="<?= $user['first_name'] ?>">
</div>

<div class="usr-field">
    <label>Last Name</label>
    <input type="text" name="last_name" value="<?= $user['last_name'] ?>">
</div>

<div class="usr-field">
    <label>Status</label>
    <select name="status">
        <option value="">Select Status</option>
        <option value="Active" <?= ($user['status']=='Active')?'selected':'' ?>>Active</option>
        <option value="Inactive" <?= ($user['status']=='Inactive')?'selected':'' ?>>Inactive</option>
    </select>
</div>

<div class="usr-field">
    <label>Email</label>
    <input type="email" name="email" value="<?= $user['email'] ?>">
</div>

<div class="usr-field">
    <label>Phone</label>
    <input type="text" name="phone" value="<?= $user['phone'] ?>">
</div>

<div class="usr-field">
    <label>Role</label>
    <select name="role" required>
        <option value="Sales" <?= ($user['role']=='Sales')?'selected':'' ?>>Sales</option>
        <option value="Sales Manager" <?= ($user['role']=='Sales Manager')?'selected':'' ?>>Sales Manager</option>
        <option value="Factory" <?= ($user['role']=='Factory')?'selected':'' ?>>Factory</option>
        <option value="Shipping" <?= ($user['role']=='Shipping')?'selected':'' ?>>Shipping</option>
        <option value="Admin" <?= ($user['role']=='Admin')?'selected':'' ?>>Admin</option>
        <option value="Accounting" <?= ($user['role']=='Accounting')?'selected':'' ?>>Accounting</option>
        <option value="CAD" <?= ($user['role']=='CAD')?'selected':'' ?>>CAD</option>
        <option value="CAD Manager" <?= ($user['role']=='CAD Manager')?'selected':'' ?>>CAD Manager</option>
    </select>
</div>

<br><br>

<h3>Module Access</h3>

<div class="module-grid">

<label class="module-item">
    <input type="checkbox" name="modules[]" value="sales"
    <?= in_array('sales', $access) ? 'checked' : '' ?>
    <span>Sales</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="client"
    <?= in_array('client', $access) ? 'checked' : '' ?>
    <span>Client</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="cad"
    <?= in_array('cad', $access) ? 'checked' : '' ?>
    <span>CAD</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="calculator"
    <?= in_array('calculator', $access) ? 'checked' : '' ?>
    <span>Calculator</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="quotation"
    <?= in_array('quotation', $access) ? 'checked' : '' ?>
    <span>Quotation</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="order"
    <?= in_array('order', $access) ? 'checked' : '' ?>
    <span>Order</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="account"
    <?= in_array('account', $access) ? 'checked' : '' ?>
    <span>Account</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="factory"
    <?= in_array('factory', $access) ? 'checked' : '' ?>
    <span>Factory</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="shipping"
    <?= in_array('shipping', $access) ? 'checked' : '' ?>
    <span>Shipping</span>
</label>

<label class="module-item">
    <input type="checkbox" name="modules[]" value="alert"
    <?= in_array('alert', $access) ? 'checked' : '' ?>
    <span>Alert</span>
</label>

</div>

<br>

<button type="submit">Update</button>

</form>

<script>
document.querySelectorAll('.module-item').forEach(item => {
    const checkbox = item.querySelector('input');

    // Initial load
    if (checkbox.checked) {
        item.classList.add('active');
    }

    // When checkbox changes
    checkbox.addEventListener('change', () => {
        if (checkbox.checked) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
});
</script>

</body>
</html>
<?= $this->endSection(); ?>
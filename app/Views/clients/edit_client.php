<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<div class="edit-client-box">

<h2 class="edit-client-title">Edit Client Details</h2>

<form class="edit-form" action="<?= base_url('clients/update/'.$client['id']) ?>" method="post">

<div class="edit-group">
    <label>First Name</label>
    <input type="text" name="first_name" value="<?= $client['first_name'] ?>">
</div>

<div class="edit-group">
    <label>Last Name</label>
    <input type="text" name="last_name" value="<?= $client['last_name'] ?>">
</div>

<div class="edit-group">
    <label>Email</label>
    <input type="email" name="email" value="<?= $client['email'] ?>">
</div>

<div class="edit-group">
    <label>Phone</label>
    <input type="text" name="phone" value="<?= $client['phone'] ?>">
</div>

<div class="edit-group">
    <label>City</label>
    <input type="text" name="city" value="<?= $client['city'] ?>">
</div>

<div class="edit-btn-group">
    <button type="submit" class="edit-save-btn">Update</button>
    <a href="<?= base_url('clients') ?>" class="edit-cancel-btn">Cancel</a>
</div>

</form>

</div>
<?= $this->endSection(); ?>
<?php /** @var array $user */ ?>
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<h2 class="usr-profile-title">My Profile</h2>

<form method="post" action="<?= base_url('profile/update') ?>" class="usr-profile-form">

<div class="usr-field">
    <label>First Name</label>
    <input type="text" name="first_name" value="<?= explode(' ', session()->get('name'))[0] ?? '' ?>">
</div>

<div class="usr-field">
    <label>Last Name</label>
    <input type="text" name="last_name" value="<?= explode(' ', session()->get('name'))[1] ?? '' ?>">
</div>

<div class="usr-field">
    <label>Email</label>
    <input type="email" name="email" value="<?= session()->get('email') ?>">
</div>

<div class="usr-field">
    <label>Role</label>
    <input type="text" value="<?= session()->get('role') ?>" readonly>
</div>

<button type="submit" class="usr-btn">Update Profile</button>

</form>
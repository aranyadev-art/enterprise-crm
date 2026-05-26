<!DOCTYPE html>
<html>
<head>

<title>Change Password</title>

<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

</head>

<body>

<div class="container">

<h2>Change Password</h2>

<?php if(session()->getFlashdata('error')): ?>
<p style="color:red"><?= session()->getFlashdata('error') ?></p>
<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>
<p style="color:green"><?= session()->getFlashdata('success') ?></p>
<?php endif; ?>

<form method="post" action="<?= base_url('update-password') ?>">

<label>Current Password</label>
<input type="password" name="current_password"><br><br>

<label>New Password</label>
<input type="password" name="new_password"><br><br>

<label>Confirm Password</label>
<input type="password" name="confirm_password"><br><br>

<button type="submit">Update Password</button>

</form>

</div>

</body>
</html>
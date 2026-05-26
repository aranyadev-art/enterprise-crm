<!DOCTYPE html>
<html>

<head>
<title>register </title>

<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

</head>

<body></body>

<h2>Register</h2>

<form action="<?= base_url('save-register') ?>" method="post">

First Name <br>
<input type="text" name="first_name"><br><br>

Last Name <br>
<input type="text" name="last_name"><br><br>

Email <br>
<input type="email" name="email"><br><br>

Password <br>
<input type="password" name="password"><br><br>

<button type="submit">Register</button>

</form>

<br>

<a href="<?= base_url('login') ?>">Login</a>
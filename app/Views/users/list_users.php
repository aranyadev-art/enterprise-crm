<?php /** @var array $users */ ?>
<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<!-- SUCCESS MESSAGE -->
<?php if(session()->getFlashdata('delete_success')): ?>
<div id="successMessage" class="success-msg">
    <?= session()->getFlashdata('delete_success'); ?>
</div>

<script>
setTimeout(function(){
    var msg = document.getElementById("successMessage");
    if(msg){
        msg.remove();
    }
},1500);
</script>
<?php endif;?>

<h2 class="ul-title">User List</h2>

<div class="ul-actions">
    <a class="ul-btn ul-add" href="/codei/public/users/create">Add User</a>
    <a class="ul-btn ul-back" href="<?= base_url('dashboard') ?>">Back to Dashboard</a>
</div>

<form id="deleteForm">

<table class="ul-table">

<tr>
<th><input type="checkbox" id="selectAll"></th>   
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Role</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php foreach($users as $user): ?>
<tr>

<td>
    <input type="checkbox" class="rowCheckbox" name="ids[]" value="<?= $user['id'] ?>">
</td>  

<td><?= $user['id'] ?></td>
<td><?= $user['first_name'].' '.$user['last_name'] ?></td>
<td><?= $user['email'] ?></td>
<td><?= $user['phone'] ?></td>

<td>
    <span class="ul-role"><?= $user['role'] ?></span>
</td>

<td>
   <span class="ul-status <?= strtolower($user['status']) ?>">
    <?= $user['status'] ?>
</span>
</td>

<td>
<a class="ul-btn ul-edit" href="<?= base_url('user/edit/'.$user['id']) ?>">Edit</a>

<a class="ul-btn ul-delete"
href="<?= base_url('user/delete/'.$user['id']) ?>"
onclick="return confirm('Are you sure?')">
Delete
</a>
</td>

</tr>
<?php endforeach; ?>

</table>

<br>

<button type="button" class="ul-delete-all" id="deleteSelected">
    Delete Selected
</button>

</form>

<script>
document.getElementById('deleteSelected').addEventListener('click', function () {

    let ids = [];

    document.querySelectorAll('input[name="ids[]"]:checked').forEach(el => {
        ids.push(el.value);
    });

    if (ids.length === 0) {
        alert("Please select at least one employee");
        return;
    }

    if (!confirm("Are you sure to delete selected employees?")) return;

    fetch("<?= base_url('user/deleteMultiple') ?>", {  // ✅ FIXED
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ ids: ids })
    })
    .then(res => res.text())
    .then(data => {
        console.log(data);
        location.reload();
    });

});
</script>

<?= $this->endSection(); ?>
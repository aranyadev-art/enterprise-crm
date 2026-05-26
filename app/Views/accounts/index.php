<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<h2 class="acc-title">Accounts List</h2>

<a href="<?= base_url('accounts/create') ?>" class="acc-create-btn">
    + Add Account
</a>

<br><br>
<form method="post" action="<?= base_url('accounts/deleteMultiple') ?>">
<table class="acc-table">
<tr>
    <th><input type="checkbox" id="select_all"></th>
    <th>ID</th>
    <th>Due Balance</th>
    <th>Credit Limit</th>
    <th>Status</th>
   <th>Shipping Status</th>
   <th>Payment</th>
</tr>

<?php if(!empty($accounts)): ?>
    <?php foreach($accounts as $acc): ?>
        <tr>
               <td>
              <input type="checkbox" name="ids[]" value="<?= $acc['id'] ?>" class="checkbox">
             </td>
            <td><?= $acc['id'] ?></td>
            <td><?= $acc['due_balance'] ?></td>
            <td><?= $acc['credit_limit'] ?></td>

            <td>
               <?php if($acc['due_balance'] > $acc['credit_limit']): ?>
                <span class="acc-badge red">Exceeded</span>
               <?php else: ?>
                <span class="acc-badge green">OK</span>
               <?php endif; ?>
            </td>

          <td>
    <?php if($acc['due_balance'] <= 0): ?>
        <span class="acc-badge green">Ready to Ship</span>
    <?php else: ?>
        <span class="acc-badge red">Payment Pending</span>
    <?php endif; ?>
</td>
               <td class="acc-action-cell">
    <a href="<?= base_url('accounts/payment/'.$acc['id']) ?>" class="acc-btn pay-btn">
        💳 Pay Now
    </a>

    <a href="<?= base_url('accounts/payments/'.$acc['id']) ?>" class="acc-btn history-btn">
        📜 History
    </a>
</td>
           
        </tr>
    <?php endforeach; ?>
<?php else: ?>
<tr>
    <td colspan="5" class="acc-no-data">No Records Found</td>
</tr>
<?php endif; ?>

</table>

<br>
<button type="submit" class="acc-delete-btn"
onclick="return confirm('Delete selected records?')">
    Delete Selected
</button>
</form>

<script>
document.getElementById('select_all').onclick = function() {
    let checkboxes = document.querySelectorAll('.checkbox');
    checkboxes.forEach(cb => cb.checked = this.checked);
};
</script>
<?= $this->endSection(); ?>
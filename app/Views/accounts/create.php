<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<h2 class="acc-title">Account Module</h2>

<div class="acc-container">

<form method="post" action="<?= base_url('accounts/save') ?>" class="acc-form">

<div class="acc-row">
    <div class="acc-group">
        <label>Due Balance</label>
        <input type="number" id="due_balance" name="due_balance" required>
    </div>

    <div class="acc-group">
        <label>Credit Limit</label>
        <input type="number" id="credit_limit" name="credit_limit" required>
    </div>
</div>

<p id="excess_msg" class="acc-msg"></p>

<div class="acc-group">
    <label>Shipping Status</label>
</div>


<div class="acc-btn-group">
    <button type="button" class="acc-btn acc-reminder">Send Reminder</button>
    <button type="submit" class="acc-btn acc-save">Save</button>
</div>

</form>
</div>
<?= $this->endSection(); ?>
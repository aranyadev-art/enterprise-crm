<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<h2>Payment History</h2>

<div class="pay-history-actions">
    <a href="<?= base_url('accounts') ?>" class="pay-back-btn">⬅ Back</a>

    <a href="<?= base_url('accounts/payments/pdf/'.$account_id) ?>" 
       class="pay-pdf-btn">
       📄 Download PDF
    </a>
</div>

<table class="pay-history-table">
    <tr>
        <th>ID</th>
        <th>Amount</th>
        <th>Method</th>
        <th>UPI ID</th>
        <th>Date</th>
    </tr>

    <?php if(!empty($payments)): ?>
        <?php foreach($payments as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['amount'] ?></td>
            <td><?= strtoupper($p['payment_method']) ?></td>
            <td><?= $p['upi_id'] ?? '-' ?></td>
            <td><?= $p['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No Payments Found</td>
        </tr>
    <?php endif; ?>
</table>
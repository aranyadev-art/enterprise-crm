<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<h2>Alerts</h2>

<button onclick="location.reload()" class="refresh-btn">🔄 Refresh</button>

<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>Alert Title</th>
        <th>Module</th>
        <th>Date & Time</th>
        <th>Status</th>
        <th>Action</th> <!-- ✅ ADDED -->
    </tr>

<?php foreach($alerts as $alert): ?>

    <?php if(!isset($alert['id'])) continue; // ✅ ADD THIS LINE ?>

    <?php 
        $status = session()->get('alert_status_' . $alert['id']) ?? 'Unread';
    ?>

    <tr>
        <td><?= $alert['title'] ?? '' ?></td>
        <td><?= $alert['module'] ?? '' ?></td>
        <td><?= $alert['datetime'] ?? '' ?></td>

        <td>
            <span class="<?= strtolower($status) ?>">
                <?= $status ?>
            </span>
        </td>

        <td>
            <?php if($status == 'Unread'): ?>
                <a href="/codei/public/index.php/alerts/read/<?= $alert['id'] ?>">Mark Read</a>
            <?php else: ?>
                ✔ Read
            <?php endif; ?>
        </td>
    </tr>

<?php endforeach; ?>
</table>

<?= $this->endSection(); ?>
<style>
.container {
    width: 80%;
    margin: 20px auto;
    font-family: Arial;
}

.section {
    margin-bottom: 30px;
}

.section h3 {
    border-left: 5px solid #007bff;
    padding-left: 10px;
    color: #333;
}

.card {
    border: 1px solid #ddd;
    padding: 12px;
    margin-top: 10px;
    border-radius: 6px;
    background: #f9f9f9;
    transition: 0.3s;
}

.card:hover {
    background: #eef5ff;
    transform: translateY(-2px);
}

.name {
    font-weight: bold;
    font-size: 16px;
}

.email {
    color: gray;
    font-size: 14px;
}

.empty {
    color: red;
    font-size: 14px;
}
</style>

<div class="container">

<h2>Search Results</h2>

<!-- Leads -->
<div class="section">
<h3>Leads</h3>

<?php if(!empty($leads)): ?>
    <?php foreach ($leads as $lead): ?>
        <div class="card">
            <div class="name"><?= $lead['name'] ?></div>
            <div class="email"><?= $lead['email'] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">No leads found</p>
<?php endif; ?>

</div>

<!-- Clients -->
<div class="section">
<h3>Clients</h3>

<?php if(!empty($clients)): ?>
    <?php foreach ($clients as $client): ?>
        <div class="card">
            <div class="name"><?= $client['first_name']  ?> <?= $client['last_name'] ?></div>
            <div class="email"><?= $client['email'] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">No clients found</p>
<?php endif; ?>

</div>

<!-- Orders -->
<div class="section">
<h3>Orders</h3>

<?php if(!empty($orders)): ?>
    <?php foreach ($orders as $order): ?>
        <div class="card">
            <div class="name">Order: <?= $order['order_number'] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">No orders found</p>
<?php endif; ?>

</div>
<div class="section">
<h3>Users</h3>

<?php if(!empty($users)): ?>
    <?php foreach ($users as $user): ?>
        <div class="card">
            <div class="name"><?= $user['first_name'] ?></div>
            <div class="email"><?= $user['email'] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">No users found</p>
<?php endif; ?>

</div>

<div class="section">
<h3>Sales</h3>

<?php if(!empty($sales)): ?>
    <?php foreach ($sales as $sale): ?>
        <div class="card">
            <div class="name">Sale ID: <?= $sale['id'] ?></div>
            <div class="email">Metal: <?= $sale['metal_type'] ?></div>
            <div class="email">Stone: <?= $sale['stone_type'] ?></div>
            <div class="email">Status: <?= $sale['status'] ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">No sales found</p>
<?php endif; ?>

</div>

<div class="section">
<h3>Shipping</h3>

<?php if(!empty($shipping)): ?>
    <?php foreach ($shipping as $ship): ?>
        <div class="card">
            <div class="name">Tracking: <?= $ship['tracking_number'] ?></div>
            <div class="email">Status: <?= $ship['status'] ?? '' ?></div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="empty">No shipping data found</p>
<?php endif; ?>

</div>

</div>
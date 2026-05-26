<!DOCTYPE html>
<html>
<head>
    <title>Client Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .cd-title {
            margin-top: 40px;
            font-size: 30px;
            color: #333;
        }

        .cd-subtitle {
            color: #777;
            margin-bottom: 30px;
        }

        /* ===== SUMMARY CARDS ===== */
        .summary-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .summary-card {
            width: 180px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .summary-card h3 {
            margin: 0;
            font-size: 26px;
            color: #333;
        }

        .summary-card p {
            margin: 5px 0 0;
            color: #777;
            font-size: 14px;
        }

        /* ===== MAIN BOXES ===== */
        .cd-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            padding: 20px;
        }

        .cd-box {
            background: white;
            width: 220px;
            height: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-size: 18px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .cd-box:hover {
            transform: translateY(-5px);
            background: #667eea;
            color: white;
        }
        .order-table {
    width: 80%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

.order-table th {
    background: #667eea;
    color: white;
    padding: 12px;
}

.order-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}
    </style>
</head>

<body>

<h2 class="cd-title">
    Welcome <?= session('client_name') ?> 👋
</h2>

<p class="cd-subtitle">
    Manage your account and track your activities
</p>

<!-- ===== SUMMARY SECTION ===== -->
<div class="summary-container">

    <div class="summary-card">
        <h3><?= $total_orders ?? 0 ?></h3>
        <p>Total Orders</p>
    </div>

    <div class="summary-card">
        <h3><?= $active_orders ?? 0 ?></h3>
        <p>Active Orders</p>
    </div>

    <div class="summary-card">
        <h3><?= $pending_quotes ?? 0 ?></h3>
        <p>Pending Quotes</p>
    </div>

    <div class="summary-card">
        <h3><?= $pending_payment ?? 0 ?></h3>
        <p>Pending Payments</p>
    </div>

</div>

<!-- ===== MAIN ACTION BOXES ===== -->
<div class="cd-container">

    <a href="<?= base_url('client/profile') ?>" class="cd-box">
        👤 My Profile
    </a>

    <a href="<?= base_url('client/orders') ?>" class="cd-box">
        📦 My Orders
    </a>

    <a href="<?= base_url('client/quotations') ?>" class="cd-box">
        💰 My Quotations
    </a>

</div>  <!-- 👈 AFTER THIS LINE -->

<h3 style="margin-top:40px;">📦 Recent Orders</h3>

<table class="order-table">

    <tr>
        <th>Order ID</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php if (!empty($recent_orders)) : ?>
        <?php foreach ($recent_orders as $order) : ?>
            <tr>
                <td><?= $order['order_number'] ?? 'N/A' ?></td>
                <td><?= $order['status'] ?? 'Pending' ?></td>
                <td><?= $order['created_at'] ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="3">No orders found</td>
        </tr>
    <?php endif; ?>

</table>

</body>
</html>
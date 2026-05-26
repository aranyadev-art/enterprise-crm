<?php
$allModules = ['sales','client','cad','calculator','quotation','order','account','factory','shipping','alert'];

$role = session()->get('role');
$access = session()->get('module_access') ?? [];

// Convert to array if needed
if (!is_array($access)) {
    $access = explode(',', $access);
}

// Clean data
$access = array_map('trim', $access);
$access = array_map('strtolower', $access);

// 🔥 Admin override
if (trim(strtolower($role)) == 'admin') {
    $access = $allModules;
}
?>

<div class="sidebar">
    <h2>CRM</h2>
    <ul>
        <li><a href="<?= base_url('dashboard') ?>">🏠 Dashboard</a></li>

        <?php if (strtolower(trim($role)) == 'admin'): ?>
            <li><a href="<?= base_url('users') ?>">👤 User</a></li>
        <?php endif; ?>

        <?php if(in_array('client', $access)): ?>
            <li><a href="<?= base_url('clients') ?>">👥 Client</a></li>
        <?php endif; ?>

        <?php if(in_array('sales', $access)): ?>
            <li><a href="<?= base_url('sales') ?>">📦 Sales</a></li>
        <?php endif; ?>

        <?php if(in_array('quotation', $access)): ?>
            <li><a href="<?= base_url('quotation') ?>">📄 Quotation</a></li>
        <?php endif; ?>
      
             <?php if (in_array('calculator', $access)): ?>
             <li><a href="<?= base_url('calculator') ?>">📟 Calculator</a></li>
           <?php endif; ?>

        <?php if(in_array('cad', $access)): ?>
            <li><a href="<?= base_url('cod') ?>">🖼 CAD</a></li>
        <?php endif; ?>

        <?php if(in_array('order', $access)): ?>
            <li><a href="<?= base_url('orders') ?>">📦 Orders</a></li>
        <?php endif; ?>

        <?php if(in_array('account', $access)): ?>
            <li><a href="<?= base_url('accounts') ?>">💰 Accounts</a></li>
        <?php endif; ?>

        <?php if(in_array('factory', $access)): ?>
            <li><a href="<?= base_url('factory') ?>">🏭 Factory</a></li>
        <?php endif; ?>

        <?php if(in_array('shipping', $access)): ?>
            <li><a href="<?= base_url('shipping') ?>">🚚 Shipping</a></li>
        <?php endif; ?>

        <?php if(in_array('alert', $access)): ?>
            <li><a href="<?= base_url('alerts') ?>">🔔 Alert</a></li>
        <?php endif; ?>

            <li>
           <a href="<?= base_url('leads') ?>">
          <i class="fa fa-user"></i> Leads
          </a>
          </li>

          
    </ul>
</div>
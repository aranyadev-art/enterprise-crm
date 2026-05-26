
<?php
$notifications = session()->get('alerts') ?? [];
$count = count($notifications);
?>
<?php
$allModules = ['sales','client','cad','calculator','quotation','order','account','factory','shipping','alert'];

$role = session()->get('role');
$access = session()->get('module_access') ?? [];

// Convert to array
if (!is_array($access)) {
    $access = explode(',', $access);
}

// Clean data
$access = array_map('trim', $access);
$access = array_map('strtolower', $access);

// 🔥 Admin full access
if (trim(strtolower($role)) == 'admin') {
    $access = $allModules;
}
?>

<?php if(session()->getFlashdata('success')): ?>

    <div class="success-alert">
        <?= session()->getFlashdata('success') ?>
    </div>

<?php endif; ?>
 

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboard_layout.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>CRM</h2>

        <ul>
            <li><a href="<?= base_url('dashboard') ?>">🏠 Dashboard</a></li>
            
      <?php if (strtolower(trim($role)) == 'admin'): ?>
      <li><a href="<?= base_url('users') ?>">👤 User</a></li>
     <?php endif; ?>

            <?php if (in_array('client', $access)): ?>
                <li><a href="<?= base_url('clients') ?>">👥 Client</a></li>
            <?php endif; ?>
                
             <?php if (in_array('roster', $access)): ?>
              <li><a href="<?= base_url('roster') ?>" class="cd-box">
                📅 Roster
             </a></li>
             <?php endif; ?>

            <?php if (in_array('sales', $access)): ?>
                <li><a href="<?= base_url('sales') ?>">📦 Sales</a></li>
            <?php endif; ?>

            <?php if (in_array('quotation', $access)): ?>
                <li><a href="<?= base_url('quotation') ?>">📄 Quotation</a></li>
            <?php endif; ?>

              <?php if (in_array('calculator', $access)): ?>
             <li><a href="<?= base_url('calculator') ?>">📟 Calculator</a></li>
           <?php endif; ?>

            <?php if (in_array('cad', $access)): ?>
                <li><a href="<?= base_url('cod') ?>">🖼 CAD</a></li>
            <?php endif; ?>


             <?php if(in_array('order', $access)): ?>
                 <li><a href="<?= base_url('orders') ?>">📦 Orders</a></li>
            <?php endif; ?>

        
             <?php if(in_array('account', $access)): ?>
                 <li><a href="<?= base_url('accounts') ?>">💰 Accounts</a></li>
             <?php endif; ?>

            <?php if (in_array('factory', $access)): ?>
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


    <!-- MAIN CONTENT -->
<div class="main-content">

 <div class="crm-topbar">

    <div class="crm-left">
        <h2>Dashboard</h2>
    </div>

    <div class="crm-right">
        
   <div class="crm-bell-wrapper">

    <div class="crm-bell">
        <i class="fa-regular fa-bell"></i>

        <?php if(isset($count) && $count > 0): ?>
            <span class="crm-badge"><?= $count > 9 ? '9+' : $count ?></span>
        <?php endif; ?>
    </div>

    <div id="crmBellDropdown" class="crm-bell-dropdown">

        <?php if(!empty($notifications)): ?>
            
            <?php foreach($notifications as $note): ?>
                <a href="#">
                    <?= $note['title'] ?>
                </a>
            <?php endforeach; ?>

        <?php else: ?>
            <a href="#">No notifications</a>
        <?php endif; ?>

    </div>
</div>
        <div class="crm-user">

            <div class="crm-user-summary" onclick="toggleDropdown()">
                
           <div class="user-avatar-wrap">
            
            <img 
                src="<?= base_url('uploads/profile/' . (session()->get('profile_image') ?? 'default.png')) ?>" 
                class="user-avatar"
            >

          <span class="user-status-dot"></span>

        </div>

                <div class="crm-user-info">
                    <span class="crm-name"><?= session()->get('name') ?></span>
                    <span class="crm-role">(<?= session()->get('role') ?>)</span>
                </div>

                <span class="crm-arrow">▼</span>

            </div>

            <div id="crmDropdown" class="crm-dropdown">
                <a href="<?= base_url('profile') ?>">👤 Profile</a>
                <a href="<?= base_url('settings') ?>">⚙️ Settings</a>
                <a href="<?= base_url('logout') ?>" class="crm-logout">🚪 Logout</a>
            </div>

        </div>

    </div>

</div>

        <!-- CONTENT -->
        <div class="content">

           <div class="welcome-box">

              <h2>
                  Welcome, <?= session()->get('name') ?> 👋
              </h2>

               <p>
                   You have 
                   <strong><?= $todayCount ?></strong> follow-ups today
                   and
                   <strong><?= $overdueCount ?></strong> overdue follow-ups.
               </p>

            </div>

              <form action="<?= base_url('search') ?>" method="get">
                 <input type="text" name="q" placeholder="Search anything...">
                 <button type="submit">Search</button>
             </form>

            <div class="chart-box">
                <canvas id="mainChart"></canvas>
            </div>

            <!-- 🔥 CARDS WITH ACCESS CONTROL -->
            <div class="grid">

                <!-- USERS -->
                <div class="card">
                    <h3>👤 Users</h3>
                    <p><?= $total_users ?? 0 ?></p>
                </div>

                <!-- CLIENT -->
                <?php if(in_array('client', $access)): ?>
                <div class="card">
                    <h3>👥 Clients</h3>
                    <p><?= $total_clients ?? 0 ?></p>
                </div>
                <?php endif; ?>

                <!-- ORDERS -->
                <?php if(in_array('order', $access)): ?>
                <div class="card">
                    <h3>📦 Orders</h3>
                    <p><?= $total_orders ?? 0 ?></p>
                </div>
                <?php endif; ?>

                <!-- SALES -->
                <?php if(in_array('sales', $access)): ?>
                <div class="card">
                    <h3>📊 Sales</h3>
                    <p><?= $total_sales ?? 0 ?></p>
                </div>
                <?php endif; ?>

                <!-- ACCOUNT -->
              <?php if(in_array('account', $access)): ?>
                <div class="card">
                    <h3>💰 Revenue</h3>
                    <p>₹ <?= number_format($revenue ?? 0) ?></p>
                </div>
                <?php endif; ?>

            </div>

      <div class="followup-section">

    <h3 class="section-title today-title">📅 Today Follow-ups</h3>

    <?php if(!empty($today_followups)): ?>

    <?php foreach($today_followups as $lead): ?>

        <div class="followup-card today-card">

            <div>
                <div class="lead-name">
                    <?= $lead['name'] ?>
                </div>

                <div class="follow-date">
                    <?= $lead['follow_up_date'] ?>
                </div>
            </div>

            <!-- DONE BUTTON -->
            <a href="<?= base_url('followup/done/'.$lead['id']) ?>"
               class="done-btn"
               onclick="return confirm('Mark this follow-up as done?')">
               ✅ Done
            </a>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p class="empty-text">No follow-ups today</p>

<?php endif; ?>



<h3 class="section-title overdue-title">⚠️ Overdue Follow-ups</h3>

<?php if(!empty($overdue)): ?>

    <?php foreach($overdue as $lead): ?>

        <div class="followup-card overdue-card">

            <div>
                <div class="lead-name">
                    <?= $lead['name'] ?>
                </div>

                <div class="follow-date">
                    <?= $lead['follow_up_date'] ?>
                </div>
            </div>

            <!-- DONE BUTTON -->
            <a href="<?= base_url('followup/done/'.$lead['id']) ?>"
               class="done-btn"
               onclick="return confirm('Mark this overdue follow-up as done?')">
               ✅ Done
            </a>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p class="empty-text">No overdue follow-ups</p>

<?php endif; ?>

</div>

        </div>
    </div>

</div>

<!-- Chart -->
<script>
const mainChart = new Chart(document.getElementById('mainChart'), {
    type: 'line',
    data: {
        labels: <?= $chart_labels ?? '[]' ?>,
        datasets: <?= $chart_datasets ?? '[]' ?>
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<!-- Auto update -->
<script>
function updateChart() {
    fetch("<?= base_url('dashboard/getChartData') ?>")
        .then(res => res.json())
        .then(data => {
            mainChart.data.labels = data.labels;
            mainChart.data.datasets = data.datasets;
            mainChart.update();
        });
}

setInterval(updateChart, 3000);
</script>
<script>
function toggleDropdown() {
    let dropdown = document.getElementById("crmDropdown");
    let summary = document.querySelector(".crm-user-summary");

    dropdown.classList.toggle("show");
    summary.classList.toggle("active");
}

/* Close when clicking outside */
window.addEventListener("click", function(e) {
    let user = document.querySelector(".crm-user");
    let dropdown = document.getElementById("crmDropdown");
    let summary = document.querySelector(".crm-user-summary");

    if (!user.contains(e.target)) {
        dropdown.classList.remove("show");
        summary.classList.remove("active");
    }
});
</script>
<script>
function toggleBell() {
    let dropdown = document.getElementById("crmBellDropdown");
    dropdown.classList.toggle("show");
}

/* Close on outside click */
document.addEventListener("click", function(e) {
    let wrapper = document.querySelector(".crm-bell-wrapper");
    let dropdown = document.getElementById("crmBellDropdown");

    if (!wrapper.contains(e.target)) {
        dropdown.classList.remove("show");
    }
});
</script>

<script>
let bellWrapper = document.querySelector(".crm-bell-wrapper");
let dropdown = document.getElementById("crmBellDropdown");

let showTimer;
let hideTimer;

bellWrapper.addEventListener("mouseenter", () => {
    clearTimeout(hideTimer);
    showTimer = setTimeout(() => {
        dropdown.classList.add("show");
    }, 300);
});

bellWrapper.addEventListener("mouseleave", () => {
    clearTimeout(showTimer);
    hideTimer = setTimeout(() => {
        dropdown.classList.remove("show");
    }, 200);
});
</script>
</body>
</html>
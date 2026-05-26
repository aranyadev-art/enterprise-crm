<!DOCTYPE html>
<html>
<head>

<title><?= $title ?? 'CRM' ?></title>

<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<link rel="stylesheet" href="<?= base_url('css/dashboard_layout.css') ?>">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>

<body>

<div class="container" style="width:100%;"> <!-- ✅ ADD THIS -->

   <?= view('sidebar'); ?>

    <div class="main-content" style="width:100%;"> <!-- ✅ ADD -->
<div class="topbar">

    <!-- LEFT -->
    <div>
        <b><?= $title ?? 'Dashboard' ?></b>
    </div>

    <!-- RIGHT -->
   <div class="crm-right">
        
        <span class="crm-bell">🔔</span>

        <div class="crm-user">

         <div class="crm-user-summary" onclick="toggleDropdown()">

    <div class="crm-profile-wrapper">
        <img 
            src="<?= base_url('uploads/profile/' . (session()->get('profile_image') ?? 'default.png')) ?>" 
            class="crm-profile-img"
        >

        <!-- 👇 TEMPORARY always show -->
        <span class="crm-active-dot"></span>
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
      

        <!-- 🔥 DYNAMIC CONTENT -->
        <div class="content" style="width:100%;"> <!-- ✅ ADD -->
            <?= $this->renderSection('content'); ?>
        </div>

    </div>

</div>
<script>
function toggleDropdown() {
    let d = document.getElementById("crmDropdown");
    d.style.display = d.style.display === "block" ? "none" : "block";
}

window.onclick = function(e) {
    if (!e.target.closest(".crm-user")) {
        document.getElementById("crmDropdown").style.display = "none";
    }
}
</script>
</body>
</html>
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<div class="crm-settings-wrapper">

<h2 class="crm-settings-title">Settings</h2>

<a href="<?= base_url('dashboard') ?>" class="crm-dashboard-btn">
    🏠 Dashboard
</a>

<div class="settings-card">
    <h3>Change Password</h3>

    <form method="post" action="<?= base_url('settings/updatePassword') ?>">

      <div class="password-wrapper">
      <input type="password" id="password" name="password" placeholder="Password">
      <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
      </div>

      <div class="password-wrapper">
          <input type="password" name="new_password" id="new_password" placeholder="New Password" required>
       <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('new_password', this)"></i>
      </div>

        <div class="password-wrapper">
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
           <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
        </div>

        <button type="submit">Update Password</button>
    </form>
</div>

<div class="settings-card">
    <h3>Profile Picture</h3>

    <form 
        id="uploadForm"
        method="post" 
        action="<?= base_url('settings/uploadProfile') ?>" 
        enctype="multipart/form-data">

        <div class="profile-wrapper">

            <div>
                <img 
                    id="profilePreview"
                    src="<?= base_url('uploads/profile/' . session()->get('profile_image')) ?>" 
                    width="100" 
                    height="100" 
                    style="object-fit:cover;"
                />
            </div>

            <div class="upload-section">
                <input 
                    type="file" 
                    id="fileInput"
                    name="profile_image" 
                    accept="image/*" 
                    required
                >

                <button type="submit">Upload</button>
            </div>

        </div>

    </form>
</div>

<div class="settings-card">
    <h3>Notifications</h3>

    <form method="post" action="<?= base_url('settings/updateNotification') ?>">

        <label>
            <input type="checkbox" name="email_notification" value="1"
            <?= !empty($settings['email_notification']) ? 'checked' : '' ?>>
            Email
        </label>

        <label>
            <input type="checkbox" name="system_notification" value="1"
            <?= !empty($settings['system_notification']) ? 'checked' : '' ?>>
            System
        </label>

        <br><br>

        <button type="submit">Save</button>

    </form>
</div>

</div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.getElementById('fileInput').addEventListener('change', function() {
        
        let file = this.files[0];
        if (!file) return;

        // Preview
        let reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePreview').src = e.target.result;
        };
        reader.readAsDataURL(file);

    });

});
</script>

<script>
function togglePassword(id, icon) {
    let input = document.getElementById(id);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
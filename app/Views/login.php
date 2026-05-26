<!DOCTYPE html>
<html>

<head>
<title>Login</title>

<link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body>

<div class="login-container">

<h2 class="login-title">CRM Login</h2>

<form id="loginForm">

<div class="form-group">
<label>Email</label>
<input type="email" name="email" required>
</div>
<div class="crm-pass-field">
    <input type="password" id="password" name="password" placeholder="Password" required>

    <span class="crm-pass-toggle" onclick="togglePassword('password', this)">
        <i class="fa-solid fa-eye-slash"></i>
    </span>
</div>

<p id="errorMsg" class="error" style="display:none;"></p>

<button class="login-btn" type="submit">Login</button>

</form>

</div>

<script>

$(document).ready(function(){

    $('#loginForm').submit(function(e){
        e.preventDefault();

        let email = $('input[name="email"]').val().trim();
        let password = $('input[name="password"]').val().trim();

        if(email === '' || password === ''){
            showError('Please fill all fields');
            return;
        }

        $.ajax({
            url: "<?= base_url('checkLogin') ?>", // ✅ FIXED URL
            method: "POST",
            data: {
                email: email,
                password: password
            },

            beforeSend: function(){
                $('.login-btn').text('Logging in...').prop('disabled', true);
            },

            success: function(response){
                console.log(response); // ✅ Debug

                if(response.trim() === 'success'){
                    window.location.href = "<?= base_url('dashboard') ?>";

                } else if(response.trim() === 'wrong_password'){
                    showError('Incorrect Password');

                } else if(response.trim() === 'no_email'){
                    showError('Email not found');

                } else if(response.trim() === 'inactive'){
                    showError('User is inactive');

                } else {
                    showError('Something went wrong');
                }

                $('.login-btn').text('Login').prop('disabled', false);
            },

            error: function(){
                showError('Server error. Try again');
                $('.login-btn').text('Login').prop('disabled', false);
            }

        });

    });

});

// Error function
function showError(message){
    $('#errorMsg').text(message).fadeIn();

    setTimeout(function(){
        $('#errorMsg').fadeOut();
    }, 2500);
}

</script>

<script>//eye button add krne ke liye   //
function togglePassword(id, el) {
    let input = document.getElementById(id);
    let icon = el.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    }
}
</script>
<script>
document.addEventListener("keydown", function(e) {
    if (e.key === "Enter") {
        const form = document.querySelector("form");
        if (form) {
            form.requestSubmit();
        }
    }
});
</script>

</body>
</html>
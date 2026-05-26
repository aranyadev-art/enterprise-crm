
<!DOCTYPE html>
<html>
<head>
    <title>Client Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #loginForm {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            width: 300px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102,126,234,0.5);
        }

        button {
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            font-size: 16px;
        }

        button:hover {
            background: #5a67d8;
        }
    </style>
</head>

<body>

<form id="loginForm">
    <h2>Client Login</h2>

    <input type="email" id="email" name="email" placeholder="Enter Email" required>

    <input type="password" id="password" name="password" placeholder="Enter Password" required>

    <button type="submit">Login</button>
</form>

</body>
</html>>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    $("#loginForm").off("submit").on("submit", function(e){
        e.preventDefault();

        console.log("FORM SUBMITTED");

        $.ajax({
            url: "<?= base_url('client/checkLogin') ?>",
            type: "POST",
            data: {
                email: $("#email").val(),
                password: $("#password").val()
            },
            success: function(response) {

                console.log("RAW RESPONSE:", response);

                if (response.trim() === "client_success") {
                    window.location.href = "<?= base_url('client/dashboard') ?>";
                    return;
                }

                if (response.trim() === "wrong_password") {
                    alert("Wrong Password");
                    return;
                }

                if (response.trim() === "no_email") {
                    alert("Email not found");
                    return;
                }

                if (response.trim() === "inactive") {
                    alert("Account inactive");
                    return;
                }

                alert("Something went wrong");
            }
        });

    });

});
</script>
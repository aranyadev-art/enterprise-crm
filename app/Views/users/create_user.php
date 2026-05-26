<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>
<h2 class="user-title">Add User</h2>

<form method="post" action="<?= base_url('user/save') ?>" id="userForm" onsubmit="return validateForm()">

<h3 class="block-title">Personal Details</h3>

<div class="grid-row">
    <div class="field-box">
        <label>First Name *</label>
       <input type="text" name="first_name" id="first_name"
oninput="validateSingleField('first_name', /^[A-Za-z ]+$/, '', 'fnameError')">
        <span class="error" id="fnameError"></span>
    </div>

    <div class="field-box">
        <label>Last Name *</label>
        <input type="text" name="last_name" id="last_name"
oninput="validateSingleField('last_name', /^[A-Za-z ]+$/, '', 'lnameError')">
        <span class="error" id="lnameError"></span>
    </div>
</div>

<div class="grid-row">
    <div class="field-box">
        <label>Email *</label>
       <input type="email" name="email" id="email"
oninput="validateSingleField('email', null, 'required', 'emailError')">
        <span class="error" id="emailError"></span>
    </div>

    <div class="field-box">
        <label>Phone *</label>
      <input type="text" name="phone" id="phone"
oninput="validateSingleField('phone', /^[0-9]{10}$/, '', 'phoneError')">
        <span class="error" id="phoneError"></span>
    </div>
</div>

<div class="grid-row">
    <div class="field-box">
        <label>Status *</label>
        <select name="status" id="status"
onchange="validateSingleField('status', null, 'required', 'statusError')">
            <option value="">Select Status</option>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
        </select>
        <span class="error" id="statusError"></span>
    </div>

    <div class="field-box">
        <label>Role *</label>
      <select name="role" id="role"
onchange="validateSingleField('role', null, 'required', 'roleError')">
            <option value="">Select Role</option>
            <option>Sales</option>
            <option>Sales Manager</option>
            <option>Factory</option>
            <option>Shipping</option>
            <option>Admin</option>
            <option>Accounting</option>
            <option>CAD</option>
            <option>CAD Manager</option>
        </select>
        <span class="error" id="roleError"></span>
    </div>
</div>

<div class="grid-row">
    <div class="field-box">
        <label>Password *</label>
        <input type="password" name="password" id="password"
oninput="validateSingleField('password', null, 'password', 'passError')">
        <span class="error" id="passError"></span>
    </div>
</div>

<h3 class="block-title">Address Details</h3>

<div class="grid-row">
    <div class="field-box full">
        <label>Address *</label>
      <textarea name="address" id="address"
oninput="validateSingleField('address', null, 'required', 'addressError')"></textarea>
        <span class="error" id="addressError"></span>
    </div>
</div>

<div class="grid-row">
    <div class="field-box">
        <label>City *</label>
       <input type="text" name="city" id="city"
oninput="validateSingleField('city', /^[A-Za-z ]+$/, '', 'cityError')">
        <span class="error" id="cityError"></span>
    </div>

    <div class="field-box">
        <label>State *</label>
        <input type="text" name="state" id="state"
oninput="validateSingleField('state', /^[A-Za-z ]+$/, '', 'stateError')">
        <span class="error" id="stateError"></span>
    </div>
</div>

<div class="grid-row">
    <div class="field-box">
        <label>ZIP *</label>
        <input type="text" name="zip" id="zip"
oninput="validateSingleField('zip', /^[0-9]+$/, '', 'zipError')">
        <span class="error" id="zipError"></span>
    </div>
</div>

<h3 class="block-title">Module Access</h3>

<div class="access-wrapper">

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="sales">
        <span class="check-ui"></span>
        <span>Sales</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="client">
        <span class="check-ui"></span>
        <span>Client</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="cad">
        <span class="check-ui"></span>
        <span>CAD</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="calculator">
        <span class="check-ui"></span>
        <span>Calculator</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="quotation">
        <span class="check-ui"></span>
        <span>Quotation</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="order">
        <span class="check-ui"></span>
        <span>Order</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="account">
        <span class="check-ui"></span>
        <span>Account</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="factory">
        <span class="check-ui"></span>
        <span>Factory</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="shipping">
        <span class="check-ui"></span>
        <span>Shipping</span>
    </label>

    <label class="access-card">
        <input type="checkbox" name="modules[]" value="alert">
        <span class="check-ui"></span>
        <span>Alert</span>
    </label>

</div>

<div class="email-box">
    <label class="email-label">
        <input type="checkbox" name="send_email" value="1">
        <span class="custom-check"></span>
        Send Login Details Email
        <span class="mail-icon">📩</span>
    </label>
</div>

<button class="btn-save" type="submit">Save User</button>

</form>
<script>
function validateForm() {

    let isValid = true;

    // Clear all errors
    document.querySelectorAll(".error").forEach(e => e.innerText = "");

    let nameRegex = /^[A-Za-z ]+$/;
    let phoneRegex = /^[0-9]{10}$/;
    let numberRegex = /^[0-9]+$/;

    let fname = document.getElementById("first_name").value.trim();
    let lname = document.getElementById("last_name").value.trim();
    let email = document.getElementById("email").value.trim();
    let phone = document.getElementById("phone").value.trim();
    let status = document.getElementById("status").value;
    let address = document.getElementById("address").value.trim();
    let city = document.getElementById("city").value.trim();
    let state = document.getElementById("state").value.trim();
    let zip = document.getElementById("zip").value.trim();
    let password = document.getElementById("password").value.trim();
    let role = document.getElementById("role").value;

    // First Name
    if (!nameRegex.test(fname)) {
        document.getElementById("fnameError").innerText = "Only alphabets allowed";
        isValid = false;
    }

    // Last Name
    if (!nameRegex.test(lname)) {
        document.getElementById("lnameError").innerText = "Only alphabets allowed";
        isValid = false;
    }

    // Email
    if (email === "") {
        document.getElementById("emailError").innerText = "Email required";
        isValid = false;
    }

    // Phone
    if (!phoneRegex.test(phone)) {
        document.getElementById("phoneError").innerText = "10 digit number required";
        isValid = false;
    }

    // Status
    if (status === "") {
        document.getElementById("statusError").innerText = "Select status";
        isValid = false;
    }

    // Address
    if (address === "") {
        document.getElementById("addressError").innerText = "Address required";
        isValid = false;
    }

    // City
    if (!nameRegex.test(city)) {
        document.getElementById("cityError").innerText = "Only alphabets allowed";
        isValid = false;
    }

    // State
    if (!nameRegex.test(state)) {
        document.getElementById("stateError").innerText = "Only alphabets allowed";
        isValid = false;
    }

    // ZIP
    if (!numberRegex.test(zip)) {
        document.getElementById("zipError").innerText = "Only numbers allowed";
        isValid = false;
    }

    // Password
    if (password.length < 6) {
        document.getElementById("passError").innerText = "Minimum 6 characters";
        isValid = false;
    }

    // Role
    if (role === "") {
        document.getElementById("roleError").innerText = "Select role";
        isValid = false;
    }

    return isValid;
}
function validateSingleField(id, regex = null, type = '', errorId) {

    let input = document.getElementById(id);
    let value = input.value.trim();
    let error = document.getElementById(errorId);

    let isValid = true;

    if (type === 'required') {
        isValid = value !== '';
    }

    if (regex) {
        isValid = regex.test(value);
    }

    if (type === 'password') {
        isValid = value.length >= 6;
    }

    if (isValid) {
        error.innerText = "";
        input.style.border = "1px solid #ccc";
    } else {
        input.style.border = "1px solid red";
    }
}
</script>

<script>
window.onload = function () {

    setTimeout(function () {

        let errorBox = document.querySelector("#errorBox");
        let successMsg = document.querySelector("#successMsg");

        if (errorBox !== null) {
            errorBox.style.opacity = "0";
            errorBox.style.transition = "opacity 0.5s";

            setTimeout(() => {
                errorBox.remove();
            }, 500);
        }

        if (successMsg !== null) {
            successMsg.style.opacity = "0";
            successMsg.style.transition = "opacity 0.5s";

            setTimeout(() => {
                successMsg.remove();
            }, 500);
        }

    }, 2000);
};
</script>
<?= $this->endSection(); ?>
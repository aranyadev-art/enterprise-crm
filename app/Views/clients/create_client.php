<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<?php if(session('errors')): ?>
    <div class="error-box" style="color:red; background:#ffe6e6; padding:10px; margin-bottom:10px;">
        <?php foreach(session('errors') as $error): ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<link rel="stylesheet" href="<?= base_url('css/styleclient.css') ?>">


<div class="zxcrmClient"> <!-- 🔥 UNIQUE WRAPPER -->

    <div class="client-wrapper">

        <h2 class="client-heading">Add Client</h2>

        <div class="client-box">

            <form id="clientForm" method="POST" action="<?= base_url('clients/save') ?>">

                <div class="grid-3">

                    <div class="field">
                        <label>First Name</label>
                        <input type="text" name="first_name" placeholder="Enter first name">
                    </div>

                    <div class="field">
                        <label>Last Name</label>
                        <input type="text" name="last_name" placeholder="Enter last name">
                    </div>

                    <div class="field">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter email">
                    </div>

                    <div class="field">
                        <label>Phone</label>
                        <input type="text" name="phone" placeholder="10 digit phone">
                    </div>

                    <div class="field">
                        <label>Credit Limit</label>
                        <input type="text" name="credit_limit" placeholder="Credit Limit">
                    </div>

                    <div class="field">
                        <label>Due Balance</label>
                        <input type="text" name="due_balance" placeholder="Due Balance">
                    </div>

                    <div class="field">
                        <label>Address</label>
                        <input type="text" name="address" placeholder="Enter address">
                    </div>

                    <div class="field">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Enter city">
                    </div>

                    <div class="field">
                        <label>State</label>
                        <input type="text" name="state" placeholder="Enter state">
                    </div>

                    <div class="field">
                        <label>ZIP</label>
                        <input type="text" name="zip" placeholder="5 digit zip">
                    </div>

                    <div class="field">
                        <label>Country</label>
                        <input type="text" name="country" placeholder="Enter country">
                    </div>
                     <div class="email-box">
                        <label class="email-label">
                        <input type="checkbox" name="send_email" value="1">
                          <span class="custom-check"></span>
                              Send Login Details Email
                              <span class="mail-icon">📩</span>
                          </label>
                      </div>


                    <div class="field">
                        <label>Status</label>
                        <select name="status">
                            <option>Select</option>
                            <option>Active</option>
                            <option>Inactive</option>
                        </select>
                    </div>

                <div class="btn-area">
                    <button type="submit" class="btn-primary">Save Client</button>
                    <button type="button" class="btn-secondary">Cancel</button>
                </div>

            </form>

        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

$(document).ready(function(){
                
    $('#clientForm').on('submit', function(e){

        let valid = true;

        $('.error').remove(); // old errors remove

        function showError(input, message){
            valid = false;
            $(input).after('<span class="error">'+message+'</span>');
        }

        // First Name
        let firstName = $('input[name="first_name"]').val().trim();
        if(firstName === ''){
            showError('input[name="first_name"]', 'First name required');
        }

        // Email
        let email = $('input[name="email"]').val().trim();
        let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        if(email === ''){
            showError('input[name="email"]', 'Email required');
        } else if(!emailPattern.test(email)){
            showError('input[name="email"]', 'Invalid email');
        }

        // Phone (10 digit)
        let phone = $('input[name="phone"]').val().trim();
        if(phone !== '' && !/^[0-9]{10}$/.test(phone)){
            showError('input[name="phone"]', 'Enter 10 digit phone');
        }

        // Credit Limit
        let credit = $('input[name="credit_limit"]').val().trim();
        if(credit !== '' && isNaN(credit)){
            showError('input[name="credit_limit"]', 'Must be number');
        }

        // Due Balance
        let due = $('input[name="due_balance"]').val().trim();
        if(due !== '' && isNaN(due)){
            showError('input[name="due_balance"]', 'Must be number');
        }

        // ZIP
        let zip = $('input[name="zip"]').val().trim();
        if(zip !== '' && !/^[0-9]{5}$/.test(zip)){
            showError('input[name="zip"]', 'Enter 5 digit ZIP');
        }

        // Status
        let status = $('select[name="status"]').val();
        if(status === 'Select'){
            showError('select[name="status"]', 'Select status');
        }

         
        if(!valid){
            e.preventDefault(); // stop submit
        }

    });

});
$(document).ready(function(){
            let emailExists = false;
    function showError(input, message){
        let el = $(input);

        el.addClass('error-border');

        if(el.next('.error').length === 0){
            el.after('<span class="error">'+message+'</span>');
        }
    }

    function removeError(input){
        let el = $(input);
        el.removeClass('error-border');
        el.next('.error').remove();
    }

    function validateField(input){
        let el = $(input);
        let name = el.attr('name');
        let val = el.val().trim();

        switch(name){

            case 'first_name':
                return val !== '';

            case 'email':
                return /^[^ ]+@[^ ]+\.[a-z]{2,3}$/.test(val);

            case 'phone':
                return val === '' || /^[0-9]{10}$/.test(val);

            case 'credit_limit':
            case 'due_balance':
                return val === '' || !isNaN(val);

            case 'zip':
                return val === '' || /^[0-9]{5}$/.test(val);

            case 'status':
                return val !== 'Select';

            default:
                return true;
        }
    }

    // 🔹 REAL-TIME (common for all)
    $('#clientForm').on('input change', 'input, select', function(){

        if(validateField(this)){
            removeError(this);
        }

    });

    // 🔹 SUBMIT
   $('#clientForm').on('submit', function(e){

    e.preventDefault(); // 🚨 always stop

    let form = this;
    let valid = true;

    $('.error').remove();
    $('input, select').removeClass('error-border');

    // 🔹 NORMAL VALIDATION
    $('input, select').each(function(){

        if(!validateField(this)){
            valid = false;

            let name = $(this).attr('name');
            let message = 'Invalid field';

            if(name === 'first_name') message = 'First name required';
            if(name === 'email') message = 'Valid email required';
            if(name === 'phone') message = 'Enter 10 digit phone';
            if(name === 'credit_limit') message = 'Must be number';
            if(name === 'due_balance') message = 'Must be number';
            if(name === 'zip') message = 'Enter 5 digit ZIP';
            if(name === 'status') message = 'Select status';

            showError(this, message);
        }
    });

    // ❌ STOP HERE IF NORMAL VALIDATION FAIL
    if(!valid){
        return false;
    }

    // 🔥 EMAIL DUPLICATE CHECK (FINAL)
    let email = $('input[name="email"]').val().trim();

$.ajax({
    url: "<?= base_url('clients/check-email') ?>",
    type: "POST",
    data: {
        email: email,
        '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
    },

    success: function(res){
        res = res.trim();

        if(res === 'exists'){
            showError('input[name="email"]', 'Email already exists');
        } else {
            form.submit();
        }
    },

  error: function(xhr){
    console.log("STATUS:", xhr.status);
    console.log("RESPONSE:", xhr.responseText);

    // 🔥 FALLBACK (VERY IMPORTANT)
    form.submit(); 
}
});

});
            });
            $(document).ready(function(){

    setTimeout(function(){
        $('.error-box').fadeOut();
    }, 2000); // 2000ms = 2 sec

});
</script>
<?= $this->endSection(); ?>
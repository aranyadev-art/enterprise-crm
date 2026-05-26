<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>
<!DOCTYPE html>
<html>

<head>
<title>Create Sales Job</title>
<link rel="stylesheet" href="<?= base_url('css/sales.css') ?>">


<body>

<div class="sl-container">
<h2 class="sl-title">Create Sales Job</h2>

<form id="salesForm" method="POST" action="<?= base_url('sales/save') ?>" enctype="multipart/form-data">

<div class="sl-row">
    <div class="sl-field">
        <label>Client</label>
        <select name="client_id">
            <option value="">Select Client</option>
            <?php foreach($clients as $client): ?>
            <option value="<?= $client['id'] ?>">
                <?= $client['first_name'].' '.$client['last_name'] ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="sl-field">
        <label>CAD Designer</label>
        <select name="cad_user_id">
            <option value="">Select Designer</option>
        </select>
    </div>
</div>

<div class="sl-row">
    <div class="sl-field">
        <label>Quantity</label>
        <input type="number" name="quantity">
    </div>

    <div class="sl-field">
        <label>Status</label>
        <select name="status">
            <option value="Pending">Pending</option>
            <option value="In Progress">In Progress</option>
            <option value="Completed">Completed</option>
        </select>
    </div>
</div>

<div class="sl-row">
    <div class="sl-field full">
        <label>Design Image</label>

        <input type="file" name="design_file" id="design_file" accept="image/*">

        <div class="sl-preview-box">
            <img id="preview" width="80" style="display:none;" />
        </div>
    </div>
</div>

<div class="sl-field full">
    <label>Notes</label>
    <textarea name="notes"></textarea>
</div>

<div class="sl-row">
    <div class="sl-field">
        <label>Tracking Number</label>
        <input type="text" name="tracking_number" value="<?= $tracking ?>" readonly>
    </div>
</div>

<button type="submit" class="sl-submit">
    Create Sales Job
</button>

</form>

</div>

</body>
</head>
</html>
<script>
$(document).ready(function(){

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
        let val = el.val();

        switch(name){

            case 'client_id':
                return val !== '';

            case 'quantity':
                return val !== '' && parseInt(val) > 0;

        case 'design_file':
           let fileInput = input.files;
           if(fileInput.length === 0) return true; // optional field
           let file = fileInput[0];
           return file.type.startsWith('image/');
            default:
                return true;
        }
    }
    // 🔥 REAL-TIME AUTO REMOVE (ALL FIELDS)
$('#salesForm').on('input change', 'input, select', function(){

    if(validateField(this)){
        removeError(this);
    }

});

    // 🔹 REAL-TIME VALIDATION
   $('#salesForm').on('submit', function(e){

    let valid = true;

    $('.error').remove();
    $('input, select').removeClass('error-border');

    // 🔹 NORMAL FIELDS
    $('input, select').each(function(){

        if(!validateField(this)){
            valid = false;

            let name = $(this).attr('name');
            let message = 'Invalid field';

            if(name === 'client_id') message = 'Select client';
            if(name === 'quantity') message = 'Enter valid quantity';

            showError(this, message);
        }
    });

    // 🔹 DESIGN FILE VALIDATION
    let fileInput = document.getElementById('design_file');

    if(fileInput.files.length === 0){
        showError(fileInput, 'Design image required');
        valid = false;
    } else {
        let file = fileInput.files[0];

        if(!file.type.startsWith('image/')){
            showError(fileInput, 'Only image allowed');
            valid = false;
        }
    }

    // 🚨 ONLY STOP IF INVALID
    if(!valid){
        e.preventDefault();
    }

   });
   
});

</script>
<?= $this->endSection(); ?>

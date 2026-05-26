<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="cad-error">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="crm-card">

<div class="cad-wrap">

<h2 class="cad-title">CAD Module</h2>

<div class="cad-box">

<form method="post" action="<?= base_url('cod/save') ?>" enctype="multipart/form-data">
<input type="hidden" name="sale_id" value="<?= $sale_id ?? '' ?>">
<div class="form-group cad-group">
<label>CAD Code</label>
<input type="text" name="cad_code" value="<?= $cad_code ?>" readonly>
</div>

<div class="form-group cad-group">
<label>Start Time</label>
<input type="datetime-local" name="start_time" id="start_time">
<span class="cad-err" id="startErr"></span>
</div>

<div class="form-group cad-group">
<label>End Time</label>
<input type="datetime-local" name="end_time" id="end_time">
<span class="cad-err" id="endErr"></span>
</div>

<div class="form-group cad-group">
<label>Duration</label>
<input type="text" name="duration">
</div>

<div class="form-group cad-group">
    <label>Client Email</label>
    <input type="email" name="client_email" placeholder="Enter client email">
</div>

<div class="form-group cad-group">
    <label>Factory Email</label>
    <input type="email" name="factory_email" placeholder="Enter factory email">
</div>

<div class="form-group cad-group">
<label>Upload Design</label>
<input type="file" name="design_file">
</div>

<div class="form-group cad-check">
    <label>CPX Sent</label><br>
    <small style="color: gray;">Auto (will be marked YES when design is uploaded)</small>
</div>
<br>
<div class="form-group cad-group">
<label>Comment</label>
<textarea name="comment"></textarea>
</div>

<button class="btn-save cad-btn">Save CAD</button>
<a href="<?= base_url('cod/list') ?>" class="btn-back cad-back">Back</a>

</form>

</div>
</div>

</div>



<script>

document.querySelector('[name="end_time"]').addEventListener('change', function(){

let start = new Date(document.querySelector('[name="start_time"]').value);
let end = new Date(this.value);

let diff = (end - start) / 1000 / 60;

let hours = Math.floor(diff/60);
let minutes = diff % 60;

document.querySelector('[name="duration"]').value = hours+"h "+minutes+"m";

});

</script>
<script>
document.getElementById("design_file").addEventListener("change", function() {

    if(this.files.length > 0){
        document.getElementById("cpx_sent").checked = true;
    }

});
</script>

<script>
$(document).ready(function(){

    $("form").submit(function(e){

        let isValid = true;

        // clear old errors
        $(".cad-err").text("");

        let start = $("#start_time").val();
        let end   = $("#end_time").val();

        // Start validation
        if(start === ""){
            $("#startErr").text("Start time required");
            isValid = false;
        }

        // End validation
        if(end === ""){
            $("#endErr").text("End time required");
            isValid = false;
        }

        // Time logic
        if(start !== "" && end !== ""){
            let startTime = new Date(start);
            let endTime   = new Date(end);

            if(endTime < startTime){
                $("#endErr").text("End must be greater than Start");
                isValid = false;
            }
        }

        // stop form submit
        if(!isValid){
            e.preventDefault();
        }

    });

});
</script>

<?= $this->endSection(); ?>
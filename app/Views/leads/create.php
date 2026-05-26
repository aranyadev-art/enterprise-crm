<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<h2>Add New Lead</h2>

<form id="leadForm">

    <label>Name</label><br>
    <input type="text" name="name" placeholder="Name" required><br>

    <label>Email</label><br>
    <input type="email" name="email" placeholder="Email" required><br>

    <label>Phone</label><br>
    <input type="text" name="phone" placeholder="Phone"><br>

    <label>Company</label><br>
    <input type="text" name="company" placeholder="Company"><br>

    <label>Follow Up Date</label>
     <input type="date" name="follow_up_date">

    <button type="submit">Save Lead</button>

</form>

<!-- ✅ Message will show here -->
<div id="msg"></div>

<br>

<a href="<?= base_url('leads') ?>">Back to List</a>


<!-- ✅ jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    $('#leadForm').on('submit', function(e){
        e.preventDefault();
        console.log("AJAX triggered"); // debug

        $.ajax({
            url: "<?= base_url('leads/store') ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",

            success: function(res){
                if(res.status === 'success'){
                    $('#msg').html('<div class="success">'+res.message+'</div>');
                    $('#leadForm')[0].reset();
                       setTimeout(function(){
            window.location.href = "<?= base_url('leads') ?>";
        },1000);
                    setTimeout(function(){
                        $('#msg').fadeOut(function(){
                            $(this).html('').show();
                        });
                    },2000);

                } else {
                    $('#msg').html('<div class="error">'+res.message+'</div>');
                }
            },
              
            error: function(xhr){
                console.log(xhr.responseText);
                $('#msg').html('<div class="error">Something went wrong</div>');
            }
        });

    });

});
</script>
<?= $this->endSection(); ?>
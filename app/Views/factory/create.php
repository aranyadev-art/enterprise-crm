<?= $this->extend('layout'); ?>
<?= $this->section('content'); ?>

<h2 class="or-title">Create Factory Entry</h2>
<p class="or-subtitle">Enter factory production details</p>

<div class="or-container">

<form class="or-form" method="POST" action="<?= base_url('factory/save') ?>">
<input type="hidden" name="order_id" value="<?= $order_id ?>">

<!-- 🔵 BASIC DETAILS -->
<div class="or-card">
    <div class="or-card-header">Basic Details</div>
    <div class="or-card-body">
        <div class="or-row">
            <div class="or-field">
                <label>Employee Name</label>
                <input type="text" name="employee_name" required>
            </div>

            <div class="or-field">
                <label>Email</label>
                <input type="email" name="client_email" value="<?= $client_email ?? '' ?>">
            </div>
        </div>
    </div>
</div>

<!-- 🔵 ORDER INFO -->
<div class="or-card">
    <div class="or-card-header">Order Information</div>
    <div class="or-card-body">
        <div class="or-row">
            <div class="or-field">
                <label>Order Number</label>
                <input type="text" name="order_no" value="<?= $order_no ?? '' ?>" readonly>
            </div>

            <div class="or-field">
                <label>Shipping Number</label>
                <input type="text" name="shipping_no" value="<?= $shipping_no ?? '' ?>" readonly>
            </div>
        </div>
    </div>
</div>

<!-- 🔵 WEIGHT DETAILS -->
<div class="or-card highlight-card">
    <div class="or-card-header">Weight Details</div>
    <div class="or-card-body">
        <div class="or-row">
            <div class="or-field">
                <label>Metal Weight</label>
                <input type="number" step="0.01" name="metal_weight" id="metal_weight">
            </div>

            <div class="or-field">
                <label>Stone Weight</label>
                <input type="number" step="0.01" name="stone_ct_weight" id="stone_weight">
            </div>
        </div>

        <div class="or-row">
            <div class="or-field full-width">
                <label>Final Weight</label>
                <input type="text" name="final_weight" id="final_weight" readonly class="highlight-field">
            </div>
        </div>
    </div>
</div>
<!-- 🔵 ACTIONS -->
<div class="or-footer">
    <div class="or-check">
        <label>
            <input type="checkbox" name="send_email" value="1">
            Send Email Notification 📩
        </label>
    </div>

    <div class="or-actions">
        <button type="submit" class="or-btn-primary">Save Entry</button>
    </div>
</div>

</form>
</div>

<script>
$(document).ready(function(){

    function calculateFinalWeight(){
        let metal = parseFloat($("#metal_weight").val()) || 0;
        let stone = parseFloat($("#stone_weight").val()) || 0;

        let total = metal + stone;

        $("#final_weight").val(total.toFixed(2));
    }

    $("#metal_weight, #stone_weight").on("input", function(){
        calculateFinalWeight();
    });

});
</script>

<?= $this->endSection(); ?>
<link rel="stylesheet" href="<?= base_url('css/style.css') ?>">

<div class="payment-container">
    <h2>Add Payment</h2>

    <form method="post" action="<?= base_url('accounts/savePayment') ?>" id="paymentForm">

        <input type="hidden" name="account_id" value="<?= $account_id ?>">

        <label>Amount</label>
        <input type="number" name="amount" required>

        <!-- Hidden method field -->
        <input type="hidden" name="payment_method" id="payment_method">

        <!-- UPI Field -->
        <div id="upi_field" style="display:none;">
            <label>UPI ID</label>
            <input type="text" name="upi_id" placeholder="example@upi">
        </div>

        <button type="button" onclick="handlePayment()">Pay Now</button>

    </form>
</div>

<!-- 🔥 Payment Popup -->
<div id="paymentModal" style="display:none; background:#00000080; position:fixed; top:0; left:0; width:100%; height:100%;">

    <div style="background:white; padding:20px; width:320px; margin:100px auto; border-radius:10px; text-align:center;">

        <h3>Select Payment Method</h3>

        <button onclick="payNow('cash')" style="width:100%; padding:10px; margin:5px;">💵 Cash</button>

        <button onclick="selectUPI()" style="width:100%; padding:10px; margin:5px;">📱 UPI</button>

        <button onclick="payNow('card')" style="width:100%; padding:10px; margin:5px;">💳 Card</button>

        <br><br>
        <button onclick="closeModal()">Cancel</button>

    </div>
</div>

<script>

function openPayment(){
    document.getElementById('paymentModal').style.display = 'block';
}

function closeModal(){
    document.getElementById('paymentModal').style.display = 'none';
}

// ✅ Direct payment (cash/card)
function payNow(method){

    document.getElementById('payment_method').value = method;

    alert("Payment Successful via " + method);

    document.getElementById('paymentForm').submit();
}

// ✅ UPI special flow
function selectUPI(){

    document.getElementById('payment_method').value = 'upi';

    // show UPI field
    document.getElementById('upi_field').style.display = 'block';

    alert("Enter UPI ID and click Pay again");

    closeModal();
}

function handlePayment(){

    let method = document.getElementById('payment_method').value;

    // ✅ Agar UPI already selected hai → direct submit
    if(method === 'upi'){
        document.getElementById('paymentForm').submit();
    } 
    else if(method === 'cash' || method === 'card'){
        document.getElementById('paymentForm').submit();
    } 
    else {
        // ❌ first time → open popup
        openPayment();
    }
}

</script>
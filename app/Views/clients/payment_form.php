<!DOCTYPE html>
<html>
<head>
    <title>Make Payment</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .payment-container {
            width: 380px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .payment-container h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .payment-container p {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .payment-input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        .payment-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102,126,234,0.3);
        }

        .payment-btn {
            width: 100%;
            padding: 12px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
        }

        .payment-btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
        }

        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            font-size: 13px;
            color: #667eea;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="payment-container">

    <h2>💰 Make Payment</h2>
    <p>Enter amount to proceed</p>

    <!-- ✅ FIXED ROUTE -->
    <form method="post" action="<?= base_url('client/savePayment') ?>">

        <input 
            type="number" 
            name="amount" 
            placeholder="Enter Amount" 
            class="payment-input"
            required
        >

        <button type="submit" class="payment-btn">
            Submit Payment
        </button>

    </form>

    <a href="<?= base_url('client/dashboard') ?>" class="back-link">
        ← Back to Dashboard
    </a>

</div>

</body>
</html>
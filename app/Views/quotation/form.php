<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<h2 class="qt-title">Create Quotation</h2>

<div class="qt-container">

<form method="POST" action="<?= base_url('index.php/quotation/create') ?>">

<div class="qt-row">
    <div class="qt-field">
        <label>Metal Price</label>
        <input type="text" name="metal_price">
    </div>

    <div class="qt-field">
        <label>Stone Price</label>
        <input type="text" name="stone_price">
    </div>
</div>

<div class="qt-row">
    <div class="qt-field">
        <label>Purity</label>
        <select name="purity">
            <option value="75">75</option>
            <option value="91.6">91.6</option>
        </select>
    </div>

    <div class="qt-field">
        <label>Currency</label>
        <select name="currency">
            <option value="INR">INR</option>
            <option value="USD">USD</option>
        </select>
    </div>
</div>

<div class="qt-check">
    <label class="qt-check-label">
        <input type="checkbox" name="send_email" value="1">
        <span class="qt-check-box"></span>
        Send Email 📩
    </label>
</div>

<div class="qt-actions">
    <button type="button" class="qt-calc" onclick="calculateTotal()">Calculate</button>
</div>

<div class="qt-row">
    <div class="qt-field">
        <label>Total Price</label>
        <input type="text" id="total_price" readonly>
    </div>
</div>
   <label>Client</label>
<select name="client_id" required class="form-control">
    <option value="">Select Client</option>

    <?php foreach ($clients as $c): ?>
        <option value="<?= $c['id'] ?>">
            <?= $c['first_name'] . ' ' . $c['last_name'] ?>
        </option>
    <?php endforeach; ?>
    </select> <!-- 🔥 THIS WAS MISSING -->
<label>Status</label>

<select name="status" class="status-dropdown" required>
    <option value="">Select Status</option>
    <option value="Proposed by Client">Proposed by Client</option>
    <option value="Approved by Client">Approved by Client</option>
    <option value="Create Final Quote">Create Final Quote</option>
</select>
<div class="qt-field full">
    <label>Price Breakdown</label>
    <p id="breakdown" class="qt-break"></p>
</div>

<button type="submit" class="qt-submit">Create Quotation</button>

</form>

</div>

<script>
function calculateTotal() {
    let metal = parseFloat(document.getElementsByName('metal_price')[0].value) || 0;
    let stone = parseFloat(document.getElementsByName('stone_price')[0].value) || 0;
    let purity = parseFloat(document.getElementsByName('purity')[0].value) || 0;

    let total = (metal + stone) * purity / 100;

    document.getElementById('total_price').value = total.toFixed(2);

    document.getElementById('breakdown').innerText =
        "Metal: " + metal +
        " | Stone: " + stone +
        " | Purity: " + purity +
        "% | Total: " + total.toFixed(2);
}
</script>
<?= $this->endSection(); ?>
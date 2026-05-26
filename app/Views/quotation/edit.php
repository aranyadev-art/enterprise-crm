<style>
body {
    background: #f4f7fb;
    font-family: 'Segoe UI', sans-serif;
}

/* Container */
.crm-card {
    width: 900px;              /* 🔥 width bada */
    max-width: 95%;
    margin: 60px auto;
    background: #fff;
    padding: 40px 50px;       /* thoda horizontal space */
    border-radius: 12px;      /* less round = rectangle feel */
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* Header */
.crm-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.crm-header h2 {
    font-size: 22px;
    color: #2c3e50;
}

.crm-badge {
    background: #eef2ff;
    color: #4c6fff;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
}

/* Labels */
.crm-form label {
    font-size: 13px;
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
    display: block;
}

/* Inputs */
.crm-form input,
.crm-form select {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #dcdfe6;
    font-size: 15px;
    transition: 0.3s;
}

/* Focus */
.crm-form input:focus,
.crm-form select:focus {
    border-color: #4c6fff;
    box-shadow: 0 0 0 3px rgba(76,111,255,0.15);
    outline: none;
}

/* Grid */
.crm-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
        gap: 25px;   /* space increase */
}

/* Button */
.crm-btn {
    width: 100%;
    margin-top: 25px;
    padding: 14px;
    border: none;
    border-radius: 8px;
    background: linear-gradient(135deg, #4c6fff, #6f86ff);
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.crm-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(76,111,255,0.3);
}

/* Spacing */
.crm-group {
    margin-bottom: 20px;
}
</style>

<div class="crm-card">

    <div class="crm-header">
        <h2>Edit Quotation</h2>
        <span class="crm-badge">#<?= $quotation['quotation_id'] ?></span>
    </div>

    <form method="post" action="<?= base_url('index.php/quotation/update/'.$quotation['id']) ?>" class="crm-form">

        <!-- Client -->
        <div class="crm-group">
            <label>Client</label>
            <select name="client_id" required>
                <?php foreach ($clients as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= ($c['id'] == $quotation['client_id']) ? 'selected' : '' ?>>
                        <?= $c['first_name'] . ' ' . $c['last_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Row -->
        <div class="crm-row">
            <div class="crm-group">
                <label>Total</label>
                <input type="text" name="total_price" value="<?= $quotation['total_price'] ?>">
            </div>

            <div class="crm-group">
                <label>Currency</label>
                <input type="text" name="currency" value="<?= $quotation['currency'] ?>">
            </div>
        </div>

        <!-- Status -->
        <div class="crm-group">
            <label>Status</label>
            <select name="status">
                <option <?= $quotation['status']=='Proposed by Client'?'selected':'' ?>>
                    Proposed by Client
                </option>
                <option <?= $quotation['status']=='Approved by Client'?'selected':'' ?>>
                    Approved by Client
                </option>
                <option <?= $quotation['status']=='Create Final Quote'?'selected':'' ?>>
                    Create Final Quote
                </option>
            </select>
        </div>

        <button type="submit" class="crm-btn">Update Quotation</button>

    </form>

</div>
<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<h2 style="margin-bottom:20px;">Calculator</h2>

<form action="<?= base_url('calculator/upload') ?>" method="post" enctype="multipart/form-data">

    <div class="calc-row">

        <!-- Client -->
        <div class="calc-col">
            <label>Select Client</label>
 <select name="client" class="calc-input-light" required>
    <option value="">Select</option>

    <?php if(!empty($clients)): ?>
        <?php foreach($clients as $c): ?>
            <option value="<?= $c['id'] ?>">
                <?= $c['first_name'] . ' ' . $c['last_name'] ?>
            </option>
        <?php endforeach; ?>
    <?php endif; ?>

</select>
        </div>

        <!-- Metal -->
        <div class="calc-col">
            <label>Select Metal</label>
            <select name="metal" class="calc-input-light" required>
                <option value="">Select</option>
                <option value="gold">Gold</option>
                <option value="silver">Silver</option>
            </select>
        </div>

        <!-- File -->
        <div class="calc-col">
            <label>Upload TXT File</label>
            <input type="file" name="txt_file" class="calc-input-light" required>
        </div>

    </div>

    <button type="submit" class="calc-btn-green">
        Upload
    </button>

</form>
<?php if (!empty($resultData)) : ?>

    <?php foreach ($resultData as $shape => $ranges) : ?>

        <h2 style="margin-top:30px;"><?= $shape ?></h2>

        <div class="card-container">

            <?php foreach ($ranges as $label => $group) : ?>

                <div class="card">

                    <h3><?= $label ?></h3>

                    <p><strong>Gems:</strong> <?= $group['gems'] ?></p>
                    <p><strong>Weight:</strong> <?= number_format($group['weight'], 3) ?> ct</p>

                    <table>
                        <tr>
                            <th>Size</th>
                            <th>Qty</th>
                            <th>CTW</th>
                        </tr>

                        <?php if (!empty($group['items'])) : ?>
                            <?php foreach ($group['items'] as $item) : ?>
                                <tr>
                                    <td><?= $item['size'] ?></td>
                                    <td><?= $item['qty'] ?></td>
                                   <td><?= number_format($item['ctw'] / $item['qty'], 5) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="3">No Data</td>
                            </tr>
                        <?php endif; ?>

                    </table>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
<?= $this->endSection(); ?>

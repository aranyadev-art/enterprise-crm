<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="ship-filter-card">

<form method="GET">

<div class="row g-3 align-items-end">

    <!-- Tracking -->

    <div class="col-md-4">

        <label class="ship-label">
            Tracking Number
        </label>

        <input type="text"
               name="tracking_number"
               class="form-control ship-input"
               placeholder="Search Tracking Number">

    </div>

    <!-- Status -->

    <div class="col-md-3">

        <label class="ship-label">
            Status
        </label>

        <select name="status"
                class="form-select ship-input">

            <option value="">
                All Status
            </option>

            <option value="Pending">
                Pending
            </option>

            <option value="Delivered">
                Delivered
            </option>

            <option value="Dispatched">
                Dispatched
            </option>

        </select>

    </div>

    <!-- Date -->

    <div class="col-md-3">

        <label class="ship-label">
            Dispatch Date
        </label>

        <input type="date"
               name="dispatch_date"
               class="form-control ship-input">

    </div>

    <!-- Buttons -->

    <div class="col-md-2">

        <div class="ship-btn-group">

            <button type="submit"
                    class="btn btn-primary ship-btn">

                Search

            </button>

            <a href="<?= base_url('shipping') ?>"
               class="btn btn-light border ship-btn">

                Reset

            </a>

        </div>

    </div>

</div>

</form>

</div>
<div class="shipping-wrapper">

<h2 class="shipping-heading">Shipping List</h2>

<a href="<?= base_url('shipping/create') ?>" class="btn-add">
    Add Shipping
</a>

<form method="post" action="<?= base_url('shipping/deleteMultiple') ?>">

<table class="shipping-table-new">
<tr>
    <th><input type="checkbox" id="selectAll"></th>
    <th>GFJ No</th>
    <th>Product</th>
    <th>Client</th>
    <th>Tracking</th>
    <th>Dispatch Date</th>
    <th>Status</th>
</tr>

<?php foreach($shipping as $row): ?>
<tr>
    <td><input type="checkbox" name="ids[]" value="<?= $row['id'] ?>"></td>
    <td><?= $row['gfj_no'] ?></td>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['first_name'] . ' ' . $row['last_name'] ?></td>
    <td><?= $row['tracking_number'] ?></td>
    <td>

    <?= date(
        'd M Y',
        strtotime($row['dispatch_date'])
    ) ?>

</td>
      <td>

        <?php if($row['status'] == 'Pending'){ ?>

            <span class="badge bg-warning">
                Pending
            </span>

        <?php } ?>

        <?php if($row['status'] == 'Delivered'){ ?>

            <span class="badge bg-success">
                Delivered
            </span>

        <?php } ?>


         <?php if($row['status'] == 'Dispatched'){ ?>

            <span class="badge bg-info">
                Dispatched
            </span>
        <?php } ?>

           <?php if($row['status'] == 'In Transit'){ ?>

            <span class="badge bg-info">
              In Transit
            </span>
        <?php } ?>


           <?php if($row['status'] == ' Delivered'){ ?>

            <span class="badge bg-info">
               Delivered
            </span>
        <?php } ?>



    </td>
</tr>
<?php endforeach; ?>

</table>

<br>

<button class="btn-delete">Delete Selected</button>

</form>
  
</div>
 
<script>
document.getElementById('selectAll').addEventListener('click', function() {
    let checkboxes = document.querySelectorAll('input[name="ids[]"]');
    for (let checkbox of checkboxes) {
        checkbox.checked = this.checked;
    }
});
</script>

<?= $this->endSection(); ?>
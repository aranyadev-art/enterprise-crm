<link rel="stylesheet" href="<?= base_url('css/roster-module.css') ?>">

<div class="rmf-card">

    <div class="rmf-header">
        <h2>Create Roster</h2>
    </div>

    <form method="post" action="<?= base_url('roster/save') ?>" class="rmf-form">

        <!-- TOP ROW -->
        <div class="rmf-row">

            <!-- DATE -->
            <div class="rmf-group">
                <label>Date</label>
                <input type="date" name="date" required>
            </div>

            <!-- CAD -->
            <div class="rmf-group">
                <label>CAD Designer</label>
                <select name="cad_id" required>
                    <option value="">Select CAD</option>
                    <?php foreach($cad as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['cad_code'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <!-- DRAG SECTION -->
        <div class="rmf-drag-wrapper">

            <!-- AVAILABLE -->
            <div class="rmf-box">
                <div class="rmf-box-header">Available</div>

                <div id="available" class="rmf-list">
                    <?php foreach($users as $u): ?>
                        <div class="rmf-item" data-id="<?= $u['id'] ?>">
                            ☰ <?= $u['first_name'] . ' ' . $u['last_name'] ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SELECTED -->
            <div class="rmf-box">
                <div class="rmf-box-header rmf-selected-header">
                    Selected (Drag to reorder)
                </div>

                <div id="selected" class="rmf-list"></div>
            </div>

        </div>

        <!-- ACTION BUTTONS -->
        <div class="rmf-actions">
            <button type="submit" class="rmf-btn rmf-save">Save</button>
            <a href="<?= base_url('roster') ?>" class="rmf-btn rmf-cancel">Cancel</a>
        </div>

    </form>

</div>

<!-- SORTABLE -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Available (clone only)
    new Sortable(document.getElementById('available'), {
        group: {
            name: 'shared',
            pull: 'clone',
            put: false
        },
        sort: false,
        animation: 150
    });

    // Selected
    new Sortable(document.getElementById('selected'), {
        group: 'shared',
        animation: 150
    });

    // Submit
    document.querySelector('.rmf-form').addEventListener('submit', function() {

        let selected = document.querySelectorAll('#selected .rmf-item');

        selected.forEach(item => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_sales[]';
            input.value = item.dataset.id;
            this.appendChild(input);
        });

    });

});
</script>
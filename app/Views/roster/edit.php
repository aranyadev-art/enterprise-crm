<link rel="stylesheet" href="<?= base_url('css/roster-module.css') ?>">

<div class="roster-module">

<div class="rmf-card">

    <div class="rmf-header">
        <h2>Edit Roster</h2>
    </div>

    <form method="post" action="<?= base_url('roster/update/'.$roster['id']) ?>" class="rmf-form">

        <!-- DATE + CAD -->
        <div class="rmf-row">

            <!-- DATE -->
            <div class="rmf-group">
                <label>Date</label>
                <input type="date" name="date" value="<?= $roster['date'] ?>" required>
            </div>

            <!-- CAD -->
            <div class="rmf-group">
                <label>CAD Designer</label>
                <select name="cad_id" required>
                    <option value="">Select CAD</option>
                    <?php foreach($cad as $c): ?>
                        <option value="<?= $c['id'] ?>" 
                            <?= ($c['id'] == $roster['cad_designer_id']) ? 'selected' : '' ?>>
                            <?= $c['cad_code'] ?>
                        </option>
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
                        <?php if(!in_array((string)$u['id'], $selected_users)): ?>
                            <div class="rmf-item" data-id="<?= $u['id'] ?>">
                                ☰ <?= $u['first_name'].' '.$u['last_name'] ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- SELECTED -->
            <div class="rmf-box">
                <div class="rmf-box-header rmf-selected-header">
                    Selected (Drag to reorder)
                </div>

                <div id="selected" class="rmf-list">
                    <?php foreach($users as $u): ?>
                        <?php if(in_array($u['id'], $selected_users)): ?>
                            <div class="rmf-item" data-id="<?= $u['id'] ?>">
                                ☰ <?= $u['first_name'].' '.$u['last_name'] ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>

        <!-- ACTION BUTTONS -->
        <div class="rmf-actions">
            <button type="submit" class="rmf-btn rmf-save">Update</button>
            <a href="<?= base_url('roster') ?>" class="rmf-btn rmf-cancel">Cancel</a>
        </div>

    </form>

</div>

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

    // Selected (reorder + remove)
    new Sortable(document.getElementById('selected'), {
        group: 'shared',
        animation: 150
    });

    // Submit → collect selected users
    document.querySelector('.rmf-form').addEventListener('submit', function() {

        let selected = document.querySelectorAll('#selected .rmf-item');

        selected.forEach(item => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_users[]';
            input.value = item.dataset.id;
            this.appendChild(input);
        });

    });

});
</script>
<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<div class="roster-ui-card">

    <!-- HEADER -->
    <div class="roster-ui-header">
        <h2 class="roster-ui-title">Roster Management</h2>

        <a href="<?= base_url('roster/create') ?>" class="roster-ui-btn">
            + Add Roster
        </a>
    </div>

    <!-- TABLE -->
    <table class="roster-ui-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>DATE</th>
                <th>CAD DESIGNER</th>
                <th>SALES PERSON</th>
                <th>ACTION</th>
            </tr>
        </thead>

        <tbody>
            <?php if(!empty($rosters)): ?>
                <?php foreach($rosters as $r): ?>
                    <tr>

                        <td><?= $r['id'] ?></td>

                        <td><?= date('d-m-Y', strtotime($r['date'])) ?></td>

                        <td><?= $r['cad_designer_id'] ?></td>

   <td class="roster-ui-sales">
    <?php if(!empty($r['sales_names'])): ?>
        <?php foreach($r['sales_names'] as $sp): ?>
            <span class="badge"><?= $sp ?></span>
        <?php endforeach; ?>
    <?php else: ?>
        -
    <?php endif; ?>
</td>

                        <!-- ACTION -->
                        <td class="act-cell">
                            <span class="act-btn">⋮</span>

                            <div class="act-menu">
                                <a href="<?= base_url('roster/edit/'.$r['id']) ?>">Edit</a>
                                <a href="<?= base_url('roster/delete/'.$r['id']) ?>" 
                                   onclick="return confirm('Delete?')">Delete</a>
                            </div>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">No Roster Found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- ✅ SORTABLE JS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    /* ========================= */
    /* FORM OPEN / CLOSE */
    /* ========================= */
    window.openForm = function () {
        let formBox = document.getElementById('rosterForm');
        if (formBox) formBox.style.display = 'block';
    };

    window.closeForm = function () {
        let formBox = document.getElementById('rosterForm');
        if (formBox) formBox.style.display = 'none';
    };

    /* ========================= */
    /* DRAG & DROP */
    /* ========================= */
    let available = document.getElementById('available');
    let selected = document.getElementById('selected');

    if (available && selected && typeof Sortable !== "undefined") {

        new Sortable(available, {
            group: {
                name: 'shared',
                pull: 'clone',
                put: false
            },
            sort: false,
            animation: 150
        });

        new Sortable(selected, {
            group: 'shared',
            animation: 150
        });
    }

    /* ========================= */
    /* FORM SUBMIT (USERS) */
    /* ========================= */
    let form = document.querySelector('#rosterForm form');

    if (form) {
        form.addEventListener('submit', function () {

            let selectedItems = document.querySelectorAll('#selected .rmf-item');

            selectedItems.forEach(item => {
                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_users[]';
                input.value = item.dataset.id;
                this.appendChild(input);
            });

        });
    }

    /* ========================= */
    /* DROPDOWN (FIXED VERSION) */
    /* ========================= */

    document.querySelectorAll('.act-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();

            // close all dropdowns
            document.querySelectorAll('.act-menu').forEach(menu => {
                menu.style.display = 'none';
            });

            // open current dropdown
            let dropdown = this.nextElementSibling;
            if (dropdown) {
                dropdown.style.display = 'block';
            }
        });
    });

    // close when clicking outside
    document.addEventListener('click', function() {
        document.querySelectorAll('.act-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    });

});
</script>

<?= $this->endSection(); ?>

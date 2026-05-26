<?= $this->extend('layout'); ?>

<?= $this->section('content'); ?>

<?php if(session()->getFlashdata('error')): ?>

    <div class="alert alert-danger" id="errorMsg">
        <?= session()->getFlashdata('error') ?>
    </div>

<?php endif; ?>

<?php if(session()->getFlashdata('success')): ?>

    <div class="alert alert-success" id="successMsg">
        <?= session()->getFlashdata('success') ?>
    </div>

<?php endif; ?>

<style>

.alert{
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 14px 20px;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    min-width: 260px;
    z-index: 9999;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    animation: slideIn 0.3s ease;
}

.alert-success{
    background: #16a34a;
}

.alert-danger{
    background: #dc2626;
}

@keyframes slideIn{

    from{
        opacity: 0;
        transform: translateX(40px);
    }

    to{
        opacity: 1;
        transform: translateX(0);
    }
}

.lead-table-wrapper{
    padding: 20px;
}

.lead-table{
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

.lead-table th,
.lead-table td{
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

.lead-table th{
    background: #5350f1;
}

.lead-btn-add{
    background: #2563eb;
    color: #fff;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
}

.btn{
    padding: 6px 12px;
    border-radius: 5px;
    text-decoration: none;
    color: #fff;
    font-size: 13px;
}

.btn-success{
    background: #16a34a;
}

.btn-danger{
    background: #dc2626;
}

.whatsapp-btn{
    background:#25D366;
    color:white;
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:14px;
}

</style>

<script>

setTimeout(() => {

    let error = document.getElementById('errorMsg');

    if(error){
        error.style.display = 'none';
    }

    let success = document.getElementById('successMsg');

    if(success){
        success.style.display = 'none';
    }

}, 1000);

</script>

<div class="lead-table-wrapper">

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>Leads List</h2>

        <a href="<?= base_url('leads/create') ?>" class="lead-btn-add">
            + Add Lead
        </a>
    </div>

    <br>

    <table class="lead-table">

        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th> <!-- ✅ NEW -->
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Company</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>

        <?php if(!empty($leads)): ?>

            <?php foreach($leads as $lead): ?>

            <tr>
               
                   <!-- ✅ CHECKBOX -->
                <td>
                    <input type="checkbox" name="ids[]" value="<?= $lead->id ?>">
                </td>
                <td><?= $lead->id ?></td>

                <td><?= $lead->name ?></td>

                <td><?= $lead->email ?></td>

                <td><?= $lead->phone ?></td>


                <td><?= $lead->company ?></td>

                <!-- Assigned User -->
                <td>

                    <form method="post" action="<?= base_url('leads/assign/'.$lead->id) ?>">

                        <select name="assigned_to" onchange="this.form.submit()">

                            <option value="">Unassigned</option>

                            <?php foreach($users as $user): ?>

                                <option value="<?= $user['id'] ?>"
                                    <?= ($lead->assigned_to == $user['id']) ? 'selected' : '' ?>>

                                    <?= $user['first_name'].' '.$user['last_name'] ?>

                                </option>

                            <?php endforeach; ?>

                        </select>
                    
                    </form>

                </td>

                <!-- Status -->
                <td>

                    <form method="post" action="<?= base_url('leads/update-status/'.$lead->id) ?>">

                        <select name="status" onchange="this.form.submit()">

                            <option value="new" <?= $lead->status=='new'?'selected':'' ?>>
                                New
                            </option>

                            <option value="contacted" <?= $lead->status=='contacted'?'selected':'' ?>>
                                Contacted
                            </option>

                            <option value="qualified" <?= $lead->status=='qualified'?'selected':'' ?>>
                                Qualified
                            </option>

                            <option value="converted" <?= $lead->status=='converted'?'selected':'' ?>>
                                Converted
                            </option>

                            <option value="rejected" <?= $lead->status=='rejected'?'selected':'' ?>>
                                Rejected
                            </option>

                        </select>

                    </form>

                </td>

                <!-- Actions -->
                <td>

                    <?php if ($lead->status != 'converted'): ?>

                        <a href="<?= base_url('index.php/leads/convert/'.$lead->id) ?>"
                           class="btn btn-success">

                           Convert

                        </a>

                    <?php endif; ?>

                    <a href="<?= base_url('leads/delete/'.$lead->id) ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure?')">

                       Delete

                    </a>

                
              <a href="https://wa.me/91<?= $lead->phone ?>?text=Hello%20<?= urlencode($lead->name) ?>"
                    target="_blank"
                    class="whatsapp-btn">

                      WhatsApp 

                 </a>


                </td>

            </tr>

            <?php endforeach; ?>

        <?php else: ?>

            <tr>

                <td colspan="8" style="text-align:center;">
                    No Leads Found
                </td>

            </tr>

        <?php endif; ?>

        </tbody>

    </table>
       <!-- ✅ DELETE BUTTON --><br>
                           <button type="button" id="deleteSelected" class="btn delete-btn">
                               Delete Selected
                           </button>

    

</div>
<script>
    $(document).ready(function() {
        // Select all checkboxes when the "Select All" checkbox is clicked
        $('#selectAll').click(function() {
            $('input[name="ids[]"]').prop('checked', $(this).prop('checked'));
        });

        // Delete selected leads
        $('#deleteSelected').click(function() {
            var selectedIds = [];
            $('input[name="ids[]"]:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Please select at least one lead to delete.');
                return;
            }

            if (confirm('Are you sure you want to delete the selected leads?')) {
                $.post('<?= base_url('leads/deleteMultiple') ?>', { ids: selectedIds }, function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error occurred while deleting leads.');
                    }

                });
            }
        });
    });
</script>

 
<?= $this->endSection(); ?>
<?php
namespace App\Models;

use CodeIgniter\Model;

class LeadModel extends Model
{
    protected $table = 'leads';
    protected $primaryKey = 'id';

 protected $allowedFields = [
    'name',
    'email',
    'phone',
    'company',
    'status',        // ✅ ADD THIS
    'source',
    'assigned_to',
    'created_at',
    'updated_at',
     'follow_up_date'
];
}
?>
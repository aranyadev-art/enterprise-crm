<?php

namespace App\Models;

use CodeIgniter\Model;

class SalesModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';

    protected $allowedFields = [
         'client_id', 
        'cad_user_id',
        'metal_type',
        'stone_type',
        'quantity',
        'notes',
        'status',
        'tracking_number',
         'design_image' 
    ];
}
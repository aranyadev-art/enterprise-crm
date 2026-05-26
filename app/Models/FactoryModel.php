<?php

namespace App\Models;

use CodeIgniter\Model;

class FactoryModel extends Model
{
    protected $table = 'factory';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'employee_name',
        'order_no',
        'shipping_no',
        'metal_weight',
        'stone_ct_weight',
        'final_weight',
        'daily_comment',
        'completed',
        'completed_date'
    ];
}
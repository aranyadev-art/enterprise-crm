<?php

namespace App\Models;
use CodeIgniter\Model;

class ShippingModel extends Model
{
    protected $table = 'shipping';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'gfj_no',
        'product_name',
        'sales_rep',
        'client_id',
        'final_quotation',
        'metal_stone_details',
        'tracking_number',
        'dispatch_date',
        'system_type',
        'status',
        'module_access'
    ];
}
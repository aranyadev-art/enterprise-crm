<?php

namespace App\Models;
use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table = 'orders';
        protected $primaryKey = 'id'; // ✅ FIXED
            protected $useSoftDeletes = false; // ✅ HARD DELETE
    protected $allowedFields = [
        'order_number',
        'sales_name',
        'cad_name',
        'client_name',
        'quotation_id',
        'status'
    ];
}
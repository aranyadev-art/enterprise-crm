<?php

namespace App\Models;

use CodeIgniter\Model;

class QuotationModel extends Model
{
    protected $table = 'quotations';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'quotation_id',
        'metal_price',
         'client_id' , 
        'stone_price',
        'purity',
        'currency',
        'total_price',
        'price_breakdown',
        'send_email',
         'status' // 
    ];
}
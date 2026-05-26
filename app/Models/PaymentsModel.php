<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentsModel extends Model
{
    protected $table = 'payments'; // your DB table
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'client_id',
        'amount',
        'status',
        'created_at'
    ];
}
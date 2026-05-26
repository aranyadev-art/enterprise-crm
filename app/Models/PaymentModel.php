<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'id';

 protected $allowedFields = [
    'account_id',
    'amount',
    'payment_method',
    'upi_id',
    'created_at'
];

    protected $useTimestamps = false;
}
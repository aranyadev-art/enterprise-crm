<?php

namespace App\Models;
use CodeIgniter\Model;

class AccountModel extends Model
{
    protected $table = 'accounts';

    protected $allowedFields = [
        'due_balance',
        'credit_limit',
        'shipping_approval',
        'allow_client',
        'allow_orders',
        'allow_shipping',
        'allow_alerts'
    ];
}
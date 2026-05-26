<?php

namespace App\Models;
use CodeIgniter\Model;

class ClientAccountModel extends Model
{
    protected $table = 'client_accounts';
    protected $primaryKey = 'account_id';

    protected $allowedFields = [
        'client_name',
        'due_amount',
        'credit_limit',
        'shipping_status'
    ];
}
<?php


namespace App\Models;

use CodeIgniter\Model;

class RosterSalesModel extends Model
{
    protected $table = 'roster_sales';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'roster_id',
        'sales_id',
        'sort_order'
    ];
}
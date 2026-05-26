<?php

namespace App\Models;

use CodeIgniter\Model;

class CadModel extends Model
{
    protected $table = 'cad_designs';
    protected $primaryKey = 'id';

    protected $allowedFields = [ 
        'cad_code',
        'start_time',
        'end_time',
        'duration',
        'design_file',
        'cpx_sent',
        'comment'
    ];
}
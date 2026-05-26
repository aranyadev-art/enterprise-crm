<?php

namespace App\Models;

use CodeIgniter\Model;

class RosterModel extends Model
{
    protected $table = 'rosters';
        protected $primaryKey = 'id';
     protected $allowedFields = ['date', 'cad_designer_id', 'sales_person'];
    protected $useTimestamps = true;

    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
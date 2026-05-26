<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{       

    protected $DBGroup = 'default';   // IMPORTANT

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'address',
        'city',
        'state',
        'zip',
        'password',
         'profile_image',
         'email_notification',
         'system_notification',
        'role',
        'module_access'
    ];
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'email', 'password', 'role', 'is_verified'];
    
    // Enable timestamps
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];
    
    protected function beforeInsert(array $data)
    {
        // Set default values for new users
        $data['data']['role'] = 'user';
        $data['data']['is_verified'] = 0;
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        return $data;
    }

    protected $validationRules = [
        'username' => 'required|min_length[3]|is_unique[users.username]',
        'email'    => 'required|valid_email|is_unique[users.email]',
        'password' => 'required|min_length[6]'
    ];
}

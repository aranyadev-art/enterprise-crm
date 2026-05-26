<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClientModel;

class Auth extends BaseController
{
    // ================= LOGIN VIEW =================
    public function login()
    {
        return view('login');
    }

    // ================= REGISTER =================
    public function register()
    {
        return view('register');
    }

    public function saveRegister()
    {
        $model = new UserModel();

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name'  => $this->request->getPost('last_name'),
            'email'      => $this->request->getPost('email'),
            'password'   => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),

            // Default role
            'role'       => 'sales',  

            // Optional
            'module_access' => ''
        ];

        $model->insert($data);

        return redirect()->to('login')->with('success', 'Registration Successful');
    }

    // ================= LOGIN CHECK =================

public function checkLogin()
{
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');

    $model = new UserModel();
    $clientModel = new ClientModel(); // ✅ ADD

    $user = $model->where('email', $email)->first();

    // ===============================
    // ✅ IF USER NOT FOUND → CHECK CLIENT
    // ===============================
    if (!$user) {

        $client = $clientModel->where('email', $email)->first();

        if (!$client) {
            echo "no_email";
            return;
        }
        // Verify client password
        if (!password_verify($password, $client['password'])) {
            echo "wrong_password";
            return;
        }

        // ✅ Client Session
        session()->set([
            'client_id'        => $client['id'],
            'client_name'      => $client['first_name'].' '.$client['last_name'],
            'client_email'     => $client['email'],
            'client_logged_in' => true
        ]);

        echo "client_success"; // ✅ CLIENT RESPONSE
        return;
    }

    // Check account status
    if (isset($user['status']) && $user['status'] != 'Active') {
        echo "inactive";
        return;
    }

    // Verify password
    if (!password_verify($password, $user['password'])) {
        echo "wrong_password";
        return;
    }

    date_default_timezone_set('Asia/Kolkata');
    $loginTime = date('M d, Y h:i A');

    $role = $user['role'];
    $raw = $user['module_access'];

    // --------------------------------------
    // Module access setup (FIXED)
    // --------------------------------------
    if (strtolower(trim($role)) == 'admin') {
        $access = ['sales','client','cad','calculator','quotation','order','account','factory','shipping','alert'];
    } else {
        $access = [];

        if ($raw) {
            $access = json_decode($raw, true);
        }

        if ($access === null) {
            $access = explode(',', $raw);
        }

        if (!is_array($access)) {
            $access = [$raw];
        }

        $access = array_map('trim', $access);
        $access = array_map('strtolower', $access);
    }

    // --------------------------------------
    // Set session
    // --------------------------------------
    session()->set([
        'user_id'       => $user['id'],
        'name'          => $user['first_name'].' '.$user['last_name'],
        'email'         => $user['email'],
        'role'          => $role,
        'module_access' => $access,
        'last_login'    => $loginTime,
        'logged_in'     => true,
        'profile_image' => $user['profile_image']
    ]);

    echo "success"; // ✅ ADMIN RESPONSE
    return;
}

    // ================= LOGOUT =================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('login');
    }
/*public function resetPassword()
{
    $email = 'ara@gmail.com';
    $newPassword = '123456';

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $db = \Config\Database::connect();
    $builder = $db->table('users');

    $builder->where('email', $email);
    $builder->update([
        'password' => $hashedPassword
    ]);

    echo "Password Reset Successfully";
}*/
}
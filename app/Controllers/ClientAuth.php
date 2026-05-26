<?php

namespace App\Controllers;

use App\Models\ClientModel;

class ClientAuth extends BaseController
{
      public function login()
{
    return view('clients/login');
}
       
     
public function checkLogin()
{
    $email = trim($this->request->getPost('email'));
    $password = trim($this->request->getPost('password'));

    $model = new ClientModel();
    $client = $model->where('email', $email)->first();

    // ❌ Email not found
    if (!$client) {
        echo "no_email";
        return;
    }

    // ❌ Wrong password
    if (!password_verify($password, $client['password'])) {
        echo "wrong_password";
        return;
    }

    // ✅ Session set
    session()->set([
        'client_id'        => $client['id'],
        'client_name'      => $client['first_name'].' '.$client['last_name'],
        'client_email'     => $client['email'],
        'client_logged_in' => true
    ]);

    // ✅ Success response
    echo "client_success";
    return;
}
}
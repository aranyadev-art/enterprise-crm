<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
public function index()
{
    $id = session()->get('user_id');

    if (!$id) {
        return redirect()->to('/login');
    }

    $userModel = new \App\Models\UserModel();
    $user = $userModel->find($id);

    return view('profile', ['user' => $user]);
}

public function update()
{
    $id = session()->get('user_id');

    $data = [
        'first_name' => $this->request->getPost('first_name'),
        'last_name'  => $this->request->getPost('last_name'),
        'email'      => $this->request->getPost('email')
    ];

    $userModel = new \App\Models\UserModel();

    // ✅ CHANGE IS HERE
    $userModel->update($id, $data);

    // ✅ Fetch fresh data
    $user = $userModel->find($id);

    // ✅ Update session
    session()->set([
        'name'  => $user['first_name'].' '.$user['last_name'],
        'email' => $user['email']
    ]);

    return redirect()->to('/dashboard')->with('success', 'Profile Updated');
}
}
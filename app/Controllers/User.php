<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
          public function __construct()
    {
        // 🔐 ROLE CHECK (ADMIN ONLY)
        if(session()->get('role') != 'Admin'){
            echo "Access Denied";
            exit;
        }
    }

      public function profile()
    {
        return view('profile');
    }
   
// Show Add User Form
 public function index()
{
     $role = session()->get('role');

    // 🔐 Only Admin Allowed
    if (strtolower(trim($role)) != 'admin') {
        return redirect()->to('dashboard')->with('error', 'Access Denied');
    }

    // ✅ Admin can access
    return view('users/create_user');
}
     public function create()
{
    return view('users/create_user');
}
    // Save User Data
public function save()
{
    // =========================
    // ✅ VALIDATION RULES
    // =========================
    $rules = [
        'first_name' => 'required|alpha_space',
        'last_name'  => 'required|alpha_space',
        'email'      => 'required|valid_email',
        'phone'      => 'required|exact_length[10]|numeric',
        'status'     => 'required',
        'address'    => 'required',
        'city'       => 'required|alpha_space',
        'state'      => 'required|alpha_space',
        'zip'        => 'required|numeric',
        'password'   => 'required|min_length[6]',
        'role'       => 'required'
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('validation', $this->validator);
    }

    $model = new \App\Models\UserModel();

    // =========================
    // ✅ MODULE ACCESS
    // =========================
    $modules = $this->request->getPost('modules');

    // =========================
    // ✅ GET PLAIN PASSWORD (FOR EMAIL)
    // =========================
    $plainPassword = $this->request->getPost('password');

    // =========================
    // ✅ PREPARE DATA
    // =========================
    $data = [
        'first_name' => $this->request->getPost('first_name'),
        'last_name'  => $this->request->getPost('last_name'),
        'email'      => $this->request->getPost('email'),
        'phone'      => $this->request->getPost('phone'),
        'status'     => $this->request->getPost('status'),
        'address'    => $this->request->getPost('address'),
        'city'       => $this->request->getPost('city'),
        'state'      => $this->request->getPost('state'),
        'zip'        => $this->request->getPost('zip'),
        'password'   => password_hash($plainPassword, PASSWORD_DEFAULT),
        'role'       => $this->request->getPost('role'),
        'module_access' => json_encode($modules ?? [])
    ];

    // =========================
    // ✅ INSERT USER
    // =========================
    $model->insert($data);

    // ================================
    // 🔔 EMAIL NOTIFICATION
    // ================================
    $sendEmail = $this->request->getPost('send_email');

    if ($sendEmail == 1) {

        $email = \Config\Services::email();
        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

        // 📩 USER EMAIL
       $emailTo = $this->request->getPost('email');

        $email->setTo($emailTo);
        $email->setSubject('Your Account Has Been Created');

        $email->setMessage("
            <div style='font-family:Arial;'>
                <h3>Hello {$data['first_name']} 👋</h3>
                <p>Your account has been created successfully.</p>

                <h4>Login Details:</h4>
                <p><b>Email:</b> {$data['email']}</p>
                <p><b>Password:</b> {$plainPassword}</p>
                <p><b>Role:</b> {$data['role']}</p>

                <br>
                <p>Please login and change your password after first login.</p>
            </div>
        ");

        if (!$email->send()) {
            echo $email->printDebugger(['headers']);
            die();
        }
    }

    // =========================
    // 🔥 ALERT SYSTEM
    // =========================
    $session = session();

    $alerts = $session->get('alerts') ?? [];

    $alerts[] = [
        'id' => count($alerts) + 1,
        'title' => 'New User Created: ' . $data['first_name'],
        'module' => 'Users',
        'datetime' => date('Y-m-d H:i:s'),
        'status' => 'Unread'
    ];

    $session->set('alerts', $alerts);

    return redirect()->to('/users')->with('success', 'User Saved Successfully');
}
public function list()
{
    $model = new \App\Models\UserModel();
    $data['users'] = $model->findAll();

    return view('users/list_users', $data);
}
public function edit($id)
{
    $model = new UserModel();
    $data['user'] = $model->find($id);

    return view('users/edit_user', $data);
}
public function update($id)
{
    $model = new \App\Models\UserModel();

    // ✅ GET MODULES FIRST
    $modules = $this->request->getPost('modules');

    $data = [
        'first_name' => $this->request->getPost('first_name'),
        'last_name'  => $this->request->getPost('last_name'),
        'email'      => $this->request->getPost('email'),
        'phone'      => $this->request->getPost('phone'),
        'role'       => $this->request->getPost('role'),
        'status'     => $this->request->getPost('status'),

        // ✅ SAVE MODULE ACCESS
        'module_access' => json_encode($modules ?? [])
    ];

    $model->update($id, $data);

    return redirect()->to('/users');
}
public function delete($id)
{
    $model = new UserModel();

    $model->delete($id);

    return redirect()->to('/users')->with('delete_success','User Deleted Successfully');
}
public function deleteMultiple()
{
    $data = $this->request->getJSON(true);
    $ids = $data['ids'] ?? [];

    if (!empty($ids)) {

        $db = \Config\Database::connect();

        foreach ($ids as $id) {
            $db->query("DELETE FROM users WHERE id = ?", [$id]);
        }
    }

    echo "success";
}

public function changePassword()
{
    return view('change_password');
}

public function updatePassword()
{
    $session = session();

    $current = $this->request->getPost('current_password');
    $new = $this->request->getPost('new_password');
    $confirm = $this->request->getPost('confirm_password');

    if($new != $confirm){
        return redirect()->back()->with('error','Passwords do not match');
    }

    $db = \Config\Database::connect();
    $builder = $db->table('users');

    $user = $builder->where('id',$session->get('user_id'))->get()->getRow();

    if(!$user){
        return redirect()->back()->with('error','User not found');
    }

    if(!password_verify($current,$user->password)){
        return redirect()->back()->with('error','Current password incorrect');
    }

    $builder->where('id',$session->get('user_id'))->update([
        'password' => password_hash($new, PASSWORD_DEFAULT)
    ]);

    return redirect()->back()->with('success','Password updated successfully');
}

}
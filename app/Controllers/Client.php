<?php

namespace App\Controllers;

use App\Models\ClientModel;

class Client extends BaseController
{
      
     public function index()
{
    $role = session()->get('role');
    $access = session()->get('module_access') ?? [];

    // convert
    if (!is_array($access)) {
        $access = explode(',', $access);
    }

    $access = array_map('trim', $access);
    $access = array_map('strtolower', $access);

    // 👑 ADMIN = full access
    if (strtolower(trim($role)) == 'admin') {
        return view('clients/index'); // change module accordingly
    }

    // 🔐 CHECK MODULE ACCESS
    if (!in_array('client', $access)) {
        return redirect()->to('dashboard')->with('error', 'Access Denied');
    }

    return view('clients/index');
}
     
    public function list()
    {
        $model = new ClientModel();
        $data['clients'] = $model->findAll();

        return view('clients/list_clients', $data);
    }

    public function create()
    {

                 
        return view('clients/create_client');
    }

  public function save()
{
   $model = new ClientModel();

// ✅ Generate password
$plainPassword = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// 🔥 STEP 1: VALIDATION RULES
$rules = [
    'first_name' => 'required',
    'email'      => 'required|valid_email|is_unique[clients.email]'
];

$messages = [
    'email' => [
        'is_unique' => 'Email already exists'
    ]
];

if (!$this->validate($rules, $messages)) {
    return redirect()->back()
        ->withInput()
        ->with('errors', $this->validator->getErrors());
}

// ✅ Data
$data = [
    'first_name' => $this->request->getPost('first_name'),
    'last_name'  => $this->request->getPost('last_name'),
    'email'      => trim($this->request->getPost('email')),
    'phone'      => $this->request->getPost('phone'),
    'city'       => $this->request->getPost('city'),
    'password'   => $hashedPassword 
];

// ✅ INSERT (only if valid)
$model->insert($data);

$clientId = $model->getInsertID();
    

    // ================================
    // 🔔 EMAIL NOTIFICATION
    // ================================
    $sendEmail = $this->request->getPost('send_email');

    if ($sendEmail == 1) {

        $client = $model->find($clientId);

        $email = \Config\Services::email();

        // 📩 ADMIN EMAIL
        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
        $email->setTo('2906dev@gmail.com');
        $email->setSubject('New Client Created');

        $email->setMessage("
            <h3>New Client Created ✅</h3>
            <p><b>Name:</b> {$client['first_name']} {$client['last_name']}</p>
            <p><b>Email:</b> {$client['email']}</p>
            <p><b>Phone:</b> {$client['phone']}</p>
            <p><b>City:</b> {$client['city']}</p>
        ");

        $email->send();

        // 📩 CLIENT EMAIL
        if (!empty($client['email'])) {

            $email->clear();

            $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
            $email->setTo($client['email']);
            $email->setSubject('Welcome to Our Service');

            $email->setMessage("
                <div style='font-family:Arial;'>
                    <h3>Hello {$client['first_name']} 👋</h3>
                    <p>Your account has been created successfully.</p>
                    <p><b>Email:</b> {$client['email']}</p>
                    <p><b>Password:</b> {$plainPassword}</p>
                    <p>
                        <a href='" . base_url('client/login') . "'
                        style='background:#2a7bd3;color:#fff;padding:10px 15px;text-decoration:none;border-radius:5px;'>
                        Login Now
                        </a>
                    </p>
                    <p>Please change your password after login.</p>
                </div>
            ");

            $email->send();
        }
    }

    // ================================
    // 🔔 ALERT
    // ================================
    $session = session();

    $alerts = $session->get('alerts') ?? [];

    $alerts[] = [
            'id'       => uniqid(), // ✅ ADD THIS
        'title' => 'New client created successfully',
        'module' => 'clients',
        'datetime' => date('Y-m-d H:i:s'),
        'status' => 'Unread'
    ];

    $session->set('alerts', $alerts);



    // ✅ REDIRECT
    return redirect()->to('/clients');
}
    public function edit($id)
{
    $model = new \App\Models\ClientModel();

    $data['client'] = $model->find($id);

    return view('clients/edit_client', $data);
}

public function update($id)
{
    $model = new \App\Models\ClientModel();

    $model->update($id,[
        'first_name'=>$this->request->getPost('first_name'),
        'last_name'=>$this->request->getPost('last_name'),
        'email'=>$this->request->getPost('email'),
        'phone'=>$this->request->getPost('phone'),
        'city'=>$this->request->getPost('city'),
    ]);

    return redirect()->to('/clients');
}
public function delete($id)
{
    $model = new \App\Models\ClientModel();

    $model->delete($id);

    return redirect()->to('/clients');
}

public function deleteMultiple()
{
    $data = $this->request->getJSON(true);
    $ids = $data['ids'] ?? [];

    if (!empty($ids)) {

        $db = \Config\Database::connect();

        foreach ($ids as $id) {
            $db->query("DELETE FROM clients WHERE id = ?", [$id]);
        }
    }

    echo "success";
}

public function dashboard()
{
    if (!session()->get('client_logged_in')) {
        return redirect()->to('client/login');
    }

    $orderModel = new \App\Models\OrderModel();
    $quoteModel = new \App\Models\QuotationModel();
    $paymentModel = new \App\Models\PaymentModel();

    $client_id = session()->get('client_id');

    // 📊 Stats
    $data['total_orders'] = $orderModel
        ->where('client_id', $client_id)
        ->countAllResults();

    $data['active_orders'] = $orderModel
        ->where('client_id ', $client_id)
        ->where('status !=', 'Delivered')
        ->countAllResults();
        
  $data['pending_quotes'] = $quoteModel
    ->where('client_id', $client_id)
    ->where('status', 'Pending')
    ->countAllResults();

$data['pending_payment'] = $paymentModel
    ->where('client_id', $client_id)
    ->where('status', 'Pending')
    ->countAllResults();
    // ✅ PASS DATA HERE
    return view('clients/dashboard', $data);
}
public function profile()
{
    return view('clients/profile');
}

public function orders()
{
    if (!session('client_id')) {
        return redirect()->to('/client/login');
    }

    $orderModel = new \App\Models\OrderModel();

    $data['orders'] = $orderModel
        ->where('client_id', session('client_id'))
        ->findAll();

    return view('clients/orders', $data); // 🔥 IMPORTANT FIX
}
public function orderDetails($id)
{
    if (!session('client_id')) {
        return redirect()->to('/client/login');
    }

    $orderModel = new \App\Models\OrderModel();

    $order = $orderModel
        ->where('id', $id)
        ->where('client_id', session('client_id'))
        ->first();

    if (!$order) {
        return redirect()->to('/client/orders');
    }

    return view('clients/order_details', ['order' => $order]);
}
public function quotations()
{
    return view('clients/quotations');
}

// ✅ Show Payment Form
public function payment()
{
    if (!session()->get('client_logged_in')) {
        return redirect()->to('client/login');
    }

    return view('clients/payment_form');
}


// ✅ Save Payment
public function savePayment()
{
    if (!session()->get('client_logged_in')) {
        return redirect()->to('client/login');
    }

    $model = new \App\Models\PaymentsModel();

    $data = [
        'client_id' => session()->get('client_id'),
        'amount'    => $this->request->getPost('amount'),
        'status'    => 'Pending'
    ];

    $model->insert($data);

    return redirect()->to('client/dashboard');
}
public function checkEmail()
{

     return $this->response->setBody('exists');
    $email = $this->request->getPost('email');

    $model = new ClientModel();

    $exists = $model->where('email', $email)->first();

    if ($exists) {
        return $this->response->setBody('exists');
    } else {
        return $this->response->setBody('ok');
    }
}
}
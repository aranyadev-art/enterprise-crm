<?php

namespace App\Controllers;

use App\Models\FactoryModel;

class Factory extends BaseController
{


    public function index()
    {
        $model = new FactoryModel();
        $data['factory'] = $model->findAll();
        return view('factory/index', $data);
    }

public function create($id = null)
{
    if (!$id) {
        return view('factory/create', [
            'order_id' => '',
            'order_no' => '',
            'shipping_no' => '',
            'client_email' => ''
        ]);
    }

    // 🔥 CHECK DUPLICATE (DB)
    $factoryModel = new \App\Models\FactoryModel();
    $existing = $factoryModel->where('order_id', $id)->first();

    if ($existing) {
        return redirect()->back()->with('error', 'Already sent to factory!');
    }

    // =========================
    // ✅ 1. TRY DB FIRST
    // =========================
    $orderModel = new \App\Models\OrderModel();
    $orderData = $orderModel->find($id);

    // =========================
    // ✅ 2. SESSION (fallback)
    // =========================
    $session = session();
    $orders = $session->get('orders') ?? [];

    if (!$orderData) {

        foreach ($orders as $key => $o) {

            if ($o['id'] == $id) {
                $orderData = $o;

                // 🔥 UPDATE STATUS (SESSION)
                $orders[$key]['status'] = 'In Progress';
                break;
            }
        }

        // save session update
        $session->set('orders', $orders);
    }

    // =========================
    // ❌ NOT FOUND
    // =========================
    if (!$orderData) {
        return redirect()->back()->with('error', 'Order not found');
    }

    // =========================
    // 🔥 UPDATE STATUS IN DB ALSO
    // =========================
    if (isset($orderData['id'])) {
        $orderModel->update($orderData['id'], ['status' => 'In Progress']);
    }

    // =========================
    // ✅ SHIPPING NO
    // =========================
    $shipping_no = 'SHIP-' . date('Ymd') . '-' . rand(100,999);

    return view('factory/create', [
        'order_id'     => $orderData['id'],
        'order_no'     => $orderData['order_number'] ?? '',
        'shipping_no'  => $shipping_no,
        'client_email' => $orderData['client_email'] ?? ''
    ]);
}
public function save()
{

    $model = new FactoryModel();

    // ✅ Get Order ID
    $order_id = $this->request->getPost('order_id');

    // ✅ Always mark as completed after submit
    $completed = 1;

    $data = [
        'employee_name' => $this->request->getPost('employee_name'),
        'order_no' => $this->request->getPost('order_no'),
        'shipping_no' => $this->request->getPost('shipping_no'),
        'metal_weight' => $this->request->getPost('metal_weight'),
        'stone_ct_weight' => $this->request->getPost('stone_ct_weight'),
        'final_weight' => $this->request->getPost('final_weight'),
        'daily_comment' => $this->request->getPost('daily_comment'),
        'completed' => $completed,
        'completed_date' => date('Y-m-d')
    ];

    // =========================
    // ✅ SAVE FACTORY DATA
    // =========================
    $model->save($data);


    // ===================================
    // ✅ UPDATE ORDER STATUS IN DATABASE
    // ===================================
    $orderModel = new \App\Models\OrderModel();
    $orderModel->update($order_id, [
        'status' => 'Completed'
    ]);


    // ===================================
    // ✅ UPDATE ORDER STATUS IN SESSION (OPTIONAL)
    // ===================================
    $session = session();
    $orders = $session->get('orders') ?? [];

    foreach ($orders as $key => $o) {
        if ($o['id'] == $order_id) {
            $orders[$key]['status'] = 'Completed';
            break;
        }
    }

    $session->set('orders', $orders);


// ================================
// 🔔 EMAIL NOTIFICATION
// ================================
$sendEmail = $this->request->getPost('send_email');

    // ================================
// 🔔 EMAIL NOTIFICATION (FIXED)
// ================================
$sendEmail = $this->request->getPost('send_email');
$clientEmail = $this->request->getPost('client_email');

// ✅ Only check Send Email (remove completed dependency)
if ($sendEmail == 1) {

    $email = \Config\Services::email();
    $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

    // ============================
    // 📩 ADMIN EMAIL (ALWAYS WORK)
    // ============================
    $email->setTo('2906dev@gmail.com');
    $email->setSubject('Factory Production Update');

    $email->setMessage("
        <h3>Factory Update 🏭</h3>
        <p><b>Order No:</b> {$data['order_no']}</p>
        <p><b>Employee:</b> {$data['employee_name']}</p>
        <p><b>Final Weight:</b> {$data['final_weight']}</p>
        <p><b>Status:</b> " . ($completed ? 'Completed ✅' : 'In Progress') . "</p>
    ");

    $email->send();

    // ============================
    // 📩 CLIENT EMAIL (SAFE)
    // ============================
    if (!empty($clientEmail)) {

        $email->clear(true);

        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
        $email->setTo($clientEmail);
        $email->setSubject('Production Update');

        $email->setMessage("
            <div style='font-family:Arial;'>
                <h3>Hello 👋</h3>
                <p>Your product is currently in factory processing.</p>

                <p><b>Order No:</b> {$data['order_no']}</p>
                <p><b>Status:</b> " . ($completed ? 'Completed ✅' : 'In Progress') . "</p>

                <br>
                <p>Thank you 🙏</p>
            </div>
        ");

        $email->send();
    }
}

    // =========================
    // 🔥 ALERT SYSTEM
    // =========================
    $session = session();

    $alerts = $session->get('alerts') ?? [];

    $alerts[] = [
        'id' => count($alerts) + 1,
        'title' => $completed 
            ? 'Production Completed: ' . $data['order_no']
            : 'Factory Update: ' . $data['order_no'],
        'module' => 'Factory',
        'datetime' => date('Y-m-d H:i:s'),
        'status' => 'Unread'
    ];

    $session->set('alerts', $alerts);

    return redirect()->to('/factory');
}

public function deleteMultiple()
{
    $data = json_decode(file_get_contents("php://input"), true);

    $ids = $data['ids'] ?? [];

    if (empty($ids)) {
        return $this->response->setJSON(['status' => 'error']);
    }

    $model = new \App\Models\FactoryModel();
    $model->whereIn('id', $ids)->delete();

    return $this->response->setJSON(['status' => 'success']);
}
}
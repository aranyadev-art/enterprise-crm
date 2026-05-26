<?php

namespace App\Controllers;
use App\Models\OrderModel;

class Order extends BaseController
{
public function index()
{
    $session = session();

    $role = $session->get('role');
    $access = $session->get('module_access') ?? [];

    // convert to array
    if (!is_array($access)) {
        $access = explode(',', $access);
    }

    $access = array_map('trim', $access);
    $access = array_map('strtolower', $access);

    // =========================
    // ✅ 1. GET DB ORDERS
    // =========================
    $model = new \App\Models\OrderModel();
    $dbOrders = $model->findAll();

    // =========================
    // ✅ 2. GET SESSION ORDERS
    // =========================
    $sessionOrders = $session->get('orders') ?? [];

    // =========================
    // ✅ 3. JSON BACKUP (IF SESSION EMPTY)
    // =========================
    $file = WRITEPATH . 'orders.json';

    if (empty($sessionOrders) && file_exists($file)) {
        $sessionOrders = json_decode(file_get_contents($file), true);
        $session->set('orders', $sessionOrders);
    }

    // =========================
    // ✅ 4. MERGE ALL ORDERS
    // =========================
    // (IMPORTANT: DB + SESSION dono dikhenge)
    $allOrders = array_merge($dbOrders, $sessionOrders);

    // =========================
    // ✅ 5. PASS TO VIEW
    // =========================
    $data['orders'] = $allOrders;

    // =========================
    // 👑 ADMIN = full access
    // =========================
    if (strtolower(trim($role)) == 'admin') {
        return view('orders/index', $data);
    }

    // =========================
    // 🔐 ACCESS CONTROL
    // =========================
    if (!in_array('order', $access)) {
        return redirect()->to('dashboard')->with('error', 'Access Denied');
    }

    return view('orders/index', $data);
}
public function create()
{
    $quotationModel = new \App\Models\QuotationModel();
    $clientModel    = new \App\Models\ClientModel();

    $data['quotations'] = $quotationModel->findAll();
    $data['clients']    = $clientModel->findAll(); // ✅ ADD THIS

    return view('orders/create', $data);
}
public function save()
{
    $session = session();
    $model = new \App\Models\OrderModel();

    // ✅ CREATE ORDER DATA
    $newOrder = [
        'order_number' => 'ORD-' . time(),
        'sales_name'   => $this->request->getPost('sales_name'),
        'cad_name'     => $this->request->getPost('cad_name'),
          // ✅ ADD THIS LINE (MOST IMPORTANT)
       'client_id' => $this->request->getPost('client_id'),
        'client_name'  => $this->request->getPost('client_name'),
        'quotation_id' => $this->request->getPost('quotation_id'),
        'status'       => 'Pending'
    ];

    // =========================
    // ✅ SAVE INTO DATABASE (MAIN FIX)
    // =========================
    $insert = $model->insert($newOrder);

    if (!$insert) {
        dd($model->errors()); // 🔍 DEBUG
    }

    // insert id
    $newOrder['id'] = $model->getInsertID();

    // =========================
    // 🔔 EMAIL NOTIFICATION (UNCHANGED)
    // =========================
    $sendEmail = $this->request->getPost('send_email');

    if ($sendEmail == 1) {

        $email = \Config\Services::email();
        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

        // ADMIN EMAIL
        $email->setTo('2906dev@gmail.com');
        $email->setSubject('New Order Created');

        $email->setMessage("
            <h3>New Order Created 📦</h3>
            <p><b>Order No:</b> {$newOrder['order_number']}</p>
            <p><b>Client:</b> {$newOrder['client_name']}</p>
            <p><b>Sales:</b> {$newOrder['sales_name']}</p>
            <p><b>CAD:</b> {$newOrder['cad_name']}</p>
            <p><b>Status:</b> {$newOrder['status']}</p>
        ");

        if (!$email->send()) {
            echo $email->printDebugger(['headers']);
            die();
        }

        // CLIENT EMAIL
        $clientEmail = $this->request->getPost('client_email');

        if (!empty($clientEmail) && filter_var($clientEmail, FILTER_VALIDATE_EMAIL)) {

            $email->clear(true);

            $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
            $email->setTo($clientEmail);
            $email->setSubject('Your Order Confirmation');

            $email->setMessage("
                <div style='font-family:Arial;'>
                    <h3>Hello {$newOrder['client_name']} 👋</h3>
                    <p>Your order has been created successfully.</p>
                    <p><b>Order No:</b> {$newOrder['order_number']}</p>
                    <p>Status: {$newOrder['status']}</p>
                    <br>
                    <p>Thank you for choosing us 🙏</p>
                </div>
            ");

            if (!$email->send()) {
                echo $email->printDebugger(['headers']);
                die();
            }
        }
    }

    // =========================
    // 🔥 ALERT SYSTEM (UNCHANGED)
    // =========================
    $alerts = $session->get('alerts') ?? [];

    $alerts[] = [
        'id' => count($alerts) + 1,
        'title' => 'New Order: ' . $newOrder['order_number'] . ' | ' . ($newOrder['client_name'] ?? 'Client'),
        'module' => 'Orders',
        'datetime' => date('Y-m-d H:i:s'),
        'status' => 'Unread'
    ];

    $session->set('alerts', $alerts);

    return redirect()->to('/orders');
}

public function deleteMultiple()
{
    $request = service('request');
    $session = session();

    $data = $request->getJSON(true);
    $ids = $data['ids'] ?? [];

    if (empty($ids)) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No IDs received'
        ]);
    }

    // =========================
    // ✅ 1. DELETE FROM DB
    // =========================
    $model = new \App\Models\OrderModel();

    foreach ($ids as $id) {
        if (is_numeric($id)) {
            $model->delete($id);
        }
    }

    // =========================
    // ✅ 2. DELETE FROM SESSION
    // =========================
    $orders = $session->get('orders') ?? [];

    $orders = array_filter($orders, function ($order) use ($ids) {
        return !in_array($order['id'], $ids);
    });

    $session->set('orders', $orders);

    // =========================
    // ✅ 3. UPDATE JSON FILE
    // =========================
    $file = WRITEPATH . 'orders.json';

    if (file_exists($file)) {
        file_put_contents($file, json_encode(array_values($orders), JSON_PRETTY_PRINT));
    }

    // =========================
    // ✅ RESPONSE
    // =========================
    return $this->response->setJSON([
        'status' => 'success'
    ]);
}
}
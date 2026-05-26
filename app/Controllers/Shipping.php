<?php

namespace App\Controllers;

use App\Models\ShippingModel;
use App\Models\ClientModel;
 
class Shipping extends BaseController
{
    protected $session;

    public function __construct()
    {
        $this->session = session();
    }

    private function checkAccess($module = 'shipping')
    {
        $role = strtolower(trim($this->session->get('role')));
        $access = $this->session->get('module_access') ?? [];

        // Normalize
        $access = array_map('trim', $access);
        $access = array_map('strtolower', $access);

        // Admin bypass
        if ($role == 'admin') {
            return true;
        }

        if (!in_array($module, $access)) {
            die("Access Denied: $module not allowed for your role");
        }
    }

    public function index()
    {
        // ✅ Check access
        $this->checkAccess('shipping');

        $model = new ShippingModel();

        $tracking_number = $this->request->getGet('tracking_number');
        $status = $this->request->getGet('status');
        $dispatch_date = $this->request->getGet('dispatch_date');

        $query = $model
            ->select('shipping.*, clients.first_name, clients.last_name')
            ->join('clients', 'clients.id = shipping.client_id', 'left');

        if (!empty($tracking_number)) {
            $query->like('tracking_number', $tracking_number);
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($dispatch_date)) {
            $query->where('dispatch_date', $dispatch_date);
        }

        $data['shipping'] = $query->findAll();

        return view('shipping/index', $data);
    }

    public function create($id = null)
{
    $this->checkAccess('shipping');

    $shippingModel = new ShippingModel();
    $clientModel   = new ClientModel();

    $last   = $shippingModel->orderBy('id', 'DESC')->first();
    $nextId = $last ? $last['id'] + 1 : 1;

    $data['gfj_no'] = 'GFJ' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    $data['tracking_number'] = 'TRK' . date('Ymd') . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    $data['clients'] = $clientModel->findAll();

    // ✅ GET ORDER DATA
    $session = session();
    $orders = $session->get('orders') ?? [];

    $orderData = null;

    foreach ($orders as $o) {
        if ($o['id'] == $id) {
            $orderData = $o;
            break;
        }
    }

    // ✅ PASS SALES REP
    $data['sales_rep'] = $orderData['sales_name'] ?? '';

    return view('shipping/create', $data);
}

   public function store()
{
    $this->checkAccess('shipping');

    $model = new ShippingModel();

    $last = $model->orderBy('id', 'DESC')->first();
    $nextId = $last ? $last['id'] + 1 : 1;

    $gfj_no = 'GFJ' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    $tracking_number = 'TRK' . date('Ymd') . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    $access = $this->request->getPost('module_access');
    $accessString = is_array($access) ? implode(',', $access) : $access;

    $model->save([
        'gfj_no' => $gfj_no,
        'product_name' => $this->request->getPost('product_name'),
        'sales_rep' => $this->request->getPost('sales_rep'),
        'client_id' => $this->request->getPost('client_id'),
        'status' => $this->request->getPost('status'),
        'courier_name' => $this->request->getPost('courier_name'),
        'dispatch_date' => $this->request->getPost('dispatch_date'),
        'delivery_date' => $this->request->getPost('delivery_date'),
        'final_quotation' => $this->request->getPost('final_quotation'),
        'metal_stone_details' => $this->request->getPost('metal_stone_details'),
        'tracking_number' => $tracking_number,
        'system_type' => $this->request->getPost('system_type'),
        'module_access' => $accessString
    ]);

    // ================================
    // 🔔 EMAIL NOTIFICATION (SHIPPING)
    // ================================
    $sendEmail = $this->request->getPost('send_email');
    $clientEmail = $this->request->getPost('client_email');

    if ($sendEmail == 1 && !empty($clientEmail)) {

        $email = \Config\Services::email();
        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

        $email->setTo($clientEmail);
        $email->setSubject('Your Product Has Been Shipped 🚚');

        $email->setMessage("
            <div style='font-family:Arial;'>
                <h3>Hello 👋</h3>
                <p>We are happy to inform you that your product has been shipped.</p>

                <p><b>Product:</b> " . $this->request->getPost('product_name') . "</p>
                <p><b>Tracking Number:</b> {$tracking_number}</p>
                <p><b>GFJ No:</b> {$gfj_no}</p>
                <p><b>Handled By:</b> " . $this->request->getPost('sales_rep') . "</p>

                <br>
                <p>You can track your shipment using the tracking number.</p>
                <p>Thank you for choosing us 🙏</p>
            </div>
        ");

        $email->send();
    }

    return redirect()->to('/shipping');
}

    public function delete($id)
    {
        $this->checkAccess('shipping');

        $model = new ShippingModel();
        $model->delete($id);

        return redirect()->to('/shipping')->with('success', 'Deleted successfully');
    }

    public function deleteMultiple()
    {
        $this->checkAccess('shipping');

        $ids = $this->request->getPost('ids');

        if (!empty($ids)) {
            $model = new ShippingModel();
            $model->whereIn('id', $ids)->delete();
        }

        return redirect()->to('/shipping');
    }
}
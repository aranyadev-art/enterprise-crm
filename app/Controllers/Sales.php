<?php

namespace App\Controllers;

use App\Models\SalesModel;
use App\Models\ClientModel;

class Sales extends BaseController
{

           protected $salesModel;

    public function __construct()
    {
        $this->salesModel = new SalesModel(); // ✅ FIX
    }


public function index()
{
    return $this->list(); // ✅ load data
}

public function add()
{
    $clientModel = new \App\Models\ClientModel();

    $data['clients'] = $clientModel->findAll();
    $data['tracking'] = "TRK-" . date('Y') . "-" . rand(1000,9999);

    return view('sales/add_sales', $data);
}

public function save()
{
    $model = new SalesModel();

    // Generate tracking number
    $tracking = "TRK-" . date('Y') . "-" . rand(1000,9999);

    // ✅ IMAGE UPLOAD START
    $file = $this->request->getFile('design_file');
    $fileName = null;

    if ($file && $file->isValid() && !$file->hasMoved()) {
        $fileName = $file->getRandomName();
        $file->move(FCPATH . 'uploads/', $fileName);
    }
    // ✅ IMAGE UPLOAD END

    $data = [
        'client_id'       => $this->request->getPost('client_id'),
        'cad_user_id'     => $this->request->getPost('cad_user_id'),
        'quantity'        => $this->request->getPost('quantity'),
        'notes'           => $this->request->getPost('notes'),
        'status'          => $this->request->getPost('status'),
        'tracking_number' => $tracking,

        // ✅ SAVE IMAGE NAME
        'design_image'    => $fileName
    ];

    $model->insert($data);

    // =========================
    // 🔥 ALERT CODE (UNCHANGED)
    // =========================
    $session = session();

    $alerts = $session->get('alerts') ?? [];

    $alerts[] = [
        'id' => count($alerts) + 1,
        'title' => 'New Sale Created: ' . $tracking,
        'module' => 'Sales',
        'datetime' => date('Y-m-d H:i:s'),
        'status' => 'Unread'
    ];

    $session->set('alerts', $alerts);

    return redirect()->to('/sales')
        ->with('success','Sales Job Created Successfully');
}
public function list()
{
    $model = new SalesModel();

    // Get search value from URL
    $tracking = $this->request->getGet('tracking');

    $builder = $model
        ->select('sales.*, 
                  clients.first_name AS client_first, 
                  clients.last_name AS client_last,
                  users.first_name AS cad_first, 
                  users.last_name AS cad_last')
        ->join('clients', 'clients.id = sales.client_id', 'left')
        ->join('users', 'users.id = sales.cad_user_id', 'left');

    // Apply search filter
    if (!empty($tracking)) {
        $builder->like('sales.tracking_number', $tracking);
    }

    $data['sales'] = $builder->findAll();

    return view('sales/sales_list', $data);
}


public function uploadDesign()
{

    $file = $this->request->getFile('design_file');
    $sale_id = $this->request->getPost('sale_id'); // ✅ ADD THIS

    if (empty($sale_id)) {
        return "Sale ID missing ❌";
    }

    if ($file->isValid() && !$file->hasMoved()) {

        $newName = $file->getRandomName();
        $file->move(FCPATH.'uploads', $newName);

        // ✅ SAVE IMAGE TO DB
        $this->salesModel->update($sale_id, [
            'design_image' => $newName
        ]);

        return "<p style='color:green;'>Design uploaded successfully</p>";

    } else {

        return "<p style='color:red;'>Upload failed</p>";
    }
}
    #new updated code 

public function deleteMultiple()
{
    $data = $this->request->getJSON(true);
    $ids = $data['ids'] ?? [];

    if (!empty($ids)) {

        $db = \Config\Database::connect();

        foreach ($ids as $id) {
            $db->query("DELETE FROM sales WHERE id = ?", [$id]);
        }
    }

    echo "success";
}

}
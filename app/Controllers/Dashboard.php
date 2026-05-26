<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ClientModel;
use App\Models\SalesModel;
use App\Models\OrderModel;


class Dashboard extends BaseController
{
    public function index()
{
    // ✅ Counts
    $userModel   = new UserModel();
    $clientModel = new ClientModel();
    $salesModel  = new SalesModel();
    $orderModel  = new OrderModel();
    


    $data['total_users']   = $userModel->countAll();
    $data['total_clients'] = $clientModel->countAll();
    $data['total_sales']   = $salesModel->countAll();
    $file = WRITEPATH . 'orders.json';

$orders = session()->get('orders');
  $data['revenue'] = rand(20000, 100000);

// If session empty → restore from JSON
if (empty($orders) && file_exists($file)) {
    $orders = json_decode(file_get_contents($file), true);
    session()->set('orders', $orders);
}

$data['total_orders'] = !empty($orders) ? count($orders) : 0;

    // ✅ Graph Modules
    $modulesGraph = [
        'Users'     => \App\Models\UserModel::class,
        'Clients'   => \App\Models\ClientModel::class,
        'Sales'     => \App\Models\SalesModel::class,
        'Quotation' => \App\Models\QuotationModel::class,
    ];

    $labels   = [];
    $datasets = [];

    // ✅ Labels (Last 7 Days)
    for ($i = 6; $i >= 0; $i--) {
        $date     = date('Y-m-d', strtotime("-$i days"));
        $labels[] = date('d M', strtotime($date));
    }

    // ✅ Graph Data
    foreach ($modulesGraph as $name => $modelClass) {

        $model      = new $modelClass();
        $dataPoints = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));

            $count = $model
                ->where('created_at >=', $date . ' 00:00:00')
                ->where('created_at <=', $date . ' 23:59:59')
                ->countAllResults();

            $dataPoints[] = $count;
        }

        $datasets[] = [
            'label'       => $name,
            'data'        => $dataPoints,
            'borderWidth' => 2,
            'tension'     => 0.4
        ];
    }

    // ✅ Chart Data
    $data['chart_labels']   = json_encode($labels);
    $data['chart_datasets'] = json_encode($datasets);

   // 🔥 MODULE ACCESS SYSTEM
$allModules = ['users','clients','sales','cad','calculator','quotation','orders','accounts','shipping','factory','alerts'];

$role = strtolower(trim(session()->get('role')));
$extraModules = session()->get('module_access') ?? [];

// ✅ ADMIN → ALL MODULES
if ($role == 'admin') {
    $modules = $allModules;
} else {
    // ✅ OTHER USERS → ONLY SELECTED
    $modules = $extraModules;
}

$data['modules'] = $modules;
  $leadModel = new \App\Models\LeadModel();

    $today = date('Y-m-d');

    $data['today_followups'] = $leadModel
        ->where('follow_up_date', $today)
        ->findAll();

    $data['overdue'] = $leadModel
        ->where('follow_up_date <', $today)
        ->findAll();

// ✅ Send to view
$data['modules'] = $modules;

    $userModel = new \App\Models\UserModel();
    $user = $userModel->find(session()->get('user_id'));

    $is_online = false;

    if ($user && !empty($user['last_activity']) && strtotime($user['last_activity']) > time() - 60){
        $is_online = true;
    }

    $data['is_online'] = $is_online;

  $leadModel = new \App\Models\LeadModel();

    /* Today Followups Count */
    $todayCount = $leadModel
        ->where('follow_up_date', date('Y-m-d'))
        ->countAllResults();

    /* New Model Object */
    $leadModel2 = new \App\Models\LeadModel();

    /* Overdue Count */
    $overdueCount = $leadModel2
        ->where('follow_up_date <', date('Y-m-d'))
        ->where('follow_up_date !=', null)
        ->countAllResults();

    $data['todayCount'] = $todayCount;
    $data['overdueCount'] = $overdueCount;

return view('dashboard', $data);

}
    // ✅ API for live update (IMPORTANT)
    public function getChartData()
    {
        $modules = [
            'Users'   => \App\Models\UserModel::class,
            'Clients' => \App\Models\ClientModel::class,
            'Sales'   => \App\Models\SalesModel::class,
            'Quotation' => \App\Models\QuotationModel::class,
        ];

        $labels = [];
        for($i = 6; $i >= 0; $i--){
            $labels[] = date('d M', strtotime("-$i days"));
        }

        $datasets = [];

        foreach($modules as $name => $modelClass){

            $model = new $modelClass();
            $dataPoints = [];

            for($i = 6; $i >= 0; $i--){
                $date = date('Y-m-d', strtotime("-$i days"));

                $count = $model
                    ->where('created_at >=', $date . ' 00:00:00')
                    ->where('created_at <=', $date . ' 23:59:59')
                    ->countAllResults();

                $dataPoints[] = $count;
            }

            $datasets[] = [
                'label' => $name,
                'data'  => $dataPoints,
                'borderWidth' => 2,
                'tension' => 0.4
            ];
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }

  public function globalSearch()
{
    $keyword = $this->request->getGet('q');

    $leadModel = new \App\Models\LeadModel();
    $clientModel = new \App\Models\ClientModel();
    $orderModel = new \App\Models\OrderModel();
    $userModel = new \App\Models\UserModel();
    $salesModel = new \App\Models\SalesModel();
    $shippingModel = new \App\Models\ShippingModel();

    $data['leads'] = $leadModel->like('name', $keyword)->findAll();
    $data['clients'] = $clientModel->like('first_name', $keyword)->orLike('last_name', $keyword)->findAll();
    $data['orders'] = $orderModel->like('order_number', $keyword)->findAll();

    // ✅ New modules
    $data['users'] = $userModel->like('first_name', $keyword)->orLike('email', $keyword)->findAll();

   $data['sales'] = $salesModel->like('id', $keyword)->findAll();

    $data['shipping'] = $shippingModel->like('tracking_number', $keyword)->findAll();

    return view('search_results', $data);
}

public function markFollowupDone($id)
{
    $leadModel = new \App\Models\LeadModel();

    // Update follow_up_date to NULL
    $leadModel->update($id, [
        'follow_up_date' => null
    ]);

    // Success message
    session()->setFlashdata('success', 'Follow-up marked as done successfully.');

    // Redirect back dashboard
    return redirect()->to('/dashboard');
}
}
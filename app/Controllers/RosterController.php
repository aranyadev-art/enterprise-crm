<?php

namespace App\Controllers;

use App\Models\RosterModel;
use App\Models\RosterSalesModel;
use App\Models\CadModel;
use App\Models\UserModel;

class RosterController extends BaseController
{
    protected $rosterModel;
    protected $rosterSalesModel;
    protected $cadModel;
    protected $userModel;

    public function __construct()
    {
        $this->rosterModel = new RosterModel();
        $this->rosterSalesModel = new RosterSalesModel();
        $this->cadModel = new CadModel();
        $this->userModel = new UserModel(); // ✅ correct place
    }

    // 🟢 LIST PAGE
public function index()
{
    $rosters = $this->rosterModel->findAll();

    foreach ($rosters as &$r) {

        $sales = $this->rosterSalesModel
            ->select("CONCAT(users.first_name, ' ', users.last_name) as name")
            ->join('users', 'users.id = roster_sales.sales_id')
            ->where('roster_id', $r['id'])
            ->orderBy('sort_order', 'ASC')
            ->findAll();

        $r['sales_names'] = array_column($sales, 'name');
    }

    $data['rosters'] = $rosters;

    return view('roster/index', $data);
}

    // 🟣 CREATE PAGE
    public function create()
    {
        $data['cad'] = $this->cadModel->findAll();
        $data['users'] = $this->userModel->findAll(); // ✅ users भेजो

        return view('roster/create', $data);
    }

    // 🟣 SAVE
    public function save()
{

    $date  = $this->request->getPost('date');
    $cad   = $this->request->getPost('cad_id');
   $sales = $this->request->getPost('selected_sales');

    // insert roster
    $this->rosterModel->insert([
        'date' => date('Y-m-d', strtotime($date)),
        'cad_designer_id' => $cad
    ]);

    $rosterId = $this->rosterModel->getInsertID();

    // insert sales persons
 if (!empty($sales)) {
    foreach ($sales as $index => $userId) {

        // ✅ yaha condition add ki hai
        if (!empty($userId) && $userId != 0) {

            $this->rosterSalesModel->insert([
                'roster_id' => $rosterId,
                'sales_id' => $userId,
                'sort_order' => $index + 1
            ]);

        }
    }
}

    return redirect()->to('/roster')->with('success', 'Roster Created');
}
public function edit($id)
{
    $data['roster'] = $this->rosterModel->find($id);

    $data['cad'] = $this->cadModel->findAll();
    $data['users'] = $this->userModel->findAll();

    // ✅ GET SELECTED USERS FROM roster_sales TABLE
    $selected = $this->rosterSalesModel
        ->where('roster_id', $id)
        ->orderBy('sort_order', 'ASC')
        ->findAll();

    // extract only user IDs
    $data['selected_users'] = array_column($selected, 'sales_id');

    return view('roster/edit', $data);
}
public function update($id)
{
    // update main roster
    $this->rosterModel->update($id, [
        'date' => $this->request->getPost('date'),
        'cad_designer_id' => $this->request->getPost('cad_id')
    ]);

    // delete old users
    $this->rosterSalesModel->where('roster_id', $id)->delete();

    // insert new users
    $sales = $this->request->getPost('selected_users');
    if (!empty($sales)) {
        foreach ($sales as $index => $userId) {
            $this->rosterSalesModel->insert([
                'roster_id' => $id,
                'sales_id' => $userId,
                'sort_order' => $index + 1
            ]);
        }
    }

    return redirect()->to('/roster')->with('success', 'Updated successfully');
}
public function delete($id)
{
    $model = new RosterModel();
    $model->delete($id);

    return redirect()->to('/roster')->with('success', 'Deleted successfully');
}
}
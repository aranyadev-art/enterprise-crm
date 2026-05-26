<?php
namespace App\Controllers;

use App\Models\LeadModel;
use App\Models\ClientModel;
use App\Models\UserModel;

class Leads extends BaseController
{
    protected $db;
    protected $leadModel;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->leadModel = new LeadModel();
        $this->userModel = new UserModel();
    }

public function index()
{ 
        $data['users'] = $this->userModel
        ->where('role', 'sales')
        ->findAll();

            
    $role = session()->get('role');
    $user_id = session()->get('user_id');

    $builder = $this->db->table('leads');
    $builder->select('leads.*, CONCAT(users.first_name, " ", users.last_name) as assigned_user');
    $builder->join('users', 'users.id = leads.assigned_to', 'left');

    if ($role == 'sales') {
        $builder->where('leads.assigned_to', $user_id);
    }

    $data['leads'] = $builder->get()->getResult();

    return view('leads/index', $data);
}

    public function create()
    {
        return view('leads/create');
    }

 public function store()
{
    $model = new \App\Models\LeadModel();

    $data = [
        'name' => $this->request->getPost('name'),
        'email' => $this->request->getPost('email'),
        'phone' => $this->request->getPost('phone'),
        'company' => $this->request->getPost('company'),
        'status' => 'new',
        'source' => $this->request->getPost('source') ?? 'manual',
        'assigned_to' => session()->get('user_id'),
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
        'follow_up_date' => $this->request->getPost('follow_up_date')
    ];

    if($model->insert($data)){
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Lead added successfully'
        ]);
    } else {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'DB insert failed'
        ]);
    }
}

    public function delete($id)
    {
        $model = new LeadModel();
        $model->delete($id);

        return redirect()->to('/leads');
    }

public function updateStatus($id)
{
    $status = $this->request->getPost('status');

    $this->leadModel->update($id, [
        'status' => $status,
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/leads');
}

public function convert($id)
{
    $leadModel = new LeadModel();
    $clientModel = new ClientModel();

    // Fetch lead
    $lead = $leadModel->find($id);

    if (!$lead) {
        return redirect()
            ->back()
            ->with('error', 'Lead not found');
    }

    // Prevent duplicate client
    $exists = $clientModel
        ->where('email', $lead['email'])
        ->first();

    if ($exists) {
        return redirect()
            ->back()
            ->with('error', 'Client already exists');
    }

    // Split full name
    $nameParts = explode(' ', trim($lead['name']), 2);

    $firstName = $nameParts[0] ?? '';
    $lastName  = $nameParts[1] ?? '';

    // Assigned sales user
    $salesUserId = !empty($lead['assigned_to'])
        ? $lead['assigned_to']
        : null;

    // Client insert data
    $clientData = [
        'first_name'    => $firstName,
        'last_name'     => $lastName,
        'email'         => $lead['email'],
        'phone'         => $lead['phone'],
        'city'          => '',
        'sales_user_id' => $salesUserId,
        'password'      => password_hash('123456', PASSWORD_DEFAULT)
    ];

    // Save client
    $save = $clientModel->save($clientData);

    if (!$save) {
        return redirect()
            ->back()
            ->with('error', 'Client conversion failed');
    }

    // Update lead status
    $leadModel->update($id, [
        'status' => 'converted'
    ]);

    return redirect()
        ->to(base_url('index.php/leads'))
        ->with('success', 'Lead converted successfully');
}
   public function assign($id)
{
    $this->leadModel->update($id, [
        'assigned_to' => $this->request->getPost('assigned_to')
    ]);

    return redirect()->to('/leads');
}
/**
 * Deletes multiple leads based on an array of IDs received from POST data.
 *
 * Expects 'ids' in POST data as an array of lead IDs to delete.
 * Returns a JSON response indicating success or error.
 */
public function deleteMultiple()
{
    $ids = $this->request->getPost('ids');

    if(!empty($ids))
    {
        $leadModel = new \App\Models\LeadModel();

        foreach($ids as $id)
        {
            $leadModel->delete($id);
        }

        return $this->response->setJSON([
            'success' => true
        ]);
    }

    return $this->response->setJSON([
        'success' => false
    ]);
}
  }
   

 ?>
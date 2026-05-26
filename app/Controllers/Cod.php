<?php

namespace App\Controllers;

use App\Models\CadModel;

class Cod extends BaseController
{
               protected $cadModel;

    public function __construct()
    {
        $this->cadModel = new CadModel();
    }
public function index()
{
    $role = session()->get('role');
    $access = session()->get('module_access') ?? [];

    if (!is_array($access)) {
        $access = explode(',', $access);
    }

    $access = array_map('trim', $access);
    $access = array_map('strtolower', $access);

    // 🔐 Access check
    if (strtolower(trim($role)) != 'admin') {
        if (!in_array('cad', $access)) {
            return redirect()->to('dashboard')->with('error', 'Access Denied');
        }
    }


    return view('cod/index');
}
public function create()
{

   if(
   session()->get('role') != 'Admin' &&
   session()->get('role') != 'Sales' &&
   session()->get('role') != 'Shipping'
){
   return redirect()->to('/dashboard');
}

    $model = new CadModel();

    $last = $model->orderBy('id','DESC')->first();

    $next = $last ? $last['id'] + 1 : 1;

    $cad_code = "CAD-" . str_pad($next,4,"0",STR_PAD_LEFT);

  // 🔥 ADD THIS
    $sale_id = $this->request->getGet('sale_id');

    return view('cod/create', [
        'cad_code' => $cad_code,
    ]);

}
public function list()
{
    $model = new CadModel();

    $data['records'] = $model->findAll();

    return view('cod/list', $data);
}
public function download($id)
{
    $model = new \App\Models\CadModel();
    $data = $model->find($id);

    if (!$data || empty($data['design_file'])) {
        return redirect()->back()->with('error', 'File not found');
    }

    $filePath = FCPATH . 'uploads/cad/' . $data['design_file'];

    if (!file_exists($filePath)) {
        return redirect()->back()->with('error', 'File does not exist');
    }

    return $this->response->download($filePath, null);
}


public function save()
{

    if(
        session()->get('role') != 'Admin' &&
        session()->get('role') != 'Sales' &&
        session()->get('role') != 'Shipping'
    ){
        return redirect()->to('/dashboard');
    }

    $model = new CadModel();

    $file = $this->request->getFile('design_file');

// ✅ FILE UPLOAD (ONLY ONCE)
$filename = '';
$cpx_sent = 0;

if($file && $file->isValid() && !$file->hasMoved()){
    $filename = $file->getRandomName();
    $file->move('uploads/cad',$filename);

    $cpx_sent = 1;
}

// ✅ GET EMAILS
$clientEmail  = $this->request->getPost('client_email');
$factoryEmail = $this->request->getPost('factory_email');

// ================================
// 🔔 CAD EMAIL NOTIFICATION
// ================================
if ($cpx_sent == 1) {

    $email = \Config\Services::email();
    $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

    // CLIENT
    if (!empty($clientEmail)) {

        $email->setTo($clientEmail);
 $email->setSubject('Your CAD Design is Ready');

$email->setMessage("
<div style='font-family: Arial; line-height:1.6;'>

    <h2 style='color:#2a7bd3;'>Hello 👋</h2>

    <p>Your CAD design has been successfully completed.</p>

    <p>Please find your design attached with this email for review.</p>

    <p><b>What you need to do:</b><br>
    Kindly check the design and let us know if any changes are required or approve it to proceed further.</p>

    <br>

    <p><b>CAD Code:</b> ".$this->request->getPost('cad_code')."</p>
    <p><b>Date:</b> ".date('d M Y')."</p>

    <br>

    <p>If you have any questions, feel free to reply to this email.</p>

    <br>

    <p>Thank you for choosing us 🙏</p>

    <p style='margin-top:20px;'>
        <b>Best Regards,</b><br>
        CAD Team
    </p>

</div>
");

        if (!empty($filename)) {
            $email->attach('uploads/cad/' . $filename);
        }

        $email->send();
    }

    // FACTORY
    if (!empty($factoryEmail)) {

        $email->clear(true);

        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
        $email->setTo($factoryEmail);

        $email->setSubject('New CAD Design for Production');

$email->setMessage("
<div style='font-family: Arial; line-height:1.6;'>

    <h2 style='color:#28a745;'>New Design Received 🏭</h2>

    <p>A new CAD design has been uploaded and is ready for production.</p>

    <p><b>CAD Code:</b> ".$this->request->getPost('cad_code')."</p>
    <p><b>Date:</b> ".date('d M Y')."</p>

    <br>

    <p><b>Action Required:</b><br>
    Please review the attached design and start the production process.</p>

    <br>

    <p>If there are any issues with the design, kindly report back immediately.</p>

    <br>

    <p style='margin-top:20px;'>
        <b>Production Team</b><br>
        CAD Department
    </p>

</div>
");

        if (!empty($filename)) {
            $email->attach('uploads/cad/' . $filename);
        }

        $email->send();
    }
}

    // 🔥 GET TIMES
    $start = $this->request->getPost('start_time');
    $end   = $this->request->getPost('end_time');

    // 🔥 VALIDATION
    if(empty($start) || empty($end)){
        return redirect()->back()->with('error', 'Start & End time required');
    }

    $startTime = strtotime($start);
    $endTime   = strtotime($end);

    if($endTime < $startTime){
        return redirect()->back()->with('error', 'End time must be greater than Start time');
    }

    // 🔥 CALCULATE DURATION (BACKEND)
    $diff = $endTime - $startTime;

    $hours = floor($diff / 3600);
    $minutes = floor(($diff % 3600) / 60);

    $duration = $hours . 'h ' . $minutes . 'm';
  $sale_id = $this->request->getPost('sale_id');

    // 🔥 SAVE DATA
    $data = [
        'cad_code' => $this->request->getPost('cad_code'),
        'start_time' => $start,
        'end_time' => $end,
        'duration' => $duration, // ✅ FIXED
        'design_file' => $filename,
        'cpx_sent' => $cpx_sent,
        'comment' => $this->request->getPost('comment')
    ];

     // ✅ FILE UPLOAD (your existing code)
// ================================
// 🔔 CAD EMAIL NOTIFICATION
// ================================
if ($cpx_sent == 1) {

    $email = \Config\Services::email();
    $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);

    // CLIENT
    if (!empty($clientEmail)) {

        $email->setTo($clientEmail);
        $email->setSubject('CAD Design Uploaded');
        $email->setMessage("Design ready. Check attachment.");

        if (!empty($filename)) {
            $email->attach('uploads/cad/' . $filename);
        }

        $email->send();
    }

    // FACTORY
    if (!empty($factoryEmail)) {

        $email->clear(true);

        $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
        $email->setTo($factoryEmail);
        $email->setSubject('New CAD Design');

        $email->setMessage("New design uploaded.");

        if (!empty($filename)) {
            $email->attach('uploads/cad/' . $filename);
        }

        $email->send();
    }
}


    $model->insert($data);

    return redirect()->to('/cod/list');
}


   public function deleteMultiple()
{
    $data = json_decode(file_get_contents("php://input"), true);

    $ids = $data['ids'] ?? [];

    if (empty($ids)) {
        return $this->response->setJSON(['status' => 'error']);
    }

    $model = new \App\Models\CadModel(); // 👈 your model name

    $model->whereIn('id', $ids)->delete();

    return $this->response->setJSON(['status' => 'success']);
}
}
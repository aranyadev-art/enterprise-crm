<?php

namespace App\Controllers;

use App\Models\AccountModel;
use Dompdf\Dompdf;

class Accounts extends BaseController
{

public function index()
{
    $role = session()->get('role');
    $access = session()->get('module_access') ?? [];

    if (!is_array($access)) {
        $access = explode(',', $access);
    }

    $access = array_map('trim', $access);
    $access = array_map('strtolower', $access);

    // 🔐 ACCESS CHECK
    if (strtolower(trim($role)) != 'admin' && !in_array('account', $access)) {
        return redirect()->to('dashboard')->with('error', 'Access Denied');
    }

    // ✅ DATA LOAD
    $model = new \App\Models\AccountModel();
    $data['accounts'] = $model->findAll();

    return view('accounts/index', $data); // ✅ data pass kiya
}
 public function create()
{
    $role = session()->get('role');
    $access = session()->get('module_access') ?? [];

    if (!is_array($access)) {
        $access = explode(',', $access);
    }

    $access = array_map('trim', $access);
    $access = array_map('strtolower', $access);

    // 👑 ADMIN = full access
    if (strtolower(trim($role)) == 'admin') {
        return view('accounts/create');
    }

    // 🔐 CHECK MODULE ACCESS
    if (!in_array('account', $access)) {
        return redirect()->to('dashboard')->with('error', 'Access Denied');
    }

    return view('accounts/create');
}

    public function save()
    {
        $model = new AccountModel();

        $data = [
            'due_balance' => $this->request->getPost('due_balance'),
            'credit_limit' => $this->request->getPost('credit_limit'),
            'allow_client' => $this->request->getPost('allow_client') ?? 0,
            'allow_orders' => $this->request->getPost('allow_orders') ?? 0,
            'allow_shipping' => $this->request->getPost('allow_shipping') ?? 0,
            'allow_alerts' => $this->request->getPost('allow_alerts') ?? 0
        ];

        $model->insert($data);

       return redirect()->to('/accounts/list');
    }
    public function list()
{
    $role = strtolower(session()->get('role'));

    if(!in_array($role, ['admin','sales'])){
        return redirect()->to('/dashboard');
    }

    $model = new AccountModel();
    $data['accounts'] = $model->findAll();

    return view('accounts/index', $data);
}

public function dispatch($id)
{
    $model = new AccountModel();

    $account = $model->find($id);

    // ❌ Agar payment pending hai toh block karo
    if($account['due_balance'] > 0){
        return redirect()->back()->with('error', 'Payment Pending!');
    }

    // ✅ Agar payment complete hai
    $model->update($id, [
        'status' => 'Shipped'
    ]);

    return redirect()->back()->with('success', 'Order Dispatched!');
}

public function deleteMultiple()
{
    $ids = $this->request->getPost('ids');

    if(!empty($ids)){
        $model = new \App\Models\AccountModel();
        $model->whereIn('id', $ids)->delete();
    }

    return redirect()->to('/accounts')->with('success', 'Deleted!');
}
public function addPayment($id)
{
    return view('accounts/payment_form', ['account_id' => $id]);
}
public function savePayment()
{
    $paymentModel = new \App\Models\PaymentModel();
    $accountModel = new \App\Models\AccountModel();

    // ✅ Get & sanitize input
    $account_id = (int) $this->request->getPost('account_id');
    $amount     = (float) $this->request->getPost('amount');
    $method     = $this->request->getPost('payment_method');
    $upi_id = $this->request->getPost('upi_id');

    // ✅ Validation
    if (!$account_id || !$amount || !$method) {
        return redirect()->back()->with('error', 'All fields are required');
    }

    if ($amount <= 0) {
        return redirect()->back()->with('error', 'Invalid amount');
    }

    // ✅ Fetch account
    $account = $accountModel->find($account_id);

    if (!$account) {
        return redirect()->back()->with('error', 'Account not found');
    }

    // ✅ Business rule check
  if ($account['due_balance'] > 0 && $amount > $account['due_balance']) {
    return redirect()->back()->with('error','Amount exceeds due!');
}

    // ✅ DB Transaction (VERY IMPORTANT - professional practice)
    $db = \Config\Database::connect();
    $db->transStart();

    try {

        // 1️⃣ Save payment
        $paymentModel->insert([
            'account_id'     => $account_id,
            'amount'         => $amount,
            'payment_method' => $method,
               'upi_id'         => ($method == 'upi') ? $upi_id : null,
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        // 2️⃣ Update due balance
      if ($account['due_balance'] > 0) {
    // normal due (client needs to pay)
    $new_due = $account['due_balance'] - $amount;
} else {
    // advance case (already extra paid)
    $new_due = $account['due_balance'] + $amount;
}

        $accountModel->update($account_id, [
            'due_balance' => $new_due
        ]);

    } catch (\Exception $e) {
        $db->transRollback();
        return redirect()->back()->with('error', 'Something went wrong');
    }

    $db->transComplete();

    return redirect()->to('/accounts')->with('success', 'Payment added successfully');
}
public function paymentHistory($account_id)
{
    $paymentModel = new \App\Models\PaymentModel();

    $data['payments'] = $paymentModel
        ->where('account_id', $account_id)
        ->orderBy('id', 'DESC')
        ->findAll();

    $data['account_id'] = $account_id;

    return view('accounts/payment_history', $data);
}




public function downloadPaymentsPDF($account_id)
{
    $paymentModel = new \App\Models\PaymentModel();

    $payments = $paymentModel
        ->where('account_id', $account_id)
        ->findAll();

    // HTML design
    $html = '<h2 style="text-align:center;">Payment History</h2>';

    $html .= '<table border="1" width="100%" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Amount</th>
            <th>Method</th>
            <th>UPI ID</th>
            <th>Date</th>
        </tr>';

    foreach ($payments as $p) {
        $html .= '<tr>
            <td>'.$p['id'].'</td>
            <td>'.$p['amount'].'</td>
            <td>'.strtoupper($p['payment_method']).'</td>
            <td>'.($p['upi_id'] ?? '-').'</td>
            <td>'.$p['created_at'].'</td>
        </tr>';
    }

    $html .= '</table>';

    // PDF generate
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Download
    $dompdf->stream("payment_history.pdf", ["Attachment" => true]);
}
}
<?php

namespace App\Controllers;

use App\Models\QuotationModel;
use Dompdf\Dompdf;

class Quotation extends BaseController
{


    
    public function index()
    {
        return redirect()->to(base_url('index.php/quotation/list'));
    }

    public function list()
    {
        $model = new QuotationModel();
        $data['quotations'] = $model->findAll();

        return view('quotation/list', $data);
    }

  public function create()

{
    // ✅ YAHI ADD KARNA HAI (GET PART)
    if ($this->request->getMethod() === 'GET') {

        $clientModel = new \App\Models\ClientModel();
        $data['clients'] = $clientModel->findAll();

        return view('quotation/form', $data);
    }

    // POST → save
    $model = new QuotationModel();

    $metal = (float)$this->request->getPost('metal_price');
    $stone = (float)$this->request->getPost('stone_price');
    $purity = (float)$this->request->getPost('purity');
    $currency = $this->request->getPost('currency');

    $total = ($metal + $stone) * $purity / 100;

    $model->save([
        'quotation_id' => 'QT' . rand(1000,9999),
        'metal_price' => $metal,
        'stone_price' => $stone,
        'purity' => $purity,
        'currency' => $currency,
        'total_price' => $total,
       'client_id' => $this->request->getPost('client_id'),
        'status' => $this->request->getPost('status')
    ]);
$sendEmail = $this->request->getPost('send_email');

$userModel = new \App\Models\UserModel();
$user = $userModel->find(session()->get('user_id'));

// ✅ Simplify condition for now (testing)
if ($sendEmail == 1) {

    $email = \Config\Services::email();

    $email->setFrom('2906dev@gmail.com', 'CRM System');

    // 👉 Change later to client email
    $email->setTo('2906dev@gmail.com');

    $email->setSubject('New Quotation Created');

    $message = "
        <h3>New Quotation Created ✅</h3>
        <p><b>Metal Price:</b> {$metal}</p>
        <p><b>Stone Price:</b> {$stone}</p>
        <p><b>Purity:</b> {$purity}%</p>
        <p><b>Total:</b> {$total} {$currency}</p>
    ";

    $email->setMessage($message);

    // ✅ IMPORTANT: check result
 if (!$email->send()) {
    echo $email->printDebugger(['headers', 'subject', 'body']);
    die();

    }
}

    return redirect()->to(base_url('index.php/quotation/list'));
}
     public function deleteMultiple()
{
    $data = $this->request->getJSON(true);
    $ids = $data['ids'] ?? [];

    if (!empty($ids)) {

        $db = \Config\Database::connect();

        foreach ($ids as $id) {
            $db->query("DELETE FROM quotations WHERE id = ?", [$id]);
        }
    }

    echo "success";
}



    public function downloadPdf($id)
    {
        $model = new QuotationModel();
        $data['quotation'] = $model->find($id);

        $html = view('quotation/pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->render();

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="quotation.pdf"')
            ->setBody($dompdf->output());
    }

  public function storeFromCalculator()
{
    $model = new \App\Models\QuotationModel();

    $total = $this->request->getPost('total');

    if (!$total) {
        echo "No total received";
        exit;
    }

    $quotationId = 'QT' . rand(1000,9999);

    $model->insert([
        'quotation_id' => $quotationId,   // ✅ FIXED
        'total_price'  => $total,         // ✅ FIXED
        'currency'     => 'INR',
        'created_at'   => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/quotation')->with('success', 'Quotation Created');
}
public function edit($id)
{
    $model = new \App\Models\QuotationModel();
    $clientModel = new \App\Models\ClientModel();

    $data['quotation'] = $model->find($id);

    // ✅ ADD THIS LINE
    $data['clients'] = $clientModel->findAll();

    return view('quotation/edit', $data);
}
public function delete($id)
{
    $model = new \App\Models\QuotationModel();

    $model->delete($id);

    return redirect()->to(base_url('index.php/quotation'))
                     ->with('success', 'Quotation Deleted Successfully');
}
public function update($id)
{
    $model = new \App\Models\QuotationModel();

    $data = [
        'client_id'   => $this->request->getPost('client_id'), // ✅ FIX
        'total_price' => $this->request->getPost('total_price'),
        'currency'    => $this->request->getPost('currency'),
        'status'      => $this->request->getPost('status')
    ];

    $model->update($id, $data);

    return redirect()->to(base_url('index.php/quotation/list')); // ✅ better redirect
}
}
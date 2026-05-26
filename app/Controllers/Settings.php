<?php
namespace App\Controllers;

use App\Models\UserModel;

class Settings extends BaseController
{

    public function testEmail()
{
    $email = \Config\Services::email();

    $email->setTo('yourtestemail@gmail.com');
    $email->setSubject('Test Email');
    $email->setMessage('<h3>Email working ✅</h3>');

    if ($email->send()) {
        echo "Email sent successfully";
    } else {
        echo $email->printDebugger(['headers']);
    }
}




  public function index()
{
    $userModel = new \App\Models\UserModel();

    $userId = session()->get('user_id');

    $data['settings'] = $userModel->find($userId);

    return view('settings', $data);
}

    // 🔐 CHANGE PASSWORD
public function updatePassword()
{
    $userModel = new \App\Models\UserModel();

    // ✅ FIXED: correct session key
    $id = session()->get('user_id');

    $user = $userModel->find($id);

    $current = $this->request->getPost('current_password');
    $new     = $this->request->getPost('new_password');
    $confirm = $this->request->getPost('confirm_password');

    // ✅ Check user exists
    if (!$user || empty($user['password'])) {
        return redirect()->back()->with('error', 'User not found');
    }
    // ✅ Verify current password
    if (!password_verify($current, $user['password'])) {
        return redirect()->back()->with('error', 'Wrong current password');
    }

    // ✅ Check new password match
    if ($new !== $confirm) {
        return redirect()->back()->with('error', 'Passwords do not match');
    }

    // ✅ IMPORTANT: update using model->update()
    $updated = $userModel->update($id, [
        'password' => password_hash($new, PASSWORD_DEFAULT)
    ]);

    // ✅ Debug check (optional – remove after testing)
    if (!$updated) {
        return redirect()->back()->with('error', 'Password not updated');
    }

    // 🔐 Force logout after change
// Optional: refresh session (not mandatory)
session()->set('last_password_change', time());

return redirect()->to('/dashboard')->with('success', 'Password updated successfully.');
}
    // 🖼️ PROFILE IMAGE
 public function uploadProfile()
{
    $file = $this->request->getFile('profile_image');

    // Check file
    if (!$file || !$file->isValid() || $file->hasMoved()) {
        return redirect()->back()->with('error', 'Upload failed');
    }

    // Allow only images
    $allowedTypes = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

    if (!in_array($file->getMimeType(), $allowedTypes)) {
        return redirect()->back()->with('error', 'Only images allowed');
    }

    // Create folder if not exists
    $uploadPath = FCPATH . 'uploads/profile/';

    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Generate unique name
    $newName = time() . '_' . $file->getRandomName();

    // Move file (IMPORTANT FIX)
    $file->move($uploadPath, $newName);

    // Save in DB
    $userModel = new UserModel();
    $userId = session()->get('user_id');

    $userModel->update($userId, [
        'profile_image' => $newName
    ]);

    // Update session
    session()->set('profile_image', $newName);

    return redirect()->to('/settings')->with('success', 'Profile updated');
}
    // 🔔 NOTIFICATIONS
public function updateNotification()
{
    $userModel = new \App\Models\UserModel();

    // ✅ FIXED (same as other functions)
    $userId = session()->get('user_id');

    $userModel->update($userId, [
        'email_notification'  => $this->request->getPost('email_notification') ? 1 : 0,
        'system_notification' => $this->request->getPost('system_notification') ? 1 : 0,
    ]);

  return redirect()->to(base_url('index.php/dashboard'))->with('success', 'Settings saved');
}
}
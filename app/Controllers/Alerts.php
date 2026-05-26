<?php

namespace App\Controllers;

class Alerts extends BaseController
{
    public function index()
    {
        $session = session();
        $data['alerts'] = $session->get('alerts') ?? [];

        return view('alerts/index', $data);
    }

    // ✅ Alert create (jab new user create ho)
    public function createAlert($name)
    {
        $session = session();
        $alerts = $session->get('alerts') ?? [];

        $alerts[] = [
            'id' => count($alerts) + 1, // ✅ FIXED
            'title' => 'New User Created: ' . $name,
            'module' => 'Users',
            'datetime' => date('Y-m-d H:i:s') // ✅ FIXED
        ];

        $session->set('alerts', $alerts);

        return redirect()->to('/alerts');
    }

    // ✅ Mark as Read
    public function markAsRead($id)
    {
        session()->set('alert_status_' . $id, 'Read');
        return redirect()->back();
    }
}
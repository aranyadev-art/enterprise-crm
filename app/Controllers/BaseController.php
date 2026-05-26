<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
   public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
{
    parent::initController($request, $response, $logger);

    // ✅ Get current controller name
   $currentController = strtolower($request->getUri()->getSegment(1));

    // ❗ Skip for dashboard or auth (IMPORTANT)
    $skip = ['dashboard', 'auth'];

    if (session()->get('user_id')) {

    $userModel = new \App\Models\UserModel();
    $user = $userModel->find(session()->get('user_id'));

    $is_online = false;

   if ($user && isset($user['last_activity']) && strtotime($user['last_activity']) > time() - 60) {
    $is_online = true;
    }

    // ✅ Force pass to all views (dashboard included)
      // ✅ CORRECT WAY (CI4)
    service('renderer')->setVar('is_online', $is_online);
}

}  

}
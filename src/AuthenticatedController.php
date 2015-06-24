<?php
namespace Tt;

use Vtk13\Mvc\Http\RedirectResponse;
use Vtk13\Mvc\Http\Response;

class AuthenticatedController extends BaseController
{
    protected $currentUser;

    protected function beforeHandle($action, $params)
    {
        if (isset($_SESSION['userId'])) {
            $this->currentUser = $this->db->selectRow('SELECT * FROM user WHERE id=' . (int)$_SESSION['userId']);
            return null;
        } else {
            $_SESSION['login-redirect'] = $_SERVER['REQUEST_URI'];
            return new RedirectResponse('/auth/google-login');
        }
    }

    public function actionResultToResponse($result, $templatePath, $action)
    {
        if (is_null($result) || is_array($result)) {
            $result['currentUser'] = $this->currentUser;
        }
        return parent::actionResultToResponse($result, $templatePath, $action);
    }
}

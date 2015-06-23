<?php
namespace Tt;

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
            return new Response('Access Denied', 403);
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

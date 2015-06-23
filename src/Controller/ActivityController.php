<?php
namespace Tt\Controller;

use Vtk13\LibSql\IDatabase;
use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\RedirectResponse;

class ActivityController extends AbstractController
{
    /**
     * @var IDatabase
     */
    protected $db;

    protected $defaultAction = 'list';

    public function __construct()
    {
        parent::__construct('activity');

        $this->db = $GLOBALS['db'];
    }

    public function listGET()
    {
        return [
            'activities' => $this->db->select('SELECT * FROM activity'),
        ];
    }

    public function removePOST($id)
    {
        $this->db->delete('activity', 'id=' . (int)$id);
        $this->db->delete('activity_log', 'activity_id=' . (int)$id);

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function editGET($id)
    {
        return $this->db->selectRow('SELECT * FROM activity WHERE id=' . (int)$id);
    }

    public function editPOST()
    {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $data = [
            'title' => $_POST['title'],
        ];
        if ($id) {
            $this->db->update('activity', $data, "id={$id}");
        } else {
            $this->db->insert('activity', $data);
        }

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }
}

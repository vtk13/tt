<?php
namespace Tt\Controller;

use Tt\AuthenticatedController;
use Vtk13\Mvc\Http\RedirectResponse;

class ActivityController extends AuthenticatedController
{
    protected $defaultAction = 'list';

    public function __construct()
    {
        parent::__construct('activity');
    }

    public function listGET()
    {
        $activities = $this->db->select(
            'SELECT a.*, sum(IF(time_end=0, UNIX_TIMESTAMP(), time_end) - time_start) as spent
               FROM activity a
                    LEFT JOIN activity_log b ON a.id=b.activity_id
              WHERE a.user_id=' . $this->currentUser['id'] . '
           GROUP BY a.id'
        );
        return [
            'activities' => $activities,
        ];
    }

    public function removePOST($id)
    {
        $this->db->delete('activity', $this->db->where([
            'user_id'   => $this->currentUser['id'],
            'id'        => $id,
        ]));
        $this->db->delete('activity_log', $this->db->where([
            'user_id'       => $this->currentUser['id'],
            'activity_id'   => $id,
        ]));

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function editGET($id)
    {
        return $this->db->selectRow('SELECT * FROM activity WHERE ' . $this->db->where([
            'user_id'   => $this->currentUser['id'],
            'id'        => $id,
        ]));
    }

    public function editPOST()
    {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $data = [
            'title'     => $_POST['title'],
            'user_id'   => $this->currentUser['id'],
        ];
        if ($id) {
            $this->db->update('activity', $data, $this->db->where([
                'user_id'   => $this->currentUser['id'],
                'id'        => $id,
            ]));
        } else {
            $this->db->insert('activity', $data);
        }

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }
}

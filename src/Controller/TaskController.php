<?php
namespace Tt\Controller;

use Tt\AuthenticatedController;
use Vtk13\Mvc\Http\RedirectResponse;

class TaskController extends AuthenticatedController
{
    protected $defaultAction = 'list';

    public function __construct()
    {
        parent::__construct('task');
    }

    public function listGET()
    {
        $tasks = $this->db->select(
            'SELECT a.*, sum(IF(time_end=0, UNIX_TIMESTAMP(), time_end) - time_start) as spent
                   FROM task a
                        LEFT JOIN activity_log b ON a.id=b.task_id
                  WHERE a.user_id=' . $this->currentUser['id'] . '
               GROUP BY a.id'
        );
        return [
            'tasks' => $tasks,
        ];
    }

    public function removePOST($id)
    {
        $this->db->delete('task', $this->db->where([
            'user_id'   => $this->currentUser['id'],
            'id'        => $id,
        ]));
        $this->db->delete('activity_log', $this->db->where([
            'user_id'   => $this->currentUser['id'],
            'task_id'   => $id,
        ]));

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function editGET($id)
    {
        return $this->db->selectRow('SELECT * FROM task WHERE ' . $this->db->where([
            'user_id'   => $this->currentUser['id'],
            'id'        => $id,
        ]));
    }

    public function editPOST()
    {
        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $data = [
            'title'         => $_POST['title'],
            'description'   => $_POST['description'],
            'url'           => $_POST['url'],
            'user_id'       => $this->currentUser['id'],
        ];
        if ($id) {
            $this->db->update('task', $data, $this->db->where([
                'user_id'   => $this->currentUser['id'],
                'id'        => $id,
            ]));
        } else {
            $this->db->insert('task', $data);
        }

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }
}

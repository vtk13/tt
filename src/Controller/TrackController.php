<?php
namespace Tt\Controller;

use Vtk13\LibSql\IDatabase;
use Vtk13\LibSql\Mysql\Mysql;
use Vtk13\Mvc\Handlers\AbstractController;
use Vtk13\Mvc\Http\RedirectResponse;

class TrackController extends AbstractController
{
    /**
     * @var IDatabase
     */
    protected $db;

    public function __construct()
    {
        parent::__construct('track');

        $this->db = $GLOBALS['db'];
    }

    public function actionResultToResponse($result, $templatePath, $action)
    {
        if (is_null($result) || is_array($result)) {
            $result['currentActivity'] = $this->db->selectRow('SELECT * FROM activity_log WHERE time_end=0');
            if (empty($result['currentActivity'])) {
                $result['currentActivity'] = [
                    'task_id'       => 0,
                    'activity_id'   => 0,
                ];
            }

            $result['tasks'] = $this->db->select(
                'SELECT a.*, sum(IF(time_end=0, UNIX_TIMESTAMP(), time_end) - time_start) as spent
                   FROM task a
                        LEFT JOIN activity_log b ON a.id=b.task_id
               GROUP BY a.id'
            );

            if (isset($result['selectedTask'])) {
                $taskId = (int)$result['selectedTask'];
                $result['activities'] = $this->db->select(
                    "SELECT a.*, {$taskId} as task_id, sum(IF(time_end=0, UNIX_TIMESTAMP(), time_end) - time_start) as spent
                       FROM activity a
                            LEFT JOIN activity_log b ON b.task_id={$taskId} AND a.id=b.activity_id
                   GROUP BY a.id"
                );
            } else {
                $result['selectedTask'] = 0;
                $result['activities'] = [];
            }
        }
        return parent::actionResultToResponse($result, $templatePath, $action);
    }

    public function indexGET()
    {
    }

    public function taskGET($id)
    {
        return [
            'selectedTask' => $id,
        ];
    }

    public function startPOST($task, $activity)
    {
        foreach ($this->db->select('SELECT * FROM activity_log WHERE time_end=0') as $row) {
            $row['time_end'] = time();
            $this->db->update('activity_log', $row, "id={$row['id']}");
        }

        $this->db->insert(
            'activity_log',
            [
                'task_id'       => $task,
                'activity_id'   => $activity,
                'time_start'    => time(),
                'time_end'      => 0,
            ]
        );

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function stopPOST()
    {
        foreach ($this->db->select('SELECT * FROM activity_log WHERE time_end=0') as $row) {
            $row['time_end'] = time();
            $this->db->update('activity_log', $row, "id={$row['id']}");
        }

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function addTaskPOST()
    {
        $this->db->insert(
            'task',
            [
                'title'         => $_POST['title'],
                'description'   => $_POST['description'],
                'url'           => $_POST['url'],
            ]
        );
        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function removeTaskPOST($id)
    {
        $this->db->delete('task', 'id=' . (int)$id);
        $this->db->delete('activity_log', 'task_id=' . (int)$id);

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }
}

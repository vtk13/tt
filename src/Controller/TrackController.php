<?php
namespace Tt\Controller;

use Tt\AuthenticatedController;
use Vtk13\Mvc\Http\RedirectResponse;

class TrackController extends AuthenticatedController
{

    public function __construct()
    {
        parent::__construct('track');
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

            $today = empty($result['onlyToday']) ? '' : ' AND time_start>UNIX_TIMESTAMP(DATE_FORMAT(NOW(), "%Y-%m-%d 00:00:00"))';

            $result['tasks'] = $this->db->select(
                "SELECT a.*, SUM(IF(time_end=0, UNIX_TIMESTAMP(), time_end) - time_start) AS spent
                   FROM task a
                        LEFT JOIN activity_log b ON a.id=b.task_id
                  WHERE a.user_id={$this->currentUser['id']} {$today}
               GROUP BY a.id"
            );

            if (isset($result['selectedTask'])) {
                $taskId = (int)$result['selectedTask'];
                $result['activities'] = $this->db->select(
                    "SELECT a.*, {$taskId} as task_id, SUM(IF(time_end=0, UNIX_TIMESTAMP(), time_end) - time_start) AS spent
                       FROM activity a
                            LEFT JOIN activity_log b ON b.task_id={$taskId} AND a.id=b.activity_id
                      WHERE a.user_id={$this->currentUser['id']} {$today}
                   GROUP BY a.id"
                );
            } else {
                $result['selectedTask'] = 0;
                $result['activities'] = [];
            }
        }
        return parent::actionResultToResponse($result, $templatePath, $action);
    }

    public function indexGET($today = 0)
    {
        return [
            'onlyToday' => (bool)$today,
        ];
    }

    public function taskGET($id)
    {
        return [
            'selectedTask' => $id,
        ];
    }

    /**
     * Get active record for current user
     *
     * In fact there must be only one active record, but in case of any bugs you should handle all such records
     *
     * @return array
     */
    private function getActiveRecords()
    {
        return $this->db->select('SELECT * FROM activity_log WHERE ' . $this->db->where([
            'user_id'   => $this->currentUser['id'],
            'time_end'  => 0,
        ]));
    }

    public function startPOST($task, $activity)
    {
        foreach ($this->getActiveRecords() as $row) {
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
                'user_id'       => $this->currentUser['id'],
            ]
        );

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function stopPOST()
    {
        foreach ($this->getActiveRecords() as $row) {
            $row['time_end'] = time();
            $this->db->update('activity_log', $row, "id={$row['id']}");
        }

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }

    public function removeTaskPOST($id)
    {
        $this->db->delete('task', 'id=' . (int)$id);
        $this->db->delete('activity_log', 'task_id=' . (int)$id);

        return new RedirectResponse($_SERVER['HTTP_REFERER']);
    }
}

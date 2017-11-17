<?php

class logsController
{
    public function index()
    {
        $limit = intval(request::get('limit', 0));
        $offset = intval(request::get('offset', 0));
        $order_by = request::get('order_by', 'logged_at');
        $order_way = request::get('order_by', 'asc');
        $user_id = intval(request::get('user_id', null));
        $task_id = intval(request::get('task_id', null));

        switch(strtoupper($order_way))
        {
            default:
            case 'ASC':
                $orderway = 'ASC';
                break;
            case 'DESC':
                $orderway = 'DESC';
                break;
        }
        
        switch($order_by)
        {
            default:
            case 'logged_at':
                $orderby = "`logged_at` ".$orderway;
                break;
            case 'duration':
                $orderby = "`duration` ".$orderway;
                break;
        }

        $query = "
            SELECT `react_hackathon_log`.*,
                `react_hackathon_task`.`name` AS task_name
            FROM `react_hackathon_log`
            LEFT JOIN `react_hackathon_task`
              ON `react_hackathon_log`.`task_id` = `react_hackathon_task`.`id`
            WHERE 1
            ".(!empty($user_id) ? " AND `react_hackathon_log`.`user_id` = {$user_id}" : "")."
            ".(!empty($task_id) ? " AND `task_id` = {$task_id}" : "")."
            ORDER BY {$orderby}
            LIMIT {$offset}, {$limit}
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }

    public function last($limit = 10, $offset = 0)
    {
        $limit = intval($limit);
        $offset = intval($offset);

        $query = "
            SELECT `react_hackathon_log`.*,
                `react_hackathon_task`.`name` AS task_name
            FROM `react_hackathon_log`
            LEFT JOIN `react_hackathon_task`
                ON `react_hackathon_log`.`task_id` = `react_hackathon_task`.`id`
            WHERE 1
            ORDER BY `logged_at` DESC
            ".(!empty($limit) ? "LIMIT {$offset}, {$limit}": "")."
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }

    public function create()
    {
        $task_id = request::get('task_id', null);
        $duration = request::get('duration', null);
  
        $valid = true;
        if(!$task_id)
        {
            return ['error' => 'task_id must not be empty'];
        }

        if($duration === null)
        {
            return ['error' => 'duration must be set'];
        }

        if($valid)
        {
            $logged_at = date('Y-m-d H:i:s');
            $user_id = user::getUser()->id;

            $query = "
                INSERT INTO `react_hackathon_log`
                (`user_id`, `task_id`, `duration`, `logged_at`)
                VALUES
                (?, ?, ?, ?)
            ";
            db::query($query, [$user_id, $task_id, $duration, $logged_at]);

            $id = db::getLastInsertId();

            $query = "
                SELECT `react_hackathon_log`.*
                FROM `react_hackathon_log`
                WHERE `id` = ?
            ";
            $task = db::fetch($query, $id);

            return $task;
        }

    }


    public function user()
    {
        return user::getUser();
    }
}
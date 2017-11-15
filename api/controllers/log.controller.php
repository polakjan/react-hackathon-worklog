<?php

class logController
{
    public function index()
    {
        $query = "
            SELECT * 
            FROM `log`
            WHERE 1
            ORDER BY `logged_at` ASC
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }

    public function last($limit = 10, $offset = 0)
    {
        $limit = intval($limit);
        $offset = intval($offset);

        $query = "
            SELECT * 
            FROM `log`
            WHERE 1
            ORDER BY `logged_at` DESC
            LIMIT {$offset}, {$limit}
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

        if(!$duration)
        {
            return ['error' => 'duration must not be empty'];
        }

        if($valid)
        {
            $logged_at = date('Y-m-d H:i:s');

            $query = "
                INSERT INTO `log`
                (`task_id`, `duration`, `logged_at`)
                VALUES
                (?, ?, ?)
            ";
            db::query($query, [$task_id, $duration, $logged_at]);

            $id = db::getLastInsertId();

            $query = "
                SELECT `log`.*
                FROM `log`
                WHERE `id` = ?
            ";
            $task = db::fetch($query, $id);

            return $task;
        }

    }
}
<?php

class tasksController
{
    public function index()
    {
        $query = "
            SELECT * 
            FROM `react_hackathon_task`
            WHERE 1
            ORDER BY `name` ASC
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }

    public function create()
    {
        $name = request::get('name', null);

        $valid = true;
        if(!$name)
        {
            return [
                'error' => 'name must not be empty'
            ];
            $valid = false;
        }

        if($valid)
        {
            $query = "
                INSERT INTO `react_hackathon_task`
                (`name`)
                VALUES
                (?)
            ";
            db::query($query, [$name]);

            $id = db::getLastInsertId();

            $query = "
                SELECT `react_hackathon_task`.*
                FROM `react_hackathon_task`
                WHERE `id` = ?
            ";
            $task = db::fetch($query, $id);

            return $task;
        }

    }

    public function totals()
    {
        $query = "
            SELECT `react_hackathon_task`.*, SUM(`react_hackathon_log`.`duration`) AS total
            FROM `react_hackathon_log`
            LEFT JOIN `react_hackathon_task`
                ON `react_hackathon_log`.`task_id` = `react_hackathon_task`.`id`
            WHERE 1
            GROUP BY `react_hackathon_log`.`task_id`
            ORDER BY `react_hackathon_task`.`name` ASC
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }
}
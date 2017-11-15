<?php

class tasksController
{
    public function index()
    {
        $query = "
            SELECT * 
            FROM `task`
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
                INSERT INTO `task`
                (`name`)
                VALUES
                (?)
            ";
            db::query($query, [$name]);

            $id = db::getLastInsertId();

            $query = "
                SELECT `task`.*
                FROM `task`
                WHERE `id` = ?
            ";
            $task = db::fetch($query, $id);

            return $task;
        }

    }

    public function totals()
    {
        $query = "
            SELECT `task`.*, SUM(`log`.`duration`) AS total
            FROM `log`
            LEFT JOIN `task`
                ON `log`.`task_id` = `task`.`id`
            WHERE 1
            GROUP BY `log`.`task_id`
            ORDER BY `task`.`name` ASC
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }
}
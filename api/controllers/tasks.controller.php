<?php

class tasksController
{
    public function index()
    {
        $limit = intval(request::get('limit', 0));
        $offset = intval(request::get('offset', 0));
        $order_by = request::get('order_by', 'name');
        $order_way = request::get('order_by', 'asc');
        $user_id = intval(request::get('user_id', null));

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
            case 'name':
                $orderby = "`name` ".$orderway;
                break;
        }

        $query = "
            SELECT * 
            FROM `react_hackathon_task`
            WHERE 1
            ".(!empty($user_id) ? " AND `user_id` = {$user_id}" : "")."
            ORDER BY {$orderby}
            ".(!empty($limit) ? "LIMIT {$offset}, {$limit}": "")."
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
            $user_id = user::getUser()->id;

            $query = "
                INSERT INTO `react_hackathon_task`
                (`name`, `user_id`)
                VALUES
                (?, ?)
            ";
            db::query($query, [$name, $user_id]);

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
        $limit = intval(request::get('limit', 0));
        $offset = intval(request::get('offset', 0));
        $order_by = request::get('order_by', 'name');
        $order_way = request::get('order_by', 'asc');
        $user_id = intval(request::get('user_id', null));

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
            case 'name':
                $orderby = "`react_hackathon_task`.`name` ".$orderway;
                break;
            case 'last':
                $orderby = "`totals`.`max_logged_at` ".$orderway;
                break;
        }

        $query = "
            SELECT `react_hackathon_task`.*, COALESCE(`totals`.total, 0) AS total
            FROM `react_hackathon_task`
            LEFT JOIN (
                SELECT `react_hackathon_log`.`task_id`, SUM(`react_hackathon_log`.`duration`) AS total, MAX(`react_hackathon_log`.`logged_at`) AS max_logged_at
                FROM `react_hackathon_log`
                GROUP BY `react_hackathon_log`.`task_id`
            ) totals
                ON `react_hackathon_task`.`id` = `totals`.`task_id`
            WHERE 1
            ".(!empty($user_id) ? " AND `react_hackathon_task`.`user_id` = {$user_id}" : "")."
            ORDER BY {$orderby}
            ".(!empty($limit) ? "LIMIT {$offset}, {$limit}": "")."
        ";
        $tasks = db::fetchAll($query);

        return $tasks;
    }
}
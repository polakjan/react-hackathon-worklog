<?php

class user
{
    public static $initialized = false;
    public static $user = null;

    public static function init()
    {
        if(!isset($_SESSION))
        {
            session_start();
        }

        if(!empty($_SESSION['user_id']) && !empty($_SESSION['user_hash']))
        {
            $user_id = $_SESSION['user_id'];
            $log_hash = $_SESSION['user_hash'];

            $query = "
                SELECT `react_hackathon_user`.*
                FROM `react_hackathon_user`
                WHERE `react_hackathon_user`.`id` = ?
            ";
            $user = db::fetch($query, [$user_id])->fetch();
            if($user && ($user->auth_hash != $log_hash || $user->expires_at < date('Y-m-d H:i:s')))
            {
                $user = null;
            }
        }

        if(empty($user))
        {
            $user = static::createUser();
        }

        static::$user = $user;

        static::$initialized = true;
    }

    public static function createUser()
    {
        $auth_hash = substr(sha1(microtime(true).'muhehe'), rand(0, 10), 8);
        $expires_at = date('Y-m-d H:i:s', time()+86400);
        $query = "
            INSERT INTO `react_hackathon_user`
            (`username`, `password`, `auth_hash`, `expires_at`)
            VALUES
            (?, ?, ?, ?)
        ";
        db::query($query, [null, null, $auth_hash, $expires_at]);

        $user_id = db::getLastInsertId();

        $query = "
            SELECT `react_hackathon_user`.*
            FROM `react_hackathon_user`
            WHERE `react_hackathon_user`.`id` = ?
        ";
        return db::fetch($query, [$user_id]);
    }

    public static function getUser()
    {
        static::$initialized OR static::init();

        return static::$user;
    }
}
<?php

class userController
{
    public function index()
    {
        return user::getUser();
    }

    public function display()
    {
        return user::getUser();
    }

    public function id()
    {
        return user::getUser()->id;
    }
}
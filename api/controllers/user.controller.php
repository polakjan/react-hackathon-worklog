<?php

class userController
{
    public function index()
    {
        return user::getUser();
    }
}
<?php

namespace controllers;

class Server {

    protected $module;

    public function __construct()
    {
    }

    public function index() {
    }

    public function lists($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("info" == $target) {
            return gd_info();
        }
    }

}
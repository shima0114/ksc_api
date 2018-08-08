<?php

namespace controllers;

require_once './module/FileModule.php';

class File {

    protected $module;

    public function __construct()
    {
        $this->module = new \FileModule();
    }

    public function index() {
    }

    public function upload($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("album" == $target) {
            return $json = $this->module->uploadAlbum();
        } else if ("staff" == $target) {
            return $json = $this->module->uploadStaff();
        } else if ("player" == $target) {
            return $json = $this->module->uploadPlayer();
        }
    }
}
<?php

namespace controllers;

require_once './module/AlbumModule.php';

class Album {

    protected $module;

    public function __construct()
    {
        $this->module = new \AlbumModule();
    }

    public function index() {
    }

    public function lists($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("detail" == $target) {
            return $this->getDetailList();
        } else if ("list" == $target) {
            return $this->getList();
        } else if ("year" == $target) {
            return $this->getYearList();
        } else if ("title" == $target) {
            return $json = $this->module->getTitleList();
        } else if ("info" == $target) {
            return gd_info();
        }
    }

    private function createGroup() {
        // Link（group）の登録
        $params = json_decode(file_get_contents('php://input'), true);
        //var_dump($params);
        return array("params" => $params,
                     "result" => "success");
    }

    private function createContents() {
        // Link（contents）の登録
        return array("result" => "Create team success");
    }

    private function getDetailList() {
        // link（group）の取得
        $rows = $this->module->getDetailList();
        return $rows;
    }

    private function getList() {
        // Link（表示用データ）の取得
        $json = $this->module->getList();
        return $json;
    }

    private function getYearList() {
        $json = $this->module->getYearList();
        return $json;
    }
}
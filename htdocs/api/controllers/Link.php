<?php

namespace controllers;

require_once './module/LinkModule.php';

class Link {

    protected $module;

    public function __construct()
    {
        $this->module = new \LinkModule();
    }

    public function index() {
    }

    public function lists($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("group" == $target) {
            return $this->getGroupList();
        } else if ("contents" == $target) {
            return $this->getContentsList();
        } else if ("all" == $target) {
            return $this->getAllList();
        }
    }

    public function createGroup() {
        // パラメータのGet
        $_params = $_GET["param"];
        // Link（group）の登録
        $params = json_decode(file_get_contents('php://input'), true);
        //var_dump($params);
        return array("params" => $params,
                     "result" => "success");
    }

    public function createContents() {
        // Link（contents）の登録
        return array("result" => "Create team success");
    }

    public function getGroupList() {
        // link（group）の取得
        $rows = $this->module->getGroupList();
        return $rows;
    }

    public function getContentsList() {
        // Link（contents）の取得
        return array("result" => "Create team success");
    }

    private function getAllList() {
        // Link（表示用データ）の取得
        $json = $this->module->getAllList();
        return $json;
    }
}
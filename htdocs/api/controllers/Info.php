<?php

namespace controllers;

require_once './module/InfoModule.php';

class Info {

    protected $module;

    public function __construct()
    {
        $this->module = new \InfoModule();
    }

    public function index() {
    }

    public function get($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("new" == $target) {
            return $this->module->getNewInformation();
        } else if ("list" == $target) {
            return $this->getInformationList();
        }
    }

    public function createGroup() {
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

    public function getNewInformation() {
        // link（group）の取得
        $rows = $this->module->getGroupList();
        return $rows;
    }

    private function getInformationList() {
        // Link（表示用データ）の取得
        $json = $this->module->getInfoList();
        return $json;
    }
}
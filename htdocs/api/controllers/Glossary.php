<?php

namespace controllers;

require_once './module/GlossaryModule.php';

class Glossary {

    protected $module;

    public function __construct()
    {
        $this->module = new \GlossaryModule();
    }

    public function index() {
    }

    public function lists($target) {
        if (empty($target)) {
            return "Invalid api call!!";
        } else if ("line" == $target) {
            return $this->module->getGroupList();
        } else if ("all" == $target) {
            return $this->module->getAllList();
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

    public function getMemberList() {
        // Link（contents）の取得
        return array("result" => "Create team success");
    }
}
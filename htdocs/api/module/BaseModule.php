<?php

require_once './config/ServerConfig.php';

class BaseModule
{
    private static $connInfo;
    private $serverConfig;
    protected $db;
    protected $name;

    public function __construct()
    {
        $this->setConnectionInfo();
        $this->initDb();
    }

    public function initDb()
    {
        $dsn = sprintf(
        'mysql:host=%s;dbname=%s;port=3306;',
        self::$connInfo['host'],
        self::$connInfo['dbname']
    );
        $this->db = new PDO($dsn, self::$connInfo['dbuser'], self::$connInfo['password']);
    }

    private function setConnectionInfo()
    {
        $this->serverConfig = new ServerConfig();
        self::$connInfo = $this->serverConfig->connInfo;
    }

    public function prepare($sql) {
        return $this->db->prepare($sql);
    }

    public function executeQueryALL($sql) {
        return $this->db->query($sql)->fetchAll();
    }

    public function executeQueryStmt($sql) {
        return $this->db->query($sql);
    }

    public function outputLog($array) {
        error_log(print_r($array,true),"0");
    }

    public function lastInsertId($name) {
        return $this->db->lastInsertId($name);
    }
}
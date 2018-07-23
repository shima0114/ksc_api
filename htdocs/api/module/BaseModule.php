<?php

class BaseModule
{
    private static $connInfo;
    protected $db;
    protected $name;

    public function __construct()
    {
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

    public static function setConnectionInfo($connInfo)
    {
        self::$connInfo = $connInfo;
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
}
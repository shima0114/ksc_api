<?php

require_once 'BaseModule.php';

class InfoModule extends BaseModule
{
    protected $dba;

    public function __construct()
    {
        $this->dba = new BaseModule();
    }

    public function getNewInformation() {
        $sql = 'SELECT detail FROM information ';
        $sql = $sql.' WHERE start_at < now() and end_of > now() ';
        $sql = $sql.' order by start_at desc, end_of asc limit 1';
        $rows = $this->dba->executeQueryALL($sql);
        return $rows;
    }

    public function getInfoList() {
        $sql = 'SELECT id, start_at, end_of, detail ';
        $sql = $sql.'FROM information ';
        $sql = $sql.'ORDER BY start_at desc, end_of asc';
        $stmt =
        $stmt = $this->dba->executeQueryStmt($sql);
        $ret=[];
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $ret["info"][]=Array("id"=>$row['id'],
                "start_at" => $row['start_at'],
                "end_of" => $row['end_of'],
                "detail"=> $row['detail']);
        }
        return $ret;
    }
}
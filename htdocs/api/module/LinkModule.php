<?php

require_once 'BaseModule.php';

class LinkModule extends BaseModule
{
    protected $dba;

    public function __construct()
    {
        $this->dba = new BaseModule();
    }

    public function getGroupList() {
        $sql = 'SELECT * FROM link_group order by id';
        $rows = $this->dba->executeQueryALL($sql);
        return $rows;
    }

    public function getAllList() {
        $sql = 'SELECT g.code group_code, g.title, c.id, c.name, c.url, c.comment ';
        $sql = $sql.'FROM link_group g left outer join link_content c on g.code = c.group_code ';
        $sql = $sql.'ORDER BY g.list_order, g.updated_at ';
        $stmt = $this->dba->executeQueryStmt($sql);
        $ret=[];
        $group=[];
        $prevTitle="";
        $prevCode="";
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($prevTitle != $row["title"]) {
                if (!empty($prevCode)) {
                    $ret["links"][]=Array("title"=>$prevTitle, "group_code" => $prevCode, "contents"=> $group);
                }
                $group=[];
            }
            $group[]=Array("id" => $row["id"], "name" => $row["name"], "url" => $row["url"], "comment" => $row["comment"]);
            $prevTitle = $row["title"];
            $prevCode = $row["group_code"];
        }
        $ret["links"][]=Array("title"=>$prevTitle, "id" => $prevCode, "contents"=> $group);
        return $ret;
    }
}
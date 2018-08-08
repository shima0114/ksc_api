<?php

require_once 'BaseModule.php';

class GlossaryModule extends BaseModule
{
    protected $dba;

    public function __construct()
    {
        $this->dba = new BaseModule();
    }

    public function getGroupList() {
        $sql = 'SELECT * FROM glossary_line order by code';
        $rows = $this->dba->executeQueryALL($sql);
        return $rows;
    }

    public function getAllList() {
        $sql = 'SELECT g.code, g.name, c.id, c.title, c.content ';
        $sql = $sql.'FROM glossary_line g left outer join glossary_contents c on g.code = c.code ';
        $sql = $sql.'ORDER BY g.code, c.updated_at ';
        $stmt = $this->dba->executeQueryStmt($sql);
        $ret=[];
        $words=[];
        $prevName="";
        $prevCode="";
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($prevCode != $row["code"]) {
                if (!empty($prevCode)) {
                    $ret["glossary"][]=Array("name"=>$prevName, "code" => $prevCode, "words"=> $words);
                }
                $words=[];
            }
            $words[]=Array("id" => $row["id"], "title" => $row["title"], "contents" => $row["content"]);
            $prevName = $row["name"];
            $prevCode = $row["code"];
        }
        $ret["glossary"][]=Array("name"=>$prevName, "code" => $prevCode, "words"=> $words);
        return $ret;
    }
}
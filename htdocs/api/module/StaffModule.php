<?php

require_once 'BaseModule.php';

class StaffModule extends BaseModule
{
    protected $dba;

    public function __construct()
    {
        $this->dba = new BaseModule();
    }

    public function getGroupList() {
        $sql = 'SELECT * FROM image_group order by id';
        $rows = $this->dba->executeQueryALL($sql);
        return $rows;
    }

    public function getStaffList() {
        $sql = 'SELECT c.title, c.file_path, c.comment ';
        $sql = $sql.'FROM image_group g left outer join image_content c on g.code = c.group_code ';
        $sql = $sql.'WHERE g.code = ? ';
        $sql = $sql.'ORDER BY c.updated_at';
        $stmt = $this->dba->prepare($sql);

        $code='staff';
        $stmt->bindParam(1, $code);
        $stmt->execute();
        $ret=[];
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $ret["staff"][]=Array("imgFile"=>$row['file_path'], "title" => $row['title'], "comment"=> $row['comment']);
        }
        return $ret;
    }
}
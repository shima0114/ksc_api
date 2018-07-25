<?php

require_once 'BaseModule.php';

class AlbumModule extends BaseModule
{
    protected $dba;

    public function __construct()
    {
        $this->dba = new BaseModule();
    }

    public function getDetailList() {
        $params = $_GET;
        $sql = 'SELECT g.name, c.title, c.file_path, c.comment ';
        $sql = $sql.'FROM image_group g left outer join image_content c on g.code = c.group_code ';
        $sql = $sql.'WHERE g.code = ? ';
        $sql = $sql.'ORDER BY c.updated_at';
        $stmt = $this->dba->prepare($sql);

        $code='album_' . $params["id"];
        $stmt->bindParam(1, $code);
        $stmt->execute();
        $ret=[];
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $ret["album"][]=Array("imgFile"=>$row['file_path'], "title" => $row['title'], "comment"=> $row['comment']);
            $ret["name"]=$row["name"];
        }
        return $ret;
    }

    public function getList() {
        $sql = 'SELECT id, year, title ';
        $sql = $sql.'FROM album ';
        $sql = $sql.'ORDER BY year desc, id desc';
        $stmt = $this->dba->prepare($sql);
        $stmt->execute();
        $prevYear="";
        $lists=[];
        $ret=[];
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($prevYear != $row["year"]) {
                if (!empty($prevYear)) {
                    $ret["album"][]=Array("year"=>$prevYear, "lists"=> $lists);
                }
                $lists=[];
            }
            $lists[]=Array("id" => $row["id"], "title" => $row["title"]);
            $prevYear = $row["year"];
        }
        $ret["album"][]=Array("year"=>$prevYear, "lists"=> $lists);
        return $ret;
    }

    public function getYearList() {
        $sql = 'SELECT year ';
        $sql = $sql.'FROM album ';
        $sql = $sql.'GROUP BY year ';
        $sql = $sql.'ORDER BY year desc ';
        $stmt = $this->dba->prepare($sql);
        $stmt->execute();
        $yearGroup=[];
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $yearGroup[]=Array("year" => $row["year"]);
        }
        return Array("album" => $yearGroup);
    }

    public function getTitleList() {
        $params = $_GET;
        $sql = 'SELECT a.id, a.title ';
        $sql = $sql.'FROM album a ';
        $sql = $sql.'WHERE a.year = ? ';
        $sql = $sql.'ORDER BY a.id';
        $stmt = $this->dba->prepare($sql);

        $year = $params["year"];
        $stmt->bindParam(1, $year);
        $stmt->execute();
        $album=[];
        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            $album[]=Array("id" => $row["id"], "title" => $row["title"]);
        }
        return Array("album" => $album);
    }
}
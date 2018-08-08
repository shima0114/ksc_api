<?php

require_once 'BaseModule.php';
require_once './config/FtpConfig.php';

class FileModule extends BaseModule
{
    protected $dba;
    protected $ftpConfig;

    public function __construct()
    {
        $this->dba = new BaseModule();
        $this->ftpConfig = new FtpConfig();
    }

    public function uploadAlbum() {
        $method = "uploadLocalServer";
        // POSTパラメータ取得
        $params = $_POST;
        $ret=[];

        // ファイルオブジェクトの分ループ（複数アップロード対応）
        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            // ファイル拡張子取得
            $file_ext = pathinfo($_FILES['file']['name'][$i]);
            //ファイル名をランダム文字列に変更
            $file_name = $this->makeRandFileName().".".$file_ext[extension];
            //保存先のパス
            $file_path = "/images/album/".$file_name;
            //UPLOAD実施
            $result = $this->$method($file_path, $_FILES['file']['tmp_name']);

            //UPLOADに成功したらファイル情報をDBに登録
            if ($result) {
                $groupCode="";
                // new or add
                if ("new" == $params["proc"]) {
                    $this->outputLog(Array("message" => "in proc new.", "params" => $params));
                    // 新規の場合は先にアルバム情報を作成
                    $stmt = $this->dba->prepare("INSERT INTO album (year, title) values (?, ?)");
                    $stmt->bindParam(1, $params["year"]);
                    $stmt->bindParam(2, $params['group-title']);
                    $stmt->execute();
                    $this->outputLog(Array("message" => "create album record."));
                    $insId = $this->dba->lastInsertId("id");
                    $this->outputLog(Array("message" => "get last insert id.", "id" => $insId));
                    // image_group作成
                    $groupCode = 'album_'.$insId;
                    $stmt = $this->dba->prepare("INSERT INTO image_group (code, name) values (?, ?)");
                    $stmt->bindParam(1, $groupCode);
                    $stmt->bindParam(2, $params['group-title']);
                    $stmt->execute();
                    $this->outputLog(Array("message" => "create group record."));
                } else {
                    $groupCode = $params["id"];
                }
                $this->outputLog(Array("message" => "insert image start.", "group code" => $groupCode));
                // image_content
                $stmt = $this->dba->prepare("INSERT INTO image_content (group_code, title, file_path) values (?, ?, ?)");
                $stmt->bindParam(1, $groupCode);
                $stmt->bindParam(2, $params['title']);
                $stmt->bindParam(3, $file_path);
                $stmt->execute();
                $ret[] = Array("result" => "Success.", "file" => $_FILES['file']['name'][$i]);
            } else {
                $ret[] = Array("result" => "Upload error.", "file" => $_FILES['file']['name'][$i]);
            }
        }
        return $ret;
    }

    public function uploadStaff() {
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

    public function uploadPlayer() {

    }

    private function uploadLocalServer($serverPath, $file) {
        try {
            $file_path = "/Users/koshimab/IdeaProjects/Funabashi_ksc/htdocs".$serverPath;
            $result = rename($file, $file_path);
            if (!$result) {
                $this->outputLog(Array("error" => "move_upload_file error.", "result" => $result));
                return false;
            }
            $this->outputLog($file_path);
        } catch (Exception $e) {
            $this->outputLog($e);
            return false;
        }
        return true;
    }

    private function uploadHtmlServer($serverPath, $file) {
        // ftp to html server.
        $serverInfo = $this->ftpConfig->FTP_SERVER_INFO;
        $ftpValue = array(
            'ftp_server' => $serverInfo["FTP_ADDRESS"],
            'ftp_user_name' => $serverInfo["FTP_USER"],
            'ftp_user_pass' => $serverInfo["FTP_PASS"]
        );
        $remote_file = $serverPath;
        $upload_file = $file;

        $connection = ftp_connect($ftpValue['ftp_server']);

        $login_result = ftp_login(
            $connection,
            $ftpValue['ftp_user_name'],
            $ftpValue['ftp_user_pass']
        );

        ftp_pasv($connection, true);
        $ftpResult = ftp_put($connection, $remote_file, $upload_file, FTP_BINARY, false);
        $retCode = true;
        if (!$ftpResult) {
            //throw new InternalErrorException('Something went wrong.');
            $retCode = false;
        }

        ftp_close($connection);
        return $retCode;
    }

    function makeRandFileName() {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < 15; $i++) {
            $r_str .= $str[rand(0, count($str) - 1)];
        }
        return uniqid($r_str);
    }

    /*
    第一引数は、<input type="file" name="img" />でアップした$_FILES["img"]などです。

    第二引数は、リサイズ後の横幅です。

    第三引数は、リサイズ後の画像を保存するディレクトリのパスです。（最後に/は不要）

    第三引数を指定しない場合は、この関数を使用したPHPファイルと同じディレクトリに保存されます。
    */
    function compressImageFile($image, $file_name, $new_width, $dir){
        list($width,$height,$type) = getimagesize($image);
        $new_height = round($height*$new_width/$width);
        $emp_img = imagecreatetruecolor($new_width,$new_height);
        switch($type){
            case IMAGETYPE_JPEG:
                $new_image = imagecreatefromjpeg($image);
                break;
            case IMAGETYPE_GIF:
                $new_image = imagecreatefromgif($image);
                break;
            case IMAGETYPE_PNG:
                imagealphablending($emp_img, false);
                imagesavealpha($emp_img, true);
                $new_image = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($emp_img, $new_image,0,0,0,0,$new_width,$new_height,$width,$height);
        switch($type){
            case IMAGETYPE_JPEG:
                imagejpeg($emp_img,$dir."/".$file_name);
                break;
            case IMAGETYPE_GIF:
                $bgcolor = imagecolorallocatealpha($new_image,0,0,0,127);
                imagefill($emp_img, 0, 0, $bgcolor);
                imagecolortransparent($emp_img,$bgcolor);
                imagegif($emp_img,$dir."/".$file_name);
                break;
            case IMAGETYPE_PNG:
                imagepng($emp_img,$dir."/".$file_name);
                break;
        }
        imagedestroy($emp_img);
        imagedestroy($new_image);
    }
}
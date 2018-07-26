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
        $params = $_POST;
        // ファイル拡張子取得
        $file_ext = pathinfo($_FILES['file']['name']);
        //ファイル名を日付に変更
        $time = date("YmdHis");
        $file_name = $time.".".$file_ext[extension];
        //保存先のパス
        //index.php file_api.phpと同層にupload_fileディレクトリが存在
        $file_path = "/images/album/".$file_name;
$this->outputLog(Array("on_debug" => $params));
return;
        $tmp_file = $_FILES['file']['tmp_name'];
        $result = $this->uploadHtmlServer($file_path, $tmp_file);
        //FTPに成功したらファイル情報をDBに登録
        $ret=[];
        if ($result) {
            $stmt = $this->db->prepare("INSERT INTO image_content (group_code, title, file_path) values (?, ?, ?)");
            $stmt->bindPartam(1, $params["id"]);
            $stmt->bindPartam(2, $params['title']);
            $stmt->bindPartam(3, $file_path);
            $stmt->execute();
            $ret = Array("result" => "Success.");
        } else {
            $ret = Array("result" => "FTP error.");
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

        if (!$ftpResult) {
            //throw new InternalErrorException('Something went wrong.');
            return false;
            ftp_close($connection);
        }

        ftp_close($connection);
        return true;
    }
}
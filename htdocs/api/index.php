<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');


require_once './module/BaseModule.php';

if (empty($_SERVER['PATH_INFO'])) {
    header('./index.html');
}
// DB接続情報設定
/*
$connInfo = array(
    'host'     => 'mysql1.php.xdomain.ne.jp',
    'dbname'   => 'funabashiksc_db',
    'dbuser'   => 'funabashiksc_dbu',
    'password' => 'vauevr7hD3K'
);
*/
$connInfo = array(
    'host'     => '127.0.0.1',
    'dbname'   => 'kscdb',
    'dbuser'   => 'kscdbuser',
    'password' => 'kscdbuser'
);
BaseModule::setConnectionInfo($connInfo);


// パス先頭のスラッシュは省く
$url = ltrim($_SERVER['PATH_INFO'], "/");
//スラッシュで区切られたurlを取得します
$parse = explode('/', $url);
$call = "";
$method = "index";
$target = "";
if (empty($parse)) {
    // 404 error
}
// controller
if (!empty($parse[0])) {
    $call=$parse[0];
}
// method
if (!empty($parse[1])) {
    $method=$parse[1];
}
// target
if (!empty($parse[2])) {
    $target=$parse[2];
}
//各Controllerをインクルードし、JSONを返します
if (file_exists('./controllers/'.$call.'.php')) {
    include('./controllers/'.$call.'.php');
    //$call名のcontrollerをインスタンス化します
    $className = "controllers\\".$call;
    //echo $className;
    $obj = new $className();
    //controllerのindexメソッドを呼びます
    $response = json_encode($obj->$method($target));
    //ヘッダーを指定してJSONを出力
    header("Content-Type: application/json; charset=utf-8");
    echo $response;
} else {

}

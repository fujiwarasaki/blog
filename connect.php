<?php 

function dbconnect($sql=''){
$host='mysql1.php.xdomain.ne.jp';
$dbname='suya_phpkiso';
$dsn="mysql:dbname=$dbname;host=$host;charset=utf8;";
$user='suya_phpkiso';
// $user='root';
$password='sankaku3';
// $password='';
$dbh=new PDO($dsn,$user,$password);
//PDOのエラーモードをonにする
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//構文チェックと実行を分離する 必須
$dbh->setAttribute(PDO::ERRMODE_EXCEPTION ,false);

return $dbh;
}
?>
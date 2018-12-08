<?php
//アップロードされている方をチェック
if(@is_uploaded_file($_FILES['up_file']['tmp_name'])){
  $fils = $_FILES['up_file'];
  $img='';
}elseif(@is_uploaded_file($_FILES['up_toko']['tmp_name'])){
	$fils = $_FILES['up_toko'];
	$img = 'img/';
}
// アップされていなければ$filsはnullなのでtrue判定されない
if(isset($fils)){
//ファイル形式が画像の場合のみなら  の条件を追加
$type = $file['type'];
$size = $file['size'];

if( ($type == "image/jpeg" || $type == "image/png" || $type == "image/gif") && $size < 5000000){
//ファイルサイズは5Mまで
if(move_uploaded_file($fils['tmp_name'],"./$img".$fils['name'])){
echo $fils['name'];
}else{echo "コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー）";}
}else{ echo "認められないファイルです";}
}else{ echo "ファイルが来てない";}
?>

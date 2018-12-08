<?php 

		session_start();

		//世界標準から+9時間になる
		date_default_timezone_set('Asia/Tokyo');
		// 送信されていたら
		if (!empty($_POST['toko']) && !empty($_POST['Author'])) { //カラじゃなければtrue

		// 投稿者ID(Author)の取得する方法 login.phpセッション代入取得

		// DBに繋げる
		require_once("counnect.php");
		$dbh=dbcounnect();

		// php 現在時刻の習得
		$post_date = date("Y-m-d H:i:s"); //mysqlはハイフン区切り

		// INSERTするSQL文 → 実行
		$sql="INSERT INTO posts(post,post_date,Author) VALUES(?,?,?)";
    $stmt = $dbh->prepare($sql);
		$stmt->bindValue(1, $_POST['toko'], PDO::PARAM_STR);
		$stmt->bindValue(2, $post_date, PDO::PARAM_STR);
		$stmt->bindValue(3, $_POST['Author'], PDO::PARAM_STR);
		$stmt->execute();
		} 
		//記事投稿終了
 
 ?>






 <!DOCTYPE html>
 <html lang="ja">
 <head>
 	<meta charset="UTF-8">
 	<title>投稿文作成</title>
 </head>
 <body>



<?php 
if (empty($_SESSION['code'])){
echo "ログインしてください";
exit; //ここで処理を止める
}
 ?>

 	<form method="post" id="imgfrom" action="" enctype="multipart/form-data">
 		<label>投稿画像アップロード</label>
 		<p>ファイル:<input type="file" name="up_toko"></p>
 		<input type="button" name="imageup" value="upload" >
 	</form>

 	<hr>記事本文
 	<img id="img_file" alt="アップした画像" style="display: none;">

 	<form action="" method="post">
 		<textarea name="toko" id="toko" cols="30" rows="10"></textarea>
 		  <input type="hidden" name="gazo" id="gazo" value="">
 		  <input type="hidden" value="<?=$_SESSION['code']?>" name="Author">
 		<p><input type="submit" value="公開"></p>
 	</form>

 	<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
 	<script>
 		$('#imageup').click(function(){
	 //画像送信して映すまでのajax通信を書く
	 var updir = '/php/7%20login-blog' ;
	 //ファイルを送る場合はこうかく↓
	 var formdata = new FormData($('#imgform').get(0));
	 $.ajax({
    url: "http://localhost" + updir + "/image_up.php",
		type: "post",        //method
		processData: false,  //文字列に変換しない
    contentType: false,  // デフォルトではない
    dataType: "html",    // 送信データの種類 ,html ,json とか
    data:formdata 
	})
	.done(function (response) {
  	// 通信が成功した場合 php からの戻り値がreoponseにはいる
  	var gazoName = updir + "/img/" + response ;
		$("#image_file").attr('src',gazoName).show();
		$('#gazo').val(response);  //画像のhiddenフィールドに入れる
		$('textarea').text("<img src='"+gazoName+"'>");
	})
	.fail(function (xhr,textStatus,errorThrown) {
      //通信が失敗した場合
      alert('error');
});
});

 	</script>

 </body>
 </html>
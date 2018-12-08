<?php   

    session_start();
    $sid = session_id();
	
	  //フォームの入力値が有ればを確認
	  if ( !empty($_POST['password'])	&& !empty($_POST['email']) ){

		require_once ("mojifilter.php"); 
		require_once("connect.php");   
		$dbh = dbconnect(); 

		$email = h($_POST['email']);
	  $password = h($_POST['password']);
		//counterが0に戻れば 
		$zerofrag = false; 

	  //emailでusersテーブルを検索
	  $sql="SELECT code, email, password , timestump, counter FROM users WHERE email = ?";
		$stmt = $dbh->prepare($sql); 
		$stmt->bindValue(1, $email , PDO::PARAM_STR);
		$stmt->execute();
			
		// ヒットした行数を数える
		$rowcount = $stmt->rowCount();

		if ($rowcount){
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// 1件あったら タイムスタンプと現時刻の差を調べる
		if($row['timestump']!==0 	&&  time() - $row['timestump'] > (60*30) ){
		//counterを0に戻す
		if($row['counter']!=0){
		//いまの値0でないなら				
		$sql="UPDATE users SET counter = 0 ,timestump=0 WHERE email = ? ";
		$stmt = $dbh->prepare($sql);
	  $stmt->bindValue(1, $email , PDO::PARAM_STR);;
		$res=$stmt->execute();
		$zerofrag = true ;		
	  }

	  // ここから パスワード照合
 		if( pv(1) ){
		//ユーザID
		$_SESSION['code'] = $row['code'];
		exit; // 認証成功ならSTOP
		} else{  // 追加 
		if( !$zerofrag && $row['counter'] >= 3){
		updateTime(); //タイムスタンプを刻印する関数	   
	  }
	  }//追加ここまで

    //30分経っていないなら
    }else{				
	  // 失敗回数が < 3 
		if($row['counter'] < 3){
	  // ここから パスワード照合
		if( pv(2) ) exit;
		//ユーザID
		$_SESSION['code'] = $row['code'];
		exit; // 認証成功ならSTOP
		
		}else{
		echo "只今ログインできません";
		// 失敗回数が >=3
		updateTime();//タイムスタンプを刻印する関数
		}
    } //30分経っていないならEND

		}else{
		// 登録されていない 
		echo "メールアドレスかパスワードが違います";
		}  // else end	
    }

    //パスワードを 入力値とDBの値で照合する関数
    function pv($t){
	  // 外側の変数を関数内で使うための宣言
	  global $password; 
	  global $row; 
	  global $dbh; 
	  global $email; 
	  global $zerofrag;
	  if(password_verify ($password , $row['password'])){
	 	//echo "認証成功";
		$sql="UPDATE users SET counter = 0 WHERE email = ?";
	 	$stmt = $dbh->prepare($sql);
	 	$stmt->bindValue(1, $email , PDO::PARAM_STR);;
  	$stmt->execute();
  	// echo "認証成功";
  	//記事投稿へのリダイレクト
  	header('Location: ./dashboard.php');
  	return true;  	
	  }else{
	  //パスワード認証失敗
	  $addcount = $zerofrag ? 1 : ++$row['counter'] ;
	  //ゼロクリアなら 1 そうじゃなければ 加算代入
	  $sql="UPDATE users SET counter =". $addcount . " WHERE email = ?";
		$stmt = $dbh->prepare($sql);
		$stmt->bindValue(1, $email , PDO::PARAM_STR);
		$stmt->execute();
		echo "認証失敗" .$t ;
		var_dump( $addcount,$zerofrag);
	  return false;
	  }
    }

    function updateTime(){
    global $dbh ;
    global $email ;
    $sql="UPDATE users SET timestump = ". time() . " WHERE email = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->bindValue(1, $email , PDO::PARAM_STR);
    $stmt->execute();
    }				 
				 
?> 


<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>ログインするファイル</title>
</head>
<body>
	<h2>ユーザログイン</h2>

	<form action="" method="post">
		<input type="hidden" name="himitsu" value="<?=$sid?>">
		
		<p><label>メールアドレス</label>
		 <input type="text" name="email"></p>

		<p><label>パスワード</label>
		 <input type="password" name="password"></p>

		<input type="submit" value="送信">
	</form>
</body>
</html>
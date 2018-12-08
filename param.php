<?php //  param.php

require_once("connect.php");   //DBに接続する
require_once ("mojifilter.php"); 
$dbh = dbconnect(); 

if ( empty($_POST['password'])	&& !empty($_POST['email']) ){
	  $sql="SELECT email FROM users WHERE email = ?";
		$stmt = $dbh->prepare($sql); 
		$stmt->bindValue(1, $_POST['email'], PDO::PARAM_STR);
		$stmt->execute();
		$rowcount = $stmt->rowCount(); // 行数を数える
		if ($rowcount) // 1件あったら
		echo "すでに登録されています<a href='login.php'>ログインへ</a>";
	}elseif( !empty($_POST['password'])	&& !empty($_POST['email'])){
	// 両方あったら
	
	$sql="SELECT email FROM users WHERE email = ?";
	$stmt = $dbh->prepare($sql); 
	$stmt->bindValue(1, $_POST['email'], PDO::PARAM_STR);
	$stmt->execute();
		$rowcount = $stmt->rowCount(); // 行数を数える
		if ($rowcount) {		
			echo "すでに登録されています<a href='login.php'>ログインへ</a>";
		}else{
		// 登録されていないので usersに挿入する
			$email = h($_POST['email']);
			$password = h($_POST['password']);
			$password =	password_hash($password, PASSWORD_DEFAULT);

			$sql = "INSERT INTO users( email , password)	VALUES ( ? , ? )";
			$stmt = $dbh->prepare($sql); 
		
			$stmt->bindValue(1, $email, PDO::PARAM_STR);
				$stmt->bindValue(2, $password, PDO::PARAM_STR);
				$res = $stmt->execute(); 
				 echo $res ? "正常に登録しました" : "登録失敗しました";
				 exit; 
		}  //1件あったら の終わり
	}   // else end	 


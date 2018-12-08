<?php 
				require_once("connect.php");
				$dbh=dbconnect();
				
				//このファイルのパスを取得
				$dir=$_SERVER['REQUEST_URI']; 
				$path=pathinfo($dir)['dirname'];
				//var_dump($path);

				$sql="SELECT post_id,post,post_date,Author,gazo 
				      FROM posts
				      LEFT JOIN users
				      ON Author = code"; //テーブルを結合
				
				$stmt=$dbh->prepare($sql);
				$stmt->execute();

				if (isset($stmt)) 
				foreach ($stmt as $row) {

				// タグをいっぱい書くからphpをここで終わらす
?>


<link rel="stylesheet" type="text/css" href="style.css">

<article>
	<div class="author">
		<img src="<?=$path."/".$row['gazo']?>" alt="投稿者">
	</div>

	<p> <?=$row['post']?> </p>

</article>


 <?php  }  ?>
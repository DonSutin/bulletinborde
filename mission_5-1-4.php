<?php

//静的プロパティ、静的メソッド	::
//インスタンスプロパティ、インスタンスメソッド	->

//まずはデータベースへの接続を行う。
	$dsn = 'databasename';//dsn=data sorce name
	$user = 'username';
	$password = 'password';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));//インスタンス化
	//PDO=PHP Data Objects:データベース抽象化レイヤの一つ、pdoクラスというものが存在し、定数が入ってる。どのデータベースにも使える




	//！は「否定」　emptyは変数が存在しない場合にtrueを返す
if (!empty ($_POST['name'])) {
	echo $_POST['name'] . "さんですね<br>";
}


if (!empty ($_POST['comment'])) {
	if($_POST['comment'] == "完成！") {
		echo "おめでとう！";
	} elseif (!empty ($_POST['comment'])) {
		echo $_POST['comment'] . "を受け付けました。<br>";
	}
}






//新規投稿時入力フォーム
if (!empty($_POST['name']) && !empty ($_POST['comment']) && empty($_POST['edi'])) {

	if ($_POST['pass1'] == "かわ"){

			//作成したテーブルに、insertを行ってデータを入力する。
		$sql = $pdo -> prepare("INSERT INTO bulletin (name, comment, date) VALUES (:name, :comment, :date)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
		$name = $_POST['name'];
		$comment =$_POST['comment'];
		$date =date("Y/m/d h:i:m");
		$sql -> execute();

	}
}






//編集時入力フォーム
if(!empty($_POST['name']) && !empty ($_POST['comment']) && !empty($_POST['edi'])){//編集番号コメントフォームに入っているとき

	if ($_POST['pass1'] == "かわ"){

	//入力したデータをupdateによって編集する。
	//bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
	$id = $_POST['edi']; //変更する投稿番号
	$name = $_POST['name'];
	$comment = $_POST['comment'];
	$date = date("Y/m/d h:i:m");
	$sql = 'update bulletin set name=:name,comment=:comment,date=:date where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':date', $date, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

	}
}





//削除フォーム

	//入力したデータをdeleteによって削除する。
if (!empty ($_POST['deleteNo'])){

	if ($_POST['pass2'] == "かわ"){

		$id = $_POST['deleteNo'];
		$sql = 'delete from bulletin where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

	}
}




 //編集フォーム
if(!empty($_POST['editNo'])){

	if ($_POST['pass3'] == "かわ"){

		$id = $_POST['editNo'];
		$stmt = $pdo->prepare('SELECT id, name, comment, date FROM bulletin WHERE id=:id');
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetchAll();

		$toukou=$result[0];

		$editname=$toukou[1];
		$editcomment=$toukou[2];

	}
}
?>



<html>
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
	<meta charset="utf-8">
</head>

<body>

<h2>passward「かわ」</h2><br>

<h2>入力フォーム</h2>
<form method="POST" action="mission_5-1-4.php">
	お名前　　：<input type="text" name="name" value="<?php if(!empty($editname)){ echo $editname; }?>"><br>
	コメント　：<input type="text" name="comment" value="<?php if(!empty($editcomment)){ echo $editcomment; }?>"><br>
	<input type="hidden" name="edi" value="<?php if(!empty($_POST['editNo'])){ echo $_POST['editNo']; }?>">
	パスワード：<input type="text" name="pass1" ><br>
	<input type="submit" value="送信">
</form><br>
 
<h2>削除番号指定用フォーム</h2>
<form method="POST" action="mission_5-1-4.php">
	削除対象番号：<input type="text" name="deleteNo"><br>
	パスワード　：<input type="text" name="pass2" ><br>
<input type="submit" name="delete" value="削除">
</form><br>

<h2>編集フォーム</h2>
<form method="POST" action="mission_5-1-4.php">
	編集対象番号：<input type="text" name="editNo"><br>
	パスワード　：<input type="text" name="pass3" ><br>
	<input type="submit" name="edit" value="編集">
</form>

</body>

<?php
	//入力したデータをselectによって表示する
	//$rowの添字（[ ]内）は4-2でどんな名前のカラムを設定したかで変える必要がある。
	$sql = 'SELECT * FROM bulletin';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].' ';
		echo $row['date'].'<br>';
		echo "<hr>";
	}

?>

</html>

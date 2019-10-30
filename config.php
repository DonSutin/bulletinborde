<?php
			$dsn = 'mysql:dbname=tb210403db;host=localhost';
			$user = 'tb-210403';
			$password = 'xShUdhZpyS';
			$pdo = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
			//例外処理を投げる（スロー）ようにする




	//テーブルの作成





		$sql = "CREATE TABLE IF NOT EXISTS member" //テーブル名に予約語、ハイフンを使用できない
		." ("
		. "id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,"
		. "account VARCHAR(50) NOT NULL,"
		. "mail VARCHAR(50) NOT NULL,"
		. "password VARCHAR(128) NOT NULL,"
		. "flag TINYINT(1) NOT NULL DEFAULT 1"
		.")ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
		$stmt = $pdo->query($sql);




//dateのカラム追加
	//$sql = "alter table tbtest add date datetime";
	//$stmt = $pdo->query($sql);

//テーブル一覧を表示するコマンドを使って作成が出来たか確認する。
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";


//テーブルの中身を確認するコマンドを使って、意図した内容のテーブルが作成されているか確認する

	$sql ='SHOW CREATE TABLE pre_member';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";




	$sql ='SHOW CREATE TABLE member';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";



$sql ='SHOW CREATE TABLE preserve';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";



//テーブル確認
try {
	$sql = 'SELECT * FROM member';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['account'].',';
		echo $row['mail'].',';
		echo $row['password'].',';
		echo $row['flag'].'<br>';
		echo "<hr>";
	}
} catch (\Exception $e) {
	print $e->getMessage();
}


//仮登録テーブタ表示
try {
	$sql = 'SELECT * FROM pre_member';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['urltoken'].',';
		echo $row['mail'].',';
		echo $row['date'].',';
		echo $row['flag'].'<br>';
		echo "<hr>";
	}
} catch (\Exception $e) {
	print $e->getMessage();
}
?>

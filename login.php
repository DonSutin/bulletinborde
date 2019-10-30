<html lang="ja">
 <head>
   <meta charset="utf-8">
   <title>Login</title>
 </head>
 <body>
<?php

session_start();


//データベース接続
require_once('db.php');

//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');

//エラーメッセージの初期化
$errors = array();


//ポストが送られてきたかどうか
if(empty($_POST)) {

	//指定のアドレスへリダイレクトする
	header("Location: signup.php");

	//強制的に処理を終了する。function内、クラス内、呼び出したファイル内のみ終了したいときはreturnを使う
	exit();
}else{

	//POSTのvalidate
	if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
		$errors['mail_validate'] = '入力された値が不正です。';
	}


	//DB内でPOSTされたメールアドレスを検索
	try {
		$stmt = $pdo->prepare('select * from member where mail = ?');
		$stmt->execute([$_POST['mail']]);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

	} catch (\Exception $e) {
		$errors['strange']='原因不明のエラーが発生しました。';
		//echo $e->getMessage() . PHP_EOL;
	}


	//emailがDB内に存在しているか確認
	if (!isset($row['mail'])) {
		$errors['not_exist'] = 'メールアドレス又はパスワードが間違っています。';
	}


	//パスワード確認後sessionにメールアドレスを渡す
	if (password_verify($_POST['password'], $row['password'])) {


		//session_id（クッキー）を新しく生成し、置き換えるこれによってセッションIDが読み取られた場合、アクセス時に第三者によってセッションIDが書き換えられた場合のセキュリティ対策になる
		session_regenerate_id(true); 

		$_SESSION['MAIL'] = $row['mail'];
	} else {
		$errors['not_much'] = 'メールアドレス、又はパスワードが間違っています。';
	}
}
?>

<?php if (count($errors) === 0): ?>
 
<p>ログインしました</p>
 
<?php elseif(count($errors) > 0): ?>
 
<?php
foreach($errors as $value){
	echo "<p>".$value."</p>";
}
?>
<input type="button" value="戻る" onClick="history.back()">
<?php endif; ?>
</body>
</html>

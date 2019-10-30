<html lang="ja">
 <head>
   <meta charset="utf-8">
   <title>sendemail</title>
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
	//POSTされたデータを変数に入れる
	$mail = isset($_POST['mail']) ? $_POST['mail'] : NULL;


	//メール入力判定
	if ($mail == ''){
		$errors['mail'] = "メールが入力されていません。";
	}else{
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $mail)){
			$errors['mail_check'] = "メールアドレスの形式が正しくありません。";
		}


		//POSTのValidate。
		//$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
		//var_dump($email);
		//if (!$email) {
		//	echo '入力された値が不正です。';
		//	return false;
		//}



	//ここで本登録用のmemberテーブルにすでに登録されているmailかどうかをチェックする。

		//DB内でPOSTされたメールアドレスを検索
		try {
			$stmt = $pdo->prepare('select * from member where mail = ?');
			$stmt->execute([$mail]);
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
		} catch (\Exception $e) {
			echo $e->getMessage() . PHP_EOL;
		}


		//emailがDB内に存在しているか確認
		if (isset($row['mail'])) {
			$errors['member_check'] = "このメールアドレスはすでに利用されております。";
		}
	}
}




//エラー配列0の時
if (count($errors) === 0){
	

	//rand関数はランダムに値を返す。uniqid関数は一意の値を返す。
	$urltoken = password_hash(uniqid(rand(),1), PASSWORD_BCRYPT);
	$url = "https://tb-210403.tech-base.net/registration1.php"."?urltoken=".$urltoken;
	



	//ここで仮登録データベースに登録する
	try{
	
		
		$statement = $pdo->prepare("INSERT INTO pre_member (urltoken,mail,date) VALUES (:urltoken,:mail,now() )");
		
		//プレースホルダへ実際の値を設定する
		$statement->bindValue(':urltoken', $urltoken, PDO::PARAM_STR);
		$statement->bindValue(':mail', $mail, PDO::PARAM_STR);
		$statement->execute();
			
		//データベース接続切断
		$pdo = null;	
		
	}catch (PDOException $e){
		print('Error:'.$e->getMessage());


		//強制的に処理を終了する。function内、クラス内、呼び出したファイル内のみ終了したいときはreturnを使う
		die();
	}
	
	//メールの宛先
	$mailTo = $mail;





	//以下phpmailer


	require 'src/Exception.php';
	require 'src/PHPMailer.php';
	require 'src/SMTP.php';
	require 'setting.php';




	// PHPMailerのインスタンス生成
	$mail = new PHPMailer\PHPMailer\PHPMailer();

	$mail->isSMTP(); // SMTPを使うようにメーラーを設定する
	$mail->SMTPAuth = true;
	$mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
	$mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
	$mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
	$mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
	$mail->Port = SMTP_PORT; // 接続するTCPポート

	// メール内容設定
	$mail->CharSet = "UTF-8";
	$mail->Encoding = "base64";
	$mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
	$mail->addAddress($mailTo, '受信者さん'); //受信者（送信先）を追加する
//    $mail->addReplyTo('kannta7777777@gmail.com','返信先');
//    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
//    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加
	$mail->Subject = MAIL_SUBJECT; // メールタイトル
	$mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
	$body = <<< EOM
	24時間以内に下記のURLからご登録下さい。
	{$url}
	EOM;

	$mail->Body  = $body; // メール本文

	// メール送信の実行
	if(!$mail->send()) {
		echo 'メールの送信に失敗しました。残念！';
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {

		//セッション変数を全て解除
		$_SESSION = array();
	
		//クッキーの削除
		if (isset($_COOKIE["PHPSESSID"])) {
			setcookie("PHPSESSID", '', time() - 1800, '/');
		}
	
		//セッションを破棄する
		session_destroy();

		echo 'メールをお送りしました。24時間以内にメールに記載されたURLからご登録下さい。';
	}
} else {
	foreach($errors as $value){
		echo "<p>".$value."</p>";
	}
}
?>
<br><br>
<input type="button" value="戻る" onClick="history.back()">



 </body>
</html>

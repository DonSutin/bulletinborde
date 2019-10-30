<html lang="ja">
 <head>
   <meta charset="utf-8">
   <title>Login</title>
 </head>
 <body>
   <h1>パスワード　recommend00　で統一</h1>
   <h1>ようこそ、ログインしてください。</h1>
   <form  action="login.php" method="post">
     <label for="email">email   </label>
     <input type="email" name="mail"><br>
     <label for="password">password</label>
     <input type="password" name="password"><br>
     <button type="submit">   ログイン</button>
   </form>
<br><br>
   <h1>初めての方はこちら</h1>
   <h3>以下にメールアドレスをご入力ください。</h3>
   <form action="send_email.php" method="post">
     <label for="email">email</label>
     <input type="email" name="mail">
     <input type="hidden" name="token" value="<?=$token?>">
     <button type="submit">送信</button>
     
   </form>
 </body>
</html>





<?php
//セッション関数を使う時は
session_start();
 
header("Content-type: text/html; charset=utf-8");



require_once('db.php');


//クロスサイトリクエストフォージェリ（CSRF）対策
$_SESSION['token'] = base64_encode(openssl_random_pseudo_bytes(32));
$token = $_SESSION['token'];


//クリックジャッキング対策
header('X-FRAME-OPTIONS: SAMEORIGIN');






?>
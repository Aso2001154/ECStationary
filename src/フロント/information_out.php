<?php
session_start();
$user_number = $_SESSION['user']['user_number'];
$user_id = @$_POST['user_id'];
$user_pass = @$_POST['user_pass'];
$user_name = @$_POST['user_name'];
$user_address = @$_POST['user_address'];
$credit_number = @$_POST['credit_number'];
$form_link = 'pencil.php';
$from_btn_message = 'top';
$message = '既に登録されているIDかパスワードです。';
require "data_base.php";
$pdo = data_base();//別のファイルから呼び出す
$sql = user();//別のファイルから呼び出す
    $stmt = $pdo->prepare($sql);
    $stmt -> execute([$user_id,$user_pass]);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt > 0) {
        $login_message = '更新失敗';

    }else {

        $sql1 = 'UPDATE `user` SET `user_id` = ?,`user_pass` = ?,`user_name` = ?,`user_address` = ?,`credit_number` = ?
            WHERE `user_number` = ?';
        $stmt = $pdo->prepare($sql1);
        $stmt->execute([$user_id, $user_pass, $user_name, $user_address, $credit_number, $user_number]);
        $message = '';
        $login_message = '更新成功';
        $form_link = 'login_in.php';
        $from_btn_message = 'login';
        unset($_SESSION['user']); // 更新に成功したら今ログインしているIDからログアウトする
    }

?>
<!DOCTYPE html>
<html>
 <head>
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <link rel="stylesheet" href="css/information.css"><!-- login_in.php、login_in1.php、signup_in.php、signup_out.php、logout.php、information.php、information_out.php、re_signup_in.php、re_information.php-->
     <link rel="stylesheet" href="css/mobile_information.css" media="screen and (max-width:400px)">
     <title>会員情報認証画面</title>
 </head>
 <body>
 <header class="header">
     <p class="head_img"><img src="img/header_name.png" alt="画像" class="header_log"></p>
 </header>
     <?php
     echo '<form action="',$form_link,'" method="post">';
     echo '<h2 class="login_message">',$login_message,'</h2>';
     echo '<p class="message">',$message,'</p>';
     echo '<p style="text-align: center;"><button type="submit" value="send" class="back_login">',$from_btn_message,'</button></p>';
     echo '</form>';
     ?>
 </body>
</html>

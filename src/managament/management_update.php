<?php
/*会員情報更新結果*/
$user = @$_POST['user'];
$user_id = @$_POST['user_id'];
$user_pass = @$_POST['user_pass'];
$user_name = @$_POST['user_name'];
$user_address = @$_POST['user_address'];
$credit_number = @$_POST['credit_number'];
$form_link = 'management_input.php';
$from_btn_message = 'search';

require "data_base.php";
$pdo = data_base();

$message = 'information';

$sql = user();
$stmt = $pdo->prepare($sql);
$stmt -> execute([$user_id,$user_pass]);
$cnt = $stmt -> rowCount(); //取得件数
if($cnt > 0) {
    $login_message = '更新失敗';
}else {
    $sql1 = 'UPDATE `user` SET `user_id` = ?,`user_pass` = ?,`user_name` = ?,`user_address` = ?,`credit_number` = ?
            WHERE `user_id` = ?';
    $stmt = $pdo->prepare($sql1);
    $stmt->execute([$user_id, $user_pass, $user_name, $user_address, $credit_number,$user]);
    $message = '';
    $login_message = '更新成功';
    $form_link = 'management.php';
    $from_btn_message = 'top';
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>更新結果</title>
    <link rel="stylesheet" href="css/management_update.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <script>
        let btnCheck = () =>{
            let beforeId = document.getElementById('id').value;
            let beforePass = document.getElementById('pass').value;
            let beforeCredit = document.getElementById('credit').value;
            if(beforeId.match(/[a-zA-Z0-9]{8}/) && beforePass.match(/[0-9]{4}/) && beforeCredit.match(/[0-9]{16}/)){
                if(beforeId.match(/[a-zA-Z0-9]{9}/) || beforePass.match(/[0-9]{5}/) || beforeCredit.match(/[0-9]{17}/)) {
                    swal('文字数をオーバーしています');
                    return false;
                }else{
                    if(beforeId.match(/[あ-ん-ー*@/()$#&!%<>]/) || beforePass.match(/[あ-んa-zA-Z-ー*@/()$#&!%<>]/) || beforeCredit.match(/[-]/)) {
                        swal('誤入力があります。');
                        return false;
                    }else{
                        return true;
                    }
                }
            }else{
                swal('文字数が足りない部分があります');
                return false;
            }
        }
    </script>
</head>
<body>
<header>
    <a href="management.php"><img src="img/header_name.png" alt="ヘッダーの画像" class="header_name"></a>
    <p class="head_border"></p>
</header>
<div class="container1">
<?php
echo '<form action="',$form_link,'" method="post">';
echo '<p style="text-align: center;margin-top: 50px;">',$login_message,'</p>';
echo '<p style="text-align: center;"><button type="submit" name="action" value="send" class="top_btn">',$from_btn_message,'</button></p>';
echo '</form>';
?>
</div>
</body>
</html>
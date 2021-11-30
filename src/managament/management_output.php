<?php
/*管理画面の会員情報検索結果*/
$user = $_POST['user'];
$pass = $_POST['pass'];
$message = 'information';
$flg = 0;
require "data_base.php";
$pdo = data_base();//別のファイルから呼び出す
$sql = user();//別のファイルから呼び出す
$stmt = $pdo->prepare($sql);
$stmt->execute([$user,$pass]);
$cnt = $stmt->rowCount();
if($cnt==0){
    $message = '情報がありません';
    $flg = 1;
}
?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>会員情報検索結果</title>
        <link rel="stylesheet" href="css/management_input.css">
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
                        if(beforeId.match(/[あ-ん-ー*@/()$#&!%<>亜-熙０-９]/) || beforePass.match(/[あ-んa-zA-Z-ー*@/()$#&!%<>亜-熙０-９]/) || beforeCredit.match(/[-]/)) {
                            swal('誤入力があります。');
                            return false;
                        }else{
                            if(beforeId.match(/[a-zA-Z]/)&&beforeId.match(/[0-9]/)) {
                                return true;
                            }else{
                                swal('idはアルファベットと数字の組み合わせにしましょう。');
                                return false;
                            }
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
    <header class="header">
        <a href="management.php"><img src="img/header_name.png" alt="画像" class="header_name"></a>
        <p class="head_border"></p>
    </header>
    <div class="container">
    <?php
    if($cnt!=0){
        echo '<h1 class="title">',$message,'</h1>';
        echo '<form action="management_re_output.php" method="post">';
        foreach ($stmt as $row){
            echo '<h2 class="subtitle">id</h2><p style="margin-left: 25%;"><span class="span">※文字種：アルファベット、数字(半角)の組み合わせ/制限：ハイフン、*、@、/、(、)、<、>、$、#、&、%、!、ひらがな、漢字はなし/文字数：8文字</span></p>';
            echo '<p class="txt_information"><input type="text" class="text" id="id"name="user_id" value="',htmlspecialchars($row['user_id']), '"></p>';
            echo '<h2 class="subtitle">pass</h2><p style="margin-left: 25%;"><span class="span">※文字種：数字(半角)/制限:ハイフン、*、@、/、(、)、<、>、$、#、&、%、!、アルファベット、ひらがな、漢字はなし/文字数：4文字</span></p>';
            echo '<p class="txt_information"><input type="password" class="text" id="pass" name="user_pass" value="',htmlspecialchars($row['user_pass']), '"></p>';
            echo '<h2 class="subtitle">name</h2>';
            echo '<p class="txt_information"><input type="text" class="text" id="name" name="user_name" value="',htmlspecialchars($row['user_name']), '"></p>';
            echo '<h2 class="subtitle">address</h2>';
            echo '<p class="txt_information"><input type="text" class="text" id="address" name="user_address" value="',htmlspecialchars($row['user_address']), '"></p>';
            echo '<h2 class="subtitle" id="credit_number">credit number</h2><p style="margin-left: 25%;"><span class="span">※文字種：数字(半角)/制限：ハイフンはなし/文字数：16文字</span></p>'; //※制限:ハイフンなし
            echo '<p class="txt_information"><input type="number" class="number_text" id="credit" name="credit_number" value="',htmlspecialchars($row['credit_number']),'"></p>';
            echo '<button value="send" class="update_btn" id="login_info" onsubmit="return btnCheck()" onclick="return btnCheck()">update</button>';
            echo '<input type="hidden" value="',$user,'" name="user">';
        }
        echo '</form>';
    }else {
        echo '<p class="error" style="text-align: center;">', $message, '</p>';
    }
    ?>
    <?php
        if($flg==0){
            echo '<form action="management.php" method="post"><button type="submit" name="action" value="send" class="top">top</button></form>';
        }else{
            echo '<form action="management.php" method="post"><p style="text-align: center;"><button type="submit" name="action" value="send" class="top_center">top</button></p></form>';
        }
    ?>
    </div>
    </body>
</html>
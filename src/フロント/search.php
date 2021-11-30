<?php
session_start();
$user_number = $_SESSION['user']['user_number'];//データベースにデータを入れるまでこの行は消しておく
$text = @$_POST['text'];
$login_message = '';

try {

    require "data_base.php";
    $pdo = data_base();
    $sql = merchandise_name();//商品名で検索するsql文を関数化しているのでその呼び出し
    $stmt = $pdo->prepare($sql);
    $stmt -> execute(['%'.$text.'%']);
    $cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('商品がありません');

}catch (PDOException $ex){
    $login_message = $ex->getMessage(); //エラーメッセージ
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/top.css">
    <link rel="stylesheet" href="css/mobile_top.css" media="screen and (max-width:400px)">
    <title>検索結果</title>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script>
        $(function(){
            var $scroll_button = $(".scroll-button");
            $(window).scroll(function(){
                var scrollTop = $(window).scrollTop();
                if(scrollTop > $(window).height()){
                    $scroll_button.css("opacity", ".7");
                }else{
                    $scroll_button.css("opacity", "0");
                }
            });
            $scroll_button.click(function(){
                $("html,body").animate({scrollTop: 0}, 500, "swing");
            });
        });
    </script>
</head>
<body>
<header class="header">
    <div class="list"><img src="img/header_name.png" alt="画像" class="header_name"></div>
    <form action="logout.php" method="post"><p><button type="submit" class="btn">ログアウト</button></p></form>
    <form action="cart.php" method="post"><p><button type="submit" class="btn">カート</button></p></form>
    <form action="information.php" method="post"><p><button type="submit" class="btn">会員情報</button></p></form>
    <form action="history.php" method="post"><p><button type="submit" class="btn">履歴</button></p></form>
</header>
<div class="genre_list">
    <form action="pencil.php" method="post"><input type="hidden" value="1" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre1">シャーペン</button></p></form><!--開いているジャンルのボタンは押せなくする(disabled)-->
    <form action="pencil.php" method="post"><input type="hidden" value="2" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre2">消しゴム</button></p></form>
    <form action="pencil.php" method="post"><input type="hidden" value="3" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre3">ボールペン</button></p></form>
    <form action="pencil.php" method="post"><input type="hidden" value="4" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre4">定　　規</button></p></form>
    <form action="pencil.php" method="post"><input type="hidden" value="5" name="genre_id"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre5">事務用品</button></p></form>
    <form action="ranking.php" method="post"><p class="genre_name"><button type="submit" value="send" class="genre" id="genre6">ランキング</button></p></form><br><br>
</div>
    <p class="search_answer">検索結果</p>
    <?php
       $cnt = 0;
       $i=1;
       foreach ($stmt as $row){
            $genre_id = $row['genre_id'];
            $merchandise_id = $row['merchandise_id'];
        if($i%2!=0) {
            // iが奇数の場合
            echo '<form action="merchandise_detail.php" method="post" name="a_form',$cnt,'">';
            echo '<div class="border_vertical">';
            echo '<input type="hidden" name="genre_id" value="',$genre_id,'">';
            echo '<input type="hidden" name="merchandise_id" value="',$merchandise_id,'">';
            echo '<div class="merchandise_range">';
            if($genre_id!=4) {
                //　定規以外の商品を出力する
                echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form', $cnt, '.submit();"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img"></a></p>';
                echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form', $cnt, '.submit();">', htmlspecialchars($row['merchandise_name']), '<br>¥', htmlspecialchars(number_format($row['price'])), '</a></p>';
            }else if($genre_id==4){
                //　定規の商品を出力する(画像のサイズが変わるため分けている)
                echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form', $cnt, '.submit();"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img" style="height: 50px;margin-top: 50px;margin-bottom: 200px;"></a></p>';
                echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form', $cnt, '.submit();">', htmlspecialchars($row['merchandise_name']), '<br>¥', htmlspecialchars(number_format($row['price'])), '</a></p>';
            }
            echo '</div>';
            echo '</div>';
            echo '</form>';
        }else{
            // iが偶数の場合
            echo '<form action="merchandise_detail.php" method="post" name="a_form',$cnt,'">';
            echo '<div class="border_vertical1">';
            echo '<input type="hidden" name="genre_id" value="', $genre_id, '">';
            echo '<input type="hidden" name="merchandise_id" value="', $merchandise_id, '">';
            echo '<div class="merchandise_range">';
            if($genre_id!=4) {
                //　定規以外の商品を出力する
                echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form', $cnt, '.submit();"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img"></a></p>';
                echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form', $cnt, '.submit();">', htmlspecialchars($row['merchandise_name']), '<br>¥', htmlspecialchars(number_format($row['price'])), '</a></p>';
            }else if($genre_id==4){
                //　定規の商品を出力する(画像のサイズが変わるため分けている)
                echo '<p class="p_btn"><a class="merchandise_img_btn" href="#" onclick="document.a_form', $cnt, '.submit();"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img" style="height: 50px;margin-top: 50px;margin-bottom: 200px;"></a></p>';
                echo '<p class="p_btn"><a class="merchandise_name_btn" href="#" onclick="document.a_form', $cnt, '.submit();">', htmlspecialchars($row['merchandise_name']), '<br>¥', htmlspecialchars(number_format($row['price'])), '</a></p>';
            }
            echo '</div>';
            echo '</div>';
            echo '</form>';
        }
        $i++;
        $cnt++;
       }
       echo '<p style="text-align: center;">',$login_message,'</p>';
       ?>
<div class="scroll-button"></div>
</body>
</html>
<?php
/*管理画面の商品検索結果*/
$message = ''; // エラーメッセージ用
$text = @$_POST['text'];//入力された商品名を$textの中に代入される
$count = 1;//$countの中の数字が偶数か奇数かで出力するときの場所が変わる(css)
try{
require "data_base.php";
$pdo = data_base();//関数化したデータベースの呼び出し
$sql = merchandise_name();//別のファイルから呼び出し
$stmt = $pdo->prepare($sql);
$stmt -> execute(['%'.$text.'%']);
$cnt = $stmt -> rowCount(); //取得件数
    if($cnt == 0) throw new PDOException('商品がありません'); // cntが0の場合データベースにそのジャンルの商品が一つもないということなのでエラーメッセージを出力するようにしている

}catch (PDOException $ex){
    $message = $ex->getMessage(); //エラーメッセージ
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>検索結果一覧</title>
    <link rel="stylesheet" href="css/search_merchandise.css">
    <link rel="stylesheet" href="css/mobile_search_merchandise.css" media="screen and (max-width:400px)"><!--モバイル用のcss-->
    <script> // 上部にスクロールさせるための処理
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
<header>
    <a href="management.php"><img src="img/header_name.png" alt="ヘッダー画像" class="header_name"></a>
    <p class="head_border"></p>
</header>
<p class="search_result" style="text-align: center">商品検索結果一覧</p>

<?php
$cnt=0;
foreach ($stmt as $row){
    $genre_id = $row['genre_id'];
    if($count%2!=0) {//$countが奇数の場合
        echo '<form action="search_detail.php" method="post">';
        echo '<div class="border_vertical">';
        echo '<input type=hidden value="', $row['genre_id'], '" name="genre_id">';
        echo '<input type=hidden value="', $row['merchandise_id'], '" name="merchandise_id">';
        echo '<div class="merchandise_range">';
        if($genre_id!=4) {
            echo '<p class="p_btn"><button type="submit" class="merchandise_img_btn"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img"></button></p>';
        }else{
            echo '<p class="p_btn"><button type="submit" class="merchandise_img_btn"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img" style="height: 50px;margin-top: 50px;margin-bottom: 200px;"></button></p>';
        }
        echo '<p class="p_btn"><button type="submit" class="merchandise_name_btn">', htmlspecialchars($row['merchandise_name']), '<br>￥', htmlspecialchars(number_format($row['price'])), '</button></p>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
    }else{//$countが偶数の場合
        echo '<form action="search_detail.php" method="post">';
        echo '<div class="border_vertical1">';
        echo '<input type=hidden value="', $row['genre_id'], '" name="genre_id">';
        echo '<input type=hidden value="', $row['merchandise_id'], '" name="merchandise_id">';
        echo '<div class="merchandise_range">';
        if($genre_id!=4) {
            echo '<p class="p_btn"><button type="submit" class="merchandise_img_btn"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img"></button></p>';
        }else{
            echo '<p class="p_btn"><button type="submit" class="merchandise_img_btn"><img src="', htmlspecialchars($row['image']), '" alt="商品画像" class="merchandise_img" style="height: 50px;margin-top: 50px;margin-bottom: 200px;"></button></p>';
        }
        echo '<p class="p_btn"><button type="submit" class="merchandise_name_btn">', htmlspecialchars($row['merchandise_name']), '<br>￥', htmlspecialchars(number_format($row['price'])), '</button></p>';
        echo '</div>';
        echo '</div>';
        echo '</form>';
    }
    $count++;
}
echo '<p style="text-align: center;">',$message,'</p>';//エラーメッセージ
echo '<div class="cd">回り込み解除</div>';//float解除要因
?>
<div>

</div>
<form action="management.php" method="post"><p style="text-align: center;"><button type="submit" value="send" class="top_btn">top</button></p></form>
<div class="scroll-button"></div> <!--スクロールのボタンを表示させる-->
</body>
</html>

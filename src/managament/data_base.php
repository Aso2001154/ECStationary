<?php
//データベースへログイン
function data_base(){
    return new PDO(
        'mysql:host=mysql153.phy.lolipop.lan;
        dbname=LAA1291139-company;charset=utf8',
        'LAA1291139',
        'company',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}
//会員情報検索(id,pass)
function user(){
    return 'SELECT * FROM `user` WHERE `user_id` = ? AND `user_pass` = ?';
}

//ジャンルIDで出力する商品を求める
function merchandise(){
    return 'SELECT * FROM `merchandise` WHERE `genre_id` = ?';
}

//　ジャンルIDと商品IDから商品を求める
function genre_merchandise(){
    return 'SELECT * FROM `merchandise` WHERE `genre_id` = ? AND `merchandise_id` = ?';
}

//売上を求める
function sales(){
    return 'select merchandise.genre_id,merchandise.merchandise_id,merchandise.merchandise_name,merchandise.image,merchandise.price,(merchandise.price*detail.quantity)as all_price
from (select history_genre_id,history_merchandise_id,sum(history_quantity)as quantity
from history_detail
group by history_genre_id,history_merchandise_id) detail,merchandise
where merchandise.genre_id = detail.history_genre_id and merchandise.merchandise_id = detail.history_merchandise_id
and merchandise.genre_id = ?';
}

//商品名で出力する商品を求める
function merchandise_name(){
    return 'SELECT * FROM `merchandise` WHERE `merchandise_name` LIKE ?';
}
?>

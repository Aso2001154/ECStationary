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
//会員情報登録
function user_signup(){
    return 'INSERT INTO `user`
            (`user_id`, `user_pass`, `user_name`, `user_address`,`credit_number`)
            VALUES(?,?,?,?,?)';
}
//会員情報検索(user_number)
function search_user(){
    return 'SELECT * FROM `user` WHERE `user_number` = ?';
}
//ジャンルIDで出力する商品を求める
function merchandise(){
    return 'SELECT * FROM `merchandise` WHERE `genre_id` = ?';
}
function genre_merchandise(){
    return 'select * from `merchandise` where `genre_id` = ? and `merchandise_id` = ?';
}
//商品名で出力する商品を求める
function merchandise_name(){
    return 'SELECT * FROM `merchandise` WHERE `merchandise_name` LIKE ?';
}
//すべての商品のランキング
function ranking_all(){
    return 'SELECT merchandise.genre_id,merchandise.merchandise_name,merchandise.price,merchandise.image,ranking.quantity
            FROM (select history_genre_id,history_merchandise_id,sum(history_quantity)as quantity 
                  from history_detail 
                  group by history_genre_id,history_merchandise_id) ranking,merchandise
            WHERE ranking.history_genre_id=merchandise.genre_id AND ranking.history_merchandise_id=merchandise.merchandise_id
            ORDER BY ranking.quantity DESC';
}
//選択されたジャンルの商品だけのランキング
function ranking_narrowing(){
    return 'SELECT merchandise.genre_id,merchandise.merchandise_name,merchandise.price,merchandise.image,ranking.quantity
            FROM (select history_genre_id,history_merchandise_id,sum(history_quantity)as quantity 
                  from history_detail 
                  where history_genre_id = ?
                  group by history_genre_id,history_merchandise_id) ranking,merchandise
            WHERE ranking.history_genre_id=merchandise.genre_id AND ranking.history_merchandise_id=merchandise.merchandise_id
            ORDER BY ranking.quantity DESC';
}

//すべての期間の履歴を出力する
function history_all(){
    return 'SELECT * FROM history_purchase WHERE user_number = ?';
}
//絞り込まれた期間の履歴を出力する
function history_period(){
    return 'SELECT * FROM `history_purchase` WHERE `user_number` = ? AND `purchase_day` >= ?';
}
//すべての期間の履歴を出力する　さらに中身を詳しく
function history_all_detail(){
    return 'SELECT * FROM merchandise m,history_detail h WHERE h.history_id in (select history_id from history_purchase where user_number = ?)
                         AND m.genre_id=h.history_genre_id AND m.merchandise_id=h.history_merchandise_id';
}
//絞り込まれた期間の履歴を出力する　さらに中身を詳しく
function history_period_detail(){
    return 'SELECT * FROM merchandise m,history_detail h WHERE h.history_id in (select history_id from history_purchase where `user_number` = ? AND `purchase_day` >= ?)
                         AND m.genre_id=h.history_genre_id AND m.merchandise_id=h.history_merchandise_id';
}
//カートの中身を出力する
function cart(){
    return 'SELECT * FROM merchandise,cart WHERE merchandise.genre_id = cart.cart_genre_id
           AND merchandise.merchandise_id = cart.cart_merchandise_id
           AND cart.user_number = ?';
}
//カートに商品の追加
function add_cart(){
    return 'INSERT INTO `cart`
            (`user_number`, `cart_count`, `cart_genre_id`, `cart_merchandise_id`,`cart_quantity`)
            VALUES(?,?,?,?,?)';
}


?>

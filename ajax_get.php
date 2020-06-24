<?php
// echo ('<pre>');
// var_dump($_GET);
// echo ('</pre>');
// exit();

include("functions.php");
// ...関数ファイル読み込み処理を記述(認証関連は省略でOK)
// ...DB接続の処理を記述
$pdo = connect_to_db();

$search_word = $_GET["serchword"]; // GETのデータ受け取り
$sql = "SELECT * FROM todo_table WHERE todo LIKE '%{$search_word}%'"; // ...SQL実行の処理を記述

//SQLの準備＆実行
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();


if ($status == false) {
    // ...エラー処理を記述 
} else {
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result); // JSON形式にして出力
    // $json_result = json_encode($result); // JSON形式にして出力
    // echo $json_result;
    exit();
}

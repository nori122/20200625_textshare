<?php
// 送信データのチェック
// var_dump($_GET);
// exit();

//sessionの開始
session_start();
// 関数ファイルの読み込み
include("functions.php");
check_session_id();


// ユーザ名取得
$user_id = $_SESSION['id'];
$user_name = $_SESSION["user_name"];
$univ = $_SESSION["univ"];

//index.phpで選択した県を取得
$pref = $_GET["pref"];

//DB接続
$pdo = connect_to_db();

// データ取得SQL作成
$sql = 'SELECT * FROM univ_table WHERE univ_location=:pref';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':pref', $pref, PDO::PARAM_INT);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は指定の11レコードを取得
    // fetch()関数でSQLで取得したレコードを取得できる
    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
    // var_dump($result);
    // exit();
    $output = "";
    // <tr><td>deadline</td><td>todo</td><tr>の形になるようにforeachで順番に$outputへデータを追加
    // `.=`は後ろに文字列を追加する，の意味
    foreach ($result as $record) {
        // $output .= "<div>{$record["univ_name"]}</div>";
        $output .= "<div><a href='search.php?univ_name={$record["univ_name"]}'>{$record["univ_name"]}</a></div>";
        // 画像出力を追加しよう
        // $output .= "<td><img src='{$record["img"]}' height=150px></td>";
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <title>大学一覧</title>
</head>

<body>
    <!-- indexへ戻るボタン -->
    <div id="return"></div>

    <form action="search.php" method="POST">
        <fieldset>
            <legend><?= $pref ?>県の大学一覧</legend>
            <div><?= $output ?></div>
        </fieldset>
    </form>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>

</html>

<!---------------------
javascript 要素
--------------------->
<script>
    $(function() {
        $("#return").load("return.php");
    });
</script>
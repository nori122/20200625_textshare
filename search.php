<!---------------------
php 要素
--------------------->

<?php

// 送信データのチェック
// var_dump($_GET);
// exit();

//sessionの開始
session_start();
// 関数ファイルの読み込み
include("functions.php");
check_session_id();

//SESSIONデータのチェック
// var_dump($_SESSION);
// exit();

// ユーザ名取得
$user_id = $_SESSION['id'];
$user_name = $_SESSION["user_name"];
$univ = $_SESSION["univ"];

//pref.phpで選択した県を取得
$univ_name = $_GET["univ_name"];

// DB接続
$pdo = connect_to_db();


// データ取得SQL作成

$sql = 'SELECT * FROM item_table LEFT JOIN univ_table ON item_table.univ_id = univ_table.univ_id where univ_name = :univ_name';
// $sql = 'SELECT * FROM item_table WHERE univ_name=:univ_name';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':univ_name', $univ_name, PDO::PARAM_STR);
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
        $output .= "<tr>";
        $output .= "<td>{$record["book_title"]}</td>";
        $output .= "<td>{$record["lesson_title"]}</td>";
        $output .= "</tr>";
        // 画像出力を追加しよう
        // $output .= "<td><img src='{$record["img"]}' height=150px></td>";
    }
}

?>



<!---------------------
HTML 要素
--------------------->
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <title>大学</title>
    <style>
        table {
            border-collapse: collapse;
            margin: 0px 5px;
        }

        table th,
        table tr,
        table td {
            border: 1px solid;
            padding: 5px;
        }

        table th {
            background-color: #d3d3d3;
        }

        .label {
            margin: 30px 5px 0px 5px;
            font-size: 24px;
        }



        ;
    </style>
</head>

<body>
    <!-- indexへ戻るボタン -->
    <div id="return"></div>

    <!-- 選択してる大学名 -->
    <div><?= $univ_name ?></div>

    <form action="search_result.php" method="POST" autocomplete="off">
        <div>
            <input style="width:200px;" type="search" name="keyword" placeholder="授業名/教科書名で検索">
            <button type='submit'>検索</button>
        </div>
    </form>

    <div class="label">出品されている教科書一覧</div>
    <table>
        <thead>
            <th>教科書名</th>
            <th>授業名</th>
        </thead>
        <tbody>
            <?= $output ?>
        </tbody>
    </table>
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
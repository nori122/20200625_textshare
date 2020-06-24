<?php
// var_dump($_POST);
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
//検索キーワードを取得
$keyword = $_POST["keyword"];

// DB接続
$pdo = connect_to_db();

// データ取得SQL作成&実行
// $sql = 'SELECT * FROM item_table WHERE book_title=:keyword OR lesson_title=:keyword';
$sql = 'SELECT * FROM item_table WHERE book_title LIKE "%":keyword"%" OR lesson_title LIKE "%":keyword"%"';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
$status = $stmt->execute();


// データ登録処理後SQL実行時にエラーがある場合
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    // 正常にSQLが実行された場合は指定のレコードを取得
    $result = $stmt->fetchALL(PDO::FETCH_ASSOC);
    // var_dump($result);
    // exit();
    $output = "";
    // `.=`は後ろに文字列を追加する，の意味
    foreach ($result as $record) {
        $output .= "<div class='item'>";
        $output .= "<div><a href='item_detail.php?item_id={$record["item_id"]}'><img class='item_img' src='{$record["image"]}' alt='商品画像'></a></div>";
        $output .= "<div>{$record["book_title"]}</div>";
        $output .= "<div>{$record["lesson_title"]}</div>";
        $output .= "<div>{$record["price"]}円 (定価：{$record["price_original"]}円)</div>";
        // $output .= "<div><a href='item_detail.php?item={$record["item_id"]}'>詳細へ</a></div>";
        $output .= "</div>";
    }
}

?>




<!---------------------
HTML 要素
--------------------->

<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='search_result.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>


<body>
    <!-- indexへ戻るボタン -->
    <div id="return"></div>

    <p>[ <?= $keyword ?> ]の検索結果</p>
    <div class="search_result_wrap">
        <!-- <div class="item">
            <div><img class='item_img' src='image/items/1.jpg' alt=''></div>
            <div>計量経済学</div>
            <div>計量経済学入門</div>
            <div>2000円(定価3500円)</div>

        </div>
        <div class="item">
            <div><img class='item_img' src='image/items/1.jpg' alt=''></div>
            <div>計量経済学</div>
            <div>計量経済学入門</div>
            <div>2000円(定価3500円)</div>
        </div> -->


        <?= $output ?>
    </div>
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
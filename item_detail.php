<!---------------------
php 要素
--------------------->
<?PHP
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
//検索キーワードを取得
$item_id = $_GET["item_id"];

// DB接続
$pdo = connect_to_db();

// データ取得SQL作成&実行
// $sql = 'SELECT * FROM item_table WHERE book_title=:keyword OR lesson_title=:keyword';
$sql = 'SELECT * FROM item_table WHERE item_id=:item_id';

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
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
    // echo $result[0]['lesson_title'];
    // exit();
}


?>

<!---------------------
HTML 要素
--------------------->
<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='item_detail.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <title>Document</title>
</head>

<body>
    <!-- indexへ戻るボタン -->
    <div id="return"></div>
    <div class="itemInfo">
        <div class="book_title"><?= $result[0]["book_title"] ?></div>
        <div class='itemPic'>
            <img src='<?= $result[0]["image"] ?>' alt=''>
        </div>
        <div><span class="price"><?= $result[0]["price"] ?>円 </span><span class="price_original">(定価:<?= $result[0]["price_original"] ?>円)</span></div>
        <table>
            <tbody>

                <tr>
                    <th>使用している授業</th>
                    <td><?= $result[0]["lesson_title"] ?></td>
                </tr>
                <tr>
                    <th>版</th>
                    <td>第<?= $result[0]["edition"] ?>版</td>
                </tr>
                <tr>
                    <th>状態</th>
                    <td><?= $result[0]["book_status"] ?></td>
                </tr>
                <tr>
                    <th>コメント</th>
                    <td><?= $result[0]["comment"] ?></td>
                </tr>
            </tbody>
        </table>

        <a href='chat.php?item_id=<?= $result[0]["item_id"] ?>'><button class="begin">ほしいのでチャットを開始する</button></a>
        <div>質問のやりとりを挿入</div>
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
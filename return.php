<!---------------------
php 要素
--------------------->
<?PHP
//sessionの開始
session_start();
// 関数ファイルの読み込み
include("functions.php");
check_session_id();


// ユーザ名取得
$user_id = $_SESSION['id'];
$user_name = $_SESSION["user_name"];
$univ = $_SESSION["univ"];



?>

<!---------------------
HTML 要素
--------------------->
<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='styles.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        .header {
            margin: -7px -7px 20px -7px;
            padding: 0 20px;
            display: flex;
            flex-direction: row;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            background-color: white;
            /* opacity: 0.8; */
            /* position: fixed; */

        }

        .orange {
            color: orange;
            margin: 0 0 0 0px;
        }

        .register {
            display: flex;
            flex-direction: column;
            /* justify-content: center; */
            align-items: center;
            margin: 0 20px 0 auto;
        }

        .register a {
            text-decoration: none;
            color: orange;
        }

        .register div {
            margin: 0 0 0 7px;
        }
    </style>
    <title>textshare</title>
</head>

<body>

    <div class="header">
        <div>
            <a href='index.php'><i class="fas fa-home"></i></a> <?= $user_name ?>
            <span>さん ログイン中</span>
            <a href="logout.php" style="font-size:12px; text-decoration:none;">( ログアウト )</a>
        </div>
        <div class="register">
            <a href='newitem.php'><i class="fas fa-camera fa-3x orange"></i>
                <div>出品</div>
            </a>
        </div>

    </div>

</body>

</html>
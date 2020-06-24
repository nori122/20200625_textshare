<!---------------------
php 要素
--------------------->
<?PHP

session_start();
include("functions.php");
check_session_id();

// ユーザ名取得
$user_id = $_SESSION['id'];
$user_name = $_SESSION["user_name"];
$univ = $_SESSION["univ"];

//検索キーワードを取得
$item_id = $_GET["item_id"];


// var_dump($user_name);
// exit();

// var_dump($_GET);
// exit();

// var_dump($item_id);
// exit();



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
    <link rel='stylesheet' href='chat.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>

    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <title>chat</title>
</head>

<body>
    <!-- indexへ戻るボタン -->
    <div id="return"></div>

    <div class='chatheader'>
        <div>
            <h2><?= $result[0]["book_title"] ?></h2>
            <p>値段：<?= $result[0]["price"] ?>円 </span><span class="price_original">(定価:<?= $result[0]["price_original"] ?>円)</p>
            <p>状態：<?= $result[0]["book_status"] ?></p>
        </div>
        <div class='itemPic'>
            <img src='<?= $result[0]["image"] ?>' alt=''>
        </div>
    </div>


    <div class="tips">
        <p><i class="far fa-question-circle fa-2x" style="color:black;"></i>
            教科書のやりとりをいつ、どこで行うかを決定しましょう。</p>
        <span>(例)</span><br><span>はじめまして。この教科書を売って欲しいです。今週か来週で大学にいらっしゃる予定はありますか？<br>
        </span>

    </div>
    <!-- データ出力場所 -->
    <div id="output"></div>

    <!-- 入力場所を作成しよう -->

    <!-- <label for='name'>name</label><input type='text' id='name'> -->

    <div class='chat' action=''>
        <textarea name='' id='text'></textarea>
        <button id='send'>取引メッセージを送信する</button>
    </div>





    <!-- Firebaseの設定 -->
    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/7.15.4/firebase.js"></script>

    <!-- TODO: Add SDKs for Firebase products that you want to use
    https://firebase.google.com/docs/web/setup#available-libraries -->

    <script>
        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBKHsaxzSBkvyiXeIq7cmgiUFZxTbBiCaw",
            authDomain: "textshare-f514a.firebaseapp.com",
            databaseURL: "https://textshare-f514a.firebaseio.com",
            projectId: "textshare-f514a",
            storageBucket: "textshare-f514a.appspot.com",
            messagingSenderId: "216912551935",
            appId: "1:216912551935:web:fb3eddb267557547c851ca"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        // 日時をいい感じの形式にする関数
        function convertFromFirestoreTimestampToDatetime(timestamp) {
            const _d = timestamp ? new Date(timestamp * 1000) : new Date();
            const Y = _d.getFullYear();
            const m = (_d.getMonth() + 1).toString().padStart(2, '0');
            const d = _d.getDate().toString().padStart(2, '0');
            const H = _d.getHours().toString().padStart(2, '0');
            const i = _d.getMinutes().toString().padStart(2, '0');
            const s = _d.getSeconds().toString().padStart(2, '0');
            return `${Y}/${m}/${d} ${H}:${i}:${s}`;
        }

        const db = firebase.firestore().collection('chat');
    </script>

    <!-- jqueryのCDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        const user_name = <?php echo json_encode($user_name) ?>;
        const item_id = <?php echo ($item_id) ?>;
        //送信処理
        $('#send').on('click', function() {
            // alert('ok');
            db.add({
                item_id: item_id,
                name: user_name,
                text: $('#text').val(),
                time: firebase.firestore.FieldValue.serverTimestamp(),
            });
            $('#text').val('');
        })

        // 受信処理の記述
        db.where("item_id", "==", <?= $item_id ?>).orderBy('time', 'desc').limit(5).onSnapshot(function(querySnapshot) {
            // onSnapshotでデータ変更時に実行される!
            // querySnapshot.docsにデータが配列形式で入る 
            let str = '';
            querySnapshot.docs.forEach(function(doc) {
                // doc.idでidを，doc.data()でデータを取得できる 
                const id = doc.id;
                const data = doc.data();
                const datetime = convertFromFirestoreTimestampToDatetime(data.time.seconds);
                if (data.name == <?php echo json_encode($user_name) ?>) {
                    str += '<div class="chat-container message-self" id="' + id + '">';
                    str += '<div class="icon-self"><i class="far fa-user-circle fa-2x"></i></div>';
                } else {
                    str += '<div class="chat-container message-other" id="' + id + '">';
                    str += '<div class="icon-other"><i class="far fa-user-circle fa-2x"></i></div>';
                }
                str += '<div><span>' + data.name + '</span>';
                str += '<p>' + data.text + '</p>';
                str += '<span style="font-size:12px;">(' + datetime + ') </span></div>';
                str += '</div>';
            });
            $('#output').html(str);
        });
    </script>
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
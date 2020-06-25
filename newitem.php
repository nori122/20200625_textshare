<!---------------------
php 要素
--------------------->
<?PHP



?>



<!---------------------
HTML 要素
--------------------->
<!DOCTYPE html>
<html lang='ja'>

<head>
    <meta charset='UTF-8'>
    <link rel='stylesheet' href='newitem.css'>
    <link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.13.0/css/all.css' integrity='sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V' crossorigin='anonymous'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>

    <title>出品</title>
</head>

<body>
    <!-- indexへ戻るボタン -->
    <div id="return"></div>

    <!-- 出品フォーム -->
    <form class="newitem-form" action="create_file.php" enctype="multipart/form-data" method="POST">

        <!-- 画像 -->
        <div class="image">
            <div>
                <label>教科書の画像</label>
                <input type="file" name="upfile" accept="image/*" capture="camera">
            </div>
        </div>

        <!-- 授業情報 -->
        <div class="lesson-info">
            <div>
                <label>授業名</label>
                <input type="text" name="lesson_title">
            </div>
        </div>

        <!-- 商品情報 -->
        <div class="item-info">
            <div>
                <label>教科書名</label>
                <input class="uploader" type="text" name="book_title">
            </div>
            <div>
                <label>版(任意入力)</label>
                <input type="number" name="edition">
            </div>
            <div>
                <label>教科書の状態</label>
                <select class="select-form" name="book_status">
                    <option class="input" selected disabled value="" style='display:none;'></option>
                    <option>未使用に近い</option>
                    <option>目立った書き込みや折り目なし</option>
                    <option>やや書き込みや折り目あり</option>
                    <option>汚れあり</option>
                </select>
            </div>
            <div>
                <label>備考</label>
                <input type="textarea" name="comment">
            </div>
        </div>

        <!-- 価格情報 -->
        <div class="price-info">
            <div>
                <label>販売価格</label>
                <input type="number" name="price">
            </div>
            <div>
                <label>定価(任意入力)</label>
                <input type="number" name="price_original">
            </div>
        </div>


        <!-- 出品ボタン -->
        <div>
            <button>出品</button>
        </div>


    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</body>

</html>

<!---------------------
javascript 要素
--------------------->
<script>
    //returnを呼び出し
    $(function() {
        $("#return").load("return.php");
    });
</script>
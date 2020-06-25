<?php
session_start();
include("functions.php");
check_session_id();

// var_dump($_POST);
// var_dump($_SESSION);
// var_dump($_FILES);
// exit();

//DBに接続
$pdo = connect_to_db();


//エラー処理
if (
  !isset($_POST['lesson_title']) || $_POST['lesson_title'] == '' ||
  !isset($_POST['book_title']) || $_POST['book_title'] == '' ||
  !isset($_POST['price']) || $_POST['price'] == '' ||
  !isset($_FILES['upfile']) || $_FILES['upfile'] == ''
) {
  // 項目が入力されていない場合はここでエラーを出力し，以降の処理を中止する
  echo json_encode(["error_msg" => "no input"]);
  exit();
}

// POSTで受け取ったデータを変数に入れる
$lesson_title = $_POST['lesson_title'];
$book_title = $_POST['book_title'];
$edition = $_POST['edition'];
$book_status = $_POST['book_status'];
$comment = $_POST['comment'];
$price = $_POST['price'];
$price_original = $_POST['price_original'];

// SESSIONで受け取ったデータを変数に入れる
$user_id = $_SESSION['user_id'];
$univ = $_SESSION['univ'];



//univ_nameからuniv_idを取得する
$sql = "SELECT `univ_id` FROM `univ_table` WHERE `univ_name`=:univ";
// echo ($sql);
// exit();

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':univ', $univ, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  echo json_encode(["error_msg" => "{$error[2]}"]);
  exit();
} else {
  // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($result);
  // exit();
}

// var_dump((int) $result["univ_id"]);
$univ_id = (int) $result["univ_id"];
var_dump($univ_id);
// exit();





// ここからファイルアップロード&DB登録の処理を追加しよう！！！
if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  //送信ファイル情報の取得
  $uploadedFileName = $_FILES['upfile']['name'];
  $tempPathName = $_FILES['upfile']['tmp_name'];
  $fileDirectoryPath = 'image/items/';

  //ファイル名の準備
  $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
  $uniqueName = date('YmdHis') . md5(session_id()) . "." . $extension;
  $fileNameToSave = $fileDirectoryPath . $uniqueName;

  // var_dump($fileNameToSave);

  //imgに格納
  $img = '';
  if (is_uploaded_file($tempPathName)) {
    if (move_uploaded_file($tempPathName, $fileNameToSave)) {
      chmod($fileNameToSave, 0644);

      // データ登録SQL作成 (univ_idは作んないといけない)
      $sql = "INSERT INTO `item_table`(`item_id`, `user_id`, `univ_id`, `book_title`, `price`, `price_original`, `lesson_title`, `image`, `edition`, `book_status`, `comment`, `created_at`, `updated_at`) VALUE (NULL,:user_id,:univ_id,:book_title,:price,:price_original,:lesson_title,:fileNameToSave,:edition,:book_status,:comment,sysdate(),sysdate())";

      // echo ($sql);
      // exit();

      // SQL準備&実行
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_STR);
      $stmt->bindValue(':book_title', $book_title, PDO::PARAM_STR);
      $stmt->bindParam(':price', $price, PDO::PARAM_INT);
      $stmt->bindParam(':price_original', $price_original, PDO::PARAM_INT);
      $stmt->bindValue(':lesson_title', $lesson_title, PDO::PARAM_STR);
      $stmt->bindValue(':fileNameToSave', $fileNameToSave, PDO::PARAM_STR);
      $stmt->bindValue(':edition', $edition, PDO::PARAM_STR);
      $stmt->bindValue(':book_status', $book_status, PDO::PARAM_STR);
      $stmt->bindValue(':comment', $comment, PDO::PARAM_STR);
      $stmt->bindValue(':univ_id', $univ_id, PDO::PARAM_STR);
      $status = $stmt->execute();

      // データ登録処理後
      if ($status == false) {
        // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
        $error = $stmt->errorInfo();
        echo json_encode(["error_msg" => "{$error[2]}"]);
        exit();
      } else {
        // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
        header("Location:index.php");
        exit();
      }
    } else {
      exit('画像アップロード失敗');
    }
  } else {
    exit('tempフォルダに画像がないです');
  }
} else {
  exit('error/image not subimitted');
}

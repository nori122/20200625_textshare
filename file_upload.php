<!---------------------
     php 要素
--------------------->
<?php
// var_dump($_FILES);
// exit();


if (isset($_FILES['upfile']) && $_FILES['upfile']['error'] == 0) {
  //送信ファイル情報の取得
  $uploadedFileName = $_FILES['upfile']['name'];
  $tempPathName = $_FILES['upfile']['tmp_name'];
  $fileDirectoryPath = 'upload/';

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
      $img = '<img src="' . $fileNameToSave . '" >';
    } else {
      exit('画像アップロード失敗');
    }
  } else {
    exit('tempフォルダに画像がないです');
  }
} else {
  exit('error/image not subimitted');
}

?>



<!---------------------
     HTML 要素
--------------------->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>file_upload</title>
</head>

<body>
  <?= $img ?>
</body>

</html>
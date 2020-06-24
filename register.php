<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TextShareユーザ登録画面</title>
</head>

<body>
  <form action="register_act.php" method="POST">
    <fieldset>
      <legend>TextShareユーザ登録画面</legend>
      <div>
        氏名: <input type="text" name="user_name">
      </div>
      <div>
        所属大学: <input type="text" name="univ">
      </div>
      <div>
        ログインID: <input type="text" name="user_id">
      </div>
      <div>
        パスワード: <input type="text" name="password">
      </div>
      <div>
        <button>Register</button>
      </div>
      <a href="login.php">or login</a>
    </fieldset>
  </form>

</body>

</html>
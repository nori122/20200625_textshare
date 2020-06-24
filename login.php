<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TextShareログイン画面</title>
</head>

<body>
  <form action="login_act.php" method="POST">
    <fieldset>
      <legend>TextShareログイン画面</legend>
      <div>
        user_id: <input type="text" name="user_id">
      </div>
      <div>
        password: <input type="text" name="password">
      </div>
      <div>
        <button>Login</button>
      </div>
      <a href="register.php">or register</a>
    </fieldset>
  </form>

</body>

</html>
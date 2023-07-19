<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="css/regstyle.css">
</head>
<body>
  <div class="reg__item">
    <div class="nameOfGame">Breakout</div>
    <form action="php/reg.php" method="post">
      <input id="signin" name="action" type="radio" value="signin">
      <label for="signin"><a href="index.php">Sign in</a></label>
      <input checked="" id="signup" name="action" type="radio" value="signup">
      <label for="signup">Sign up</label>
      <input id="reset" name="action" type="radio" value="reset">
      <label for="reset"><a href="guest.php">Guest</a></label>
      <div id="wrapper">
        <div id="arrow"></div>
        <input id="login" placeholder="Login" type="text" name="login">
        <input id="pass" placeholder="Password" type="password" name="password">
        <input id="repass" placeholder="Repeat password" type="password" name="repass">
      </div>

      <button type="submit" >
        <span> 
          Continue
          <br>
          Sign in
          <br>
          Sign up
        </span>
      </button>
  </div>
</body>
</html>
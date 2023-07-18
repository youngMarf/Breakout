<?php
if ($_COOKIE['user'] != '') {
    header('Location: ../game.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Authorization</title>
  <link rel="stylesheet" href="css/regstyle.css">
</head>
<body>
    <div class="nameOfGame">Breakout</div>
    <form action="php/auth.php" method="post">
      <input checked="" id="signin" name="action" type="radio" value="signin">
      <label for="signin">Sign in</label>
      <input id="signup" name="action" type="radio" value="signup">
      <label for="signup"><a href="registration.php">Sign up</a></label>
      <input id="reset" name="action" type="radio" value="reset">
      <label for="reset"><a href="guest.php">Guest</a></label>
      <div id="wrapper">
        <div id="arrow"></div>
        <input id="login" placeholder="Login" type="text" name="login">
        <input id="pass" placeholder="Password" type="password" name="password">
        <input id="repass" placeholder="Repeat password" type="password" name="repass">
      </div>

      <?php if (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
      <?php endif; ?>

      <button type="submit" >
        <span> 
          Continue
          <br>
          Sign in
          <br>
          Sign up
        </span>
      </button>
    </form>
</body>
</html>

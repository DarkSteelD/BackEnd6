<?php
function connect()
{
  $user = 'u52814'; // Заменить на ваш логин uXXXXX
  $pass = '2697434'; // Заменить на пароль, такой же, как от SSH
  $db = new PDO('mysql:host=localhost;dbname=u52814', $user, $pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  return $db;

}

header('Content-Type: text/html; charset=UTF-8');


session_start();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  
  //выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  print('Вы вошли под логином ');
  print((($_SESSION['login'])));
  print('. Вы можете выйти или войти под другим логином.');
  ?>
  <form action="" method="post">
    <input type="submit" name="exit" value="Выход" />
  </form>
  <?php
  $acc = False;
  if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['exit']))
  {
    $acc = True;  
    session_destroy();
  }
  if ($acc)
  {
    print('Вы вышли из аккаунта.');
    ?>
    <a href="index.php">На главную</a>
    <?php
  }
  
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="POST">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />

</form>

<?php
}

else if (!empty($_POST['login'])){
  
  $db = connect();
  $stmt = $db->prepare("SELECT l.login, l.parol FROM auth l");
  $base = False;
  if($stmt->execute()){

    foreach($stmt as $row){

      if ($row['login']==$_POST['login'] and $row['parol'] == md5($_POST['pass']))
        {
          $base = True;break;}
    }
  }
  
  if ($base)
  {
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['uid'] = rand(100000000, 9999999999999);
    header('Location: ./');
  }
  else{
    print('Пользователь не найден');
    ?>
    <br/>
    <a href="index.php">На главную</a>
    <?php
  }
  
  
}
?>
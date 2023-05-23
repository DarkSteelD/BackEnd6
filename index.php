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

$b = NAN;
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $messages = array();


  if (!empty($_COOKIE['save'])) {

    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);

    $messages[] = 'Спасибо, результаты сохранены.';
    if (!empty($_COOKIE['pass'])) {
        $messages[] = sprintf('Вы можете <a href="login.php">войти</a> с логином <strong>%s</strong>
        и паролем <strong>%s</strong> для изменения данных.',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass']));
    }
  }


  $errors = array();
  $errors['name'] = !empty($_COOKIE['name_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['gender'] = !empty($_COOKIE['gender_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['ability'] = !empty($_COOKIE['ability_error']);
  $errors['check'] = !empty($_COOKIE['check_error']);


  if ($errors['name']) {

    setcookie('name_error', '', 100000);

    $messages[] = '<div class="error">Заполните имя.</div>';
  }
  if ($errors['email']) {

    setcookie('email_error', '', 100000);

    $messages[] = '<div class="error">Заполните почту.</div>';
  }
  if ($errors['year']) {

    setcookie('year_error', '', 100000);

    $messages[] = '<div class="error">Заполните год.</div>';
  }
  if ($errors['gender']) {
    setcookie('gender_error', '', 100000);

    $messages[] = '<div class="error">Заполните пол.</div>';
  }
  if ($errors['limbs']) {

    setcookie('limbs_error', '', 100000);

    $messages[] = '<div class="error">Заполните число конечностей.</div>';
  }
  if ($errors['bio']) {

    setcookie('bio_error', '', 100000);

    $messages[] = '<div class="error">Заполните биографию.</div>';
  }
  if ($errors['ability']) {
    
    setcookie('ability_error', '', 100000);
    
    $messages[] = '<div class="error">Заполните суперспособность.</div>';
  }
  if ($errors['check']) {

    setcookie('check_error', '', 100000);

    $messages[] = '<div class="error">Согласитесь с контрактом.</div>';
  }

 
  
  $values = array();
  $values['name'] = empty($_COOKIE['name_value']) ? '' : $_COOKIE['name_value'];
  $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
  $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
  $values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
  $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
  $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
  $values['ability'] = empty($_COOKIE['ability_value']) ? array() : json_decode($_COOKIE['ability_value']);
  $values['check'] = empty($_COOKIE['check_value']) ? '' : $_COOKIE['check_value'];

  $k = 0;

  foreach($errors as $row)
  {if ($row) $k=$k+1;}
 
  if (!$k && !empty($_COOKIE[session_name()]) ) {

    session_start();
    
    $db = connect();
    $stmt = $db->prepare("SELECT l.login, z.namee, z.email, z.godrod, z.pol, z.konech, z.biogr FROM auth l,  application z WHERE l.login = :my_log_c and l.id_z = z.id_z");
    $stmt->bindParam(':my_log_c',  $_SESSION['login']);     


    if($stmt->execute()){
      foreach($stmt as $row){
  

        $values['name']= $row["namee"];
        $values['email'] = $row["email"];
        $values['year'] = $row["godrod"];
        $values['limbs'] = $row["konech"];
        $values['gender'] = $row["pol"];
        $values['bio'] = $row["biogr"];

      }
    }
    
    $sp = array();
    $stmt = $db->prepare("SELECT s.tip FROM auth l, application
 z, abilities s, sv sz WHERE l.login = '1876' and l.id_z = z.id_z and z.id_z = sz.id_z and sz.id_s = s.id_s");  
    if($stmt->execute()){
      foreach($stmt as $row){
        array_push($sp, $row['tip']);
      }
      $values['ability'] = $sp;

    }
    
    

    printf('Вход с логином %s ', $_SESSION['login']);
  }

  include('form.php');
}
else{
  
  $errors = FALSE;
if (empty($_POST['name']) || !preg_match('/^([a-zA-Z\'\-]+\s*|[а-яА-ЯёЁ\'\-]+\s*)$/u', $_POST['name'])) {
    
    setcookie('name_error', '1', time() + 24 * 60 * 60); $errors = TRUE; }
    else {

    setcookie('name_value', $_POST['name'], time() + 30 * 24 * 60 * 60);
  }

if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])) {
    
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
  }

if (empty($_POST['email'])  || !preg_match('/^((([0-9A-Za-z]{1}[-0-9A-z\.]{1,}[0-9A-Za-z]{1})|([0-9А-Яа-я]{1}[-0-9А-я\.]{1,}[0-9А-Яа-я]{1}))@([-A-Za-z]{1,}\.){1,2}[-A-Za-z]{2,})$/u', $_POST['email'])) { // На случай если нельхя библиотеки
    
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }

if (empty($_POST['gender']) || !($_POST['gender']=='1' || $_POST['gender']=='2')) {
    
    setcookie('gender_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('gender_value', $_POST['gender'], time() + 30 * 24 * 60 * 60);
  }

if (empty($_POST['limbs']) || !is_numeric($_POST['limbs']) || !($_POST['limbs']==2 || $_POST['limbs']==4))  {
    
    setcookie('limbs_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('limbs_value', $_POST['limbs'], time() + 30 * 24 * 60 * 60);
  }
  
if (empty($_POST['bio']) || !preg_match('/^([a-zA-Z\'\-]+\s*|[а-яА-ЯёЁ\'\-]+\s*)$/u', $_POST['bio'])) {
    
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }

  foreach ($_POST['ability'] as $ability) {
    if (!in_array($ability, ['intangibility', 'immortality', 'levitation'])) {
      setcookie('ability_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
      break;
    }
  }
  if (!empty($_POST['ability'])) {
    setcookie('ability_value', json_encode($_POST['ability']), time() + 24 * 60 * 60);
  }
  else{
    setcookie('ability_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }

  if (empty($_POST['check'])) {
    
    setcookie('check_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {

    setcookie('check_value', $_POST['check'], time() + 30 * 24 * 60 * 60);
  }


  if ($errors) {

    header('Location: index.php');
    exit();
  }
  else {

    setcookie('name_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('gender_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('ability_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('check_error', '', 100000);
    


    
  }
  if (!empty($_COOKIE[session_name()]) &&
      session_start() && !empty($_SESSION['login'])) {
      $db = connect();
      $stmt = $db->prepare("UPDATE application SET namee = :my_namee, email = :my_email, godrod = :my_godrod, pol = :my_pol, konech = :my_konech, biogr = :my_biogr WHERE id_z IN (SELECT id_z FROM auth WHERE login = :my_login)");
      $stmt->bindParam(':my_namee', $_POST['name']);
      $stmt->bindParam(':my_email', $_POST['email']);
      $stmt->bindParam(':my_godrod', $_POST['year']);
      $stmt->bindParam(':my_pol', $_POST['gender']);
      $stmt->bindParam(':my_konech', $_POST['limbs']);
      $stmt->bindParam(':my_biogr', $_POST['bio']);
      $stmt->bindParam(':my_login', $_SESSION['login']);
      $stmt->execute();
  }
  else {
    // Генерируем уникальный логин и пароль.
    
    $db = connect();
    $log_b = TRUE;
    $log_c = '123';
    while ($log_b)
    {
      try{

        $log_c = rand(1000, 9999);
        $sql = $db ->prepare ("SELECT * FROM auth WHERE login = $log_c");
        $log_b = False;
        if($result = $sql->execute()){
          foreach($result as $row){
            $log_b = True;
          }
        }       
      }
      catch(PDOException $e){
        print('Error : ' . $e->getMessage());
        exit();
      }
    }
    $token = rand(10000, 99999);
    $par = md5($token);
    
    // Сохраняем в Cookies.
    setcookie('login', $log_c);
    setcookie('pass', $token);
    try{

      $stmt = $db->prepare("INSERT INTO auth SET login = :my_log_c , parol = :my_par");
      $stmt->bindParam(':my_log_c', $log_c);
      $stmt->bindParam(':my_par', $par);
 
      $stmt->execute();
      $max_id_z1 = ($db->lastInsertId());
      
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
      exit();
    }
    
    // Сохранение в базу данных формы. Осталось сохранить логин и хеш пароль в бд

  
  try {
    
    $stmt = $db->prepare("INSERT INTO application SET namee = ?, email = ?, godrod = ?, pol = ?, konech = ?, biogr = ?");
    $stmt->execute([$_POST['name'], $_POST['email'], $_POST['year'], $_POST['gender'], $_POST['limbs'], $_POST['bio']]);
  //foreach ($_POST['abilities'] as $ability)
  //{
  //  print($ability);
  //}
    $max_id_z = ($db->lastInsertId());
    foreach ($_POST['superpowers'] as $ability) {
     print($max_id_z);
    //$stmt = $db->prepare("INSERT INTO sposob SET tip = ? ");
    //$stmt->execute([$_POST['$ability']]);
      $stmt = $db->prepare("INSERT INTO abilities SET tip = :mytip");
      $stmt->bindParam(':mytip', $ability);
      $stmt->execute();

      $max_id_s = ($db->lastInsertId());

      $stmt = $db->prepare("INSERT INTO sv (id_z, id_s) VALUES (:myidz, :myids)");
      $stmt->bindParam(':myids', $max_id_s);
      $stmt->bindParam(':myidz', $max_id_z);
      $stmt->execute();
    }
  
    
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  }
 
  
  setcookie('save', '1');
  header('Location: index.php');
}



?>й
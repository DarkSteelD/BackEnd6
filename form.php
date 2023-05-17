
<!DOCTYPE html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 

<html lang="ru">
  <head>
 <title>Superhero Registration Form</title>
     <link rel="stylesheet" href="style.css">
  </head>
<body>

    <?php
  if (!empty($messages)) {
    print('<div id="messages">');
    // Выводим все сообщения.
    foreach ($messages as $message) {
      print($message);
    }
    print('</div>');
  }

  // Далее выводим форму отмечая элементы с ошибками классом error
  // и задавая начальные значения элементов ранее сохраненными.
  ?>
       
      
      
      
    

<h1 style="text-align: center;"> Набор героев</h1>
  <form action="index.php" method="POST">
  <label for="name">Имя:</label>
  <input name="name" <?php if ($errors['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?>" />

  <label for="email">E-mail:</label>
  <input name="email" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>" type="email" />

  <label for="year">Год рождения:</label>
  <select id="year" name="year"  >
    <option  name="year" value = "2006" <?php print($errors['year'] ? 'class="error"' : '');?> <?php if ($values['year']=='2006') print 'checked';?>/> 2006 
    <option  name="year" value = "2005" <?php print($errors['year'] ? 'class="error"' : '');?> <?php if ($values['year']=='2005') print 'checked';?>/> 2005
    <option  name="year" value = "2004" <?php print($errors['year'] ? 'class="error"' : '');?> <?php if ($values['year']=='2004') print 'checked';?>/> 2004
    <!-- и так далее -->
  </select>

  <label>Пол:</label>
  <input type="radio" name="gender" value = "2" <?php print($errors['gender'] ? 'class="error"' : '');?> <?php if ($values['gender']=='2') print 'checked';?>/> Женский<br>
  <input type="radio" name="gender" value = "1" <?php print($errors['gender'] ? 'class="error"' : '');?> <?php if ($values['gender']=='1') print 'checked';?>/> Мужской <br>

  <label>Количество конечностей:</label>
  <input type="radio" name="limbs" value = "2" <?php print($errors['limbs'] ? 'class="error"' : '');?> <?php if ($values['limbs']=='2') print 'checked';?>/> 2<br>
  <input type="radio" name="limbs" value = "4" <?php print($errors['limbs'] ? 'class="error"' : '');?> <?php if ($values['limbs']=='4') print 'checked';?>/> 4<br>
  

  <label for="superpowers">Сверхспособности:</label>
  <select name="ability[]" multiple="multiple" <?php print($errors['ability'] ? 'class="error"' : '');?>>
      <option value="intangibility" <?php print(in_array('intangibility', $values['ability']) ? 'selected ="selected"' : '');?>>Прохождение сквозь стены</option>
      <option value="immortality" <?php print(in_array('immortality', $values['ability']) ? 'selected ="selected"' : '');?>>Бессмертие</option>
      <option value="levitation" <?php print(in_array('levitation', $values['ability']) ? 'selected ="selected"' : '');?>>Левитация</option>
   </select>
  

  <label for="bio">Биография:</label>
  <textarea name="bio" <?php print($errors['bio'] ? 'class="error"' : '');?> value = "<?php print $values['bio']; if (empty($values['bio'])) print('Empty Bio')?>"></textarea>


  <label for="contract"><input type="checkbox" id="contract" name="check"  >Я согласен с условиями контракта</label>
  <button type="submit">Отправить</button>
 
</form>


</body>

</html>
<?php
session_start();

if (isset($_SESSION['error'])) unset($_SESSION['error']);

if (isset($_POST['email'])) {
  //Validation start
  $correct_flag = true;

  //Check correctness of nick
  $username = $_POST['username'];

  //Check length of nick
  if ((strlen($username) < 3) || (strlen($username) > 20)) {
    $correct_flag = false;
    $_SESSION['e_username'] = "Nick musi posiadać od 3 do 20 znaków!";
  }

  if (ctype_alnum($username) == false) {
    $correct_flag = false;
    $_SESSION['e_username'] = "Nick może składać się tylko z liter i cyfr (bez polskich znaków)";
  }

  // Check correctness of email 
  $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $emailB = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

  if (($emailB != $email) || !$email) {
    $correct_flag = false;
    $_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
  } else if ($email) {
    require_once "database.php";
    $emailQuery = $db->prepare("SELECT email FROM users WHERE email=:email");
    $emailQuery->bindValue(':email', $email, PDO::PARAM_STR);
    $emailQuery->execute();
    if ($emailQuery->rowCount()) {
      $correct_flag = false;
      $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu e-mail!";
    }
  }

  //Check correctness of password
  $password1 = $_POST['password1'];
  $password2 = $_POST['password2'];
  if ((strlen($password1) < 8) || (strlen($password1) > 20)) {
    $correct_flag = false;
    $_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków!";
  }
  if ($password1 != $password2) {
    $correct_flag = false;
    $_SESSION['e_password'] = "Podane hasła nie są identyczne!";
  }

  $password_hash = password_hash($password1, PASSWORD_DEFAULT);


  //Save data
  $_SESSION['fr_username'] = $username;
  $_SESSION['fr_email'] = $email;
  $_SESSION['fr_password1'] = $password1;
  $_SESSION['fr_password2'] = $password2;

  //if (!isset($_POST['regulamin'])) {
  // $correct_flag = false;
  // $_SESSION['e_regulamin'] = '< style="color:red">Potwierdź akceptację regulaminu!<///span>';
  //}
  //Bot check
  $secretKey = "";

  $checkC = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);

  $answerCheckC = json_decode($checkC);

  if ($answerCheckC->success == false) {
    $correct_flag = false;
    $_SESSION['e_bot'] = '<span style="color:red">Potwierdź, że nie jesteś botem!</span>';
  }

  //No validation crashes
  if ($correct_flag) {
    require_once "database.php";
    //add new user to users
    $registerUser = $db->prepare("INSERT INTO users VALUES (NULL, :username, :password_hash, :email)");
    $registerUser->bindValue(':username', $username, PDO::PARAM_STR);
    $registerUser->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
    $registerUser->bindValue(':email', $email, PDO::PARAM_STR);
    $registerUser->execute();

    //get user id
    $queryNewUserId = $db->prepare("SELECT id FROM users WHERE email=:email");
    $queryNewUserId->bindValue(':email', $email, PDO::PARAM_STR);
    $queryNewUserId->execute();
    $newUserId = $queryNewUserId->fetch();
    //copy default categories
    $copyPayments = $db->prepare("INSERT INTO payment_methods_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM payment_methods_default");
    $copyPayments->bindValue(':newUserId', $newUserId['id'], PDO::PARAM_INT);
    $copyPayments->execute();
    $copyIncomes = $db->prepare("INSERT INTO incomes_category_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM incomes_category_default");
    $copyIncomes->bindValue(':newUserId', $newUserId['id'], PDO::PARAM_INT);
    $copyIncomes->execute();
    $copyExpenses = $db->prepare("INSERT INTO expenses_category_assigned_to_users (id, user_id, name) SELECT NULL, :newUserId, name FROM expenses_category_default");
    $copyExpenses->bindValue(':newUserId', $newUserId['id'], PDO::PARAM_INT);
    $copyExpenses->execute();

    $_SESSION['registrationOK']=true;

    header('Location: witajWaplikacji.php');
    exit();
  }
}


?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="finances, incomes, expenses, saldo, money" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="author" content="Arkadiusz Łątka" />
  <title>registration</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">




  <link rel="stylesheet" href="styleMPB.css">
  <script src="https://kit.fontawesome.com/efe29b5125.js" crossorigin="anonymous"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>

  <div class="container d-flex justify-content-center  ">

    <div class=" col-8  col-lg-4 ">

      <form class="register-box" method="post">

        <div class="form-title mt-5 ">
          <i class="fas fa-piggy-bank text-success"></i> MyPerso<span class="text-success">nal</span>Budget
        </div>

        <div class="form-outline mb-4 mt-5 ">
          <input type="text" id="form2Example1" name="username" class="form-control" required />
          <label class="form-label" for="form2Example1">Nazwa użytkownika </label>
        </div>
        <?php
        if (isset($_SESSION['e_nickname'])) {
          echo '<div class="error">' . $_SESSION['e_nickname'] . '</div>';
          unset($_SESSION['e_nickname']);
        }

        ?>


        <div class="form-outline mb-4">
          <input type="email" id="form2Example3" name="email" class="form-control" required />
          <label class="form-label" for="form2Example3">Adres email</label>
        </div>

        <?php
        if (isset($_SESSION['e_email'])) {
          echo '<div class ="error">' . $_SESSION['e_email'] . '</div>';
          unset($_SESSION['e_emial']);
        }

        ?>

        <div class="form-outline mb-4">
          <input type="password" id="form2Example4" name="password1" class="form-control" value="<?php
                                                                                              if (isset($_SESSION['f_password'])) {
                                                                                                echo $_SESSION['f_password'];
                                                                                                unset($_SESSION['f_password']);
                                                                                              }
                                                                                              ?>" required />
          <label class="form-label" for="form2Example4">Hasło</label>
        </div>

        <?php
        if (isset($_SESSION['e_password'])) {
          echo '<div class ="error">' . $_SESSION['e_password'] . '</div>';
          unset($_SESSION['e_password']);
        }

        ?>


        <div class="form-outline mb-4">
          <input type="password" id="form2Example4" name="password2" class="form-control" value="<?php
                                                                                              if (isset($_SESSION['f_password2'])) {
                                                                                                echo $_SESSION['f_password2'];
                                                                                                unset($_SESSION['f_password2']);
                                                                                              }
                                                                                              ?>" required />
          <label class="form-label" for="form2Example4"> Powtórz hasło</label>
        </div>
        
        <div class="g-recaptcha" data-sitekey=""></div>
        <?php
        if (isset($_SESSION['e_bot'])) {
          echo '<div class ="error">' . $_SESSION['e_bot'] . '</div>';
          unset($_SESSION['e_bot']);
        }

        ?>



        <!-- Submit button -->

        <button type="submit"  class="btn btn-success btn-block mb-4 mt-5">Zarejestruj się</button>





      </form>


    </div>



  </div>









  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>

</html>
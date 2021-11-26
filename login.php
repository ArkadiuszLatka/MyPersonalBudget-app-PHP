<?php
  session_start();
	
  if ((!isset($_POST['email'])) || (!isset($_POST['password'])))
  {
      header('Location: index.php');
      exit();
  }

  require_once 'database.php';
  
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  $email = filter_input(INPUT_POST, 'email');
  $password = filter_input(INPUT_POST, 'password');

  $userQuery = $db->prepare('SELECT * FROM users WHERE email = :email');
  $userQuery->bindValue(':email', $email, PDO::PARAM_STR);
  $userQuery->execute();
  //echo $userQuery->rowCount();

  if($userQuery->rowCount()>0)
  {
      $user = $userQuery->fetch();
      if (password_verify($password, $user['password']))
      {
          $_SESSION['logged_id']= $user['id'];
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
          unset($_SESSION['error']);
          header('Location: home.php');
      }
      else 
      {
          $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
          header('Location: index.php');
      }
  } else 
  {
      
      $_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
      header('Location: index.php');
      
  }
   
?>
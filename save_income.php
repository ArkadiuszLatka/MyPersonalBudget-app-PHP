<?php 

session_start();

if (!isset($_SESSION['logged_id'])) {
  header('Location: index.php');
  exit();
}

if (isset($_POST['income_amount'])) {
  $good = true;
  $amount = $_POST['income_amount'];
  $date = $_POST['income_date'];
  $category = $_POST['category'];
  $comment = $_POST['income_comment'];

  if ($amount <= 0) {
    $good = false;
    $_SESSION['e_amount'] = "Wpisz pozytywną wartość";
  }
  $comment = htmlentities($comment, ENT_QUOTES, "UTF-8");

  require_once 'database.php';

  if ($good == true) {
    $queryIncome = $db->prepare("INSERT INTO incomes (id, user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
          VALUES (NULL, :user_id, :category_id, :amount, :date, :comment)");
    $queryIncome->bindValue(':user_id', $_SESSION['logged_id'], PDO::PARAM_INT);
    $queryIncome->bindValue(':category_id', $category, PDO::PARAM_INT);
    $queryIncome->bindValue(':amount', $amount, PDO::PARAM_STR);
    $queryIncome->bindValue(':date', $date, PDO::PARAM_STR);
    $queryIncome->bindValue(':comment', $comment, PDO::PARAM_STR);
    $queryIncome->execute();

    $queryCategory = $db->prepare("SELECT name FROM incomes_category_assigned_to_users WHERE id=:category_id");
    $queryCategory->bindValue(':category_id', $category, PDO::PARAM_INT);
    $queryCategory->execute();
    $categoryName = $queryCategory->fetchAll();

    header('Location: home.php');
	exit();

  }
}

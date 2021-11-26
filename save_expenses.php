<?php 
 session_start();

  
 if (!isset($_SESSION['logged_id'])) {
 header('Location: index.php');
 exit();
}

   if (isset($_POST['expense_amount'])){

   $operation = true;
   $expense_amount = $_POST['expense_amount'];
   $expense_date = $_POST['expense_date'];
   $payment_method = $_POST['payment_method'];
   $expense_category = $_POST['expense_category'];
   $expense_comment = $_POST['expense_comment'];

   if($expense_amount <= 0)
     {
         $operation =false;
         $_SESSION['err_amount']='<span style="color:red">Wpisz dodatnią wartość</span>';
     }

     require_once 'database.php';

   if($operation == true){

         $query = $db->prepare('INSERT INTO expenses VALUES (NULL, :userId,:category_id,:payment_method_id, :amount, :date,  :comment)');
         $query->bindValue(':userId', $_SESSION['logged_id'], PDO::PARAM_INT);
         $query->bindValue(':category_id', $expense_category, PDO::PARAM_STR);
         $query->bindValue(':payment_method_id', $payment_method, PDO::PARAM_STR);
         $query->bindValue(':amount', $expense_amount, PDO::PARAM_STR);
         $query->bindValue(':date', $expense_date, PDO::PARAM_STR);
         $query->bindValue(':comment', $expense_comment, PDO::PARAM_STR);
         $query->execute();



         $queryPayCategory = $db->prepare("SELECT name FROM payment_methods_assigned_to_users WHERE id=:payment_method_id");
         $queryPayCategory->bindValue(':payment_method_id', $payment_method, PDO::PARAM_INT);
         $queryPayCategory->execute();
         $categoryPayName = $queryPayCategory->fetch();

         
         $queryCategory = $db->prepare("SELECT name FROM expenses_category_assigned_to_users WHERE id=:category_id");
         $queryCategory->bindValue(':category_id', $expense_category, PDO::PARAM_INT);
         $queryCategory->execute();
         $categoryName = $queryCategory->fetch();

         header('Location: home.php');
	      exit();


   }

 }
  
  
  
  




?>

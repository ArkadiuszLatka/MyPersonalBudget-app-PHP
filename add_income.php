<?php

session_start();

if (!isset($_SESSION['logged_id'])) {
  header('Location: index.php');
  exit();
}

require_once "database.php";
	
	$queryIncomeCategories=$db->prepare("SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id=:id");
	$queryIncomeCategories->bindValue(':id',$_SESSION['logged_id'],PDO::PARAM_INT);
	$queryIncomeCategories->execute();
	$incomeCategories=$queryIncomeCategories->fetchAll();

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="keywords" content="finances, incomes, expenses, saldo, money" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="author" content="Arkadiusz Łątka" />
  <title>add-income</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />

  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

  <link rel="stylesheet" href="styleMPB.css" />

  <script src="https://kit.fontawesome.com/efe29b5125.js" crossorigin="anonymous"></script>

  <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css" />
</head>


<body>
  <div class="container">
    <form class="well form-horizontal mt-5 bg-white" action="save_income.php" method="post" id="contact_form">
      <div class="form-title">
        <i class="fas fa-piggy-bank text-success"></i> MyPerso<span class="text-success">nal</span>Budget
      </div>
      <fieldset>
        <!-- Form Name -->
        <legend>
          <h3 class="text-center"><b>Dodaj wydatek</b></h3>
        </legend>
        <br />

        <!-- Text input Data-->

        <div class="form-group">
          <label class="col-md-4 control-label">Data</label>
          <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
              <span class="input-group-addon"><i class="far fa-calendar"></i></span>
              <input type="date" id="date" name="income_date" min="2000-01-01" value="2000-01-02" class="form-control text-center p-1"required>
            </div>
          </div>
        </div>

        <div class="form-group">
          <label class="col-md-4 control-label">Rodzaj przychodu</label>
          <div class="col-md-4 selectContainer">
            <div class="input-group">
              <span class="input-group-addon"><i class="fas fa-money-check-alt"></i></span>

              <select name="category" class="form-control selectpicker text-center p-1 "required>
                <?php
                      foreach ($incomeCategories as $incCaty) {
                        echo '<option value ="' . $incCaty['id'] . '"> ' . $incCaty['name'] . ' </option>';
                      }
                ?>

              </select>
            </div>
          </div>
        </div>

        <!-- Text amount-->

        <div class="form-group">
          <label class="col-md-4 control-label">Kwota</label>
          <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
              <span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span>
              <input name="income_amount" placeholder="Kwota" class="form-control text-center p-1" type="number" step="0.01" min="0.01" max="999999.99" required />
            </div>
            
            <?php
							if (isset($_SESSION['e_amount']))
							{
								echo '<div class="error">'.$_SESSION['e_amount'].'</div>';
								unset($_SESSION['e_amount']);
							}
					?>
          </div>
        </div>

        <!-- Textarea-->

        <div class="form-group">
          <label class="col-md-4 control-label">Komentarz</label>
          <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
              <span class="input-group-addon"><i class="fas fa-comment-alt"></i></span>
              <textarea name="income_comment" class="form-control" rows="4" cols="30" maxlength="40"></textarea>
            </div>
          </div>
        </div>

        <!-- Button -->
        <div class="row mb-4">
          <div class="col-7 d-flex justify-content-end">
            <div class="form-group">
              <label class="row-md-4 control-label"></label>

              <button type="submit" name="add" value="Submit" class="btn btn-success">Dodaj</button>

              <button type="reset" href='home.php' class="btn btn-warning">Anuluj</button>
              <a href="home.php"><div class="d-inline button mx-2"><i class="icon-cancel"></i>Wróć</div></a>
              
              
            </div>
          </div>
        </div>
      </fieldset>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

  <script src="jquery.js"></script>
  <script src="add-items.js"></script>
</body>

</html>
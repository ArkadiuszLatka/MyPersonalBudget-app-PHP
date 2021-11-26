<?php


session_start();

if (!isset($_SESSION['logged_id'])) {
    header('Location: index.php');
    exit();
}



$logged_id = $_SESSION['logged_id'];
$selectMonth = $_POST['first_date'] && $_POST['second_date'];
require_once 'database.php';

if (isset($_POST['first_date']) && isset($_POST['second_date'])) {
    $correct_flag = true;
    $first_date = date('Y-m-d', strtotime($_POST['first_date']));
    $second_date = date('Y-m-d', strtotime($_POST['second_date']));

    $incomeAllQuery = $db->query("SELECT incomes.user_id,incomes.amount,
    incomes_category_assigned_to_users.name, 
    incomes.amount,
    incomes.date_of_income,
    incomes.income_comment
    FROM incomes 
    INNER JOIN incomes_category_assigned_to_users ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id 
    WHERE incomes.user_id ='$logged_id' AND date_of_income >='$first_date' AND date_of_income <= '$second_date' ORDER BY date_of_income DESC");
$incomesAllCat = $incomeAllQuery->fetchAll();


    $incomeCatQuery = $db->query("SELECT incomes.user_id,
        incomes_category_assigned_to_users.name, 
        incomes.amount,
        SUM(incomes.amount),
        incomes.date_of_income,
        income_comment
        FROM incomes 
        INNER JOIN incomes_category_assigned_to_users ON incomes.income_category_assigned_to_user_id = incomes_category_assigned_to_users.id 
        WHERE incomes.user_id ='$logged_id' AND date_of_income >='$first_date' AND date_of_income <= '$second_date' GROUP BY income_category_assigned_to_user_id");
    $incomesCat = $incomeCatQuery->fetchAll();

    $expenseAllQuery = $db->query("SELECT expenses.user_id,
    expenses_category_assigned_to_users.name,
    payment_methods_assigned_to_users.name AS paymethod,
    expenses.amount,
    expenses.date_of_expense,
    expenses.expense_comment
    FROM expenses
    INNER JOIN expenses_category_assigned_to_users ON 
    expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
    INNER JOIN payment_methods_assigned_to_users ON
    expenses.payment_method_assigned_to_user_id =payment_methods_assigned_to_users.id

    WHERE expenses.user_id ='$logged_id'AND date_of_expense >='$first_date'AND date_of_expense <= '$second_date' ORDER BY date_of_expense DESC");
    $expensesAll = $expenseAllQuery->fetchAll();
    $expenseQuery = $db->query("SELECT expenses.user_id,
        expenses_category_assigned_to_users.name,
        payment_methods_assigned_to_users.name AS paymethod,
        expenses.amount,
        SUM(expenses.amount),
        expenses.date_of_expense,
        expense_comment
        FROM expenses
        INNER JOIN expenses_category_assigned_to_users ON 
        expenses.expense_category_assigned_to_user_id = expenses_category_assigned_to_users.id
        INNER JOIN payment_methods_assigned_to_users ON
        expenses.payment_method_assigned_to_user_id =payment_methods_assigned_to_users.id

        WHERE expenses.user_id ='$logged_id'AND date_of_expense >='$first_date'AND date_of_expense <= '$second_date' GROUP BY expense_category_assigned_to_user_id");
    $expenses = $expenseQuery->fetchAll();



    $IncomesSumQuery = $db->query("SELECT SUM(amount) FROM incomes WHERE user_id='$logged_id' && date_of_income >='$first_date' AND date_of_income <= '$second_date' ");
    $IncomesSum = $IncomesSumQuery->fetchAll();

    $ExpensesSumQuery = $db->query("SELECT SUM(amount) FROM expenses WHERE user_id='$logged_id' && date_of_expense>='$first_date'AND date_of_expense <= '$second_date'  ");
    $ExpensesSum = $ExpensesSumQuery->fetchAll();

    //SALDO
    foreach ($ExpensesSum as $Expenses) {
        foreach ($IncomesSum as $Incomes) {
            $IncomesAndExpensesSubstraction = $Incomes['SUM(amount)'] - $Expenses['SUM(amount)'];
        }
    }
    if ($first_date >$second_date) {
        $correct_flag = false;
        $_SESSION['e_first_date'] = "Pierwsza data nie powinna być późniejsza od drugiej daty !";
      }
}

if ($selectMonth) {

    foreach ($incomesCat as $income) {
  
      if ($income > 0) {
  
        $do_wykresu[] = "['" . $income['name'] . "', " . $income['SUM(incomes.amount)'] . "]";
      } else {
        $do_wykresu[] = "['" . $income['name'] . "', " . $income['name'] . "]";
        
      }
      $data_for_chart = implode(",", $do_wykresu);
    }
  }
  
  
  if ($selectMonth) {
  
  
  
    foreach ($expenses as $expense) {
  
      if ($expense > 0) {
  
        $do_wykresu_expense[] = "['" . $expense['name'] . "', " . $expense['SUM(expenses.amount)'] . "]";
      } else {
        $do_wykresu_expense[] = "['" . $expense['name'] . "', " . $expense['name'] . "]";
      }
  
      $data_for_chart_expense = implode(",", $do_wykresu_expense);
    }
  }





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="keywords" content="finances, incomes, expenses, saldo, money" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Arkadiusz Łątka" />
    <title>balance</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous" />

    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />

    <link rel="stylesheet" href="styleMPB.css" />

    <script src="https://kit.fontawesome.com/efe29b5125.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="//cdn.jsdelivr.net/jquery.bootstrapvalidator/0.5.2/css/bootstrapValidator.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>



</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">

        <div class="container">


            <a class="navbar-brand" href="#"><i class="fas fa-piggy-bank text-success"></i> MyPerso<span class="text-success">nal</span>Budget</a>



            <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars px-4"></i>
            </button>
            <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
                <div class="navbar-nav ml-auto">
                    <a class="nav-link active px-3" aria-current="page" href="add_income.php">Dodaj przychód</a>
                    <a class="nav-link text-success px-3" href="add_expense.php">Dodaj Wydatek</a>
                    <a class="nav-link text-dark px-3" href="">Bilans</a>
                    <a class="nav-link text-success px-3" href="#">Opcje</a>
                    <a class="nav-link text-dark px-3 " href="logout.php" aria-disabled="true">Wyloguj się</a>

                </div>
            </div>
        </div>


    </nav>
    <nav method="post">



        <div class="container ">
            <!-- Choose period of balance -->

            <div class="dropdown ml-auto px-5 mt-4">
                <button class=" btn btn-secondary dropdown-toggle bg-success con margin20 offset-lg-10 offset-md-9 offset-sm-8 offset-7 col-lg-2 col-md-3 col-sm-4 col-5 btn btn-balance dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Zakres
                </button>
                <div class="dropdown-menu col-lg-2 col-md-3 col-sm-4 col-5" aria-labelledby="dropdownMenu2">
                    <form action="balance.php" method="post">
                        <button class="dropdown-item" type="submit" name="balance_period" value="current_month">Bieżący miesiąc</button>
                        <button class="dropdown-item" type="submit" name="balance_period" value="last_month">Poprzedni miesiąc</button>
                        <button class="dropdown-item" type="submit" name="balance_period" value="current_year">Bieżący rok</button>
                        <button type="button" class="dropdown-item" data-toggle="modal" data-target="#exampleModal" name="balance_period" value="select_period">
                            Niestandardowy
                        </button>
                    </form>

                </div>
            </div>

        </div>
        </div>
    </nav>

    <main>
        <section>
            <div class="container">
                <div class="panel-info mt-4">
                    <div class="mt-4">
                        <?php
                        if ($selectMonth) {

                            echo '<h3>Bilans za wybrany okres: ';
                            echo $IncomesAndExpensesSubstraction . ' zł';
                            echo '</h3>';
                            if ($IncomesAndExpensesSubstraction > 0) {
                                echo '<p style="color: green;" >Dobrze ci idzie z finansami - trzymaj tak dalej!<p>';
                            } else if ($IncomesAndExpensesSubstraction < 0) {
                                echo '<p style="color: red;">To nie jest twój najlepszy okres - musisz się bardziej postarać<p>';
                            } else {
                                echo '<p style="color: blue;">Niby stabilnie ale zawsze może być lepiej<p>';
                            }
                        }
                        ?>

                    </div>
                </div>
            </div>
        </section>
        <!-- incomes -->

        <section class="border-bottom">

      <div class="container  ">

        <div class="incomes-info">
          <div class="row">
            <div class="col-md-12 main-table p-3">

            <div class="row">
                <h3 class="text-dark px-5 mb-5">Przychody</h3>




                <table class="table table-hover table-light">

                  <thead>
                    <tr>
                      <th class="bg-success" scope="col">Id</th>
                      <th class="bg-success" scope="col">Kategoria</th>
                      <th class="bg-success" scope="col">Kwota</th>
                      <th class="bg-success" scope="col">Waluta</th>
                      <th class="bg-success" scope="col">Data</th>
                      <th class="bg-success" scope="col">Komentarz</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    if ($currentMonth='current_month' || $currentMonth= 'last_month' || $currentMonth= 'current_year') {
                      $number = 1;
                      foreach ($incomesAllCat as $income) {

                        echo " <tr>
                                    <th scope=" . "row" . ">{$number}</th>
                                    <td>{$income['name']}</td>
                                    <td >{$income['amount']}</td>
                                    <td>PLN</td>
                                    <td>{$income['date_of_income']}</td>
                                    <td>{$income['income_comment']}</td>
                                    
                                    
                                  </tr>";

                        $number++;
                      }
                    }

                    ?>

                  </tbody>
                </table>
                

              </div>
              <div class="row">
                <h3 class="text-dark px-5 mb-5">Podsumowanie</h3>




                <table class="table table-hover table-light">

                  <thead>
                    <tr>
                      <th class="bg-success" scope="col">Id</th>
                      <th class="bg-success" scope="col">Kategoria</th>
                      <th class="bg-success" scope="col">Kwota</th>
                      <th class="bg-success" scope="col">Waluta</th>
                      
                    </tr>
                  </thead>
                  <tbody>
                    <?php

                    if ($selectMonth) {
                      $number = 1;
                      foreach ($incomesCat as $income) {

                        echo " <tr>
                                    <th scope=" . "row" . ">{$number}</th>
                                    <td>{$income['name']}</td>
                                    <td>{$income['SUM(incomes.amount)']}</td>
                                    <td>PLN</td>
                                   
                                    
                                    
                                  </tr>";

                        $number++;
                      }
                    }

                    ?>

                  </tbody>
                </table>
                

              </div>
              <div class=" pie-chart d-flex justify-content-center">

                <div id="piechart_3d"></div>
              </div>

            </div>
          </div>
        </div>
    </section>
        <!-- expense -->
        <section class="border-bottom">
            <div class="container ">
                <div class="expense-info">
                    <div class="row">
                        <div class="col-md-12 justify-content-sm-center main-table p-3">
                        <div class="row">
                <h3 class="text-dark px-5 mb-5">Wydatki</h3>

                

              <table class="table table-hover table-light">
                <thead>
                  <tr>
                    <th class="bg-danger" scope="col"></th>
                    <th class="bg-danger" scope="col">Kategoria</th>
                    <th class="bg-danger" scope="col">Metoda płatności</th>
                    <th class="bg-danger" scope="col">Kwota</th>
                    <th class="bg-danger" scope="col">Waluta</th>
                    <th class="bg-danger" scope="col">Data</th>
                    <th class="bg-danger" scope="col">Komentarz</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  if ( 'current_month' ||  'last_month' ||  'current_year') {
                    $number = 1;
                    foreach ($expensesAll as $expense) {

                      echo " <tr>
                                    <th scope=" . "row" . ">{$number}</th>
                                    <td>{$expense['name']}</td>
                                    <td>{$expense['paymethod']}</td>
                                    <td>{$expense['amount']}</td>
                                    <td>PLN</td>
                                    <td>{$expense['date_of_expense']}</td>
                                    <td>{$expense['expense_comment']}</td>
                                  
                                  </tr>";
                      $number++;
                    }
                  }

                  ?>
                </tbody>
              </table>

              
              
            </div>
                            <div class="row">
                                <h3 class="text-dark px-5 mb-5">Wydatki</h3>

                                

                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th class="bg-danger" scope="col"></th>
                                        <th class="bg-danger" scope="col">Kategoria</th>
                                        <th class="bg-danger" scope="col">Metoda płatności</th>
                                        <th class="bg-danger" scope="col">Kwota</th>
                                        <th class="bg-danger" scope="col">Waluta</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    if ($selectMonth) {
                                        $number = 1;
                                        foreach ($expenses as $expense) {

                                            echo " <tr>
                                    <th scope=" . "row" . ">{$number}</th>
                                    <td>{$expense['name']}</td>
                                    <td>{$expense['paymethod']}</td>
                                    <td>{$expense['SUM(expenses.amount)']}</td>
                                    <td>PLN</td>
                                    
                                  </tr>";
                                            $number++;
                                        }
                                    }

                                    ?>
                                </tbody>
                            </table>
                            
                        </div>
                        <div class="pie-chart d-flex justify-content-center">
                            <div id="1piechart_3d"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bd-footer">
            <!-- Copyright -->
            <div class="text-center p-3">© 2021 Copyright:</div>
            <!-- Copyright -->
        </footer>

        
        <!-- Modal -->
        <div class="modal fade post-modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form class="d-flex justify-content-around" action="balance_modal.php" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Niestandardowy okres</h5>

                        </div>
                        <div class="modal-body">
                            <section>



                                <div class="form-group  " name="balance_period" value="select_period">
                                    <label for="first_date">Od: </label>
                                    <?php 
                     if (isset($_SESSION['e_first_date'])) {
                     echo '<div class ="error">' . $_SESSION['e_first_date'] . '</div>';
                    unset($_SESSION['e_first_date']); }
                                        ?>
                                    <input class="form-control-modal px-5" type="date" min="2000-01-01" name="first_date" </div>
                                    
                                    <div class="form-group">
                                        <label for="second_date">Do: </label>
                                        <input class="form-control-modal px-5" type="date" min="2000-01-01" name="second_date" </div>

                            </section>
                        </div>
                        <div class="modal-footer">


                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">
                                    Zamknij
                                </button>
                                <button type="submit" class="btn btn-success" value="Submit">Akceptuj</button>
                            </div>
                        </div>
                    </form>




                </div>
            </div>
        </div>





    </main>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.1', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Marka'); // dodawanie kolumny
            data.addColumn('number', 'Ilość');
            data.addRows([
                <?php echo $data_for_chart_expense; ?>
            ]);

            // Set chart options
            var options = {
                'backgroundColor': '#F5F5F5',
                'width': 600,
                'height': 500,
                tooltip: {
                    text: 'percentage'
                },
                'is3D': true
            };

            // Instantiate and draw our chart, passing in some options.
            var table = new google.visualization.PieChart(document.getElementById('1piechart_3d'));
            table.draw(data, options);
        }
    </script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.1', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Marka'); // dodawanie kolumny
            data.addColumn('number', 'Ilość');
            data.addRows([
                <?php echo $data_for_chart; ?>
            ]);

            // Set chart options
            var options = {
                'backgroundColor': '#F5F5F5',
                'width': 450,
                'height': 450,

                tooltip: {
                    text: 'percentage'
                },
                'is2D': true

            };

            // Instantiate and draw our chart, passing in some options.
            var table = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            table.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        // Load the Visualization API and the piechart package.
        google.load('visualization', '1.1', {
            'packages': ['corechart']
        });

        // Set a callback to run when the Google Visualization API is loaded.
        google.setOnLoadCallback(drawChart);

        // Callback that creates and populates a data table,
        // instantiates the pie chart, passes in the data and
        // draws it.
        function drawChart() {

            // Create the data table.
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Marka'); // dodawanie kolumny
            data.addColumn('number', 'Ilość');
            data.addRows([
                <?php
                if ($data_for_chart > 0) {
                    echo $data_for_chart;
                } else {

                    echo "brak danych";
                }

                ?>


            ]);

            // Set chart options
            var options = {
                'backgroundColor': '#F5F5F5',
                'width': 600,
                'height': 500,
                tooltip: {
                    text: 'percentage'
                },
                'is3D': true
            };

            // Instantiate and draw our chart, passing in some options.
            var table = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            table.draw(data, options);
        }
    </script>
</body>

</html>
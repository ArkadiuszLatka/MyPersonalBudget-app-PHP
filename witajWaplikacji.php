<?php

session_start();

if ((!isset($_SESSION['registrationOK']))) {
    header('Location: index.php');
    exit();
} 
//require_once 'database.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="finances, incomes, expenses, saldo, money" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="Arkadiusz Łątka" />
    <title>index</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">




    <link rel="stylesheet" href="styleMPB.css">
    <script src="https://kit.fontawesome.com/efe29b5125.js" crossorigin="anonymous"></script>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">

        <div class="container">

            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="fas fa-piggy-bank text-success"></i> MyPerso<span class="text-success">nal</span>Budget</a>




            </div>
        </div>

    </nav>

    <div class="container d-flex  ">

        <div class="row  ">
            <div class="col">
                <div class="jumbotron">
                    <div class="container ">

                        <h1 class="Site-title display-4 text-success text-justify"> Dziękujemy za rejestracje w aplikacji.
                            Zaloguj się aby przejść na swoje konto.</h1>


                    </div>
                </div>
            </div>
            <div class="col-md-4 col-md-offset-4 ">
                <div class="panel panel-default mt-6">
                    <div class="panel-heading ">
                        <h3 class="panel-title text-center">Logowanie</h3>
                    </div>
                    <div class="panel-body">
                        <form action="login.php" method="post">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="yourmail@gmail.com" name="email" type="email">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="hasło" name="password" type="password" value="password">
                                </div>
                                <?php
                                if (isset($_SESSION['blad']))    echo $_SESSION['blad'];
                                ?>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me"> Zapamiętaj mnie
                                    </label>
                                </div>
                                <input class="btn btn-lg btn-success btn-block" type="submit" value="Zaloguj się">
                            </fieldset>
                        </form>

                        <hr />
                        <h4 class="text-center">lub</h4>
                        <form action="registration.php">
                            <input class="btn btn-lg bg-registration btn-block" type="submit" value="Zarejestruj się">
                        </form>

                    </div>
                </div>
            </div>

        </div>

    </div>



    <footer class="bd-footer">
        <!-- Copyright -->
        <div class="text-center p-3">
            © 2021 Copyright:

        </div>
        <!-- Copyright -->
    </footer>















    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>

</html>
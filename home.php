
<?php

session_start();
if (!isset($_SESSION['logged_id']))
	{
		header('Location: index.php');
		exit();
	}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="finances, incomes, expenses, saldo, money" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="author" content="Arkadiusz Łątka"/>		
    <title>home</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> 




    <link rel="stylesheet" href="styleMPB.css">
    <script src="https://kit.fontawesome.com/efe29b5125.js" crossorigin="anonymous"></script>
</head>
<body>


    <nav class="navbar navbar-expand-lg navbar-light bg-light py-3">

        <div class="container-fluid">

            
                <a class="navbar-brand" href="#"><i class="fas fa-piggy-bank text-success"></i> MyPerso<span
                    class="text-success">nal</span>Budget</a>
                    

                    
                    <button class="navbar-toggler border-0" type="button" data-toggle="collapse"
                    data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <i class="fas fa-bars px-4"></i>
                </button>
                <div class="collapse navbar-collapse " id="navbarNavAltMarkup">
                  <div class="navbar-nav ml-auto">
                    <a class="nav-link active px-3" aria-current="page" href="add_income.php">Dodaj przychód</a>
                    <a class="nav-link text-success px-3" href="add_expense.php">Dodaj Wydatek</a>
                    <a class="nav-link text-dark px-3" href="balance.php">Bilans</a>
                    <a class="nav-link text-success px-3" href="#">Opcje</a>
                    <a class="nav-link text-dark px-3 " href="logout.php" aria-disabled="true">Wyloguj się</a>
                    
                  </div>
                </div>
              </div>
        

      </nav>
      <main>
       
        


        <section class="py-5 projects mb-5 ">

          <div class="container text-center py-5 ">
            <p class="display-3 text-success  ">Witaj w aplikacji</p>
                             
            <div class="login_info">
			<?php 
      echo "Witaj ".$_SESSION['username']."!"; 
      ?>
		</div>
           
          </div>

          <div class="container ">
            <div class="row">

              <div class="col-md-6 col-lg-4 d-flex justify-content-between">

                <div class="card mb-3 " style="width: 40rem; height: 30rem;">
                  <div class="card-body text-center"><i class="fas fa-book-open mb-5 display-3"></i>
                      <h1 class="card-title text-success">Planuj budżet </h1>
                    
                        <p class="card-text">za pomocą kilku kliknięć. Sprawdzaj postępy na czytelnych wykresach</p>
                    
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4 d-flex justify-content-between ">

                <div class="card mb-3 " style="width: 40rem; height: 30rem;">
                  <div class="card-body text-center"><i class="fas fa-chart-bar  mb-5 text-success display-3"></i>
                      <h1 class="card-title">Analizuj finanse  </h1>
                    
                        <p class="card-text">i wyciągaj wnioski na podstawie raportów oraz zestawień, które możesz dowolnie zapisywać</p>
                    
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-lg-4 d-flex justify-content-between ">

                <div class="card mb-3   " style="width: 40rem; height: 30rem;">
                  <div class="card-body text-center "><i class="fas fa-edit  mb-5 display-3"></i>
                      <h1 class="card-title text-success">Spisuj wydatki  </h1>
                    
                        <p class="card-text">szybko i wygodnie. Gdzie chcesz i kiedy chcesz. Zajmie to tylko chwilę</p>
                    
                  </div>
                </div>
              </div>

              
            </div>
          </div>
        </section>


        <footer class="bd-footer">
          <!-- Copyright -->
          <div class="text-center p-3">
            © 2021 Copyright:
            
          </div>
          <!-- Copyright -->
        </footer>






      </main>

      











    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>  
</body>
</html>
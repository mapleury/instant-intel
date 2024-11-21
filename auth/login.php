
<?php


session_start();
include '../db/connect.php';
include '../function/auth.php';


if(isset ($_POST['login'])) {
   $username = $_POST['username'];
   $password = $_POST['password'];


   $login = login($username, $password, $conn);


   //periksa apakah login berhasil
   if(isset($login['role'])) {
       //menyimpan data user kedalam session
       $_SESSION['username']   = $username;
       $_SESSION['role']   = $login['role'];
       $_SESSION['user_id']   = $login['user_id'];


       //arahkan halaman sesuai role
       if($login['role'] == 2) {
           header('Location: ../petugas/dashboard-petugas.php');
       }elseif ($login['role'] == 1) {
           header('Location: ../masyarakat/dashboard-masyarakat.php');
       }
   } else {
       echo "Login Gagal, cek password dan username anda!";
   }
}


?>






<!DOCTYPE html>
<html lang="en">


<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login InstantIntel</title>
   <link rel="shortcut icon" href="../assets/flag-line.png" type="image/x-icon">
   <link rel="stylesheet" href="../bootstrap/css/sb-admin-2.css">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>


<body style="background: linear-gradient(to bottom, #79E4FF, #00CCFF);">



   <div class="container">


       <!-- Outer Row -->
       <div class="row justify-content-center align-items-center" style="height: 100vh;">

<div class="col-xl-6 col-lg-8 col-md-10">
    <div class="card o-hidden shadow-lg" style="border-radius: 50px;">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-12 text-center"  style="display: flex; justify-content: center; align-items: center; height: 100%; margin-top: 35px;">
                    <dotlottie-player src="https://lottie.host/9b81795d-d6e8-4049-a38e-795cabac6f5a/2xWrXBobK0.json" background="transparent" speed="1" style="width: 300px; height: 300px; display: flex; justify-content: center; align-items: center;" loop autoplay></dotlottie-player>
                </div>
                <div class="col-12">
                    <div class="p-4">
                        <div class="text-center">
                            <h1 class="h3 text-gray-900 mb-2">Welcome</h1>
                            <h3 class="h4 text-gray-900 mb-4">to Instant Intel!</h3>
                        </div>
                        <form class="user" method="POST">
                            <div class="form-group">
                                <label class="lable-control" for="username">Username</label>
                                <input type="text" name="username" class="form-control form-control-user" placeholder="Enter Username">
                            </div>
                            <div class="form-group">
                                <label class="lable-control" for="password">Password</label>
                                <input type="password" name="password" class="form-control form-control-user" placeholder="Enter Password">
                            </div>
                            <button name="login" type="submit" class="btn btn-user btn-block" style="background-color: #F89151; color: #ffff;">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>


   </div>


   <!-- Bootstrap core JavaScript-->
   <script src="vendor/jquery/jquery.min.js"></script>
   <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


   <!-- Core plugin JavaScript-->
   <script src="vendor/jquery-easing/jquery.easing.min.js"></script>


   <!-- Custom scripts for all pages-->
   <script src="js/sb-admin-2.min.js"></script>


   <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
   <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>


</body>


</html>
<?php

ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

session_start();


include '../db/connect.php';
include '../function/pengaduan.php';


//cek yang login bener bener masyarakat atau bukan (biar hanya masyarakat yang bisa akses)
if($_SESSION['role'] != 1) {
   header('Location: ../auth/login/php');
   exit();
}


//proses input data
if(isset($_POST['submit'])) {
   //validasi input
   $report = $_POST['message'] ?? '';


   //persiapan input image
   if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
       $image = $_FILES['image']['name'];
       $tmp_name = $_FILES['image']['tmp_name'];
       $target_dir = '../uploads/';


       move_uploaded_file($tmp_name, $target_dir . $image);
   } else {
       $image = '';
   }


   //proses inout ke database
   if(!empty($report)) {
       $status = buat_pengaduan($_SESSION['user_id'], $report, $image, $conn);


       if($status) {
           header("Location:dashboard-masyarakat.php");
       } else {
           echo "terjadi kesalahan, coba masukan data lagi";
       }
   } else {
       echo "masukan data terlebih dahulu!";
   }
}


?>


<!DOCTYPE html>
<html lang="en">


<head>


   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">


   <title>Instant Intel</title>


   <!-- Custom fonts for this template-->
   <link href="../bootstrap/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link
       href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
       rel="stylesheet">


   <!-- Custom styles for this template-->
   <link href="../bootstrap/css/sb-admin-2.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

</head>


<body id="page-top">


   <!-- Page Wrapper -->
   <div id="wrapper">


       <!-- Sidebar -->
       <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #52ADF8;">


           <!-- Sidebar - Brand -->
           <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
               <div class="sidebar-brand-icon rotate-n-15">
                   <i class="fas fa-laugh-wink"></i>
               </div>
               <div class="sidebar-brand-text mx-1">InstanntIntel</div>
           </a>


           <!-- Divider -->
           <hr class="sidebar-divider my-0">


           <!-- Nav Item - Dashboard -->
           <li class="nav-item active">
               <a class="nav-link" href="dashboard-masyarakat.php">
                   <i class="fas fa-fw fa-tachometer-alt"></i>
                   <span>Dashboard</span></a>
           </li>


           <!-- Divider -->
           <hr class="sidebar-divider">


           <!-- Heading -->
           <div class="sidebar-heading">
               Interface
           </div>


           <!-- Nav Item - Charts -->
           <li class="nav-item">
               <a class="nav-link" href="laporan.php">
                   <i class="fas fa-fw fa-chart-area"></i>
                   <span>Buat Laporan</span>
               </a>
           </li>


           <!-- Divider -->
           <hr class="sidebar-divider d-none d-md-block">


       </ul>
       <!-- End of Sidebar -->


       <!-- Content Wrapper -->
       <div id="content-wrapper" class="d-flex flex-column">


           <!-- Main Content -->
           <div id="content">


               <!-- Topbar -->
               <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


                   <!-- Sidebar Toggle (Topbar) -->
                   <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                       <i class="fa fa-bars"></i>
                   </button>


                   <!-- Topbar Navbar -->
                   <ul class="navbar-nav ml-auto">


                       <!-- Nav Item - User Information -->
                       <li class="nav-item dropdown no-arrow">
                           <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                               <img class="img-profile rounded-circle"
                                   src="../bootstrap/img/undraw_profile.svg">
                           </a>
                           <!-- Dropdown - User Information -->
                           <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                               aria-labelledby="userDropdown">
                               <a class="dropdown-item" href="../auth/logout.php" data-toggle="modal" data-target="#logoutModal">
                                   <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                   Logout
                               </a>
                           </div>
                       </li>


                   </ul>


               </nav>
               <!-- End of Topbar -->


               <!-- Begin Page Content -->
               <div class="container-fluid">


                   <!-- Page Heading -->
                   <div class="align-items-center justify-content-between mb-4">
                       <h1 class="h3 mb-2 text-gray-800">Add your Report 📝💥</h1>
                   </div>


                   <!-- Content Row -->


                   <div class="row">


                       <!-- Area Chart -->
                       <div class="col-xl-12 col-lg-7">
                           <div class="card shadow mb-4">
                               <!-- Card Header - Dropdown -->
                               <div
                                   class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                   <h6 class="m-0 font-weight-reguler text-gray-900">Fill out all of the data</h6>
                               </div>
                               <!-- Card Body -->
                               <div class="card-body">
                                   <form method="POST" enctype="multipart/form-data">
                                       <div class="form-group">
                                           <label class="lable-control" for="username">Report Description</label>
                                           <textarea name="message" type="message" class="form-control" placeholder="Silahkan masukkan laporan"></textarea>
                                       </div>
                                       <div class="form-group">
                                           <label class="lable-control" for="password">Document</label>
                                           <input type="file" name="image" class="form-control" placeholder="Enter Password">
                                       </div>
                                       <button type="submit" name="submit" class="btn" style="background-color: #F89151; color: #ffff;">Submit</button>
                                   </form>
                               </div>
                           </div>
                       </div>
                   </div>




               </div>
               <!-- /.container-fluid -->


           </div>
           <!-- End of Main Content -->


           <!-- Footer -->
           <footer class="sticky-footer bg-white">
               <div class="container my-auto">
                   <div class="copyright text-center my-auto">
                       <span>Copyright &copy; Your Website 2021</span>
                   </div>
               </div>
           </footer>
           <!-- End of Footer -->


       </div>
       <!-- End of Content Wrapper -->


   </div>
   <!-- End of Page Wrapper -->


   <!-- Bootstrap core JavaScript-->
   <script src="../bootstrap/vendor/jquery/jquery.min.js"></script>
   <script src="../bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>


   <!-- Core plugin JavaScript-->
   <script src="../bootstrap/vendor/jquery-easing/jquery.easing.min.js"></script>


   <!-- Custom scripts for all pages-->
   <script src="../bootstrap/js/sb-admin-2.min.js"></script>


   <!-- Page level plugins -->
   <script src="../bootstrap/vendor/chart.js/Chart.min.js"></script>


   <!-- Page level custom scripts -->
   <script src="../bootstrap/js/demo/chart-area-demo.js"></script>
   <script src="../bootstrap/js/demo/chart-pie-demo.js"></script>


</body>


</html>


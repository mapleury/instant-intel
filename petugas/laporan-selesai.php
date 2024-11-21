<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include '../db/connect.php';
include '../function/pengaduan.php';

// Memeriksa apakah pengguna adalah admin
if ($_SESSION['role'] != 2) {
    header('Location: ../auth/login.php');
    exit;
}

// Mendapatkan pengaduan dengan status "selesai"

$report_with_feedback = get_reports_with_feedback_by_status($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="">
   <meta name="author" content="">
   <title>SICepu</title>
   <link href="../bootstrap/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
   <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
   <link href="../bootstrap/css/sb-admin-2.css" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body id="page-top">
   <div id="wrapper">
       
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color: #52ADF8;" id="accordionSidebar">


            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-1">InstantIntel</div>
            </a>


            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard-petugas.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider">


            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            
           <!-- Nav Item - Tables -->
           <li class="nav-item">
               <a class="nav-link" href="laporan-selesai.php">
                   <i class="fas fa-fw fa-table"></i>
                   <span>Finished Reports</span></a>
           </li>
           <!-- Nav Item - Tables -->
           <li class="nav-item">
               <a class="nav-link" href="laporan-proses.php">
                   <i class="fas fa-fw fa-table"></i>
                   <span>Processed Reports</span></a>
           </li>


           <!-- Divider -->
           <hr class="sidebar-divider d-none d-md-block">


       </ul>
       <!-- End of Sidebar -->

       <div id="content-wrapper" class="d-flex flex-column">
           <div id="content">
               <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                   <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                       <i class="fa fa-bars"></i>
                   </button>
                   <ul class="navbar-nav ml-auto">
                       <li class="nav-item dropdown no-arrow">
                           <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?= $_SESSION['username'] ?></span>
                               <img class="img-profile rounded-circle" src="../bootstrap/img/undraw_profile.svg">
                           </a>
                           <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                               <a class="dropdown-item" href="../auth/logout.php" data-toggle="modal" data-target="#logoutModal">
                                   <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                   Logout
                               </a>
                           </div>
                       </li>
                   </ul>
               </nav>

               <div class="container-fluid">
                   <h1 class="h3 mb-2 text-gray-800">Tables of responded complaints</h1>
                   <p class="mb-4">A finnished resolved complaints staff already responded to</p>

                   <div class="card shadow mb-4">
                       <div class="card-header py-3">
                           <h6 class="m-0 font-weight-bold text-primary"></h6>
                       </div>
                       <div class="card-body">
                           <div class="table-responsive">
                               <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                   <thead>
                                       <tr>
                                           <th>Created at</th>
                                           <th>Pelapor</th>
                                           <th>Laporan</th>
                                           <th>Gambar</th>
                                           <th>Feedback</th>
                                           <th>Taggal Feedback</th>
                                       </tr>
                                   </thead>
                                   <?php foreach($report_with_feedback as $row): ?>
                                   <tbody>
                                       <tr>
                                           <td><?= date('d-m-Y', strtotime($row['report_date']) ) ?></td>
                                           <td><?= $row['pelapor']?></td>
                                           <td><?= $row['message']?></td>
                                           <td>
                                               <?php if(!empty($row['image'])):?>
                                                   <img src="../uploads/<?= $row['image']?>" alt="" width="300px">
                                               <?php else: ?>
                                                   <span>tidak ada gambar</span>
                                               <?php endif ?>
                                           </td>
                                           <td><?= htmlspecialchars($row['feedback']) ?></td>
                                           <td><?= date('d-m-Y', strtotime($row['feedback_date']) )?></td>

                                           </tr>
                                   </tbody>
                                   <?php endforeach ?>
                               </table>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <a class="scroll-to-top rounded" href="#page-top">
       <i class="fas fa-angle-up"></i>
   </a>

   <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                   <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">×</span>
                   </button>
               </div>
               <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
               <div class="modal-footer">
                   <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                   <a class="btn btn-primary" href="../auth/logout.php">Logout</a>
               </div>
           </div>
       </div>
   </div>

   <script src="../bootstrap/vendor/jquery/jquery.min.js"></script>
   <script src="../bootstrap/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="../bootstrap/vendor/jquery-easing/jquery.easing.min.js"></script>
   <script src="../bootstrap/js/sb-admin-2.min.js"></script>
   <script src="../bootstrap/vendor/chart.js/Chart.min.js"></script>
   <script src="../bootstrap/js/demo/chart-area-demo.js"></script>
   <script src="../bootstrap/js/demo/chart-pie-demo.js"></script>
</body>
</html>

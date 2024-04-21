<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="/">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskVelocity | Admin</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- JQVMap -->
        <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/adminlte.min.css">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
        <!-- summernote -->
        <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
        <!-- Select 2 -->
        <link rel="stylesheet" href="plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    </head>
<body class="hold-transition sidebar-mini layout-fixed <?php echo isset($_COOKIE['dark'])? 'dark-mode':'';?>">
        <div class="wrapper"> 
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>      
                </ul>

                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <!-- Navbar Search -->
                    <li class="nav-item">
          <?php echo isset($_COOKIE['dark'])? 
                                '<a class="nav-link" href="/light" role="button">
                <i class="text fas fa-sun"></i>
        </a>      
      </li>' :
                                '   <a class="nav-link" href="/dark" role="button">
          <i class="text fas fa-moon"></i>
        </a>      
      </li>' ;
                        ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo isset($_SESSION['usuario']['id_usuario']) ? '/logout' : ''; ?>" role="button">
                            <?php if (isset($_SESSION['usuario']['id_usuario'])) { ?>
                                <i class="text-danger fas fa-sign-out-alt"></i> 
                                <?php
                            } else {
                                ?>
                                <i class="text-blue fas fa-sign-in-alt"></i> 

                                <?php
                            }
                            ?>
                        </a>        
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                            <i class="fas fa-expand-arrows-alt"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.navbar -->

            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="/" class="brand-link">
                    <img src="assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text font-weight-light">TaskVelocity</span>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user panel (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="assets/img/users/avatar-<?php echo $_SESSION['usuario']['id_usuario'] . "."; ?><?php echo file_exists("assets/img/users/avatar-" . $_SESSION['usuario']["id_usuario"] . ".png") ? "png" : "jpg" ?>"" class="img-circle elevation-2" alt="Avatar Usuario <?php echo $_SESSION['usuario']['username'] ?>">
                        </div>
                        <div class="info">
                            <a href="<?php echo isset($_SESSION['usuario']['id_usuario']) ? '/admin/profile/' . $_SESSION['usuario']['id_usuario'] : ''; ?>"
                               class="d-block"> <?php echo isset($_SESSION['usuario']['username']) ? $_SESSION['usuario']['username'] : '<i>Sin establecer</i>'; ?> </a>    
                        </div>
                    </div>

                    <?php
      include $_ENV['folder.views'].'/admin/templates/left-menu.view.php';
                    ?>
                </div>
                <!-- /.sidebar -->
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
            <h1 class="m-0"><?php             
            echo isset($titulo) ? $titulo : '' ?></h1>
                            </div><!-- /.col -->
                            <?php
          if(isset($breadcrumb) && is_array($breadcrumb)){
                                ?>          
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <?php
                
                foreach($breadcrumb as $b){
                                            ?>
                                            <li class="breadcrumb-item"><?php echo $b; ?></li>             
              <?php
                }?>
                                    </ol>
                                </div><!-- /.col -->
                                <?php
                            }
                            ?>
                        </div><!-- /.row -->
                    </div><!-- /.container-fluid -->
                </div>
                <!-- /.content-header -->

                <section class="content">
                    <div class="container-fluid"><!--Fin header -->
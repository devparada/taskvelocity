<!DOCTYPE html>
<html lang="es">
    <head>
        <base href="/">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TaskVelocity | Login</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- icheck bootstrap -->
        <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="assets/css/admin/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="/"><b>TaskVelocity</b></a>
            </div>
            <!-- /.login-logo -->  
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Iniciar sesión</p>
                    <form action="/login" method="post">
                        <div class="input-group mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo isset($email) ? $email : ''; ?>">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <input type="password" class="form-control" name="password" placeholder="Contraseña">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($loginError)) { ?>
                            <p class="login-box-msg text-danger"><?php echo $loginError ?></p> 
                        <?php } ?>
                        <div class="text-center">
                            <p>¿No tienes una cuenta? <a href="/register">Crea una cuenta</a></p>
                        </div>
                        <div class="row">
                            <div class="col-12">            
                                <button type="submit" class="btn btn-primary btn-block float-right">Iniciar sesión</button>
                            </div>
                            <!-- /.col -->
                        </div>
                    </form>
                    <!-- /.login-card-body -->
                </div>
            </div>
            <!-- /.login-box -->

            <!-- jQuery -->
            <script src="plugins/jquery/jquery.min.js"></script>
            <!-- Bootstrap 4 -->
            <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!-- AdminLTE App -->
            <script src="dist/js/adminlte.min.js"></script>
    </body>
</html>

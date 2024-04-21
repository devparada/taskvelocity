<div class="row">           
    <?php
    if (isset($informacion)) {
        ?>
        <div class="col-12">
            <div class="alert alert-<?php echo $informacion["estado"]; ?>">
                <p><?php echo $informacion["texto"]; ?></p>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <div class="col-6">
                    <h6 class="m-0 installfont-weight-bold text-primary">Usuarios</h6>
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/usuarios/add/" class="btn btn-success ml-1 float-right"> Nuevo usuario <i class="fas fa-plus-circle"></i></a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
                <?php
                if (count($usuarios) > 0) {
                    ?>
                    <table id="tabladatos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Avatar</th>
                                <th>Nombre</th>
                                <th>Email</th>                          
                                <th>Rol</th>                            
                                <th>Última conexión</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($usuarios as $u) {
                                ?>
                                <tr>
                                    <td><img src="assets/img/users/avatar-<?php echo $u['id_usuario'] . "."; ?><?php echo file_exists("assets/img/users/avatar-" . $u["id_usuario"] . ".png") ? "png" : "jpg" ?>" alt="Avatar usuario <?php echo $u['id_usuario'] ?>"></td>
                                    <td><?php echo $u['username']; ?></td>
                                    <td><a href="mailto: <?php echo $u['email']; ?>"><?php echo $u['email']; ?></a></td>
                                    <td><?php echo $u['nombre_rol']; ?></td>
                                    <td><?php echo (!empty($u['fecha_login'])) ? $u['fecha_login'] : "No se ha conectado aún" ?></td>
                                    <td>
                                        <a href="/usuarios/view/<?php echo $u['id_usuario']; ?>" class="btn btn-default ml-1"><i class="fas fa-eye"></i></a>
                                        <a href="/usuarios/edit/<?php echo $u['id_usuario']; ?>" class="btn btn-primary ml-1"><i class="fas fa-edit"></i></a>
                                        <a href="/usuarios/delete/<?php echo $u['id_usuario']; ?>" class="btn btn-danger ml-1"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de usuarios: <?php echo count($usuarios); ?></p>
                        </tfoot>
                    </table>
                    <?php
                } else {
                    ?>
                    <p class="text-danger">No existen usuarios que cumplan los requisitos.</p>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>                        
</div>

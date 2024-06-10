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
                    <p class="m-0 font-weight-bold">Usuarios</p>
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/admin/usuarios/add/" class="btn btn-success ml-1 float-right"> Nuevo usuario <i class="fas fa-user-plus"></i></a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
                <form action="/admin/usuarios/?pagina=<?php echo $paginaActual ?>" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="mb-2">
                                <label for="id_rol">Roles</label>
                                <select name="id_rol" id="id_rol" class="form-control select2">
                                    <option value="">Selecciona un rol</option>
                                    <?php foreach ($roles as $rol) { ?>
                                        <option value="<?php echo $rol['id_rol']; ?>" <?php echo (isset($_GET['id_rol']) && $rol['id_rol'] == $_GET['id_rol']) ? 'selected' : ''; ?>><?php echo $rol['nombre_rol'] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <input type="hidden" name="page" id="page" value="<?php echo isset($_GET["pagina"]) ? $_GET["pagina"] : "1"; ?>">
                        </div>
                    </div>
                    <div class="col-12 text-right">                     
                        <a href="/admin/usuarios/?pagina=<?php echo $paginaActual ?>" value="" name="reiniciar" class="btn btn-danger">Reiniciar</a>
                        <input type="submit" value="Enviar" class="btn btn-info ml-2"/>
                    </div>
                </form>
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
                                    <td><img src="assets/img/usuarios/avatar-<?php echo $u['id_usuario'] ?>" alt="Avatar usuario <?php echo $u['id_usuario'] ?>" class="img-circle imagenes-usuarios"></td>
                                    <td><?php echo $u['username']; ?></td>
                                    <td><a href="mailto: <?php echo $u['email']; ?>"><?php echo $u['email']; ?></a></td>
                                    <td><?php echo $u['nombre_rol']; ?></td>
                                    <td><?php echo (!empty($u['fecha_login'])) ? $u['fecha_login'] : "No se ha conectado aún" ?></td>
                                    <td>
                                        <a href="/admin/usuarios/view/<?php echo $u['id_usuario']; ?>" class="btn btn-info ml-1"><i class="fas fa-user-alt"></i></a>
                                        <a href="/admin/usuarios/edit/<?php echo $u['id_usuario']; ?>" class="btn btn-warning ml-1"><i class="fas fa-user-edit"></i></a>
                                        <?php if ($u["id_usuario"] != 1) { ?>
                                            <a href="/admin/usuarios/delete/<?php echo $u['id_usuario']; ?>" class="btn btn-danger ml-1"><i class="fas fa-user-minus"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de usuarios: <?php echo$contarUsuarios ?></p>
                        </tfoot>
                    </table>
                    <div class="card-footer">
                        <nav aria-label="Navegacion por paginas">
                            <ul class="pagination justify-content-center">
                                <?php
                                if ($paginaActual >= 1) {
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/tareas?pagina=0" aria-label="Primero">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span class="sr-only">Primero</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/tareas?pagina=<?php echo ($paginaActual - 1) ?>" aria-label="Anterior">
                                            <span aria-hidden="true">&lt;</span>
                                            <span class="sr-only">Anterior</span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>

                                <li class="page-item active"><a class="page-link"><?php echo ++$paginaActual ?></a></li>
                                <?php
                                if ($maxPagina > $paginaActual) {
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/tareas?pagina=<?php echo ($paginaActual) ?>" aria-label="Siguiente">
                                            <span aria-hidden="true">&gt;</span>
                                            <span class="sr-only">Siguiente</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/tareas?pagina=<?php echo $maxPagina - 1 ?>" aria-label="Último">
                                            <span aria-hidden="true">&raquo;</span>
                                            <span class="sr-only">Último</span>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </nav>
                    </div>
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

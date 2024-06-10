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
                    <p class="m-0 font-weight-bold">Tareas</p>
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/admin/tareas/add/" class="btn btn-success ml-1 float-right">Nueva tarea <i class="fas fa-tasks"></i></a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
                <form action="/admin/tareas/?pagina=<?php echo $paginaActual ?>" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="mb-2">
                                <label for="id_usuario">Propietario</label>
                                <select name="id_usuario" id="id_usuario" class="form-control select2">
                                    <option value="">Selecciona un usuario</option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <option value="<?php echo $usuario['id_usuario']; ?>" <?php echo (isset($_GET['id_usuario']) && $usuario['id_usuario'] == $_GET['id_usuario']) ? 'selected' : ''; ?>><?php echo $usuario['username'] ?></option>
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
                        <a href="/admin/logs/?pagina=<?php echo $paginaActual ?>" value="" name="reiniciar" class="btn btn-danger">Reiniciar</a>
                        <input type="submit" value="Enviar" class="btn btn-info ml-2"/>
                    </div>
                </form>
                <?php
                if (count($tareas) > 0) {
                    ?>
                    <table id="tabladatos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Proyecto asociado</th>
                                <th>Propietario</th>
                                <th>Color</th>
                                <th>Usuarios asociados</th>                    
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($tareas as $t) {
                                ?>
                                <tr>
                                    <td><?php echo $t['nombre_tarea']; ?></td>
                                    <td><?php echo $t['nombre_proyecto']; ?></a></td>
                                    <td><?php echo $t['username']; ?></td>  
                                    <td><?php echo $t['nombre_color']; ?></td>
                                    <td><?php
                                        for ($index = 0; $index < count($t["nombresUsuarios"]); $index++) {
                                            if ($index < 3) {
                                                echo $t["nombresUsuarios"][$index] . " ";
                                            } else if ($index == 3) {
                                                echo "...";
                                            }
                                        }
                                        ?></td>  
                                    <td>
                                        <a href="/admin/tareas/view/<?php echo $t['id_tarea']; ?>" class="btn btn-info ml-1"><i class="fas fa-sticky-note"></i></a>
                                        <a href="/admin/tareas/edit/<?php echo $t['id_tarea']; ?>" class="btn btn-warning ml-1"><i class="fas fa-pen"></i></a>
                                        <a href="/admin/tareas/delete/<?php echo $t['id_tarea']; ?>" class="btn btn-danger ml-1"><i class="fas fa-box-tissue"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de tareas: <?php echo $contarTareas; ?></p>
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
                    <p class="text-danger">No existen tareas que cumplan los requisitos.</p>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>                        
</div>

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
                    <p class="m-0 font-weight-bold">Proyectos</p>
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/admin/proyectos/add/" class="btn btn-success ml-1 float-right"> Nuevo proyecto <i class="fas fa-folder-plus"></i></a>
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
                        <a href="/admin/proyectos/?pagina=<?php echo $paginaActual ?>" value="" name="reiniciar" class="btn btn-danger">Reiniciar</a>
                        <input type="submit" value="Enviar" class="btn btn-info ml-2"/>
                    </div>
                </form>
                <?php
                if (count($proyectos) > 0) {
                    ?>
                    <table id="tabladatos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Propietario</th>
                                <th>Fecha límite</th>
                                <th>Usuarios asociados</th>                    
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($proyectos as $p) {
                                ?>
                                <tr>
                                    <td><?php echo $p['nombre_proyecto']; ?></td>
                                    <td><?php echo (!empty($p["descripcion_proyecto"])) ? $p["descripcion_proyecto"] : "No tiene descripción" ?></td>
                                    <td><?php echo $p['username']; ?></td>  
                                    <td><?php echo isset($p["fecha_limite_proyecto"]) ? $p["fecha_limite_proyecto"] : "No tiene fecha límite" ?></td>
                                    <td><?php
                                        for ($index = 0; $index < count($p["nombresUsuarios"]); $index++) {
                                            if ($index < 3) {
                                                echo $p["nombresUsuarios"][$index] . " ";
                                            } else if ($index == 3) {
                                                echo "...";
                                            }
                                        }
                                        ?></td>
                                    <td>
                                        <a href="/admin/proyectos/view/<?php echo $p['id_proyecto']; ?>" class="btn btn-info ml-1"><i class="fas fa-folder-open"></i></a>
                                        <a href="/admin/proyectos/edit/<?php echo $p['id_proyecto']; ?>" class="btn btn-warning ml-1"><i class="fas fa-edit"></i></a>
                                        <a href="/admin/proyectos/delete/<?php echo $p['id_proyecto']; ?>" class="btn btn-danger ml-1"><i class="fas fa-folder-minus"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de proyectos: <?php echo $contarProyectos ?></p>
                        </tfoot>
                    </table>
                <div class="card-footer">
                        <nav aria-label="Navegacion por paginas">
                            <ul class="pagination justify-content-center">
                                <?php
                                if ($paginaActual >= 1) {
                                    ?>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/proyectos?pagina=0" aria-label="Primero">
                                            <span aria-hidden="true">&laquo;</span>
                                            <span class="sr-only">Primero</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/proyectos?pagina=<?php echo ($paginaActual - 1) ?>" aria-label="Anterior">
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
                                        <a class="page-link" href="/admin/proyectos?pagina=<?php echo ($paginaActual) ?>" aria-label="Siguiente">
                                            <span aria-hidden="true">&gt;</span>
                                            <span class="sr-only">Siguiente</span>
                                        </a>
                                    </li>
                                    <li class="page-item">
                                        <a class="page-link" href="/admin/proyectos?pagina=<?php echo $maxPagina - 1 ?>" aria-label="Último">
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
                    <p class="text-danger">No existen proyectos que cumplan los requisitos.</p>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>                        
</div>

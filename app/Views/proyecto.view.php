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
                    <h6 class="m-0 installfont-weight-bold text-primary">Tareas</h6>
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/proyectos/add/" class="btn btn-success ml-1 float-right"> Nuevo proyecto <i class="fas fa-plus-circle"></i></a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
                <?php
                if (count($proyectos) > 0) {
                    ?>
                    <table id="tabladatos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripcion</th>
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
                                    <td><?php echo $p['descripcion_proyecto']; ?></a></td>
                                    <td><?php echo $p['username']; ?></td>  
                                    <td><?php echo $p['fecha_limite_proyecto']; ?></td>
                                    <td><?php
                                        for ($index = 0; $index < count($p["nombresUsuarios"]); $index++) {
                                            if ($index < 3) {
                                                echo $p["nombresUsuarios"][$index];
                                            } else if ($index == 3) {
                                                echo "...";
                                            }
                                        }
                                        ?></td>
                                    <td>
                                        <a href="/proyectos/view/<?php echo $p['id_proyecto']; ?>" class="btn btn-default ml-1"><i class="fas fa-eye"></i></a>
                                        <a href="/proyectos/edit/<?php echo $p['id_proyecto']; ?>" class="btn btn-primary ml-1"><i class="fas fa-edit"></i></a>
                                        <a href="/proyectos/delete/<?php echo $p['id_proyecto']; ?>" class="btn btn-danger ml-1"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de proyectos: <?php echo count($proyectos); ?></p>
                        </tfoot>
                    </table>
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

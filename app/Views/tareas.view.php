<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <div class="col-6">
                    <h6 class="m-0 installfont-weight-bold text-primary">Tareas</h6>
                </div>
                <div class="col-6">
                    <div class="m-0 font-weight-bold justify-content-end">
                        <a href="/usuarios/add/" class="btn btn-success ml-1 float-right"> Nueva tarea <i class="fas fa-plus-circle"></i></a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
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
                                    <td><?php echo $t["nombresUsuarios"]; ?></td>
                                    <td>
                                        <a href="/usuarios/view/<?php echo $t['id_tarea']; ?>" class="btn btn-default ml-1"><i class="fas fa-eye"></i></a>
                                        <a href="/usuarios/edit/<?php echo $t['id_tarea']; ?>" class="btn btn-primary ml-1"><i class="fas fa-edit"></i></a>
                                        <a href="/usuarios/delete/<?php echo $t['id_tarea']; ?>" class="btn btn-danger ml-1"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de tareas: <?php echo count($tareas); ?></p>
                        </tfoot>
                    </table>
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

<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <div class="col-6">
                    <h6 class="m-0 installfont-weight-bold text-primary">Logs</h6>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
                <?php
                if (count($logs) > 0) {
                    ?>
                    <table id="tabladatos" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($logs as $l) {
                                ?>
                                <tr>
                                    <td><?php echo $l['username']; ?></td>  
                                    <td><?php echo $l["fecha_log"] ?></td>
                                    <td><?php echo $l["asunto"]; ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                        <tfoot>
                        <p>Total de logs: <?php echo count($logs); ?></p>
                        </tfoot>
                    </table>
                    <?php
                } else {
                    ?>
                    <p class="text-danger">No existen registros que cumplan los requisitos.</p>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>                        
</div>

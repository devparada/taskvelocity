<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg col-12">
        <!-- small box -->
        <div class="small-box bg-purple">
            <div class="inner">
                <h2><?php echo $numProyectos; ?></h2>
                <p>Proyectos</p>
            </div>
            <div class="icon">
                <i class="ion ion-folder"></i>
            </div>
            <a href="/admin/proyectos" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg col-12">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h2><?php echo $numTareas; ?></h2>
                <p>Tareas</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="/admin/tareas" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg col-12">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h2><?php echo $numUsuarios; ?></h2>
                <p>Usuarios</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="/admin/usuarios" class="small-box-footer">Más información <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>       
    <!-- ./col -->
</div>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <h3 class="card-title">Logs (los últimos 6 logs)</h3>
                <div class="card-tools">
                    <a href="/admin/logs">
                        <button class="btn btn-primary mr-2"><i class="fas fa-cogs nav-icon"></i> Ver todos</button> 
                    </a>        
                    <!--
                    <a href="#" class="btn btn-success">
                        <i class="fas fa-download"></i>
                    </a>-->
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs as $l) { ?>
                            <tr>
                                <td><?php echo $l["username"] ?></td>
                                <td><?php echo $l["fecha_log"] ?></td>
                                <td><?php echo $l["asunto"] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
<!-- Main row -->
<!-- /.row (main row) -->


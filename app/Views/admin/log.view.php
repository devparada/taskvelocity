<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <div class="col-6">
                    <p class="m-0 font-weight-bold">Logs</p>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body table-responsive" id="card_table">
                <form action="/admin/logs/?pagina=<?php echo $paginaActual ?>" method="get">
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="mb-2">
                                <label for="id_usuario">Usuario</label>
                                <select name="id_usuario" id="id_usuario" class="form-control select2" data-placeholder="Usuario">
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
                        <input type="submit" value="Enviar" class="btn btn-success ml-2"/>
                    </div>
                </form>
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
                        <p>Total de logs: <?php echo $contarLogs ?></p>
                        </tfoot>
                    </table>
                </div>
                <div class="card-footer">
                    <nav aria-label="Navegacion por paginas">
                        <ul class="pagination justify-content-center">
                            <?php
                            if ($paginaActual >= 1) {
                                ?>
                                <li class="page-item">
                                    <a class="page-link" href="/admin/logs?pagina=0" aria-label="Primero">
                                        <span aria-hidden="true">&laquo;</span>
                                        <span class="sr-only">Primero</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="/admin/logs?pagina=<?php echo ($paginaActual - 1) ?>" aria-label="Anterior">
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
                                    <a class="page-link" href="/admin/logs?pagina=<?php echo ($paginaActual) ?>" aria-label="Siguiente">
                                        <span aria-hidden="true">&gt;</span>
                                        <span class="sr-only">Siguiente</span>
                                    </a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link" href="/admin/logs?pagina=<?php echo $maxPagina ?>" aria-label="Último">
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
                <p class="text-danger">No existen registros que cumplan los requisitos.</p>
                <?php
            }
            ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>

    </script>
</div>

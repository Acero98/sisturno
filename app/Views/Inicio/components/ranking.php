<div class="col-lg-6">

    <div class="card dashboard-card shadow-sm h-100">

        <div class="card-body p-4">

            <h5 class="fw-bold mb-4">

                <i class="fa-solid fa-trophy text-warning me-2"></i>

                Top Empleados

                <span style="opacity: 0.5;">
                    (Con más atenciones)
                </span>

            </h5>


            <div class="table-responsive">

                <table class="table table-modern align-middle">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Operador</th>
                            <th class="text-center">
                                Tickets
                            </th>
                            <th class="text-center">
                                Tiempo Promedio
                            </th>
                        </tr>

                    </thead>


                    <tbody>

                        <?php if ($rankingOperadores && $rankingOperadores->num_rows > 0): ?>

                            <?php $posicion = 1; ?>

                            <?php while ($row = $rankingOperadores->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <strong>
                                            #<?= $posicion++ ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nombre_user']) ?>
                                    </td>

                                    <td class="text-center">

                                        <strong>
                                            <?= number_format($row['total_atenciones']) ?>
                                        </strong>

                                    </td>

                                    <td class="text-center">

                                        <span class="badge bg-primary">
                                            <?= round($row['promedio_atencion']) ?> min
                                        </span>

                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>

                                <td colspan="4"
                                    class="text-center py-4 text-muted">

                                    No existen operadores con
                                    atenciones finalizadas hoy.

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>
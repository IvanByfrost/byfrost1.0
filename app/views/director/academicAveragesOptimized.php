<?php
// CONSULTA OPTIMIZADA PARA BALDUR.SQL
$query = "
SELECT 
    act.term_name AS academic_term_name, 
    ROUND(AVG(ss.score), 2) AS promedio,
    COUNT(ss.score_id) AS total_calificaciones,
    MIN(ss.score) AS nota_minima,
    MAX(ss.score) AS nota_maxima,
    COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS aprobados,
    COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS reprobados,
    ROUND(
        (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
    ) AS tasa_aprobacion
FROM student_scores ss
JOIN activities a ON ss.activity_id = a.activity_id
JOIN academic_terms act ON a.term_id = act.term_id
WHERE ss.score IS NOT NULL
GROUP BY act.term_id, act.term_name
ORDER BY act.term_id ASC
";

$result = mysqli_query($conn, $query);
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-chart-line"></i> Promedios por Período Académico</h3>
        <p class="text-muted">Análisis de rendimiento por período</p>
    </div>
    
    <div class="card-body">
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Período</th>
                            <th>Promedio</th>
                            <th>Total Calificaciones</th>
                            <th>Rango</th>
                            <th>Aprobación</th>
                            <th>Tasa Aprobación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td>
                                    <strong><?= htmlspecialchars($row['academic_term_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $row['promedio'] >= 3.0 ? 'success' : 'warning' ?> fs-6">
                                        <?= $row['promedio'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted"><?= $row['total_calificaciones'] ?> calificaciones</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= $row['nota_minima'] ?> - <?= $row['nota_maxima'] ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="text-success"><?= $row['aprobados'] ?></span> / 
                                    <span class="text-danger"><?= $row['reprobados'] ?></span>
                                </td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-<?= $row['tasa_aprobacion'] >= 80 ? 'success' : ($row['tasa_aprobacion'] >= 60 ? 'warning' : 'danger') ?>" 
                                             style="width: <?= $row['tasa_aprobacion'] ?>%">
                                            <?= $row['tasa_aprobacion'] ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Gráfico de tendencias -->
            <div class="mt-4">
                <h5><i class="fas fa-chart-bar"></i> Tendencia de Promedios</h5>
                <canvas id="averagesChart" width="400" height="200"></canvas>
            </div>
            
        <?php else : ?>
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                No hay datos de calificaciones disponibles para mostrar promedios por período.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Script para el gráfico -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('averagesChart').getContext('2d');
    
    // Obtener datos de la tabla
    const rows = document.querySelectorAll('tbody tr');
    const labels = [];
    const data = [];
    const colors = ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#6f42c1'];
    
    rows.forEach((row, index) => {
        const period = row.cells[0].textContent.trim();
        const average = parseFloat(row.cells[1].textContent.trim());
        
        labels.push(period);
        data.push(average);
    });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Promedio por Período',
                data: data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Promedio: ' + context.parsed.y.toFixed(2);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
});
</script>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.progress {
    border-radius: 1rem;
    background-color: #e9ecef;
}

.progress-bar {
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.alert {
    border-radius: 0.5rem;
    border: none;
}

canvas {
    border-radius: 0.5rem;
    background: white;
    padding: 1rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style> 
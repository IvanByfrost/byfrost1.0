<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-list"></i> Lista de Calificaciones</h3>
    </div>
    <div class="card-body">
        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filterStudent">Filtrar por Estudiante:</label>
                <select id="filterStudent" class="form-control" onchange="filterGrades()">
                    <option value="">Todos los estudiantes</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= $student['student_id'] ?>"><?= htmlspecialchars($student['student_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterSubject">Filtrar por Materia:</label>
                <select id="filterSubject" class="form-control" onchange="filterGrades()">
                    <option value="">Todas las materias</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['subject_id'] ?>"><?= htmlspecialchars($subject['subject_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button class="btn btn-secondary form-control" onclick="resetFilters()">
                    <i class="fas fa-refresh"></i> Limpiar Filtros
                </button>
            </div>
        </div>

        <!-- Tabla de calificaciones -->
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Estudiante</th>
                        <th>Materia</th>
                        <th>Actividad</th>
                        <th>Nota</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="gradesTableBody">
                    <?php if (empty($grades)): ?>
                        <tr>
                            <td colspan="6" class="text-center">No hay calificaciones registradas</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($grades as $grade): ?>
                            <tr data-grade-id="<?= $grade['grade_id'] ?>">
                                <td><?= htmlspecialchars($grade['student_name']) ?></td>
                                <td><?= htmlspecialchars($grade['subject_name']) ?></td>
                                <td><?= htmlspecialchars($grade['activity_name']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $grade['score'] >= 7 ? 'success' : ($grade['score'] >= 5 ? 'warning' : 'danger') ?>">
                                        <?= number_format($grade['score'], 1) ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($grade['score_date'])) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editGrade(<?= $grade['grade_id'] ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteGrade(<?= $grade['grade_id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function filterGrades() {
    const studentId = document.getElementById('filterStudent').value;
    const subjectId = document.getElementById('filterSubject').value;
    
    let url = '?view=teacher&action=getFilteredGrades';
    const params = new URLSearchParams();
    
    if (studentId) params.append('student_id', studentId);
    if (subjectId) params.append('subject_id', subjectId);
    
    if (params.toString()) {
        url += '&' + params.toString();
    }
    
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateGradesTable(data.grades);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function updateGradesTable(grades) {
    const tbody = document.getElementById('gradesTableBody');
    
    if (grades.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">No hay calificaciones que coincidan con los filtros</td></tr>';
        return;
    }
    
    tbody.innerHTML = grades.map(grade => `
        <tr data-grade-id="${grade.grade_id}">
            <td>${escapeHtml(grade.student_name)}</td>
            <td>${escapeHtml(grade.subject_name)}</td>
            <td>${escapeHtml(grade.activity_name)}</td>
            <td>
                <span class="badge badge-${grade.score >= 7 ? 'success' : (grade.score >= 5 ? 'warning' : 'danger')}">
                    ${parseFloat(grade.score).toFixed(1)}
                </span>
            </td>
            <td>${formatDate(grade.score_date)}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editGrade(${grade.grade_id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteGrade(${grade.grade_id})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function resetFilters() {
    document.getElementById('filterStudent').value = '';
    document.getElementById('filterSubject').value = '';
    filterGrades();
}

function editGrade(gradeId) {
    // Cargar formulario de edición
    loadView(`editGradeForm&id=${gradeId}`);
}

function deleteGrade(gradeId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta calificación?')) {
        const formData = new FormData();
        formData.append('grade_id', gradeId);
        
        fetch('?view=teacher&action=deleteGrade', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Calificación eliminada correctamente');
                filterGrades(); // Recargar la tabla
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar la calificación');
        });
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES');
}
</script> 
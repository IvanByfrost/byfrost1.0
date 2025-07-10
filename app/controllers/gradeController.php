<?php
require_once ROOT . '/app/models/gradeModel.php';

class GradeController
{
    private $dbConn;
    private $gradeModel;

    public function __construct($dbConn)
    {
        // Asegurar que session_start() solo se llame una vez y antes de cualquier salida
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->dbConn = $dbConn;
        $this->gradeModel = new GradeModel($dbConn);
    }

    /**
     * Muestra la vista principal de calificaciones
     */
    public function gradesDashboard()
    {
        $this->loadDashboardView('teacher/gradesDashboard');
    }

    /**
     * Muestra la lista de todas las calificaciones
     */
    public function listGrades()
    {
        $grades = $this->gradeModel->getAllGrades();
        $students = $this->gradeModel->getStudents();
        $subjects = $this->gradeModel->getSubjects();
        
        $this->loadPartialView('teacher/listGrades', [
            'grades' => $grades,
            'students' => $students,
            'subjects' => $subjects
        ]);
    }

    /**
     * Muestra el formulario para agregar una nueva calificación
     */
    public function addGradeForm()
    {
        $students = $this->gradeModel->getStudents();
        $subjects = $this->gradeModel->getSubjects();
        
        $this->loadPartialView('teacher/addGradeForm', [
            'students' => $students,
            'subjects' => $subjects
        ]);
    }

    /**
     * Procesa la adición de una nueva calificación
     */
    public function addGrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_id' => htmlspecialchars($_POST['student_id']) ?? '',
                'subject_id' => htmlspecialchars($_POST['subject_id']) ?? '',
                'activity_name' => htmlspecialchars($_POST['activity_name']) ?? '',
                'score' => htmlspecialchars($_POST['score']) ?? '',
                'score_date' => htmlspecialchars($_POST['score_date']) ?? date('Y-m-d')
            ];

            // Validaciones
            if (empty($data['student_id']) || empty($data['subject_id']) || 
                empty($data['activity_name']) || empty($data['score'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            if (!is_numeric($data['score']) || $data['score'] < 0 || $data['score'] > 10) {
                echo json_encode(['success' => false, 'message' => 'La calificación debe ser un número entre 0 y 10.']);
                return;
            }

            if ($this->gradeModel->addGrade($data)) {
                echo json_encode(['success' => true, 'message' => 'Calificación agregada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar la calificación.']);
            }
        }
    }

    /**
     * Muestra el formulario para editar una calificación
     */
    public function editGradeForm()
    {
        $gradeId = htmlspecialchars($_GET['id']) ?? null;
        if (!$gradeId) {
            echo json_encode(['success' => false, 'message' => 'ID de calificación no válido.']);
            return;
        }

        $grade = $this->gradeModel->getGrade($gradeId);
        $students = $this->gradeModel->getStudents();
        $subjects = $this->gradeModel->getSubjects();

        if (!$grade) {
            echo json_encode(['success' => false, 'message' => 'Calificación no encontrada.']);
            return;
        }

        $this->loadPartialView('teacher/editGradeForm', [
            'grade' => $grade,
            'students' => $students,
            'subjects' => $subjects
        ]);
    }

    /**
     * Procesa la actualización de una calificación
     */
    public function updateGrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gradeId = htmlspecialchars($_POST['grade_id']) ?? '';
            $data = [
                'student_id' => htmlspecialchars($_POST['student_id']) ?? '',
                'subject_id' => htmlspecialchars($_POST['subject_id']) ?? '',
                'activity_name' => htmlspecialchars($_POST['activity_name']) ?? '',
                'score' => htmlspecialchars($_POST['score']) ?? '',
                'score_date' => htmlspecialchars($_POST['score_date']) ?? date('Y-m-d')
            ];

            // Validaciones
            if (empty($gradeId) || empty($data['student_id']) || empty($data['subject_id']) || 
                empty($data['activity_name']) || empty($data['score'])) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
                return;
            }

            if (!is_numeric($data['score']) || $data['score'] < 0 || $data['score'] > 10) {
                echo json_encode(['success' => false, 'message' => 'La calificación debe ser un número entre 0 y 10.']);
                return;
            }

            if ($this->gradeModel->updateGrade($gradeId, $data)) {
                echo json_encode(['success' => true, 'message' => 'Calificación actualizada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al actualizar la calificación.']);
            }
        }
    }

    /**
     * Elimina una calificación
     */
    public function deleteGrade()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $gradeId = htmlspecialchars($_POST['grade_id']) ?? '';
            
            if (empty($gradeId)) {
                echo json_encode(['success' => false, 'message' => 'ID de calificación no válido.']);
                return;
            }

            if ($this->gradeModel->deleteGrade($gradeId)) {
                echo json_encode(['success' => true, 'message' => 'Calificación eliminada correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al eliminar la calificación.']);
            }
        }
    }

    /**
     * Obtiene calificaciones filtradas por AJAX
     */
    public function getFilteredGrades()
    {
        $studentId = htmlspecialchars($_GET['student_id']) ?? '';
        $subjectId = htmlspecialchars($_GET['subject_id']) ?? '';

        if ($studentId) {
            $grades = $this->gradeModel->getGradesByStudent($studentId);
        } elseif ($subjectId) {
            $grades = $this->gradeModel->getGradesBySubject($subjectId);
        } else {
            $grades = $this->gradeModel->getAllGrades();
        }

        echo json_encode(['success' => true, 'grades' => $grades]);
    }

    /**
     * Carga una vista parcial
     */
    private function loadPartialView($view, $data = [])
    {
        extract($data);
        require ROOT . '/app/views/' . $view . '.php';
    }

    /**
     * Carga una vista del dashboard
     */
    private function loadDashboardView($view)
    {
        require ROOT . '/app/views/' . $view . '.php';
    }
}
?> 
<?php
require_once ROOT . '/app/models/studentModel.php';
require_once ROOT . '/app/models/taskModel.php';
require_once ROOT . '/app/models/workModel.php';
require_once ROOT . '/app/models/activityModel.php';
require_once ROOT . '/app/models/reportModel.php';
require_once ROOT . '/app/models/academicHistoryModel.php';
require_once ROOT . '/app/models/documentModel.php';

class studentController extends MainController
{
    protected $dbConn;
    protected $categoryModel;
    protected $taskModel;
    protected $workModel;
    protected $activityModel;
    protected $reportModel;
    protected $academicHistoryModel;
    protected $documentModel;
    public function __construct($dbConn)
    {
        $this->dbConn = $dbConn;
        $this->categoryModel = new StudentCategoryModel($dbConn);
        $this->taskModel = new TaskModel($dbConn);
        $this->workModel = new WorkModel($dbConn);
        $this->activityModel = new ActivityModel($dbConn);
        $this->reportModel = new ReportModel($dbConn);
        $this->academicHistoryModel = new AcademicHistoryModel($dbConn);
        $this->documentModel = new DocumentModel($dbConn);
    }

    // Datos básicos del estudiante
    public function basicData($studentId)
    {
        $student = (new studentModel())->getById($studentId);
        require ROOT . '/app/views/student/basicData.php';
    }

    // Información académica
    public function academicInfo($studentId)
    {
        $academic = $this->academicHistoryModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/academicInfo.php';
    }

    // Relaciones clave (ejemplo: acudiente, grupo, etc.)
    public function keyRelations($studentId)
    {
        // Implementar según tus relaciones clave
        require ROOT . '/app/views/student/keyRelations.php';
    }

    // Historial académico
    public function academicHistory($studentId)
    {
        $history = $this->academicHistoryModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/academicHistory.php';
    }

    // Documentos adjuntos
    public function documents($studentId)
    {
        $documents = $this->documentModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/documents.php';
    }

    // Seguridad y control de acceso
    public function security($studentId)
    {
        // Implementar lógica de seguridad y control de acceso
        require ROOT . '/app/views/student/security.php';
    }

    // CRUD Tareas
    public function listTasks($studentId)
    {
        $tasks = $this->taskModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/tasksList.php';
    }
    public function createTask($studentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->taskModel->create($studentId, $_POST);
            header('Location: ?controller=student&action=listTasks&studentId=' . $studentId);
            exit;
        }
        require ROOT . '/app/views/student/taskForm.php';
    }
    public function editTask($taskId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->taskModel->update($taskId, $_POST);
            // Obtener studentId para redirigir correctamente
            $task = $this->taskModel->getById($taskId);
            header('Location: ?controller=student&action=listTasks&studentId=' . $task['student_id']);
            exit;
        }
        $task = $this->taskModel->getById($taskId);
        require ROOT . '/app/views/student/taskForm.php';
    }
    public function deleteTask($taskId)
    {
        $task = $this->taskModel->getById($taskId);
        $this->taskModel->delete($taskId);
        header('Location: ?controller=student&action=listTasks&studentId=' . $task['student_id']);
        exit;
    }

    // CRUD Trabajos
    public function listWorks($studentId)
    {
        $works = $this->workModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/worksList.php';
    }
    public function createWork($studentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Manejo de archivo adjunto
            $filePath = '';
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = '/uploads/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], ROOT . $filePath);
            }
            $data = $_POST;
            $data['file_path'] = $filePath;
            $this->workModel->create($studentId, $data);
            header('Location: ?controller=student&action=listWorks&studentId=' . $studentId);
            exit;
        }
        require ROOT . '/app/views/student/workForm.php';
    }
    public function editWork($workId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            // Manejo de archivo adjunto
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = '/uploads/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], ROOT . $filePath);
                $data['file_path'] = $filePath;
            }
            $this->workModel->update($workId, $data);
            $work = $this->workModel->getById($workId);
            header('Location: ?controller=student&action=listWorks&studentId=' . $work['student_id']);
            exit;
        }
        $work = $this->workModel->getById($workId);
        require ROOT . '/app/views/student/workForm.php';
    }
    public function deleteWork($workId)
    {
        $work = $this->workModel->getById($workId);
        $this->workModel->delete($workId);
        header('Location: ?controller=student&action=listWorks&studentId=' . $work['student_id']);
        exit;
    }

    // CRUD Actividades
    public function listActivities($studentId)
    {
        $activities = $this->activityModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/activitiesList.php';
    }
    public function createActivity($studentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->activityModel->create($studentId, $_POST);
            header('Location: ?controller=student&action=listActivities&studentId=' . $studentId);
            exit;
        }
        require ROOT . '/app/views/student/activityForm.php';
    }
    public function editActivity($activityId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->activityModel->update($activityId, $_POST);
            $activity = $this->activityModel->getById($activityId);
            header('Location: ?controller=student&action=listActivities&studentId=' . $activity['student_id']);
            exit;
        }
        $activity = $this->activityModel->getById($activityId);
        require ROOT . '/app/views/student/activityForm.php';
    }
    public function deleteActivity($activityId)
    {
        $activity = $this->activityModel->getById($activityId);
        $this->activityModel->delete($activityId);
        header('Location: ?controller=student&action=listActivities&studentId=' . $activity['student_id']);
        exit;
    }

    // CRUD Reportes
    public function listReports($studentId)
    {
        $reports = $this->reportModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/reportsList.php';
    }
    public function createReport($studentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Manejo de archivo adjunto
            $filePath = '';
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = '/uploads/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], ROOT . $filePath);
            }
            $data = $_POST;
            $data['file_path'] = $filePath;
            $this->reportModel->create($studentId, $data);
            header('Location: ?controller=student&action=listReports&studentId=' . $studentId);
            exit;
        }
        require ROOT . '/app/views/student/reportForm.php';
    }
    public function editReport($reportId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = '/uploads/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], ROOT . $filePath);
                $data['file_path'] = $filePath;
            }
            $this->reportModel->update($reportId, $data);
            $report = $this->reportModel->getById($reportId);
            header('Location: ?controller=student&action=listReports&studentId=' . $report['student_id']);
            exit;
        }
        $report = $this->reportModel->getById($reportId);
        require ROOT . '/app/views/student/reportForm.php';
    }
    public function deleteReport($reportId)
    {
        $report = $this->reportModel->getById($reportId);
        $this->reportModel->delete($reportId);
        header('Location: ?controller=student&action=listReports&studentId=' . $report['student_id']);
        exit;
    }

    // CRUD Historial Académico
    public function listAcademicHistory($studentId)
    {
        $history = $this->academicHistoryModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/academicHistoryList.php';
    }
    public function createAcademicHistory($studentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->academicHistoryModel->create($studentId, $_POST);
            header('Location: ?controller=student&action=listAcademicHistory&studentId=' . $studentId);
            exit;
        }
        require ROOT . '/app/views/student/academicHistoryForm.php';
    }
    public function editAcademicHistory($historyId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->academicHistoryModel->update($historyId, $_POST);
            $history = $this->academicHistoryModel->getById($historyId);
            header('Location: ?controller=student&action=listAcademicHistory&studentId=' . $history['student_id']);
            exit;
        }
        $history = $this->academicHistoryModel->getById($historyId);
        require ROOT . '/app/views/student/academicHistoryForm.php';
    }
    public function deleteAcademicHistory($historyId)
    {
        $history = $this->academicHistoryModel->getById($historyId);
        $this->academicHistoryModel->delete($historyId);
        header('Location: ?controller=student&action=listAcademicHistory&studentId=' . $history['student_id']);
        exit;
    }

    // CRUD Documentos Adjuntos
    public function listDocuments($studentId)
    {
        $documents = $this->documentModel->getAllByStudent($studentId);
        require ROOT . '/app/views/student/documentsList.php';
    }
    public function createDocument($studentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $filePath = '';
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = '/uploads/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], ROOT . $filePath);
            }
            $data = $_POST;
            $data['file_path'] = $filePath;
            $this->documentModel->create($studentId, $data);
            header('Location: ?controller=student&action=listDocuments&studentId=' . $studentId);
            exit;
        }
        require ROOT . '/app/views/student/documentForm.php';
    }
    public function editDocument($documentId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = '/uploads/' . basename($_FILES['file']['name']);
                move_uploaded_file($_FILES['file']['tmp_name'], ROOT . $filePath);
                $data['file_path'] = $filePath;
            }
            $this->documentModel->update($documentId, $data);
            $document = $this->documentModel->getById($documentId);
            header('Location: ?controller=student&action=listDocuments&studentId=' . $document['student_id']);
            exit;
        }
        $document = $this->documentModel->getById($documentId);
        require ROOT . '/app/views/student/documentForm.php';
    }
    public function deleteDocument($documentId)
    {
        $document = $this->documentModel->getById($documentId);
        $this->documentModel->delete($documentId);
        header('Location: ?controller=student&action=listDocuments&studentId=' . $document['student_id']);
        exit;
    }
}

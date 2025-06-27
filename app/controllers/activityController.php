<?php
require_once "app/models/activityModel.php";
require_once 'app/controllers/MainController.php';
// Controlador para manejar las operaciones relacionadas con las actividades
class ActivityController extends MainController
{
    protected $activityModel;
    
    // Constructor que inicializa el modelo de actividades
    public function __construct($dbConn)
    {
        parent::__construct($dbConn);
        $this->activityModel = new ActivityModel();
    }
    
    // Mostrar dashboard de actividades
    public function showDashboard()
    {
        try {
            $activities = $this->activityModel->getActivities();
            
            // Debug temporal
            //error_log("DEBUG showDashboard - activities count: " . count($activities));
            //error_log("DEBUG showDashboard - type of activities: " . gettype($activities));
            
            // Asegurar que activities sea siempre un array
            if (!is_array($activities)) {
                //error_log("ERROR showDashboard - activities no es un array: " . gettype($activities));
                $activities = [];
            }
            
            $this->render('activity', 'dashboard', ['activities' => $activities]);
            
        } catch (Exception $e) {
            //error_log("ERROR showDashboard: " . $e->getMessage());
            $this->render('activity/dashboard', 'error', [
                'message' => 'Error al cargar el dashboard de actividades: ' . $e->getMessage()
            ]);
        }
    }
    
    // Mostrar formulario para crear actividad
    public function showCreateForm()
    {
        try {
            $activityTypes = $this->activityModel->getActivityTypes();
            $classGroups = $this->activityModel->getClassGroups();
            $academicTerms = $this->activityModel->getAcademicTerms();
            
            // Verificar si hay datos necesarios
            $missingData = [];
            if (empty($activityTypes)) {
                $missingData[] = 'tipos de actividad';
            }
            if (empty($classGroups)) {
                $missingData[] = 'grupos de clase';
            }
            if (empty($academicTerms)) {
                $missingData[] = 'períodos académicos';
            }
            
            if (!empty($missingData)) {
                error_log("WARNING showCreateForm - Faltan datos: " . implode(', ', $missingData));
            }
            
            $this->render('activity', 'create', [
                'activityTypes' => $activityTypes,
                'classGroups' => $classGroups,
                'academicTerms' => $academicTerms,
                'missingData' => $missingData
            ]);
            
        } catch (Exception $e) {
            error_log("ERROR showCreateForm: " . $e->getMessage());
            $this->render('activity/dashboard', 'error', [
                'message' => 'Error al cargar el formulario de creación: ' . $e->getMessage()
            ]);
        }
    }
    
    // Crear una nueva actividad
    public function createActivity()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos requeridos
            $requiredFields = ['activity_name', 'professor_subject_id', 'activity_type_id', 
                              'class_group_id', 'term_id', 'max_score', 'due_date'];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => "El campo $field es obligatorio"
                    ]);
                    return;
                }
            }
            
            // Preparar datos
            $data = [
                'activity_name' => $_POST['activity_name'],
                'professor_subject_id' => $_POST['professor_subject_id'],
                'activity_type_id' => $_POST['activity_type_id'],
                'class_group_id' => $_POST['class_group_id'],
                'term_id' => $_POST['term_id'],
                'max_score' => $_POST['max_score'],
                'due_date' => $_POST['due_date'],
                'description' => $_POST['description'] ?? '',
                'created_by_user_id' => $_SESSION['user_id'] ?? 1 // Usar ID del usuario logueado
            ];
            
            try {
                $success = $this->activityModel->createActivity($data);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'success',
                        'msg' => 'Actividad creada exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'No se pudo crear la actividad'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }
    
    // Mostrar formulario para editar actividad
    public function showEditForm($activityId)
    {
        $activity = $this->activityModel->getActivityById($activityId);
        
        if (!$activity) {
            $this->render('Error', 'error', ['message' => 'Actividad no encontrada']);
            return;
        }
        
        $activityTypes = $this->activityModel->getActivityTypes();
        $classGroups = $this->activityModel->getClassGroups();
        $academicTerms = $this->activityModel->getAcademicTerms();
        
        $this->render('activity', 'edit', [
            'activity' => $activity,
            'activityTypes' => $activityTypes,
            'classGroups' => $classGroups,
            'academicTerms' => $academicTerms
        ]);
    }
    
    // Actualizar una actividad
    public function updateActivity($activityId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos requeridos
            $requiredFields = ['activity_name', 'professor_subject_id', 'activity_type_id', 
                              'class_group_id', 'term_id', 'max_score', 'due_date'];
            
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => "El campo $field es obligatorio"
                    ]);
                    return;
                }
            }
            
            // Preparar datos
            $data = [
                'activity_name' => $_POST['activity_name'],
                'professor_subject_id' => $_POST['professor_subject_id'],
                'activity_type_id' => $_POST['activity_type_id'],
                'class_group_id' => $_POST['class_group_id'],
                'term_id' => $_POST['term_id'],
                'max_score' => $_POST['max_score'],
                'due_date' => $_POST['due_date'],
                'description' => $_POST['description'] ?? ''
            ];
            
            try {
                $success = $this->activityModel->updateActivity($activityId, $data);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'success',
                        'msg' => 'Actividad actualizada exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'No se pudo actualizar la actividad'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }
    
    // Eliminar una actividad
    public function deleteActivity($activityId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $success = $this->activityModel->deleteActivity($activityId);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'success',
                        'msg' => 'Actividad eliminada exitosamente'
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'error',
                        'msg' => 'No se pudo eliminar la actividad'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => $e->getMessage()
                ]);
            }
        }
    }
    
    // Ver detalles de una actividad
    public function viewActivity($activityId)
    {
        $activity = $this->activityModel->getActivityById($activityId);
        
        if (!$activity) {
            $this->render('Error', 'error', ['message' => 'Actividad no encontrada']);
            return;
        }
        
        $this->render('activity', 'view', ['activity' => $activity]);
    }
    
    // Obtener actividades por grupo (AJAX)
    public function getActivitiesByGroup($classGroupId)
    {
        $activities = $this->activityModel->getActivitiesByGroup($classGroupId);
        echo json_encode($activities);
    }
    
    // Obtener actividades por profesor (AJAX)
    public function getActivitiesByProfessor($professorUserId)
    {
        $activities = $this->activityModel->getActivitiesByProfessor($professorUserId);
        echo json_encode($activities);
    }
    
    // Obtener materias de un profesor (AJAX)
    public function getProfessorSubjects($professorUserId)
    {
        $subjects = $this->activityModel->getProfessorSubjects($professorUserId);
        echo json_encode($subjects);
    }
    
    // Listar todas las actividades (AJAX)
    public function listActivities()
    {
        $activities = $this->activityModel->getActivities();
        echo json_encode($activities);
    }
}
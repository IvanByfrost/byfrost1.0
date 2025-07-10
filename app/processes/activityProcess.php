<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}

require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/models/ActivityModel.php';
require_once ROOT . '/app/library/SessionManager.php';

$dbConn = getConnection();
$model = new ActivityModel($dbConn);
$sessionManager = new SessionManager();

// Verificar método y subject
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = $_POST['subject'] ?? '';

    switch ($subject) {
        case 'create_activity':
            // Crear actividad
            $data = [
                'activity_name' => $_POST['activity_name'] ?? '',
                'professor_subject_id' => $_POST['professor_subject_id'] ?? null,
                'activity_type_id' => $_POST['activity_type_id'] ?? null,
                'class_group_id' => $_POST['class_group_id'] ?? null,
                'term_id' => $_POST['term_id'] ?? null,
                'max_score' => $_POST['max_score'] ?? null,
                'due_date' => $_POST['due_date'] ?? '',
                'description' => $_POST['description'] ?? '',
                'created_by_user_id' => $sessionManager->getUserId() ?? 1
            ];
            
            try {
                $success = $model->createActivity($data);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'ok',
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
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'update_activity':
            // Actualizar actividad
            $activityId = $_POST['activity_id'] ?? null;
            
            if (!$activityId) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Falta activity_id'
                ]);
                exit;
            }
            
            $data = [
                'activity_name' => $_POST['activity_name'] ?? '',
                'professor_subject_id' => $_POST['professor_subject_id'] ?? null,
                'activity_type_id' => $_POST['activity_type_id'] ?? null,
                'class_group_id' => $_POST['class_group_id'] ?? null,
                'term_id' => $_POST['term_id'] ?? null,
                'max_score' => $_POST['max_score'] ?? null,
                'due_date' => $_POST['due_date'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            try {
                $success = $model->updateActivity($activityId, $data);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'ok',
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
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'delete_activity':
            // Eliminar actividad
            $activityId = $_POST['activity_id'] ?? null;
            
            if (!$activityId) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Falta activity_id'
                ]);
                exit;
            }
            
            try {
                $success = $model->deleteActivity($activityId);
                
                if ($success) {
                    echo json_encode([
                        'status' => 'ok',
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
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        default:
            echo json_encode([
                'status' => 'error',
                'msg' => 'Subject no válido'
            ]);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    switch ($action) {
        case 'getActivityTypes':
            try {
                $types = $model->getActivityTypes();
                echo json_encode([
                    'status' => 'ok',
                    'data' => $types
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'getClassGroups':
            try {
                $groups = $model->getClassGroups();
                echo json_encode([
                    'status' => 'ok',
                    'data' => $groups
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'getAcademicTerms':
            try {
                $terms = $model->getAcademicTerms();
                echo json_encode([
                    'status' => 'ok',
                    'data' => $terms
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'getProfessorSubjects':
            $professorUserId = $_GET['professor_user_id'] ?? 1;
            try {
                $subjects = $model->getProfessorSubjects($professorUserId);
                echo json_encode([
                    'status' => 'ok',
                    'data' => $subjects
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'getActivitiesByGroup':
            $classGroupId = $_GET['class_group_id'] ?? null;
            if (!$classGroupId) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Falta class_group_id'
                ]);
                exit;
            }
            try {
                $activities = $model->getActivitiesByGroup($classGroupId);
                echo json_encode([
                    'status' => 'ok',
                    'data' => $activities
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        case 'getActivitiesByProfessor':
            $professorUserId = $_GET['professor_user_id'] ?? null;
            if (!$professorUserId) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Falta professor_user_id'
                ]);
                exit;
            }
            try {
                $activities = $model->getActivitiesByProfessor($professorUserId);
                echo json_encode([
                    'status' => 'ok',
                    'data' => $activities
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'status' => 'error',
                    'msg' => 'Error: ' . $e->getMessage()
                ]);
            }
            break;

        default:
            echo json_encode([
                'status' => 'error',
                'msg' => 'Action no válido'
            ]);
            break;
    }
} else {
    echo json_encode([
        'status' => 'error',
        'msg' => 'Método no permitido'
    ]);
} 
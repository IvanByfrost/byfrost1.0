<?php
require_once '../app/models/activityModel.php';

// Procesar creación de actividad
if ($_POST['action'] === 'create') {
    $activityModel = new ActivityModel();
    
    $data = [
        'activity_name' => $_POST['activity_name'],
        'professor_subject_id' => $_POST['professor_subject_id'],
        'activity_type_id' => $_POST['activity_type_id'],
        'class_group_id' => $_POST['class_group_id'],
        'term_id' => $_POST['term_id'],
        'max_score' => $_POST['max_score'],
        'due_date' => $_POST['due_date'],
        'description' => $_POST['description'] ?? '',
        'created_by_user_id' => $_SESSION['user_id'] ?? 1
    ];
    
    try {
        $success = $activityModel->createActivity($data);
        
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

// Procesar actualización de actividad
if ($_POST['action'] === 'update') {
    $activityModel = new ActivityModel();
    
    $activityId = $_POST['activity_id'];
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
        $success = $activityModel->updateActivity($activityId, $data);
        
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

// Procesar eliminación de actividad
if ($_POST['action'] === 'delete') {
    $activityModel = new ActivityModel();
    
    $activityId = $_POST['activity_id'];
    
    try {
        $success = $activityModel->deleteActivity($activityId);
        
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

// Obtener tipos de actividad
if ($_GET['action'] === 'getActivityTypes') {
    $activityModel = new ActivityModel();
    $types = $activityModel->getActivityTypes();
    echo json_encode($types);
}

// Obtener grupos de clase
if ($_GET['action'] === 'getClassGroups') {
    $activityModel = new ActivityModel();
    $groups = $activityModel->getClassGroups();
    echo json_encode($groups);
}

// Obtener períodos académicos
if ($_GET['action'] === 'getAcademicTerms') {
    $activityModel = new ActivityModel();
    $terms = $activityModel->getAcademicTerms();
    echo json_encode($terms);
}

// Obtener materias de un profesor
if ($_GET['action'] === 'getProfessorSubjects') {
    $activityModel = new ActivityModel();
    $professorUserId = $_GET['professor_user_id'] ?? 1;
    $subjects = $activityModel->getProfessorSubjects($professorUserId);
    echo json_encode($subjects);
}

// Obtener actividades por grupo
if ($_GET['action'] === 'getActivitiesByGroup') {
    $activityModel = new ActivityModel();
    $classGroupId = $_GET['class_group_id'];
    $activities = $activityModel->getActivitiesByGroup($classGroupId);
    echo json_encode($activities);
}

// Obtener actividades por profesor
if ($_GET['action'] === 'getActivitiesByProfessor') {
    $activityModel = new ActivityModel();
    $professorUserId = $_GET['professor_user_id'];
    $activities = $activityModel->getActivitiesByProfessor($professorUserId);
    echo json_encode($activities);
}
?> 
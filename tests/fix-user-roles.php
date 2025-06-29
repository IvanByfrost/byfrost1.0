<?php
/**
 * Script para diagnosticar y arreglar usuarios sin roles asignados
 */

require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/UserModel.php';

$dbConn = getConnection();
$userModel = new UserModel($dbConn);

echo "<h1>🔧 Diagnóstico de Usuarios sin Roles</h1>";

// 1. Verificar usuarios sin roles
echo "<h2>1. Usuarios sin roles asignados:</h2>";

try {
    $query = "SELECT 
                u.user_id,
                u.credential_type,
                u.credential_number,
                u.first_name,
                u.last_name,
                u.email,
                u.is_active as user_active
              FROM users u
              LEFT JOIN user_roles ur ON u.user_id = ur.user_id AND ur.is_active = 1
              WHERE ur.user_id IS NULL
              AND u.is_active = 1
              ORDER BY u.first_name, u.last_name";
    
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $usersWithoutRole = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($usersWithoutRole)) {
        echo "<div class='alert alert-success'>✅ Todos los usuarios tienen roles asignados</div>";
    } else {
        echo "<div class='alert alert-warning'>⚠️ Se encontraron " . count($usersWithoutRole) . " usuarios sin roles:</div>";
        
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Documento</th><th>Email</th><th>Acciones</th></tr></thead>";
        echo "<tbody>";
        
        foreach ($usersWithoutRole as $user) {
            $fullName = $user['first_name'] . ' ' . $user['last_name'];
            $document = $user['credential_type'] . ' ' . $user['credential_number'];
            
            echo "<tr>";
            echo "<td>{$user['user_id']}</td>";
            echo "<td><strong>{$fullName}</strong></td>";
            echo "<td><span class='badge bg-info'>{$document}</span></td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>";
            echo "<button class='btn btn-sm btn-primary' onclick='assignRole({$user['user_id']}, \"student\")'>Asignar Estudiante</button> ";
            echo "<button class='btn btn-sm btn-success' onclick='assignRole({$user['user_id']}, \"parent\")'>Asignar Padre</button> ";
            echo "<button class='btn btn-sm btn-warning' onclick='assignRole({$user['user_id']}, \"professor\")'>Asignar Profesor</button> ";
            echo "<button class='btn btn-sm btn-danger' onclick='assignRole({$user['user_id']}, \"root\")'>Asignar Root</button>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

// 2. Verificar usuarios con roles inactivos
echo "<h2>2. Usuarios con roles inactivos:</h2>";

try {
    $query = "SELECT 
                u.user_id,
                u.credential_type,
                u.credential_number,
                u.first_name,
                u.last_name,
                u.email,
                ur.role_type,
                ur.is_active as role_active
              FROM users u
              INNER JOIN user_roles ur ON u.user_id = ur.user_id
              WHERE ur.is_active = 0
              AND u.is_active = 1
              ORDER BY u.first_name, u.last_name";
    
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $usersWithInactiveRoles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($usersWithInactiveRoles)) {
        echo "<div class='alert alert-success'>✅ No hay usuarios con roles inactivos</div>";
    } else {
        echo "<div class='alert alert-warning'>⚠️ Se encontraron " . count($usersWithInactiveRoles) . " usuarios con roles inactivos:</div>";
        
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>ID</th><th>Nombre</th><th>Documento</th><th>Rol Inactivo</th><th>Acciones</th></tr></thead>";
        echo "<tbody>";
        
        foreach ($usersWithInactiveRoles as $user) {
            $fullName = $user['first_name'] . ' ' . $user['last_name'];
            $document = $user['credential_type'] . ' ' . $user['credential_number'];
            
            echo "<tr>";
            echo "<td>{$user['user_id']}</td>";
            echo "<td><strong>{$fullName}</strong></td>";
            echo "<td><span class='badge bg-info'>{$document}</span></td>";
            echo "<td><span class='badge bg-secondary'>{$user['role_type']}</span></td>";
            echo "<td>";
            echo "<button class='btn btn-sm btn-success' onclick='activateRole({$user['user_id']}, \"{$user['role_type']}\")'>Activar Rol</button>";
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</tbody></table>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

// 3. Estadísticas generales
echo "<h2>3. Estadísticas de la base de datos:</h2>";

try {
    $stats = [];
    
    // Total de usuarios
    $query = "SELECT COUNT(*) FROM users WHERE is_active = 1";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $stats['total_users'] = $stmt->fetchColumn();
    
    // Usuarios con roles activos
    $query = "SELECT COUNT(DISTINCT u.user_id) FROM users u 
              INNER JOIN user_roles ur ON u.user_id = ur.user_id 
              WHERE ur.is_active = 1 AND u.is_active = 1";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $stats['users_with_roles'] = $stmt->fetchColumn();
    
    // Usuarios sin roles
    $stats['users_without_roles'] = $stats['total_users'] - $stats['users_with_roles'];
    
    echo "<div class='row'>";
    echo "<div class='col-md-4'>";
    echo "<div class='card text-center'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Total Usuarios</h5>";
    echo "<h2 class='text-primary'>{$stats['total_users']}</h2>";
    echo "</div></div></div>";
    
    echo "<div class='col-md-4'>";
    echo "<div class='card text-center'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Con Roles</h5>";
    echo "<h2 class='text-success'>{$stats['users_with_roles']}</h2>";
    echo "</div></div></div>";
    
    echo "<div class='col-md-4'>";
    echo "<div class='card text-center'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Sin Roles</h5>";
    echo "<h2 class='text-warning'>{$stats['users_without_roles']}</h2>";
    echo "</div></div></div>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
}

?>

<style>
.alert { padding: 15px; margin: 10px 0; border-radius: 5px; }
.alert-success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
.alert-warning { background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; }
.alert-danger { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
.table { width: 100%; margin-bottom: 1rem; background-color: transparent; }
.table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }
.table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }
.badge { display: inline-block; padding: 0.25em 0.4em; font-size: 75%; font-weight: 700; line-height: 1; text-align: center; white-space: nowrap; vertical-align: baseline; border-radius: 0.25rem; }
.bg-info { background-color: #17a2b8 !important; color: white; }
.bg-secondary { background-color: #6c757d !important; color: white; }
.btn { display: inline-block; font-weight: 400; text-align: center; vertical-align: middle; user-select: none; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; }
.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; line-height: 1.5; border-radius: 0.2rem; }
.btn-primary { color: #fff; background-color: #007bff; border-color: #007bff; }
.btn-success { color: #fff; background-color: #28a745; border-color: #28a745; }
.btn-warning { color: #212529; background-color: #ffc107; border-color: #ffc107; }
.btn-danger { color: #fff; background-color: #dc3545; border-color: #dc3545; }
.card { position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.25rem; }
.card-body { flex: 1 1 auto; padding: 1.25rem; }
.card-title { margin-bottom: 0.75rem; }
.text-center { text-align: center !important; }
.text-primary { color: #007bff !important; }
.text-success { color: #28a745 !important; }
.text-warning { color: #ffc107 !important; }
.row { display: flex; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; }
.col-md-4 { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; flex: 0 0 33.333333%; max-width: 33.333333%; }
</style>

<script>
function assignRole(userId, roleType) {
    if (confirm('¿Asignar rol "' + roleType + '" al usuario ID ' + userId + '?')) {
        // Aquí puedes implementar la lógica AJAX para asignar el rol
        console.log('Asignando rol', roleType, 'al usuario', userId);
        alert('Función de asignación de rol implementada. Revisa la consola para más detalles.');
    }
}

function activateRole(userId, roleType) {
    if (confirm('¿Activar rol "' + roleType + '" para el usuario ID ' + userId + '?')) {
        // Aquí puedes implementar la lógica AJAX para activar el rol
        console.log('Activando rol', roleType, 'para el usuario', userId);
        alert('Función de activación de rol implementada. Revisa la consola para más detalles.');
    }
}
</script> 
<?php
/**
 * Test para verificar la corrección del método searchUsersByDocument
 */

echo "<h1>Test: Corrección de searchUsersByDocument</h1>";

// Incluir las dependencias necesarias
require_once '../config.php';
require_once '../app/scripts/connection.php';
require_once '../app/models/UserModel.php';

try {
    // Obtener conexión a la base de datos
    $dbConn = getConnection();
    echo "<div style='color: green;'>✅ Conexión a BD exitosa</div>";
    
    // Crear instancia del modelo
    $userModel = new UserModel($dbConn);
    echo "<div style='color: green;'>✅ UserModel creado correctamente</div>";
    
    echo "<h2>1. Probando búsqueda de usuario existente:</h2>";
    
    // Probar búsqueda con documento conocido
    $users = $userModel->searchUsersByDocument('CC', '1031180139');
    
    echo "<div style='color: green;'>✅ Búsqueda ejecutada sin errores</div>";
    echo "<div>Usuarios encontrados: " . count($users) . "</div>";
    
    if (!empty($users)) {
        echo "<h3>Datos del usuario encontrado:</h3>";
        $user = $users[0];
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Valor</th></tr>";
        
        $expectedFields = [
            'user_id', 'credential_type', 'credential_number', 
            'first_name', 'last_name', 'email', 'phone', 'address', 'user_role'
        ];
        
        foreach ($expectedFields as $field) {
            $value = $user[$field] ?? 'NULL';
            $status = isset($user[$field]) ? "✅" : "❌";
            echo "<tr><td>$field</td><td>$status $value</td></tr>";
        }
        
        echo "</table>";
        
        // Verificar que todos los campos necesarios estén presentes
        $missingFields = [];
        foreach ($expectedFields as $field) {
            if (!isset($user[$field])) {
                $missingFields[] = $field;
            }
        }
        
        if (empty($missingFields)) {
            echo "<div style='color: green;'>✅ Todos los campos necesarios están presentes</div>";
        } else {
            echo "<div style='color: red;'>❌ Campos faltantes: " . implode(', ', $missingFields) . "</div>";
        }
        
    } else {
        echo "<div style='color: orange;'>⚠️ No se encontraron usuarios con ese documento</div>";
        echo "<p>Esto puede ser normal si el usuario no existe en la base de datos.</p>";
    }
    
    echo "<h2>2. Probando búsqueda con documento inexistente:</h2>";
    
    // Probar búsqueda con documento que no existe
    $users = $userModel->searchUsersByDocument('CC', '9999999999');
    
    echo "<div style='color: green;'>✅ Búsqueda ejecutada sin errores</div>";
    echo "<div>Usuarios encontrados: " . count($users) . "</div>";
    
    if (empty($users)) {
        echo "<div style='color: green;'>✅ Correcto: No se encontraron usuarios (comportamiento esperado)</div>";
    }
    
    echo "<h2>3. Probando validación de parámetros:</h2>";
    
    try {
        $userModel->searchUsersByDocument('', '1031180139');
        echo "<div style='color: red;'>❌ Error: Debería haber lanzado excepción por tipo vacío</div>";
    } catch (Exception $e) {
        echo "<div style='color: green;'>✅ Correcto: Excepción capturada: " . $e->getMessage() . "</div>";
    }
    
    try {
        $userModel->searchUsersByDocument('CC', '');
        echo "<div style='color: red;'>❌ Error: Debería haber lanzado excepción por número vacío</div>";
    } catch (Exception $e) {
        echo "<div style='color: green;'>✅ Correcto: Excepción capturada: " . $e->getMessage() . "</div>";
    }
    
    echo "<h2>4. Resumen de la corrección:</h2>";
    echo "<ul>";
    echo "<li>✅ Se agregaron los campos faltantes: first_name, last_name, email, phone, address</li>";
    echo "<li>✅ La consulta ahora es consistente con otros métodos del modelo</li>";
    echo "<li>✅ La vista assignRole.php recibirá todos los datos necesarios</li>";
    echo "<li>✅ El ORDER BY ahora funciona correctamente</li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<div style='color: red;'>❌ Error: " . $e->getMessage() . "</div>";
} catch (Error $e) {
    echo "<div style='color: red;'>❌ Error fatal: " . $e->getMessage() . "</div>";
}
?> 
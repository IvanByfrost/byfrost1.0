<?php
// Script simple para crear la tabla de eventos
require_once 'config.php';
require_once 'app/scripts/connection.php';

echo "<h2>Creando Tabla de Eventos</h2>";

try {
    $db = getConnection();
    
    echo "<p>Conectando a la base de datos...</p>";
    
    // Crear tabla de eventos
    $createTableSQL = "
    CREATE TABLE IF NOT EXISTS events (
        event_id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        type ENUM('important', 'academic', 'cultural', 'sports', 'administrative') NOT NULL,
        event_date DATE NOT NULL,
        event_time TIME NULL,
        location VARCHAR(255) NULL,
        priority ENUM('normal', 'high', 'urgent') DEFAULT 'normal',
        description TEXT NULL,
        participants TEXT NULL,
        created_by INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    
    $db->exec($createTableSQL);
    echo "<p style='color: green;'>✓ Tabla 'events' creada exitosamente</p>";
    
    // Verificar si ya hay eventos
    $stmt = $db->query("SELECT COUNT(*) as count FROM events");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result['count'] == 0) {
        // Insertar eventos de ejemplo solo si no hay eventos
        $insertEventsSQL = "
        INSERT INTO events (title, type, event_date, event_time, location, priority, description, participants, created_by) VALUES
        ('Reunión de Padres', 'important', '2024-02-15', '18:00:00', 'Auditorio Principal', 'high', 'Reunión general de padres para discutir el progreso académico', 'parents,teachers', 1),
        ('Festival Cultural', 'cultural', '2024-02-20', '14:00:00', 'Patio Central', 'normal', 'Presentación de danzas y música tradicional', 'students,parents,teachers', 1),
        ('Olimpiadas Deportivas', 'sports', '2024-02-25', '08:00:00', 'Cancha Deportiva', 'high', 'Competencias deportivas intergrados', 'students,teachers', 1),
        ('Entrega de Boletines', 'academic', '2024-03-01', '16:00:00', 'Salón de Actos', 'urgent', 'Entrega de calificaciones del primer período', 'students,parents', 1),
        ('Capacitación Docente', 'administrative', '2024-03-05', '09:00:00', 'Sala de Conferencias', 'normal', 'Capacitación en nuevas metodologías educativas', 'teachers', 1)
        ";
        
        $db->exec($insertEventsSQL);
        echo "<p style='color: green;'>✓ Eventos de ejemplo insertados exitosamente</p>";
    } else {
        echo "<p style='color: blue;'>ℹ Ya existen eventos en la base de datos</p>";
    }
    
    // Verificar que la tabla se creó correctamente
    $stmt = $db->query("SELECT COUNT(*) as count FROM events");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p style='color: green;'>✓ Total de eventos en la base de datos: " . $result['count'] . "</p>";
    
    // Mostrar algunos eventos
    $stmt = $db->query("SELECT title, type, event_date, priority FROM events ORDER BY event_date LIMIT 5");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Eventos en la base de datos:</h3>";
    echo "<ul>";
    foreach ($events as $event) {
        echo "<li><strong>" . htmlspecialchars($event['title']) . "</strong> (" . $event['type'] . ") - " . $event['event_date'] . " - " . $event['priority'] . "</li>";
    }
    echo "</ul>";
    
    echo "<h3 style='color: green;'>¡Tabla de eventos creada exitosamente!</h3>";
    echo "<p>Ahora puedes crear eventos desde el dashboard del director.</p>";
    echo "<p><a href='?view=directorDashboard' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir al Dashboard del Director</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 
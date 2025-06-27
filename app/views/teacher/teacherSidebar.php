<div class="root-dashboard">
    <div class="root-sidebar">
        <ul>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="home"></i>Inicio</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="school"></i>Assignaturas</a></li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Actividades</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('reports/attendance')">Reportes de Asistencia</a></li>
                    <li><a href="#" onclick="loadView('reports/actStatistics')">Estadísticas</a></li>
                </ul>
            </li>
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('reports/attendance')">Reportes de Asistencia</a></li>
                    <li><a href="#" onclick="loadView('reports/attStatistics')">Estadísticas</a></li>
                </ul>
            </li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="user"></i>Calificaciones</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="bar-chart-2"></i>Reportes</a></li>
            <li><a href="<?php ROOT; ?>#"><i data-lucide="settings"></i>Configuración</a></li>
        </ul>
    </div>
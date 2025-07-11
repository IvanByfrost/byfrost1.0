<div class="treasurer-dashboard">
    <div class="treasurer-sidebar">
        <ul>
            <li><a href="#" onclick="loadView('treasurer/dashboard')"><i data-lucide="home"></i>Inicio</a></li>
            
            <!-- Sección de Nómina - Principal para el Tesorero -->
            <li class="has-submenu">
                <a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('payroll/dashboard')">📊 Dashboard</a></li>
                    <li><a href="#" onclick="loadView('payroll/employees')">👥 Empleados</a></li>
                    <li><a href="#" onclick="loadView('payroll/periods')">📅 Períodos</a></li>
                    <li><a href="#" onclick="loadView('payroll/absences')">🏥 Ausencias</a></li>
                    <li><a href="#" onclick="loadView('payroll/overtime')">⏰ Horas Extras</a></li>
                    <li><a href="#" onclick="loadView('payroll/bonuses')">🎁 Bonificaciones</a></li>
                    <li><a href="#" onclick="loadView('payroll/reports')">📈 Reportes</a></li>
                </ul>
            </li>
            
            <!-- Sección de Reportes Financieros -->
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes Financieros</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('reports/financial')">💰 Reportes Financieros</a></li>
                    <li><a href="#" onclick="loadView('reports/payroll')">💵 Reportes de Nómina</a></li>
                    <li><a href="#" onclick="loadView('reports/statistics')">📊 Estadísticas</a></li>
                </ul>
            </li>
            
            <!-- Sección de Configuración -->
            <li class="has-submenu">
                <a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=usuarios')">👥 Usuarios</a></li>
                    <li><a href="#" onclick="loadView('user/settingsRoles?section=recuperar')">🔐 Recuperar contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div> 
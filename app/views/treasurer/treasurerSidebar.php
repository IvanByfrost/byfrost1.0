<div class="treasurer-dashboard">
    <div class="treasurer-sidebar">
        <ul>
            <li><a href="#" onclick="safeLoadView('treasurer/dashboard')"><i data-lucide="home"></i>Inicio</a></li>
            
            <!-- Sección de Nómina - Principal para el Tesorero -->
            <li class="has-submenu">
                <a href="#"><i data-lucide="dollar-sign"></i>Nómina</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('payroll/dashboard')">📊 Dashboard</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/employees')">👥 Empleados</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/periods')">📅 Períodos</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/absences')">🏥 Ausencias</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/overtime')">⏰ Horas Extras</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/bonuses')">🎁 Bonificaciones</a></li>
                    <li><a href="#" onclick="safeLoadView('payroll/reports')">📈 Reportes</a></li>
                </ul>
            </li>
            
            <!-- Sección de Reportes Financieros -->
            <li class="has-submenu">
                <a href="#"><i data-lucide="bar-chart-2"></i>Reportes Financieros</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('reports/financial')">💰 Reportes Financieros</a></li>
                    <li><a href="#" onclick="safeLoadView('reports/payroll')">💵 Reportes de Nómina</a></li>
                    <li><a href="#" onclick="safeLoadView('reports/statistics')">📊 Estadísticas</a></li>
                </ul>
            </li>
            
            <!-- Sección de Configuración -->
            <li class="has-submenu">
                <a href="#"><i data-lucide="settings"></i>Configuración</a>
                <ul class="submenu">
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=usuarios')">👥 Usuarios</a></li>
                    <li><a href="#" onclick="safeLoadView('user/settingsRoles?section=recuperar')">🔐 Recuperar contraseña</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div> 
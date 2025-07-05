    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/dashboard'); return false;">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/basicData?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-user"></i>
                    <span>Datos Básicos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/academicInfo?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Información Académica</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/keyRelations?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-users"></i>
                    <span>Relaciones Clave</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/listTasks?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-tasks"></i>
                    <span>Tareas</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/listWorks?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-file-alt"></i>
                    <span>Trabajos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/listActivities?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Actividades</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/listReports?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/academicHistory?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-history"></i>
                    <span>Historial Académico</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/documents?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-folder"></i>
                    <span>Documentos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="safeLoadView('student/security?studentId=<?php echo $_SESSION['user_id']; ?>'); return false;">
                    <i class="fas fa-shield-alt"></i>
                    <span>Seguridad</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="?controller=login&action=logout" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
            <span>Cerrar Sesión</span>
        </a>
    </div>
</div> 
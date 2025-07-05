    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="?controller=student&action=dashboard" class="nav-link <?php echo ($_GET['action'] ?? '') === 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=basicData&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'basicData' ? 'active' : ''; ?>">
                    <i class="fas fa-user"></i>
                    <span>Datos Básicos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=academicInfo&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'academicInfo' ? 'active' : ''; ?>">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Información Académica</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=keyRelations&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'keyRelations' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i>
                    <span>Relaciones Clave</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=listTasks&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'listTasks' ? 'active' : ''; ?>">
                    <i class="fas fa-tasks"></i>
                    <span>Tareas</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=listWorks&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'listWorks' ? 'active' : ''; ?>">
                    <i class="fas fa-file-alt"></i>
                    <span>Trabajos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=listActivities&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'listActivities' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Actividades</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=listReports&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'listReports' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=academicHistory&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'academicHistory' ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i>
                    <span>Historial Académico</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=documents&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'documents' ? 'active' : ''; ?>">
                    <i class="fas fa-folder"></i>
                    <span>Documentos</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="?controller=student&action=security&studentId=<?php echo $_SESSION['user_id']; ?>" class="nav-link <?php echo ($_GET['action'] ?? '') === 'security' ? 'active' : ''; ?>">
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

<style>
.sidebar {
    width: 250px;
    height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

.sidebar-header {
    padding: 20px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.sidebar-header .logo {
    width: 60px;
    height: 60px;
    margin-bottom: 10px;
}

.sidebar-header h5 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
}

.sidebar-nav {
    padding: 20px 0;
}

.nav-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 5px;
}

.nav-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: white;
    text-decoration: none;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.nav-link:hover {
    background: rgba(255,255,255,0.1);
    border-left-color: #fff;
    text-decoration: none;
    color: white;
}

.nav-link.active {
    background: rgba(255,255,255,0.2);
    border-left-color: #fff;
}

.nav-link i {
    width: 20px;
    margin-right: 10px;
    font-size: 14px;
}

.nav-link span {
    font-size: 14px;
    font-weight: 500;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid rgba(255,255,255,0.1);
    position: absolute;
    bottom: 0;
    width: 100%;
    box-sizing: border-box;
}

.logout-btn {
    display: flex;
    align-items: center;
    color: white;
    text-decoration: none;
    padding: 10px;
    border-radius: 5px;
    transition: background 0.3s ease;
}

.logout-btn:hover {
    background: rgba(255,255,255,0.1);
    text-decoration: none;
    color: white;
}

.logout-btn i {
    margin-right: 10px;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
    }
    
    .sidebar.open {
        transform: translateX(0);
    }
}
</style> 
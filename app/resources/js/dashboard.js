/**
 * Dashboard JavaScript - Versión Modular
 * Maneja toda la funcionalidad del dashboard del director
 */

class DashboardManager {
    constructor() {
        this.charts = {};
        this.init();
    }

    init() {
        console.log('Inicializando DashboardManager...');
        this.loadKPIs();
        this.setupEventListeners();
        this.initializeCharts();
    }

    // Cargar KPIs
    loadKPIs() {
        // Simular datos - en producción vendrían de la API
        const kpis = {
            totalStudents: '1,247',
            totalTeachers: '89',
            attendanceRate: '94.2%',
            pendingTasks: '12'
        };

        Object.keys(kpis).forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = kpis[id];
            }
        });
    }

    // Configurar event listeners
    setupEventListeners() {
        // Toggle de secciones
        document.querySelectorAll('[data-toggle-section]').forEach(button => {
            button.addEventListener('click', (e) => {
                const sectionId = e.target.dataset.toggleSection;
                this.toggleSection(sectionId);
            });
        });
    }

    // Alternar secciones
    toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
        
        if (section && icon) {
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.className = 'fas fa-chevron-down';
            } else {
                section.style.display = 'none';
                icon.className = 'fas fa-chevron-right';
            }
        }
    }

    // Inicializar gráficos
    initializeCharts() {
        setTimeout(() => {
            this.createAttendanceChart();
            this.createStudentsChart();
            this.createPerformanceChart();
        }, 100);
    }

    // Gráfico de asistencia
    createAttendanceChart() {
        const ctx = document.getElementById('attendanceChart');
        if (!ctx) return;

        try {
            this.charts.attendance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Asistencia (%)',
                        data: [92, 94, 91, 95, 93, 94],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creando gráfico de asistencia:', error);
        }
    }

    // Gráfico de distribución de estudiantes
    createStudentsChart() {
        const ctx = document.getElementById('studentsChart');
        if (!ctx) return;

        try {
            this.charts.students = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Primaria', 'Secundaria', 'Bachillerato'],
                    datasets: [{
                        data: [450, 380, 417],
                        backgroundColor: [
                            'rgb(255, 99, 132)',
                            'rgb(54, 162, 235)',
                            'rgb(255, 205, 86)'
                        ]
                    }]
                },
                options: {
                    responsive: true
                }
            });
        } catch (error) {
            console.error('Error creando gráfico de estudiantes:', error);
        }
    }

    // Gráfico de rendimiento académico
    createPerformanceChart() {
        const ctx = document.getElementById('performanceChart');
        if (!ctx) return;

        try {
            this.charts.performance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Promedio General',
                        data: [85, 87, 86, 89, 88, 90],
                        backgroundColor: 'rgba(54, 162, 235, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error creando gráfico de rendimiento:', error);
        }
    }

    // Carga dinámica de vistas usando AJAX
    safeLoadView(viewName) {
        const mainContent = document.getElementById('mainContent');
        if (mainContent) {
            mainContent.innerHTML = '<div class="text-center p-5"><span class="spinner-border"></span> Cargando...</div>';
        }
        
        // Mapeo de vistas a módulos (para vistas que no siguen el patrón estándar)
        const viewToModuleMap = {
            // Vistas del módulo user
            'consultUser': 'user',
            'assignRole': 'user', 
            'roleHistory': 'user',
            'settingsRoles': 'user',
            'changePasswordForm': 'user',
            'assignPerm': 'user',
            
            // Vistas del módulo director
            'editDirector': 'director',
            'createDirector': 'director',
            'directorLists': 'director',
            'createEvent': 'director',
            'menuDirector': 'director',
            'reports': 'director',
            'attendanceDetails': 'director',
            'directorSidebar': 'director',
            'dashboard-simple': 'director',
            'academicAveragesOptimized': 'director',
            
            // Vistas del módulo school
            'createSchool': 'school',
            'editSchool': 'school',
            'consultSchool': 'school',
            'completeSchool': 'school',
            
            // Vistas del módulo student
            'academicHistory': 'student',
            'academicHistoryForm': 'student',
            'academicHistoryList': 'student',
            'createStudent': 'student',
            'editStudent': 'student',
            'consultStudent': 'student',
            'studentList': 'student',
            'studentProfile': 'student',
            'studentGrades': 'student',
            'studentAttendance': 'student',
            'studentSchedule': 'student',
            'studentDocuments': 'student',
            'studentPayments': 'student',
            'studentReports': 'student',
            'studentDashboard': 'student',
            
            // Vistas del módulo teacher
            'assessStudent': 'teacher',
            'readSchedule': 'teacher',
            'teacherDashboard': 'teacher',
            'teacherProfile': 'teacher',
            'teacherGrades': 'teacher',
            'teacherAttendance': 'teacher',
            'teacherSchedule': 'teacher',
            'teacherReports': 'teacher',
            
            // Vistas del módulo payroll
            'dashboard': 'payroll',
            'employees': 'payroll',
            'periods': 'payroll',
            'absences': 'payroll',
            'bonuses': 'payroll',
            'createEmployee': 'payroll',
            'editEmployee': 'payroll',
            'viewEmployee': 'payroll',
            'createPeriod': 'payroll',
            'editPeriod': 'payroll',
            'viewPeriod': 'payroll',
            
            // Vistas del módulo activity
            'create': 'activity',
            'edit': 'activity',
            'dashboard': 'activity',
            'list': 'activity',
            'view': 'activity',
            
            // Vistas del módulo schedule
            'schedule': 'schedule',
            'Events': 'schedule',
            
            // Vistas del módulo coordinator
            'coordinatorDashboard': 'coordinator',
            'studentManagement': 'coordinator',
            'teacherManagement': 'coordinator',
            'subjectManagement': 'coordinator',
            'coordSidebar': 'coordinator',
            
            // Vistas del módulo treasurer
            'treasurerDashboard': 'treasurer',
            'paymentManagement': 'treasurer',
            'financialReports': 'treasurer',
            'treasurerSidebar': 'treasurer',
            
            // Vistas del módulo parent
            'parentDashboard': 'parent',
            'childrenProgress': 'parent',
            
            // Vistas del módulo root
            'rootDashboard': 'root',
            'userManagement': 'root',
            'roleManagement': 'root',
            'systemSettings': 'root',
            'menuRoot': 'root',
            'rootSidebar': 'root',
            
            // Vistas del módulo role
            'editRole': 'role',
            'listRoles': 'role',
            
            // Vistas del módulo academicAverages
            'academicAveragesDashboard': 'academicAverages',
            'academicAveragesForm': 'academicAverages',
            'academicAveragesList': 'academicAverages',
            
            // Vistas del módulo studentStats
            'studentStatsDashboard': 'studentStats',
            'studentStatsForm': 'studentStats',
            'studentStatsList': 'studentStats'
        };
        
        // AJAX universal: si la vista contiene '/', separar módulo y vista
        if (viewName.includes('/')) {
            const [module, partialView] = viewName.split('/');
            const localUrl = `?view=${encodeURIComponent(module)}&action=loadPartial&partialView=${encodeURIComponent(partialView)}`;
            fetch(localUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.text())
                .then(html => {
                    if (mainContent) mainContent.innerHTML = html;
                })
                .catch(error => {
                    if (mainContent) mainContent.innerHTML = `<div class='alert alert-danger'>Error al cargar la vista: ${error}</div>`;
                });
        } else if (viewToModuleMap[viewName]) {
            // Vistas que tienen un módulo específico asignado
            const module = viewToModuleMap[viewName];
            const localUrl = `?view=${encodeURIComponent(module)}&action=loadPartial&partialView=${encodeURIComponent(viewName)}`;
            fetch(localUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.text())
                .then(html => {
                    if (mainContent) mainContent.innerHTML = html;
                })
                .catch(error => {
                    if (mainContent) mainContent.innerHTML = `<div class='alert alert-danger'>Error al cargar la vista: ${error}</div>`;
                });
        } else {
            // Método actual para vistas propias del dashboard (fallback)
            const localUrl = `?view=${encodeURIComponent(viewName)}&action=loadPartial`;
            fetch(localUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(response => response.text())
                .then(html => {
                    if (mainContent) mainContent.innerHTML = html;
                })
                .catch(error => {
                    if (mainContent) mainContent.innerHTML = `<div class='alert alert-danger'>Error al cargar la vista: ${error}</div>`;
                });
        }
    }
}

// Modularización para inicialización por vista
window.initDirectorDashboard = function() {
    if (!window.dashboardManager) {
        if (typeof Chart === 'undefined') {
            console.error('Chart.js no está cargado');
            return;
        }
        window.dashboardManager = new DashboardManager();
    }
};

window.initDirectorDashboardSimple = function() {
    // Si hay lógica específica para dashboard-simple, ponla aquí
    // Por ahora, reutiliza la misma inicialización
    window.initDirectorDashboard();
};

// Eliminar la inicialización automática en DOMContentLoaded
// document.addEventListener('DOMContentLoaded', function() {
//     console.log('DOM cargado, inicializando dashboard...');
    
//     // Verificar que Chart.js esté disponible
//     if (typeof Chart === 'undefined') {
//         console.error('Chart.js no está cargado');
//         return;
//     }
    
//     // Crear instancia del dashboard
//     window.dashboardManager = new DashboardManager();
    
//     // Hacer loadView disponible globalmente
//     window.loadView = function(viewName) {
//         if (window.dashboardManager && typeof window.dashboardManager.safeLoadView === 'function') {
//             window.dashboardManager.safeLoadView(viewName);
//         } else {
//             window.location.href = '?view=' + viewName;
//         }
//     };

//     window.safeLoadView = function(viewName) {
//         // NO llamar a loadView aquí, solo redirigir
//         window.location.href = '?view=' + viewName;
//     };
// }); 
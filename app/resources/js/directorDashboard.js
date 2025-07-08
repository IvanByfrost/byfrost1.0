/**
 * Director Dashboard JavaScript
 * Funcionalidades específicas para el dashboard del director
 */

class DirectorDashboard {
    constructor() {
        this.baseUrl = window.BASE_URL || '/';
        this.charts = {};
        this.init();
    }

    /**
     * Inicializa el dashboard
     */
    init() {
        this.loadMetrics();
        this.loadCommunicationData();
        this.initializeCharts();
        this.setupEventListeners();
        this.startAutoRefresh();
        this.initializeCollapsibleSections();
    }

    /**
     * Carga las métricas del dashboard
     */
    loadMetrics() {
        fetch(`${this.baseUrl}?view=directorDashboard&action=getMetrics`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error cargando métricas:', data.error);
                return;
            }
            this.updateKPIs(data);
        })
        .catch(error => {
            console.error('Error cargando métricas:', error);
            // Usar datos por defecto si hay error
            this.updateKPIs({
                totalStudents: 1247,
                totalTeachers: 89,
                attendanceRate: 94.2,
                pendingTasks: 12
            });
        });
    }

    /**
     * Carga los datos de comunicación
     */
    loadCommunicationData() {
        fetch(`${this.baseUrl}?view=directorDashboard&action=getCommunicationData`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error cargando datos de comunicación:', data.error);
                return;
            }
            this.updateCommunicationData(data);
        })
        .catch(error => {
            console.error('Error cargando datos de comunicación:', error);
            // Usar datos por defecto si hay error
            this.updateCommunicationData({
                importantAnnouncements: [
                    {
                        title: '¡Anuncio Importante!',
                        message: 'Reunión de padres programada para el próximo viernes 15 de marzo a las 6:00 PM. Todos los padres deben asistir.',
                        type: 'warning'
                    }
                ],
                monthlyEvents: [
                    {
                        title: 'Reunión de Padres',
                        date: '2024-03-15',
                        time: '18:00',
                        type: 'important'
                    },
                    {
                        title: 'Exámenes Finales',
                        date: '2024-03-20',
                        time: '08:00',
                        type: 'academic'
                    },
                    {
                        title: 'Festival de Arte',
                        date: '2024-03-28',
                        time: '14:00',
                        type: 'cultural'
                    }
                ],
                parentCommunications: {
                    totalSent: 247,
                    readRate: 89
                },
                recentNotifications: [
                    {
                        title: 'Nuevo estudiante registrado',
                        message: 'María González se ha registrado en el sistema.',
                        time_ago: 'Hace 3 horas'
                    },
                    {
                        title: 'Reporte mensual listo',
                        message: 'El reporte de febrero está disponible.',
                        time_ago: 'Hace 1 día'
                    },
                    {
                        title: 'Actualización de horarios',
                        message: 'Los horarios han sido actualizados.',
                        time_ago: 'Hace 2 días'
                    }
                ]
            });
        });
    }

    /**
     * Actualiza los KPIs en el dashboard
     */
    updateKPIs(metrics) {
        const elements = {
            totalStudents: document.getElementById('totalStudents'),
            totalTeachers: document.getElementById('totalTeachers'),
            attendanceRate: document.getElementById('attendanceRate'),
            pendingTasks: document.getElementById('pendingTasks')
        };

        if (elements.totalStudents) {
            elements.totalStudents.textContent = this.formatNumber(metrics.totalStudents);
        }
        if (elements.totalTeachers) {
            elements.totalTeachers.textContent = this.formatNumber(metrics.totalTeachers);
        }
        if (elements.attendanceRate) {
            elements.attendanceRate.textContent = `${metrics.attendanceRate}%`;
        }
        if (elements.pendingTasks) {
            elements.pendingTasks.textContent = this.formatNumber(metrics.pendingTasks);
        }

        // Actualizar datos de comunicación si están disponibles
        if (metrics.communicationData) {
            this.updateCommunicationData(metrics.communicationData);
        }
    }

    /**
     * Actualiza los datos de comunicación
     */
    updateCommunicationData(communicationData) {
        // Actualizar anuncios importantes
        if (communicationData.importantAnnouncements) {
            this.updateImportantAnnouncements(communicationData.importantAnnouncements);
        }

        // Actualizar eventos del mes
        if (communicationData.monthlyEvents) {
            this.updateMonthlyEvents(communicationData.monthlyEvents);
        }

        // Actualizar estadísticas de comunicación con padres
        if (communicationData.parentCommunications) {
            this.updateParentCommunications(communicationData.parentCommunications);
        }

        // Actualizar notificaciones recientes
        if (communicationData.recentNotifications) {
            this.updateRecentNotifications(communicationData.recentNotifications);
        }
    }

    /**
     * Actualiza los anuncios importantes
     */
    updateImportantAnnouncements(announcements) {
        const container = document.getElementById('importantAnnouncements');
        if (!container) return;

        container.innerHTML = '';
        
        if (announcements.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay anuncios importantes en este momento.
                    </div>
                </div>
            `;
            return;
        }

        announcements.forEach(announcement => {
            const alertClass = announcement.type === 'warning' ? 'alert-warning' : 'alert-info';
            const iconClass = announcement.type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle';
            
            const announcementElement = document.createElement('div');
            announcementElement.className = 'col-12';
            announcementElement.innerHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas ${iconClass} fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">${announcement.title}</h5>
                            <p class="mb-0">${announcement.message}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            container.appendChild(announcementElement);
        });
    }

    /**
     * Actualiza los eventos del mes
     */
    updateMonthlyEvents(events) {
        const eventsContainer = document.getElementById('monthlyEventsList');
        if (!eventsContainer) return;

        eventsContainer.innerHTML = '';
        
        if (events.length === 0) {
            eventsContainer.innerHTML = `
                <div class="list-group-item text-center text-muted">
                    <i class="fas fa-calendar-times fa-2x mb-2"></i>
                    <p class="mb-0">No hay eventos programados para este mes.</p>
                </div>
            `;
            return;
        }
        
        events.forEach(event => {
            const eventElement = document.createElement('div');
            eventElement.className = 'list-group-item d-flex justify-content-between align-items-center';
            
            const badgeClass = this.getBadgeClass(event.type);
            const formattedDate = this.formatEventDate(event.date, event.time);
            
            eventElement.innerHTML = `
                <div>
                    <h6 class="mb-1">${event.title}</h6>
                    <small class="text-muted">${formattedDate}</small>
                </div>
                <span class="badge ${badgeClass} rounded-pill">${this.getEventTypeLabel(event.type)}</span>
            `;
            
            eventsContainer.appendChild(eventElement);
        });
    }

    /**
     * Formatea la fecha del evento
     */
    formatEventDate(date, time) {
        if (!date) return 'Fecha por definir';
        
        const eventDate = new Date(date);
        const options = { day: 'numeric', month: 'short' };
        const formattedDate = eventDate.toLocaleDateString('es-ES', options);
        
        return time ? `${formattedDate} - ${time}` : formattedDate;
    }

    /**
     * Actualiza las estadísticas de comunicación con padres
     */
    updateParentCommunications(stats) {
        const totalSentElement = document.getElementById('totalMessagesSent');
        const readRateElement = document.getElementById('readRate');
        const progressBar = document.getElementById('readRateProgress');

        if (totalSentElement) {
            totalSentElement.textContent = this.formatNumber(stats.totalSent || 0);
        }
        if (readRateElement) {
            readRateElement.textContent = `${stats.readRate || 0}%`;
        }
        if (progressBar) {
            progressBar.style.width = `${stats.readRate || 0}%`;
        }
    }

    /**
     * Actualiza las notificaciones recientes
     */
    updateRecentNotifications(notifications) {
        const notificationsContainer = document.getElementById('recentNotificationsList');
        if (!notificationsContainer) return;

        notificationsContainer.innerHTML = '';
        
        if (notifications.length === 0) {
            notificationsContainer.innerHTML = `
                <div class="list-group-item text-center text-muted">
                    <i class="fas fa-bell-slash fa-2x mb-2"></i>
                    <p class="mb-0">No hay notificaciones recientes.</p>
                </div>
            `;
            return;
        }
        
        notifications.forEach(notification => {
            const notificationElement = document.createElement('div');
            notificationElement.className = 'list-group-item';
            
            notificationElement.innerHTML = `
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1">${notification.title}</h6>
                    <small class="text-muted">${notification.time_ago || 'Reciente'}</small>
                </div>
                <p class="mb-1">${notification.message}</p>
            `;
            
            notificationsContainer.appendChild(notificationElement);
        });
    }

    /**
     * Obtiene la clase CSS para el badge según el tipo de evento
     */
    getBadgeClass(eventType) {
        const badgeClasses = {
            'important': 'bg-primary',
            'academic': 'bg-warning',
            'cultural': 'bg-success',
            'sports': 'bg-info',
            'default': 'bg-secondary'
        };
        
        return badgeClasses[eventType] || badgeClasses.default;
    }

    /**
     * Obtiene la etiqueta para el tipo de evento
     */
    getEventTypeLabel(eventType) {
        const labels = {
            'important': 'Importante',
            'academic': 'Académico',
            'cultural': 'Cultural',
            'sports': 'Deportes',
            'default': 'Evento'
        };
        
        return labels[eventType] || labels.default;
    }

    /**
     * Formatea números con separadores de miles
     */
    formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    /**
     * Inicializa los gráficos del dashboard
     */
    initializeCharts() {
        this.createAttendanceChart();
        this.createStudentsChart();
        this.createPerformanceChart();
    }

    /**
     * Crea el gráfico de asistencia
     */
    createAttendanceChart() {
        const ctx = document.getElementById('attendanceChart');
        if (!ctx) return;

        this.charts.attendance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Asistencia (%)',
                    data: [92, 94, 91, 95, 93, 94],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Crea el gráfico de distribución de estudiantes
     */
    createStudentsChart() {
        const ctx = document.getElementById('studentsChart');
        if (!ctx) return;

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
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }

    /**
     * Crea el gráfico de rendimiento académico
     */
    createPerformanceChart() {
        const ctx = document.getElementById('performanceChart');
        if (!ctx) return;

        this.charts.performance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                datasets: [{
                    label: 'Promedio General',
                    data: [85, 87, 86, 89, 88, 90],
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgb(54, 162, 235)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Configura los event listeners
     */
    setupEventListeners() {
        // Event listener para botones de navegación
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-load-view]')) {
                e.preventDefault();
                const view = e.target.getAttribute('data-load-view');
                this.loadView(view);
            }
        });

        // Event listener para actualizar métricas manualmente
        const refreshBtn = document.getElementById('refreshMetrics');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.loadMetrics();
                this.showNotification('Métricas actualizadas', 'success');
            });
        }

        // Event listeners para secciones desplegables
        this.setupCollapsibleSections();
    }

    /**
     * Inicializa las secciones desplegables
     */
    initializeCollapsibleSections() {
        // Por defecto, todas las secciones están abiertas
        const sections = ['metricsSection', 'academicSection', 'adminSection', 'communicationSection', 'chartsSection'];
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                section.style.display = 'block';
            }
        });
    }

    /**
     * Configura los event listeners para secciones desplegables
     */
    setupCollapsibleSections() {
        const sectionHeaders = document.querySelectorAll('.card-header[onclick]');
        
        sectionHeaders.forEach(header => {
            header.addEventListener('click', (e) => {
                e.preventDefault();
                const sectionId = header.getAttribute('onclick').match(/toggleSection\('([^']+)'\)/)[1];
                this.toggleSection(sectionId);
            });
        });
    }

    /**
     * Alterna la visibilidad de una sección
     */
    toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
        
        if (!section || !icon) return;

        const isVisible = section.style.display !== 'none';
        
        if (isVisible) {
            // Ocultar sección
            section.style.display = 'none';
            icon.className = 'fas fa-chevron-right';
            this.animateSectionClose(section);
        } else {
            // Mostrar sección
            section.style.display = 'block';
            icon.className = 'fas fa-chevron-down';
            this.animateSectionOpen(section);
        }
    }

    /**
     * Anima la apertura de una sección
     */
    animateSectionOpen(section) {
        section.style.opacity = '0';
        section.style.transform = 'translateY(-10px)';
        
        setTimeout(() => {
            section.style.transition = 'all 0.3s ease';
            section.style.opacity = '1';
            section.style.transform = 'translateY(0)';
        }, 10);
    }

    /**
     * Anima el cierre de una sección
     */
    animateSectionClose(section) {
        section.style.transition = 'all 0.3s ease';
        section.style.opacity = '0';
        section.style.transform = 'translateY(-10px)';
    }

    /**
     * Inicia la actualización automática de métricas
     */
    startAutoRefresh() {
        // Actualizar métricas cada 5 minutos
        setInterval(() => {
            this.loadMetrics();
        }, 5 * 60 * 1000);
    }

    /**
     * Carga una vista específica
     */
    loadView(viewName) {
        if (typeof window.loadView === 'function') {
            window.loadView(viewName);
        } else {
            // Fallback: redirigir a la página
            const url = `${this.baseUrl}?view=${viewName.replace('/', '&action=')}`;
            window.location.href = url;
        }
    }

    /**
     * Muestra una notificación
     */
    showNotification(message, type = 'info') {
        // Usar SweetAlert2 si está disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            // Fallback: alert básico
            alert(message);
        }
    }

    /**
     * Actualiza los datos de los gráficos
     */
    updateCharts(newData) {
        if (this.charts.attendance && newData.attendance) {
            this.charts.attendance.data.labels = newData.attendance.labels;
            this.charts.attendance.data.datasets[0].data = newData.attendance.data;
            this.charts.attendance.update();
        }

        if (this.charts.students && newData.students) {
            this.charts.students.data.labels = newData.students.labels;
            this.charts.students.data.datasets[0].data = newData.students.data;
            this.charts.students.update();
        }

        if (this.charts.performance && newData.performance) {
            this.charts.performance.data.labels = newData.performance.labels;
            this.charts.performance.data.datasets[0].data = newData.performance.data;
            this.charts.performance.update();
        }
    }

    /**
     * Exporta el dashboard como PDF
     */
    exportDashboard() {
        // Implementar exportación a PDF
        this.showNotification('Función de exportación en desarrollo', 'info');
    }

    /**
     * Imprime el dashboard
     */
    printDashboard() {
        window.print();
    }
}

// Inicializar el dashboard cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que estamos en el dashboard del director
    if (document.querySelector('.dashboard-container')) {
        window.directorDashboard = new DirectorDashboard();
    }
});

// Funciones globales para compatibilidad
window.loadMetrics = function() {
    if (window.directorDashboard) {
        window.directorDashboard.loadMetrics();
    }
};

window.refreshCharts = function() {
    if (window.directorDashboard) {
        window.directorDashboard.initializeCharts();
    }
}; 
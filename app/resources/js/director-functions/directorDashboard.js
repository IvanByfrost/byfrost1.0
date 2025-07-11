/**
 * Director Dashboard JavaScript
 * Módulo principal que coordina los sub-módulos
 */

class DirectorDashboard {
    constructor() {
        this.baseUrl = window.BASE_URL || '/';
        this.metrics = new DirectorMetrics(this.baseUrl);
        this.communication = new DirectorCommunication(this.baseUrl);
        this.charts = new DirectorCharts();
        this.init();
    }

    /**
     * Inicializa el dashboard
     */
    init() {
        this.loadData();
        this.setupEventListeners();
        this.startAutoRefresh();
        this.initializeCollapsibleSections();
    }

    /**
     * Carga todos los datos del dashboard
     */
    async loadData() {
        try {
            // Cargar métricas
            const metrics = await this.metrics.loadMetrics();
            this.metrics.updateKPIs(metrics);

            // Cargar datos de comunicación
            const communicationData = await this.communication.loadCommunicationData();
            this.communication.updateCommunicationData(communicationData);

            // Inicializar gráficos
            this.charts.initializeCharts();

        } catch (error) {
            console.error('Error cargando datos del dashboard:', error);
        }
    }

    /**
     * Configura los event listeners
     */
    setupEventListeners() {
        // Event listeners para botones de acción
        document.querySelectorAll('[data-action]').forEach(button => {
            button.addEventListener('click', (e) => {
                const action = e.target.dataset.action;
                this.handleAction(action, e.target);
            });
        });

        // Event listeners para navegación
        document.querySelectorAll('[data-view]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const view = e.target.dataset.view;
                this.loadView(view);
            });
        });
    }

    /**
     * Maneja las acciones del dashboard
     */
    handleAction(action, element) {
        switch (action) {
            case 'export':
                this.exportDashboard();
                break;
            case 'print':
                this.printDashboard();
                break;
            case 'refresh':
                this.loadData();
                break;
            default:
                console.log('Acción no reconocida:', action);
        }
    }

    /**
     * Inicializa las secciones colapsables
     */
    initializeCollapsibleSections() {
        document.querySelectorAll('[data-toggle-section]').forEach(button => {
            button.addEventListener('click', (e) => {
                const sectionId = e.target.dataset.toggleSection;
                this.toggleSection(sectionId);
            });
        });
    }

    /**
     * Alterna una sección
     */
    toggleSection(sectionId) {
        const section = document.getElementById(sectionId);
        const icon = document.getElementById(sectionId.replace('Section', 'Icon'));
        
        if (section && icon) {
            if (section.style.display === 'none') {
                section.style.display = 'block';
                icon.className = 'fas fa-chevron-down';
                this.animateSectionOpen(section);
            } else {
                section.style.display = 'none';
                icon.className = 'fas fa-chevron-right';
                this.animateSectionClose(section);
            }
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
     * Inicia el auto-refresh
     */
    startAutoRefresh() {
        // Refrescar datos cada 5 minutos
        setInterval(() => {
            this.loadData();
        }, 5 * 60 * 1000);
    }

    /**
     * Carga una vista usando loadView
     */
    loadView(viewName) {
        if (typeof loadView === 'function') {
            loadView(viewName);
        } else {
            console.error('loadView no está disponible');
        }
    }

    /**
     * Muestra una notificación
     */
    showNotification(message, type = 'info') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type === 'error' ? 'Error' : 'Información',
                text: message,
                icon: type,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            alert(message);
        }
    }

    /**
     * Exporta el dashboard
     */
    exportDashboard() {
        this.showNotification('Función de exportación en desarrollo');
    }

    /**
     * Imprime el dashboard
     */
    printDashboard() {
        window.print();
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando DirectorDashboard...');
    window.directorDashboard = new DirectorDashboard();
});

// Función para inicializar después de que loadViews.js cargue el contenido
function initDirectorDashboardAfterLoad() {
    console.log('Inicializando dashboard del director después de carga de vista...');
    
    setTimeout(() => {
        if (typeof DirectorDashboard !== 'undefined') {
            window.directorDashboard = new DirectorDashboard();
        }
    }, 100);
}

// Asegurar que la función esté disponible globalmente
window.initDirectorDashboardAfterLoad = initDirectorDashboardAfterLoad; 
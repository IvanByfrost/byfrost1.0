/**
 * Módulo de métricas para el dashboard del director
 */

class DirectorMetrics {
    constructor(baseUrl) {
        this.baseUrl = baseUrl || '/';
    }

    /**
     * Carga las métricas del dashboard
     */
    loadMetrics() {
        return fetch(`${this.baseUrl}?view=directorDashboard&action=getMetrics`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error cargando métricas:', data.error);
                throw new Error(data.error);
            }
            return data;
        })
        .catch(error => {
            console.error('Error cargando métricas:', error);
            // Usar datos por defecto si hay error
            return {
                totalStudents: 1247,
                totalTeachers: 89,
                attendanceRate: 94.2,
                pendingTasks: 12
            };
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
    }

    /**
     * Formatea números con separadores de miles
     */
    formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
} 
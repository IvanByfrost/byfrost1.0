/**
 * Módulo de gráficos para el dashboard del director
 */

class DirectorCharts {
    constructor() {
        this.charts = {};
    }

    /**
     * Inicializa todos los gráficos
     */
    initializeCharts() {
        setTimeout(() => {
            this.createAttendanceChart();
            this.createStudentsChart();
            this.createPerformanceChart();
        }, 100);
    }

    /**
     * Gráfico de asistencia
     */
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

    /**
     * Gráfico de distribución de estudiantes
     */
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

    /**
     * Gráfico de rendimiento académico
     */
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

    /**
     * Actualiza los gráficos con nuevos datos
     */
    updateCharts(newData) {
        if (newData.attendance && this.charts.attendance) {
            this.charts.attendance.data.datasets[0].data = newData.attendance;
            this.charts.attendance.update();
        }
        
        if (newData.students && this.charts.students) {
            this.charts.students.data.datasets[0].data = newData.students;
            this.charts.students.update();
        }
        
        if (newData.performance && this.charts.performance) {
            this.charts.performance.data.datasets[0].data = newData.performance;
            this.charts.performance.update();
        }
    }
} 
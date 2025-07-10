/**
 * Módulo de comunicación para el dashboard del director
 */

class DirectorCommunication {
    constructor(baseUrl) {
        this.baseUrl = baseUrl || '/';
    }

    /**
     * Carga los datos de comunicación
     */
    loadCommunicationData() {
        return fetch(`${this.baseUrl}?view=directorDashboard&action=getCommunicationData`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error('Error cargando datos de comunicación:', data.error);
                throw new Error(data.error);
            }
            return data;
        })
        .catch(error => {
            console.error('Error cargando datos de comunicación:', error);
            // Usar datos por defecto si hay error
            return {
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
            };
        });
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
            container.innerHTML += `
                <div class="col-12 mb-3">
                    <div class="alert ${alertClass}" role="alert">
                        <h6 class="alert-heading">${announcement.title}</h6>
                        <p class="mb-0">${announcement.message}</p>
                    </div>
                </div>
            `;
        });
    }

    /**
     * Actualiza los eventos del mes
     */
    updateMonthlyEvents(events) {
        const container = document.getElementById('monthlyEvents');
        if (!container) return;

        container.innerHTML = '';
        
        if (events.length === 0) {
            container.innerHTML = `
                <div class="col-12">
                    <p class="text-muted">No hay eventos programados para este mes.</p>
                </div>
            `;
            return;
        }

        events.forEach(event => {
            const badgeClass = this.getBadgeClass(event.type);
            const eventTypeLabel = this.getEventTypeLabel(event.type);
            const formattedDate = this.formatEventDate(event.date, event.time);
            
            container.innerHTML += `
                <div class="col-12 mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">${event.title}</h6>
                            <small class="text-muted">${formattedDate}</small>
                        </div>
                        <span class="badge ${badgeClass}">${eventTypeLabel}</span>
                    </div>
                </div>
            `;
        });
    }

    /**
     * Formatea la fecha del evento
     */
    formatEventDate(date, time) {
        const eventDate = new Date(date + 'T' + time);
        return eventDate.toLocaleDateString('es-ES', {
            day: 'numeric',
            month: 'long',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    /**
     * Actualiza las estadísticas de comunicación con padres
     */
    updateParentCommunications(stats) {
        const totalSentElement = document.getElementById('totalSent');
        const readRateElement = document.getElementById('readRate');
        
        if (totalSentElement) {
            totalSentElement.textContent = stats.totalSent;
        }
        if (readRateElement) {
            readRateElement.textContent = `${stats.readRate}%`;
        }
    }

    /**
     * Actualiza las notificaciones recientes
     */
    updateRecentNotifications(notifications) {
        const container = document.getElementById('recentNotifications');
        if (!container) return;

        container.innerHTML = '';
        
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="text-muted">
                    <small>No hay notificaciones recientes.</small>
                </div>
            `;
            return;
        }

        notifications.forEach(notification => {
            container.innerHTML += `
                <div class="notification-item mb-2">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1">${notification.title}</h6>
                            <p class="mb-1 small">${notification.message}</p>
                        </div>
                        <small class="text-muted">${notification.time_ago}</small>
                    </div>
                </div>
            `;
        });
    }

    /**
     * Obtiene la clase CSS para el badge del tipo de evento
     */
    getBadgeClass(eventType) {
        const badgeClasses = {
            'important': 'bg-danger',
            'academic': 'bg-primary',
            'cultural': 'bg-success',
            'sports': 'bg-warning',
            'default': 'bg-secondary'
        };
        return badgeClasses[eventType] || badgeClasses.default;
    }

    /**
     * Obtiene la etiqueta del tipo de evento
     */
    getEventTypeLabel(eventType) {
        const eventLabels = {
            'important': 'Importante',
            'academic': 'Académico',
            'cultural': 'Cultural',
            'sports': 'Deportivo',
            'default': 'Evento'
        };
        return eventLabels[eventType] || eventLabels.default;
    }
} 
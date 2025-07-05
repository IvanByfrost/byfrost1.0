// payrollManagement.js - Lógica JS centralizada para Payroll

document.addEventListener('DOMContentLoaded', function() {
    // ----------- PERIODOS -----------
    window.generatePayroll = function(periodId) {
        if (confirm('¿Estás seguro de que deseas generar la nómina para este período?')) {
            alert('Función de generación de nómina en desarrollo');
        }
    };
    window.closePeriod = function(periodId) {
        if (confirm('¿Estás seguro de que deseas cerrar este período? Esta acción no se puede deshacer.')) {
            alert('Función de cierre de período en desarrollo');
        }
    };
    if (document.getElementById('periodsTable') && typeof window.jQuery !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('#periodsTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
            order: [[1, 'desc']]
        });
    } else if (document.getElementById('periodsTable')) {
        console.warn('jQuery o DataTable no están disponibles para periodsTable.');
    }

    // ----------- HORAS EXTRA -----------
    window.viewJustification = function(justification) {
        var el = document.getElementById('justificationText');
        if (el) el.textContent = justification;
        if (document.getElementById('justificationModal'))
            new bootstrap.Modal(document.getElementById('justificationModal')).show();
    };
    window.approveOvertime = function(overtimeId) {
        if (confirm('¿Estás seguro de que deseas aprobar estas horas extras?')) {
            alert('Función de aprobación en desarrollo');
        }
    };
    window.rejectOvertime = function(overtimeId) {
        if (confirm('¿Estás seguro de que deseas rechazar estas horas extras?')) {
            alert('Función de rechazo en desarrollo');
        }
    };
    if (document.getElementById('overtimeTable') && typeof window.jQuery !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('#overtimeTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
            order: [[1, 'desc']]
        });
    } else if (document.getElementById('overtimeTable')) {
        console.warn('jQuery o DataTable no están disponibles para overtimeTable.');
    }

    // ----------- BONIFICACIONES -----------
    window.viewDescription = function(description) {
        var el = document.getElementById('descriptionText');
        if (el) el.textContent = description;
        if (document.getElementById('descriptionModal'))
            new bootstrap.Modal(document.getElementById('descriptionModal')).show();
    };
    window.approveBonus = function(bonusId) {
        if (confirm('¿Estás seguro de que deseas aprobar esta bonificación?')) {
            alert('Función de aprobación en desarrollo');
        }
    };
    window.cancelBonus = function(bonusId) {
        if (confirm('¿Estás seguro de que deseas cancelar esta bonificación?')) {
            alert('Función de cancelación en desarrollo');
        }
    };
    if (document.getElementById('bonusesTable') && typeof window.jQuery !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('#bonusesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
            order: [[4, 'desc']]
        });
    } else if (document.getElementById('bonusesTable')) {
        console.warn('jQuery o DataTable no están disponibles para bonusesTable.');
    }

    // ----------- AUSENCIAS -----------
    window.approveAbsence = function(absenceId) {
        if (confirm('¿Estás seguro de que deseas aprobar esta ausencia?')) {
            alert('Función de aprobación en desarrollo');
        }
    };
    window.rejectAbsence = function(absenceId) {
        if (confirm('¿Estás seguro de que deseas rechazar esta ausencia?')) {
            alert('Función de rechazo en desarrollo');
        }
    };
    if (document.getElementById('absencesTable') && typeof window.jQuery !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('#absencesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' },
            order: [[2, 'desc']]
        });
    } else if (document.getElementById('absencesTable')) {
        console.warn('jQuery o DataTable no están disponibles para absencesTable.');
    }

    // ----------- EMPLEADOS -----------
    window.confirmDeactivate = function(employeeId, employeeName) {
        if (confirm(`¿Estás seguro de que deseas desactivar al empleado ${employeeName}?`)) {
            alert('Función de desactivación en desarrollo');
        }
    };
    if (document.getElementById('employeesTable') && typeof window.jQuery !== 'undefined' && typeof $.fn.DataTable !== 'undefined') {
        $('#employeesTable').DataTable({
            language: { url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json' }
        });
    } else if (document.getElementById('employeesTable')) {
        console.warn('jQuery o DataTable no están disponibles para employeesTable.');
    }

    // ----------- REPORTES -----------
    window.updateReportOptions = function() {
        const reportType = document.getElementById('report_type')?.value;
        const departmentSelect = document.getElementById('department');
        if (!reportType || !departmentSelect) return;
        switch(reportType) {
            case 'department_summary':
                departmentSelect.style.display = 'none';
                break;
            case 'employee_details':
                departmentSelect.style.display = 'block';
                break;
            default:
                departmentSelect.style.display = 'block';
        }
    };
    window.exportReport = function() {
        const reportType = document.getElementById('report_type')?.value;
        if (!reportType) {
            alert('Por favor selecciona un tipo de reporte');
            return;
        }
        alert('Función de exportación a Excel en desarrollo');
    };
    window.exportPDF = function() {
        const reportType = document.getElementById('report_type')?.value;
        if (!reportType) {
            alert('Por favor selecciona un tipo de reporte');
            return;
        }
        alert('Función de exportación a PDF en desarrollo');
    };
    if (typeof Chart !== 'undefined') {
        if (document.getElementById('departmentChart')) {
            const departmentCtx = document.getElementById('departmentChart').getContext('2d');
            new Chart(departmentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Administración', 'Académico', 'Financiero', 'Recursos Humanos', 'Tecnología', 'Mantenimiento'],
                    datasets: [{
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                        ]
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
            });
        }
        if (document.getElementById('monthlyChart')) {
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Nómina Total',
                        data: [65000, 59000, 80000, 81000, 56000, 55000],
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    }

    // ----------- DATE RANGE PICKER -----------
    if (typeof $.fn.daterangepicker !== 'undefined' && document.getElementById('date_range')) {
        $('#date_range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                fromLabel: 'Desde',
                toLabel: 'Hasta'
            }
        });
    }

    // ----------- FORMULARIOS DE CREACIÓN -----------
    // Validación de createEmployee
    if (document.getElementById('createEmployeeForm')) {
        document.getElementById('createEmployeeForm').addEventListener('submit', function(e) {
            const employeeCode = document.getElementById('employee_code').value;
            const salary = document.getElementById('salary').value;
            if (!/^[A-Z]{3}\d{3}$/.test(employeeCode)) {
                e.preventDefault();
                alert('El código de empleado debe tener el formato EMP001 (3 letras + 3 números)');
                document.getElementById('employee_code').focus();
                return false;
            }
            if (salary <= 0) {
                e.preventDefault();
                alert('El salario debe ser mayor a 0');
                document.getElementById('salary').focus();
                return false;
            }
        });
        document.getElementById('user_id').addEventListener('change', function() {
            const userId = this.value;
            if (userId) {
                const employeeCode = 'EMP' + userId.padStart(3, '0');
                document.getElementById('employee_code').value = employeeCode;
            }
        });
        document.getElementById('hire_date').addEventListener('change', function() {
            const hireDate = new Date(this.value);
            const today = new Date();
            const years = today.getFullYear() - hireDate.getFullYear();
            if (years > 0) {
                console.log('Años de servicio:', years);
            }
        });
    }
    // Validación de createPeriod
    if (document.getElementById('createPeriodForm')) {
        document.getElementById('createPeriodForm').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);
            const paymentDate = new Date(document.getElementById('payment_date').value);
            if (endDate <= startDate) {
                e.preventDefault();
                alert('La fecha de fin debe ser posterior a la fecha de inicio');
                document.getElementById('end_date').focus();
                return false;
            }
            if (paymentDate <= endDate) {
                e.preventDefault();
                alert('La fecha de pago debe ser posterior a la fecha de fin del período');
                document.getElementById('payment_date').focus();
                return false;
            }
        });
        // Resumen automático
        function updateSummary() {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const paymentDate = document.getElementById('payment_date').value;
            const workingDays = document.getElementById('working_days').value;
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                const daysDiff = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
                document.getElementById('duration').textContent = daysDiff + ' días';
            }
            if (workingDays) {
                document.getElementById('workingDaysDisplay').textContent = workingDays + ' días';
            }
            if (endDate && paymentDate) {
                const end = new Date(endDate);
                const payment = new Date(paymentDate);
                const daysToPayment = Math.ceil((payment - end) / (1000 * 60 * 60 * 24));
                document.getElementById('daysToPayment').textContent = daysToPayment + ' días';
            }
        }
        document.getElementById('start_date').addEventListener('change', updateSummary);
        document.getElementById('end_date').addEventListener('change', updateSummary);
        document.getElementById('payment_date').addEventListener('change', updateSummary);
        document.getElementById('working_days').addEventListener('input', updateSummary);
        document.getElementById('start_date').addEventListener('change', function() {
            const startDate = new Date(this.value);
            const monthNames = [
                'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            const monthName = monthNames[startDate.getMonth()];
            const year = startDate.getFullYear();
            document.getElementById('period_name').value = monthName + ' ' + year;
        });
        document.addEventListener('DOMContentLoaded', updateSummary);
    }

    // ----------- DASHBOARD -----------
    window.safeLoadView = function(viewName) {
        if (typeof loadView === 'function') {
            loadView(viewName);
        } else {
            const url = `${BASE_URL}?view=${viewName.replace('/', '&action=')}`;
            window.location.href = url;
        }
    };
}); 
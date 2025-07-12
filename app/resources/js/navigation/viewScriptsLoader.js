window.loadViewScript = function(view) {
    // Mapeo exacto de vistas a scripts
    const scripts = {
        'user/editUser': ['user-management/userEdit.js'],
        'user/consultUser': [
            'user-management/userManagement.js',
            'user-management/userEdit.js'
        ],
        // Agrega aquí otros mapeos exactos según tus vistas
    };

    // Si hay coincidencia exacta, cargar esos scripts
    if (scripts[view]) {
        scripts[view].forEach(script => {
            const path = `${window.BASE_URL}app/resources/js/${script}`;
            importScript(path);
        });
        return;
    }

    // Si no, buscar por prefijo como fallback
    const prefixScripts = {
        'dashboard': ['dashboard/dashboard.js', 'dashboard/rootDashboard.js', 'dashboard/payrollDashboard.js', 'dashboard/treasurerDashboard.js', 'dashboard/studentDashboard.js', 'dashboard/teacherDashboard.js'],
        'school-management': ['school-management/schoolActions.js', 'school-management/schoolSearch.js', 'school-management/schoolForm.js', 'school-management/schoolValidation.js', 'school-management/schoolDirectorSearch.js'],
        'user-management': [
            'user-management/userManagement.js',
            'user-management/roleManagement.js',
            'user-management/userSearch.js',
            'user-management/userEdit.js'
        ],
        'role-management': ['role-management/roleManagement.js'],
        'payroll': ['payroll/payrollManagement.js'],
        'navigation': ['navigation/navigation.js'],
        'director-functions': ['director-functions/directorDashboard.js', 'director-functions/directorMetrics.js', 'director-functions/directorCharts.js', 'director-functions/directorCommunication.js'],
        'activity-management': ['activity-management/activityDashboard.js', 'activity-management/activityActions.js', 'activity-management/activityForm.js', 'activity-management/activityDataTable.js'],
        'utils': ['utils/onlyNumber.js', 'utils/toggles.js', 'utils/sessionHandler.js', 'utils/Uploadpicture.js'],
        'profileSettings': ['profileSettings/profileSettings.js'],
        'Principal': ['Principal/Principal.js'],
    };
    for (const prefix in prefixScripts) {
        if (view.startsWith(prefix)) {
            prefixScripts[prefix].forEach(script => {
                const path = `${window.BASE_URL}app/resources/js/${script}`;
                importScript(path);
            });
            break;
        }
    }
};

// js/navigation/viewScriptsLoader.js

export function loadViewScript(view) {
    // Reglas de carga según prefijos de vista
    const scripts = {
        'dashboard': ['dashboard.js'],
        'school-management': ['schoolForm.js', 'schoolValidation.js', 'schoolDirectorSearch.js'],
        'user-management': ['userManagement.js', 'roleManagement.js', 'userSearch.js'],
        'role-management': ['roleManagement.js'],
        'payroll': ['payrollManagement.js'],
        'navigation': ['navigation.js'],
        'director-functions': ['directorDashboard.js', 'directorMetrics.js', 'directorCharts.js', 'directorCommunication.js'],
        'activity-management': ['activityDashboard.js', 'activityActions.js', 'activityForm.js', 'activityDataTable.js'],
        'utils': ['onlyNumber.js', 'toggles.js', 'sessionHandler.js', 'Uploadpicture.js'],
        'profileSettings': ['profileSettings.js'],
        'Principal': ['Principal.js'],
    };

    for (const prefix in scripts) {
        if (view.startsWith(prefix)) {
            scripts[prefix].forEach(script => {
                const path = `${window.BASE_URL}app/resources/js/${script}`;
                importScript(path);
            });
            break;
        }
    }
}

// Carga dinámica de un script si no ha sido cargado antes
function importScript(src) {
    if (!document.querySelector(`script[src="${src}"]`)) {
        const s = document.createElement('script');
        s.src = src;
        s.type = 'text/javascript';
        document.body.appendChild(s);
    }
}

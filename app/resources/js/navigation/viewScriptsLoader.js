const scriptMap = {
    'director/dashboard': [
      'director-functions/directorMetrics.js',
      'director-functions/directorCharts.js',
      'director-functions/directorCommunication.js',
      'dashboard/directorDashboard.js'
    ],
    'user/profileSettings': [
      'user-management/completeProfile.js'
    ],
    'root/usuarios': [
      'user-management/userSearch.js',
      'role-management/roleManagement.js',
      'user-management/userManagement.js'
    ],
    'school/edit': [
      'school-management/schoolDirectorSelect.js',
      'school-management/schoolCoordinatorSelect.js',
      'school-management/schoolForm.js'
    ],
    // Agrega más vistas según lo necesites
  };
  
  /**
   * Carga dinámicamente scripts según el nombre de la vista
   * @param {string} viewPath - Ejemplo: 'director/dashboard'
   */
  export async function loadScriptsFor(viewPath) {
    const baseUrl = window.BASE_URL || '/';
    const scripts = scriptMap[viewPath];
    if (!scripts) return;
  
    for (const file of scripts) {
      const path = `${baseUrl}app/resources/js/${file}`;
      if (!document.querySelector(`script[src="${path}"]`)) {
        const script = document.createElement('script');
        script.src = path;
        script.type = 'text/javascript';
        document.body.appendChild(script);
      }
    }
  }
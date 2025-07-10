var user = new User();
var imageUser = (evt) => {
  user.archivo(evt, "imageUser");
}

var principal = new Principal();
$(document).ready(() => {
  let URLactual = window.location.pathname;
  principal.linkPrincipal(URLactual);
})

window.searchSchoolAJAX = function(e) {
    console.log('Interceptando submit AJAX');
    if (e) e.preventDefault();
    var input = document.getElementById('schoolSearchInput');
    var search = input ? input.value.trim() : '';
    loadView('school/consultSchool' + (search ? '?search=' + encodeURIComponent(search) : ''));
    return false;
}
if (typeof window.Principal === 'undefined') {
    class Principal {
        linkPrincipal(link) {
            window.url = window.url || "";
            let cadena = link.split("/");
            for (let i = 0; i < cadena.length; i++) {
                if (i >= 3) {
                    url += cadena[i];
                }
            }
            switch (url) {
                case "UserRegister":
                    document.getElementById('files').addEventListener('change', imageUser, false);
                    break;
            }
        }
    }
    window.Principal = Principal;
}
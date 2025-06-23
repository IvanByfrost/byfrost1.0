(function (){
    $(document).ready(function () {
    console.log("Script cargado");

    $("#completeProfile").on("submit", function (e) {
        console.log("Formulario enviado");
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: "/byfrost1.0/app/processes/profileProcess.php",
            data: formData,
            dataType: 'json',
            success: function (response) {
                console.log("Respuesta recibida:", response);

                if (response.status === "ok") {
                    Swal.fire({
                        title: "Listo",
                        text: response.msg,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    setTimeout(() => {
                        console.log("Redirigiendo...");
                        window.location.href = 'login.php';
                    }, 2100);
                } else {
                    Swal.fire({
                        title: "Error",
                        text: response.msg,
                        icon: "error"
                    });
                }
            },
            error: function (xhr, status, error) {
                console.error("Error AJAX:", xhr.responseText, status, error);
            }
        });
    });
});

})();
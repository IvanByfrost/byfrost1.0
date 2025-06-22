console.log("Script cargado");

$("#CompleteProfile").on("submit", function (e) {
    e.preventDefault();
    let formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: "<?php echo url . rq ?>processes/profileProcess.php",
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
                    timer: 2000
                });

                setTimeout(() => {
                    console.log("Redirigiendo...");
                    window.location.href = 'completeProf.php';
                }, 2100);
            } else {
                Swal.fire({
                    title: "Error",
                    text: response.msg,
                    icon: "error"
                });
            }
        }
    });
});

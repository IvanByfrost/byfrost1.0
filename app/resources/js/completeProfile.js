console.log("Script cargado");

$("#CompleteProfile").on("submit", function (e) {
    e.preventDefault();
    let formData = $(this).serialize();

    $.ajax({
        type: 'POST',
        url: ROOT + 'processes/profileProcess.php',
        data: formData,
        dataType: 'json',
        success: function (response) {
            if (response.status === "ok") {
                Swal.fire({
                    title: "Listo",
                    text: response.msg,
                    icon: "success",
                    timer: 2000
                }).then(() => {
                    window.location.href = 'login.php'; // o según el rol
                });
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

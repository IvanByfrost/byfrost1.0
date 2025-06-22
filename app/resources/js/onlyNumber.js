    console.log("Script cargado");
    function onlyNumbers(id, value) {
        var input = $("#" + id);
        input.val(input.val().replace(/[^0-9]/g, ''));
    }
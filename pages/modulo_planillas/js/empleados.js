// CHANGE FORM COMPORT
function nuevoEmpleado(){
    var clearInputs = document.getElementsByClassName("input-sm");
    for (var i = 0; i < clearInputs.length; i++) {
        clearInputs[i].value = "";
        if (clearInputs[i].readOnly == true) {
            clearInputs[i].readOnly = false;
        }
        else if (clearInputs[i].disabled == true) {
            clearInputs[i].disabled = false;
        }
    }
    document.getElementById("guardar").disabled = false;
    document.getElementById("editar").disabled = true;
    changeAction("nueva");
}
function editarOrden(){
    var editInputs = document.getElementsByClassName("input-sm");
    for (var i = 0; i < editInputs.length; i++) {
        if (editInputs[i].readOnly == true) {
            editInputs[i].readOnly = false;
        }
        else if (editInputs[i].disabled == true){
            editInputs[i].disabled = false;
        }
    }
    changeAction("editar");
}

function changeAction(action){
    switch (action) {
        case "nueva":
            document.getElementById("ordenTrabajo").action = "php/nuevaOrdenTrabajo.php";
            break;
        case "editar":
            document.getElementById("ordenTrabajo").action = "php/editarOrdenTrabajo.php";
            break;
        default:

    }
}

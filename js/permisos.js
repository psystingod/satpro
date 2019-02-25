const AGREGAR = 1;
const EDITAR = 2;
const ELIMINAR = 4;

function getAccess(permisosActuales, permisoRequerido){
    return ((parseInt(permisosActuales) & parseInt(permisoRequerido)) == 0) ? false : true;
}

//Si el usuario tiene permiso de AGREGAR, podrá agregar datos, sino se deshabilitan las opciones
if (getAccess(permisos, AGREGAR)) {
    document.getElementById('btn_agregar').disabled = true;
}
else {
    document.getElementById('btn_agregar').disabled = true;
}
//Si el usuario tiene permiso de EDITAR, podrá modificar datos, sino se deshabilitan las opciones
if (getAccess(permisos, EDITAR)) {
    var editElements = document.getElementsByClassName('editar');
    for (var i = 0; i < editElements.length; i++) {
        editElements[i].style.display = "run-in";
    }
}
else {
    var editElements = document.getElementsByClassName('editar');
    for (var i = 0; i < editElements.length; i++) {
        editElements[i].style.display = "none";
    }
}
//Si el usuario tiene permiso de ELIMINAR, podrá modificar datos, sino se deshabilitan las opciones
if (getAccess(permisos, ELIMINAR)) {
    var deleteElements = document.getElementsByClassName('eliminar');
    for (var i = 0; i < deleteElements.length; i++) {
        deleteElements[i].style.display = "run-in";
    }
}
else {
    var deleteElements = document.getElementsByClassName('eliminar');
    for (var i = 0; i < deleteElements.length; i++) {
        deleteElements[i].style.display = "none";
    }
}

//ENABLE OR DISABLE CABLE INPUTS FOR INTERNET ORDERS
/*function ordenInternet(){
    token = true;
    document.getElementById("tipoServicio").value = "I";
    var inputsCable = document.getElementsByClassName("cable");
    if (inputsCable[0].disabled == true || inputsCable[0].readOnly == true) {
        document.getElementById("btn-internet").style.color="#333333";
        for (var i = 0; i < inputsCable.length; i++) {
            if (inputsCable[i].readOnly == true) {
                inputsCable[i].readOnly = false;
            }
            else if (inputsCable[i].disabled == true) {
                inputsCable[i].disabled = false;
            }
        }
    }
    else {
        token = false;
        document.getElementById("btn-internet").style.color="#039BE5";
        for (var i = 0; i < inputsCable.length; i++) {
            inputsCable[i].disabled = true;
        }
    }

}*/

//ENABLE OR DISABLE INTERNET INPUTS FOR CABLE ORDERS
/*function ordenCable(){
    document.getElementById("tipoServicio").value = "C";
    var inputsInternet = document.getElementsByClassName("internet");
    if (inputsInternet[0].disabled == true || inputsInternet[0].readOnly == true) {
        document.getElementById("btn-cable").style.color="#333333";
        for (var i = 0; i < inputsInternet.length; i++) {
            if (inputsInternet[i].readOnly == true) {
                inputsInternet[i].readOnly = false;
            }
            else if (inputsInternet[i].disabled == true) {
                inputsInternet[i].disabled = false;
            }
        }
    }
    else {
        document.getElementById("btn-cable").style.color="#4CAF50";
        for (var i = 0; i < inputsInternet.length; i++) {
            inputsInternet[i].disabled = true;
        }
    }

}*/

// CHANGE FORM COMPORT
function nuevoCliente(){
    //var creadoPor = document.getElementById("creadoPor").value;
    window.location = "infoCliente.php?action=add"
    document.getElementById("btn-editar").disabled = true;
    document.getElementById("btn-guardar").disabled = false;
    //document.getElementById("imprimir").disabled = true;
    document.getElementById("btn-guardar").disabled = false;
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
    document.getElementById("creadoPor").value = creadoPor;
    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    var time = new Date();

    var seconds = time.getSeconds();
    var minutes = time.getMinutes();
    var hour = time.getHours();
    time = hour + ':' + minutes + ':' + seconds;

    today = dd + '/' + mm + '/' + yyyy;
    document.getElementById("fechaOrden").value = today;
    //document.getElementById("hora").value = time;
    //document.getElementById("tipoOrden").value = "Técnica";
    document.getElementById("editar").disabled = true;
    //document.getElementById("nombreOrden").style.display = "none";
    changeAction("nueva");
}

function editarCliente(){
    document.getElementById("btn-nuevo").disabled = true;
    document.getElementById("btn-guardar").disabled = false;
    //document.getElementById("imprimir").disabled = true;

    var editInputs = document.getElementsByClassName("input-sm");
    for (var i = 0; i < editInputs.length; i++) {
        if (editInputs[i].readOnly == true) {
            editInputs[i].readOnly = false;
        }
        else if (editInputs[i].disabled == true){
            editInputs[i].disabled = false;
        }
    }
    document.getElementById("activoCable").disabled = false;
    document.getElementById("suspendidoCable").disabled = false;
    document.getElementById("sinCable").disabled = false;
    document.getElementById("activoInter").disabled = false;
    document.getElementById("suspendidoInter").disabled = false;
    document.getElementById("sinInter").disabled = false;

    //CABLE
    document.getElementById("activoCable").value = "F";
    document.getElementById("suspendidoCable").value = "T";
    document.getElementById("sinCable").value = "S";

    //INTERNET
    document.getElementById("activoInter").value = "1";
    document.getElementById("suspendidoInter").value = "2";
    document.getElementById("sinInter").value = "3";

    //CORTESIA Y EXENTOS
    if (document.getElementById("exento").checked == true) {
        document.getElementById("exento").value = "T";
    }else if (document.getElementById("exento").checked == false) {
        document.getElementById("exento").value = "F";
    }


    if (document.getElementById("cortesia").checked == true) {
        document.getElementById("cortesia").value = "T";
    }else if (document.getElementById("cortesia").checked == false) {
        document.getElementById("cortesia").value = "F";
    }

    //document.getElementById("macModem").readOnly = true;
    //document.getElementById("serieModem").readOnly = true;

    changeAction("editar");
}

function changeAction(action){
    switch (action) {
        case "nueva":
            document.getElementById("ordenSuspension").action = "php/nuevoCliente.php";
            break;
        case "editar":
            document.getElementById("formClientes").action = "php/editarCliente.php";
            break;
        default:

    }
}

/*function imprimirOrden(){
    var nOrden = document.getElementById("numeroSuspension").value
    // Trigger the button element with a click
    window.open("ordenSuspensionImp.php?nOrden="+nOrden, '_blank');
}

function guardarOrden(){
    var token = document.getElementById("tipoServicio").value;
    if (token == "C" || token == "I") {
        document.getElementById('guardar2').click();
    }else {
        alert("Por favor indique si la orden es de cable o de internet");
    }
}*/

function getExento(){
    if (document.getElementById("exento").checked == true) {
        document.getElementById("exento").value = "T";
    }else if (document.getElementById("exento").checked == false) {
        document.getElementById("exento").value = "F";
    }
}

function getCortesia(){
    if (document.getElementById("cortesia").checked == true) {
        document.getElementById("cortesia").value = "T";
    }else if (document.getElementById("cortesia").checked == false) {
        document.getElementById("cortesia").value = "F";
    }
}

function setVencimientoCable(){
    var fechaCable = document.getElementById("fechaPrimerFacturaCable").value;
    var mesesCable = parseInt(document.getElementById("mesesContratoCable").value);

    var nuevaFechaCable = fechaCable.split("/").reverse().join("-");

    var date = new Date(nuevaFechaCable);
    date.setMonth(date.getMonth() + mesesCable);
    var dia = date.getDate();
    var mes = ("0" + (date.getMonth() + 1)).slice(-2);
    var ano = date.getFullYear();
    var vencimiento = dia + "/" + mes + "/" + ano;
    document.getElementById("vencimientoContratoCable").value = vencimiento;
    document.getElementById("vencimientoContratoCable").style.color = "red";

}

function setVencimientoInternet(){
    var fechaInter = document.getElementById("fechaPrimerFacturaInternet").value;
    var mesesInter = parseInt(document.getElementById("mesesContratoInternet").value);

    var nuevaFechaInter = fechaInter.split("/").reverse().join("-");

    var date = new Date(nuevaFechaInter);
    date.setMonth(date.getMonth() + mesesInter);
    var dia = date.getDate();
    var mes = ("0" + (date.getMonth() + 1)).slice(-2);
    var ano = date.getFullYear();
    var vencimiento = dia + "/" + mes + "/" + ano;
    document.getElementById("vencimientoContratoInternet").value = vencimiento;
    document.getElementById("vencimientoContratoInternet").style.color = "red";

}

function tipoServicioCabletv(){
    var tipoServicio = document.getElementById("tipoServicioCable").value;

    if (tipoServicio == 2) {
        document.getElementById('caja1').required = true;
        document.getElementById('cas1').required = true;
        document.getElementById('sn1').required = true;
        document.getElementById('check1').checked = true;
        alert("POR FAVOR ASEGURATE DE RELLENAR LOS DATOS DE LA CAJA DIGITAL");
        alert("PRESIONA EL BOTÓN DE TU DERECHA PARA DESPLEGAR LOS DATOS (EL BOTÓN AZÚL ( ͡° ͜ʖ ͡°))");
    }else {
        document.getElementById('caja1').required = false;
        document.getElementById('cas1').required = false;
        document.getElementById('sn1').required = false;
    }

}

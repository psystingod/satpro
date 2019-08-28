var cuotaCable = document.getElementById("cuotaCable").value;
var cuotaInter = document.getElementById("cuotaInter").value;

var cesc = document.getElementById("aplicarCesc").value;
document.getElementById("cesc").value = cesc;

var radios = document.getElementsByName('aplicarCesc');

function getCesc(){
    for (var i = 0, length = radios.length; i < length; i++)
    {
     if (radios[i].checked == true)
     {
      document.getElementById("cesc").value = radios[i].value;
      break;
     }
    }
}

if (document.getElementById("servicio").value == "c") {
    document.getElementById("valorCuota").value = cuotaCable;
}else if (document.getElementById("servicio").value == "i") {
    document.getElementById("valorCuota").value = cuotaInter;
}


function getValorCuota(){
    if (document.getElementById("servicio").value == "i") {
        document.getElementById("valorCuota").value = cuotaInter;
    }else if (document.getElementById("servicio").value == "c") {
        document.getElementById("valorCuota").value = cuotaCable;
    }
}

function getMesesPagar(){
    if (document.getElementById("mesx1") != null && document.getElementById("mesx2") == null){
        if (document.getElementById("mesx1").checked == true) {
            var mes1 = document.getElementById("mesx1value").value;
            document.getElementById("totalPagar").value = parseFloat(mes1);
            var mesCargo1 = document.getElementById("mesCargo1").value;
            document.getElementById("meses").value = mesCargo1;
        }
    }else if (document.getElementById("mesx1") != null && document.getElementById("mesx2") != null) {
        if (document.getElementById("mesx1").checked == true && document.getElementById("mesx2").checked == false) {
            var mes1 = document.getElementById("mesx1value").value;
            var mes2 = document.getElementById("mesx2value").value;
            document.getElementById("totalPagar").value = parseFloat(mes1);
            var total = document.getElementById("totalPagar").value;
            var cesc = document.getElementById("cesc").value
            totalSinIva = (parseFloat(total)/1.13).toFixed(2);
            document.getElementById("impSeg").value = (parseFloat(cesc)*parseFloat(totalSinIva)).toFixed(2);
            var impSeg = document.getElementById("impSeg").value;
            document.getElementById("totalAbonoImpSeg").value = (parseFloat(total)+parseFloat(impSeg)).toFixed(2);
            cargoTotal = document.getElementById("totalAbonoImpSeg").value = (parseFloat(total)+parseFloat(impSeg)).toFixed(2);
            document.getElementById("total").value = cargoTotal;
            var mesCargo1 = document.getElementById("mesCargo1").value;
            //document.getElementById("pendiente").value = mes2;
            document.getElementById("meses").value = "Resumen de abono: " + mesCargo1;

            document.getElementById("meses").value = mesCargo1;
        }else if (document.getElementById("mesx1").checked == true && document.getElementById("mesx2").checked == true) {
            var mes1 = document.getElementById("mesx1value").value;
            var mes2 = document.getElementById("mesx2value").value;
            document.getElementById("totalPagar").value = parseFloat(mes1) + parseFloat(mes2);
            var total = document.getElementById("totalPagar").value;
            var cesc = document.getElementById("cesc").value
            totalSinIva = (parseFloat(total)/1.13).toFixed(2);

            var mesCargo1 = document.getElementById("mesCargo1").value;
            var mesCargo2 = document.getElementById("mesCargo2").value;
            document.getElementById("meses").value = "Resumen de abono: " + mesCargo1+" "+mesCargo2;
            document.getElementById("impSeg").value = (parseFloat(cesc)*parseFloat(totalSinIva)).toFixed(2);
            var impSeg = document.getElementById("impSeg").value;
            document.getElementById("totalAbonoImpSeg").value = (parseFloat(total)+parseFloat(impSeg)).toFixed(2);
            cargoTotal = document.getElementById("totalAbonoImpSeg").value = (parseFloat(total)+parseFloat(impSeg)).toFixed(2);
            document.getElementById("total").value = cargoTotal;

        }else {
            document.getElementById("totalPagar").value = "0.0";
            document.getElementById("impSeg").value = "0.0";
            document.getElementById("totalAbonoImpSeg").value = "0.0";
            document.getElementById("total").value = "0.0";
        }
    }
}

/*funciones para agendat
**/
//funcion que abre una ventana para buscar el pasiente y agregar  a los terapistas
function agregarT(id){

window.open("addcita.php?idcita="+id+" ", "popupId", "location=yes,menubar=yes,titlebar=yes,resizable=yes,toolbar=yes,scrollbars=yes, menubar=yes,width=600,height=800");

}

function Emplado(e){
    document.getElementById("observaciones").value = document.getElementById(e).value ;
}

function Mesero(e){
    document.getElementById("empleado").value = document.getElementById(e).value ;
}

function cerrar(e,aa) {
//alert(aa);
tecla=(document.all) ? aa.keyCode : aa.which;
if(tecla == 1 || tecla ==13)
{
    var res;
    var data = document.getElementById(e).value;
    var data2 = e;
    document.getElementById('art').value    = data2;
    document.getElementById('desD').value    = data;
}
}

function toUpper(String){
	String.value	=	 String.value.toUpperCase();
}


function mostarB(e) {
    var platillo = document.getElementById('platillo').value;
    var elboton = document.getElementById('agregarp');

    
   
    if (platillo.length == 0) {
    	alert('here');
      elboton.style.visibility = 'visible';
    }
    else{
    	alert('hidden');
        elboton.style.visibility = 'hidden';
    }
    alert(elboton.style.visibility );
}  

function anular(e) {
	tecla=(document.all) ? e.keyCode : e.which;
    return (tecla != 13);
}
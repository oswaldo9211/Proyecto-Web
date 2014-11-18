
$(document).ready(function(){
    //

    $(document).on('click','caption',function(){
        //obtener la tabla que contiene el caption clickeado
        var objTabla=$(this).parent();
            //el cuerpo de la tabla esta visible?
            //lo siguiente es unicamente para cambiar el icono del caption
            if(objTabla.find('tbody').is(':visible')){
                //eliminamos la clase clsContraer
                $(this).removeClass('clsContraer');
                //agregamos la clase clsExpandir
                $(this).addClass('clsExpandir');
            }else{
                //eliminamos la clase clsExpadir
                $(this).removeClass('clsExpandir');
                //agregamos la clase clsContraer
                $(this).addClass('clsContraer');
            }
            //mostramos u ocultamos el cuerpo de la tabla
            objTabla.find('tbody').toggle();
    });
        
    //evento que se dispara al hacer clic en el boton para agregar una nueva fila
    $(document).on('blur','.Fila',function(){
        //almacenamos en una variable todo el contenido de la nueva fila que deseamos
        //agregar. pueden incluirse id's, nombres y cualquier tag... sigue siendo html
        var objCuerpo=$(this).parents().get(2);
        cont =  $(objCuerpo).find('tr').length;

        var posicion=document.getElementById('pieza').options.selectedIndex; //posicion
        //alert(posicion);
        //alert(document.getElementById('pieza').options[posicion].text);
        //alert('here');
        var elements = document.getElementById('pieza').length;
        //alert("Elementos" + elements);
        var selectPieza ='';
        var  valueSelect='';
        var  idSelect ='';
        for (var i = 0; i < elements; i++) {
             valueSelect = document.getElementById('pieza').options[i].text;
             idSelect    = document.getElementById('pieza').options[i].value;
            selectPieza += '<option  id="pieza'+ idSelect + cont +'" value="'+ idSelect +'"> '+ valueSelect + '</option> ';  
        };
       // alert(selectPieza);
        posicion=document.getElementById('servicio').options.selectedIndex; //posicion
        //alert(posicion);
        //alert(document.getElementById('pieza').options[posicion].text);
        //alert('here');
        elements = document.getElementById('servicio').length;
        //alert("Elementos" + elements);
        var  selectServicio ='';
          valueSelect='';
        var  idSelect ='';
        for (var i = 0; i < elements; i++) {
             valueSelect = document.getElementById('servicio').options[i].text;
             idSelect    = document.getElementById('servicio').options[i].value;
            selectServicio += '<option  id="servicio'+ idSelect + cont +'" value="'+ idSelect +'"> '+ valueSelect + '</option> ';  
        };
        //alert('SER ' + selectServicio);
        var strNueva_Fila='<tr>'+
            '<td> <select   name="pieza' + cont+'"> '+ selectPieza +' </select></td>'+
            '<td><input type="range"  name="severidad' +cont + '" id="severidad' +cont + '"  /></td>'+
            '<td> <select   name="servicio' + cont +'"> '+ selectServicio +' </select></td>'+
            '<td><input type="text"  class="Fila" name="observaciones' + cont +'"/></td>'+
            '<td ><input type="button" value="-" class="clsEliminarFila"></td>'+
        '</tr>';
        //alert(cont);
        /*obtenemos el padre del boton presionado (en este caso queremos la tabla, por eso
        utilizamos get(3)
            table -> padre 3
                tfoot -> padre 2
                    tr -> padre 1
                        td -> padre 0
        nosotros queremos utilizar el padre 3 para agregarle en la etiqueta
        tbody una nueva fila*/
        var objTabla=$(this).parents().get(3);
                
        //agregamos la nueva fila a la tabla
        $(objTabla).find('tbody').append(strNueva_Fila);
                
        //si el cuerpo la tabla esta oculto (al agregar una nueva fila) lo mostramos
        if(!$(objTabla).find('tbody').is(':visible')){
            //le hacemos clic al titulo de la tabla, para mostrar el contenido
            $(objTabla).find('caption').click();
        }
    });
    
    //cuando se haga clic en cualquier clase .clsEliminarFila se dispara el evento
    $(document).on('click','.clsEliminarFila',function(){
        /*obtener el cuerpo de la tabla; contamos cuantas filas (tr) tiene
        si queda solamente una fila le preguntamos al usuario si desea eliminarla*/
        var objCuerpo=$(this).parents().get(2);
            if($(objCuerpo).find('tr').length==1){
                if(!confirm('Esta es el única fila de la lista ¿Desea eliminarla?')){
                    return;
                }
            }
                    
        /*obtenemos el padre (tr) del td que contiene a nuestro boton de eliminar
        que quede claro: estamos obteniendo dos padres
                    
        el asunto de los padres e hijos funciona exactamente como en la vida real
        es una jergarquia. imagine un arbol genealogico y tendra todo claro ;)
                
            tr  --> padre del td que contiene el boton
                td  --> hijo de tr y padre del boton
                    boton --> hijo directo de td (y nieto de tr? si!)
        */
        var objFila=$(this).parents().get(1);
            /*eliminamos el tr que contiene los datos del contacto (se elimina todo el
            contenido (en este caso los td, los text y logicamente, el boton */
            $(objFila).remove();
    });
    
            
});
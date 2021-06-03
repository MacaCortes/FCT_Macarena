
/* funcion para botones de trabajadores*/
/*
function activar(){
    alert("diste el boton");
   var form= document.getElementById("formtra");
   var input=form.getElementsByTagName("input");
   console.log(input);
   
    
}

/*
 Funcion de Administrador para abrir ventanas emergentes 
 */
var ventanaUser;
function ventana(valor){
    console.log(valor);
      let propiedades = "width=900,height=550,top=200px,left=600px,resizable,scrollbars=yes,status=1";
    switch (valor) {
        case "1":
           //ventana1 = window.open("ventanas/anadir_p.php", "", propiedades);
         //  window.location.href="anadir_p.php";
           //window.open('ventanas/anadir_p.php');
            break;
        case "2":
          //   window.location.href="modificar_p.php";
          // ventana2= window.open("ventanas/modificar_p.php", "", propiedades);           
            break;
        
         case "3":
            //   window.location.href="anadir_receta.php";
           // window.open("ventanas/anadir_receta.php", "", propiedades);
         
            break;
        case "user" :
          window.open("user.php", "", propiedades);
            break;
    }
    
}

//aparece cuando le das en menu a selecionaar un tipo de menu
function muestraOculta(tipo) {
   console.log(tipo);
   if(tipo=="1"){
       var tipomenu="Basico";
   }
   if(tipo=="3"){
       var tipomenu="Bajo en Sal";
   }
   if(tipo=="2"){
       var tipomenu="Celiacos";
   }
   if(tipo=="4"){
       var tipomenu="Diabetico";
   }
   
   
     var elemento = document.getElementById('oculto');
     var texto = document.getElementById('apto');
     var apto = document.getElementById('tipoMenu');
     
    if (elemento.style.display == "" || elemento.style.display == "block") {
        elemento.style.display = "none";
         //texto.innerHTML= "<h2>"+ tipo +"</h2>";
    } else {
        elemento.style.display = "block"; 
        apto.innerHTML="<span class='fas fa-utensils'></span><h2 class='orange'> Menu "+tipomenu+"</h2>";
        texto.innerHTML="<input type='hidden' name='tipo_apto' value='"+tipo+"'/>\n\
       <input type='submit' class='btnapp' style='width: auto;' name='elegirMenu' value='Seguir con el pedido'/>  ";  
   // var elemento = document.getElementById('contenidos_' + num);
    }
   
} //fin funcion
 var $ingredientes=new Array();
 var $cantidades =new Array();
 var $medidas=new Array();
function mas(){
    alert("añadir ingre");
    var ingre=document.getElementById('ingrediente').value;
    var medida=document.getElementById('medida').value;
    var cantidad=document.getElementById('cantidad').value;
    ingredientes.push(ingre);
    cantidades.push(cantidad);
    medidas.push(medida);
    document.getElementById("ingrediente").value = "";
    document.getElementById("medida").value = "";
    document.getElementById("cantidad").value = "";   
    console.log(ingredientes);
    
}
function yaesta(){
    var ingre=document.getElementById('ingrediente').value;
    var medida=document.getElementById('medida').value;
    var cantidad=document.getElementById('cantidad').value;
    ingredientes.push(ingre);
    cantidades.push(cantidad);
    medidas.push(medida); 
    var arrayI="value='<?php echo htmlspecialchars(serialize($ingredientes)); ?>'";
    document.getElementById("todosingrediente").innerHTML="<input type='hidden' name='ingredientes' value='"+arrayI+"'/> ";
}
/*
function areparto(elem){
    alert("a reparto");
    elem.style.background = 'grey';
}
 /*
 function elegirmenu() {
    alert("estamos en elegir");
    let menu = document.forms["formulario"]["m"];
    for (let i = 0; i < menu.length; i++) {
        if (menu[i].checked) {
            let elegido = menu[i].value;
            console.log(elegido);
            mandar(elegido);

        }
    }

}
function mandar(elegido){
    if (elegido == 'md' || elegido == 'mdc') {
                console.log(elegido);
                location.href = "elegirMenuDia.php";
            } else {
                console.log(elegido);
                location.href = "elegirMenuSemana.php";
            }
}*/
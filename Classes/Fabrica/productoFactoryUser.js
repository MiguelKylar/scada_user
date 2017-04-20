/*Builds product: Bicycle*/
class productoFactory {
	constructor(){
		
		 //this._implementsComponentPartsFactory = new ComponentPartsFactory();
	}
     /*Input: String tipo
	 *Retur: Bicycle */
	 	
	guardarComponent(objetos){
		console.log("entre a guardar");
		var indice = objetos.length-1;
		var arregloJson = new Array();
		for (m = 1; m <= indice; m++){
			if (objetos[m] != null){
			  var json = JSON.stringify(objetos[m]);
			  console.log("almaceno");
			  arregloJson.push(json);
			}else{
			  console.log("descarto");
			}
			  
		}
		var largo = arregloJson.length;
		arregloJson =  JSON.stringify(arregloJson);
		console.log("entre a actualizar...");
		    var data = 'arregloJson=' + arregloJson + '&largo=' + largo;
			$.ajax({
                url: 'Ajax/actualizarParametros.php',
                type: 'post',
				async: false,
				data: data,
                beforeSend: function () {
                    console.log('enviando...')
                },
                success: function (resp) {
				console.log(resp);					
				console.log("actualice...");
                }
            });
	}
	crearComponent(){ 
			console.log("fabrica elemento")
            var VMFactory = new VMproductoFactory();
			var productoFinal = new producto(VMFactory);
			productoFinal.ensamblar();
		return productoFinal;
	}

}
 
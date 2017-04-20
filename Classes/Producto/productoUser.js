/*This is the product that is built*/
class productoUser{
	/*Input: Class BicyclePartsFactory*/
	constructor(productoPartFactory){
	/*Type: String*/this._dom;
	/*Type: Numbre*/this._idelemento;
	/*Type: Numbre*/this._top;
	/*Type: Numbre*/this._left;
	/*Type: Numbre*/this._heigth;
	/*Type: Numbre*/this._width;
	/*Type: String*/this._zIndex;
	/*Type: String*/this._img;
	/*Type: String*/this._imgDown;
	/*Type: String*/this._imgUp;
	/*Type: String*/this._imgNeutral;
	/*Type: String*/this._tipo;
	/*Type: String*/this._tamaño;
	/*Type: String*/this._texto;
	/*Type: String*/this._imgOriginal;	
	this.productoPartFactory = productoPartFactory;
	}
	/*Return: void*/

	//Metodos Sets////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	setIdelemento(id_elemento){
	/*Type: Numbre*/this._idelemento = id_elemento;
	}
	setTexto(texto){
	/*Type: Numbre*/this._texto = texto;
	}
	setTamaño(tamaño){
	/*Type: Numbre*/this._tamaño = tamaño;
	}
	setTop(tops){
	/*Type: Numbre*/this._top = tops;
	}
	setTipo(tipo){
	/*Type: Numbre*/this._tipo = tipo;
	}
	setLeft(lefts){
	/*Type: Numbre*/this._left = lefts;
	}
	setHeight(heigths){
	/*Type: Numbre*/this._heigth = heigths;	
	}
	setWidth(widths){
	/*Type: Numbre*/this._width = widths;	
	}
	setIndex(zIndex){
	/*Type: Numbre*/this._zIndex = zIndex;	
	}
	setImgDown(imgDown){
	/*Type: Numbre*/this._imgDown = imgDown;	
	}
	setImgUp(imgUp){
	/*Type: Numbre*/this._imgUp = imgUp;	
	}
	setImgNeutral(imgNeutral){
	/*Type: Numbre*/this._imgNeutral = imgNeutral;	
	}
	setImgOriginal(imgOriginal){
	/*Type: Numbre*/this._imgOriginal = imgOriginal;	
	}
	//Metodos Gets///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	getIdelemento(){
	return this._idelemento;	
	}
	getTexto(){
	return this._texto;	
	}
	getTop(){
	return this._top;
	}
	getLeft(){
	return this._left;
	}
	getHeight(){
	return this._heigth;	
	}
	getWidth(){
	return this._width;	
	}
	getIndex(){
	return this._zIndex;	
	}
	getTipo(){
	return this._tipo;	
	}
	getTamaño(){
	return 
	}
	getImgDown(){
	return this._imgDown;	
	}
	getImgUp(){
	return this._imgUp;	
	}
	getImgNeutral(){
	return this._imgNeutral;	
	}
	getImg(){
	return this._img;	
	}
	getImgOriginal(){
	return this._imgOriginal;	
	}	
	
	//Metodos Especiales////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	ensamblar(id_empresa,id_tipo){
	console.log("este es el tipo"+id_tipo);
	this.productoPartFactory.obtenerParametros(id_empresa,id_tipo);		
	this._idelemento = this.productoPartFactory.getIdelemento();
	this._img = this.productoPartFactory.getImg();
	this._tipo = id_tipo;
	}
	
	crearDOM(){
		if(this._tipo == 3){
		this._dom = "<div  id='dom_"+this._idelemento+"' style='z-index:"+this._zIndex+";text-align: center;position:absolute;width:"+this._width+"px;height:"+this._heigth+"px;left:"+this._left+"px;top:"+this._top+"px;'><input disabled id='l_"+this._idelemento+"'; style='background-color: white; font-size:"+this._tamaño+"%; border:none;' type='text' name='FirstName' value='"+this._texto+"'></div>";
		}else{
		this._dom = "<div  id='dom_"+this._idelemento+"' style='z-index:"+this._zIndex+";text-align: center;position:absolute;width:"+this._width+"px;height:"+this._heigth+"px;left:"+this._left+"px;top:"+this._top+"px;'><img onmouseleave='' onclick='popup(id,event);iteracion = setInterval(function(){recargarPopup(id);},120000);' id='z_"+this._idelemento+"' style='width: 100%;height: 90%;' src='"+this._img+"'></div>";
		}
		return this._dom;
	}
	destruirDOM(){
	this.productoPartFactory.eliminarParametros(this._idelemento);	
	}
	cambiarUrl(url){
		this._img = url;
	}
	cargarElementos(id_empresa){
	}
} 
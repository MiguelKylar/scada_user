
<!DOCTYPE html>
<?php
session_start();
$id_empresa = $_SESSION['id_empresa'];
?>
<html>

    <head>
        <title>scada</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>scada</title>

        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="style/sweetalert.min.js"></script>
        <link rel="stylesheet" type="text/css" href="style/sweetalert.css">
        <link rel="stylesheet" type="text/css" href="style/principal.css" media="screen" />
        <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <!--Classes -->

        <script src="Classes/Producto/productoUser.js"></script>   
        <script src="Classes/Fabrica/VMproductoFactoryUser.js"></script>   
        <script src="Classes/Fabrica/productoFactoryUser.js"></script>  
    </head>
    <script>
        var globalventana = 1;
        function unique(arr) {
            var hash = {}, result = [];
            for (var i = 0; i < arr.length; i++)
                if (!(arr[i] in hash)) { //it works with objects! in FF, at least
                    hash[arr[i]] = true;
                    result.push(arr[i]);
                }
            return result;
        }
        function ventana() {
            if (!$('#myonoffswitch2').prop('checked')) {
                globalventana = 2;
            }
            if ($('#myonoffswitch2').prop('checked')) {
                globalventana = 1;
            }
        }
        function enviarGrafico() {
            if (globalventana == 1) {
                window.open('', 'ventanaForm', 'menubar:no,toolbar=no,resizable=no,s crollbars=no,width=600,height=200,top=100,left=100 ');
                document.forms["myform"].submit();
            }
            if (globalventana == 2) {
                window.open('', 'ventanaForm', '');
                document.forms["myform"].submit();
            }
        }
        function enviarTimeline() {
            window.open('', 'ventanaForm2', 'menubar:no,toolbar=no,resizable=no,s crollbars=no,width=600,height=200,top=100,left=100 ');
            document.forms["myform2"].submit();
        }
        var id_empresa = <?php echo $id_empresa; ?>;
        var i = 1;
        var productoFinal = new Array();
        var VMFactory = new Array();
        var data = 'id_empresa=' + id_empresa;
        $.ajax({
            url: 'Ajax/elementos.php',
            type: 'post',
            data: data,
            //async:false, 
            beforeSend: function () {
            },
            success: function (resp) {
                var elementos = JSON.parse(resp);
                for (i = 1; i <= elementos.length; i++) {
                    VMFactory[i] = new VMproductoFactoryUser();
                    productoFinal[i] = new productoUser(VMFactory[i]);
                    productoFinal[i].setIdelemento(elementos[i - 1].id_elemento);
                    productoFinal[i].setTop(elementos[i - 1].top);
                    productoFinal[i].setLeft(elementos[i - 1].lefts);
                    productoFinal[i].setHeight(elementos[i - 1].height);
                    productoFinal[i].setWidth(elementos[i - 1].width);
                    productoFinal[i].setImgUp(elementos[i - 1].img_up);
                    productoFinal[i].setImgDown(elementos[i - 1].img_down);
                    productoFinal[i].setImgNeutral(elementos[i - 1].img_neutral);
                    productoFinal[i].setImgOriginal(elementos[i - 1].img);
                    productoFinal[i].setIndex(elementos[i - 1].zIndex);
                    productoFinal[i].cambiarUrl(elementos[i - 1].img);
                    productoFinal[i].setTipo(elementos[i - 1].tipo);
                    productoFinal[i].setTexto(elementos[i - 1].texto);
                    tamaño = elementos[i - 1].tamano_letra;
                    tamaño = tamaño.split("%");
                    productoFinal[i].setTamaño(tamaño[0]);
                    var div = productoFinal[i].crearDOM();
                    var nuevoElemento = $(div);
                    $("#padre").append(nuevoElemento);

                    if (elementos[i - 1].tipo == 2) {
                        var dom = "dom_" + elementos[i - 1].id_elemento;
                        $("#" + dom).css({
                            display: "none",
                            textAlign: "center"

                        });
                    }
                }
                llenarElemento();
            }
        });

        var data = 'id_empresa=' + id_empresa;
        $.ajax({
            url: 'Ajax/sensoresEmpresa.php',
            type: 'post',
            data: data,
            //async:false,    
            beforeSend: function () {
            },
            success: function (resp) {
                var sensores = JSON.parse(resp);
                var sensores = unique(sensores)
                for (i = 0; i < sensores.length; i++) {
                    $("#sensores").append("<option value=" + sensores[i] + ">" + sensores[i] + "</option>");
                }
                var id_elementos = [];
                m = $("#padre div").length;
                x = 0;
                for (i = 0; i <= 100; i++) {
                    if ($("#z_" + i + "")[0]) {
                        id_elementos[x] = i;
                        x++;
                    }
                }
                tipo = $('select[id=sensores]').val();
                tipo = 'general';
                var data = 'id_elementos=' + id_elementos + '&tipo=' + tipo;
                $.ajax({
                    url: 'Ajax/umbrales.php',
                    type: 'post',
                    data: data,
                    beforeSend: function () {
                    },
                    success: function (resp) {
                        var elementos = JSON.parse(resp);
                        for (i = 0; i < elementos.length; i++) {
                            if (tipo != "control" && tipo != "general") {
                                if (elementos[i].tipo == tipo) {
                                    valor = elementos[i].valor;
                                    for (m = 0; m < elementos.length; m++) {
                                        if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                            elementos[m].sw = 1;
                                        }
                                    }
                                    umbral_superior = elementos[i].umbral_superior;
                                    umbral_inferior = elementos[i].umbral_inferior;
                                    for (x = 1; x < productoFinal.length; x++) {
                                        id_img = "z_" + idelemento;
                                        idelemento = productoFinal[x].getIdelemento();
                                        if (idelemento == elementos[i].id_elemento) {
                                            if (valor < umbral_inferior) {
                                                $("#" + id_img).css({
                                                    webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                    filter: "hue-rotate(45deg) brightness(600%);"
                                                });
                                            } else if (valor > umbral_superior) {
                                                id_img = "z_" + idelemento;
                                                $("#" + id_img).css({
                                                    webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                    filter: "hue-rotate(45deg) brightness(600%);"
                                                });
                                            } else {
                                                id_img = "z_" + idelemento;
                                                $("#" + id_img).css({
                                                    webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                    filter: "hue-rotate(45deg) brightness(600%);"
                                                });
                                            }
                                        }
                                    }
                                } else if (elementos[i].sw != 1) {
                                    for (x = 1; x < productoFinal.length; x++) {
                                        idelemento = productoFinal[x].getIdelemento();
                                        if (idelemento == elementos[i].id_elemento) {
                                            id_img = "z_" + idelemento;
                                            $("#" + id_img).css({
                                                webkitfilter: "hue-rotate(108deg) brightness(600%);",
                                                filter: "hue-rotate(108deg) brightness(600%);"
                                            });
                                        }
                                    }
                                }
                            } else if (tipo == "control") {
                                if (elementos[i].tipo == tipo) {
                                    valor = elementos[i].valor;
                                    for (m = 0; m < elementos.length; m++) {
                                        if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                            elementos[m].sw = 1;
                                        }
                                    }
                                    umbral_superior = elementos[i].umbral_superior;
                                    umbral_inferior = elementos[i].umbral_inferior;
                                    for (x = 1; x < productoFinal.length; x++) {
                                        idelemento = productoFinal[x].getIdelemento();
                                        id_img = "z_" + idelemento;
                                        if (idelemento == elementos[i].id_elemento) {
                                            if (valor == "Detenido") {
                                                $("#" + id_img).css({
                                                    webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                    filter: "hue-rotate(45deg) brightness(600%);"
                                                });
                                            } else if (valor == "Funcionando") {
                                                id_img = "z_" + idelemento;
                                                $("#" + id_img).css({
                                                    webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                    filter: "hue-rotate(45deg) brightness(600%);"
                                                });
                                            }
                                        }
                                    }
                                } else if (elementos[i].sw != 1) {
                                    for (x = 1; x < productoFinal.length; x++) {
                                        idelemento = productoFinal[x].getIdelemento();
                                        if (idelemento == elementos[i].id_elemento) {
                                            id_img = "z_" + idelemento;
                                            $("#" + id_img).css({
                                                webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                filter: "hue-rotate(45deg) brightness(600%);"
                                            });
                                        }
                                    }
                                }
                            } else if (tipo == "general") {
                                if (elementos[i].prioridad == 1) {
                                    if (elementos[i].tipo == "control") {
                                        valor = elementos[i].valor;
                                        for (m = 0; m < elementos.length; m++) {
                                            if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                                elementos[m].sw = 1;
                                            }
                                        }
                                        umbral_superior = elementos[i].umbral_superior;
                                        umbral_inferior = elementos[i].umbral_inferior;
                                        for (x = 1; x < productoFinal.length; x++) {
                                            idelemento = productoFinal[x].getIdelemento();
                                            id_img = "z_" + idelemento;
                                            if (idelemento == elementos[i].id_elemento) {
                                                if (valor == "Detenido") {
                                                    $("#" + id_img).css({
                                                        webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                        filter: "hue-rotate(45deg) brightness(600%);"
                                                    });
                                                } else if (valor == "Funcionando") {
                                                    id_img = "z_" + idelemento;
                                                    $("#" + id_img).css({
                                                        webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                        filter: "hue-rotate(45deg) brightness(600%);"
                                                    });
                                                }
                                            }
                                        }
                                    } else {
                                        valor = elementos[i].valor;
                                        for (m = 0; m < elementos.length; m++) {
                                            if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                                elementos[m].sw = 1;
                                            }
                                        }
                                        umbral_superior = elementos[i].umbral_superior;
                                        umbral_inferior = elementos[i].umbral_inferior;
                                        for (x = 1; x < productoFinal.length; x++) {
                                            id_img = "z_" + idelemento;
                                            idelemento = productoFinal[x].getIdelemento();
                                            if (idelemento == elementos[i].id_elemento) {
                                                if (valor < umbral_inferior) {
                                                    $("#" + id_img).css({
                                                        webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                        filter: "hue-rotate(45deg) brightness(600%);"
                                                    });
                                                } else if (valor > umbral_superior) {
                                                    id_img = "z_" + idelemento;
                                                    $("#" + id_img).css({
                                                        webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                        filter: "hue-rotate(45deg) brightness(600%);"
                                                    });
                                                } else {
                                                    id_img = "z_" + idelemento;
                                                    $("#" + id_img).css({
                                                        webkitfilter: "hue-rotate(45deg) brightness(600%);",
                                                        filter: "hue-rotate(45deg) brightness(600%);"
                                                    });
                                                }
                                            }
                                        }
                                    }
                                } else if (elementos[i].sw != 1) {
                                    for (x = 1; x < productoFinal.length; x++) {
                                        idelemento = productoFinal[x].getIdelemento();
                                        if (idelemento == elementos[i].id_elemento) {
                                            id_img = "z_" + idelemento;
                                            $("#" + id_img).css({
                                                webkitfilter: "hue-rotate(103deg) brightness(600%);",
                                                filter: "hue-rotate(103deg) brightness(600%);"
                                            });
                                        }
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
        function recargarPopup(id) {
            //erasepopup();
            //clearInterval(iteracion);
        }
        function agregarElemento() {
            var valvula = new productoFactoryUser();
            productoFinal[i] = valvula.crearComponent();
            var div = productoFinal[i].crearDOM();
            var nuevoElemento = $(div);
            nuevoElemento.draggable();
            nuevoElemento.resizable();
            $(document.body).append(nuevoElemento);
            i = i + 1;
        }

        function eliminarElemento(id) {
            var res = id.split("_");
            var x = res[1];
            for (p = 1; p < i; p++) {
                if (productoFinal[p] != null) {
                    id_elemento = productoFinal[p].getIdelemento();
                    if (x == id_elemento) {
                        productoFinal[p].destruirDOM();
                        var y = document.getElementById("dom_" + x);
                        y.remove(y.selectedIndex);
                        productoFinal[p] = null;
                    }
                }
            }
        }
        function llenarElemento() {
            for (var i = 1; i < productoFinal.length; i++) {
                tipo = productoFinal[i].getTipo();
                if (tipo == 1) {
                    var id_elemento = productoFinal[i].getIdelemento();
                    var top = document.getElementById('dom_' + id_elemento).style.top;
                    var data = 'id_empresa=' + id_empresa + '&id_elemento=' + id_elemento;

                    $.ajax({
                        url: 'Ajax/llenarElemento.php',
                        type: 'post',
                        data: data,
                        async: false,
                        beforeSend: function () {
                        },
                        success: function (resp) {
                            if (elemento = JSON.parse(resp)) {
                                var umbral_min = elemento[0].umbral_inferior;
                                var umbral_max = elemento[0].umbral_superior;
                                var valor = elemento[0].valor;
                                var porcentaje = valor * 100;
                                porcentaje = porcentaje / umbral_max;
                                porcentaje = Math.round(porcentaje);
                                alert(porcentaje);
                                $("#dom_" + id_elemento).prepend("<div id='porcentaje_" + id_elemento + "''><strong>" + porcentaje + "%</strong></div>");
                                document.getElementById('porcentaje_' + id_elemento).style.top = top;
                            }
                            /*var left = document.getElementById('dom_'+id_elemento).style.left;
                             alert(left);
                             left = left.split("p");
                             var derecha = left[0]; 
                             var derecha = parseInt(derecha);
                             derecha = derecha + 62; 
                             
                             document.getElementById('porcentaje_'+id_elemento).style.left = derecha+"px";
                             document.getElementById('porcentaje_'+id_elemento).style.position = "absolute";*/
                        }
                    });
                }
            }
        }
        function guardarElemento() {
            var indice = productoFinal.length - 1;
            for (m = 1; m < i; m++) {
                if (productoFinal[m] != null) {
                    id_elemento = productoFinal[m].getIdelemento();
                    top1 = document.getElementById('dom_' + id_elemento).style.top;
                    left1 = document.getElementById('dom_' + id_elemento).style.left;
                    width1 = document.getElementById('dom_' + id_elemento).style.width;
                    height1 = document.getElementById('dom_' + id_elemento).style.height;
                    productoFinal[m].setTop(top1);
                    productoFinal[m].setTop(top1);
                    productoFinal[m].setLeft(left1);
                    productoFinal[m].setWidth(width1);
                    productoFinal[m].setHeight(height1);
                }
            }
            var producto1 = new productoFactoryUser();
            producto1.guardarComponent(productoFinal);
        }
        function cambiarUrlImagen(id) {
            var res = id.split("_");
            var x = res[1];
            swal({
                title: "Nueva Imagen",
                text: "Ingrese la url de la imagen",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                animation: "slide-from-top",
                inputPlaceholder: "Write something"
            },
                    function (inputValue) {
                        if (inputValue === false)
                            return false;

                        if (inputValue === "") {
                            swal.showInputError("necesita escribir algo");
                            return false
                        }
                        for (p = 1; p < i; p++) {
                            id_elemento = productoFinal[p].getIdelemento();
                            if (x == id_elemento) {
                                productoFinal[p].cambiarUrl(inputValue);
                                var y = document.getElementById("dom_" + x);
                                y.remove(y.selectedIndex);
                                var div = productoFinal[p].crearDOM();
                                var nuevoElemento = $(div);
                                nuevoElemento.draggable();
                                nuevoElemento.resizable();
                                $(document.body).append(nuevoElemento);
                                swal("Bien", "Su url a cambiado a: " + inputValue, "success");
                            }
                        }
                    });
        }
        function popup(id, event) {
            swOn = 0;
            if ($("#info").length) {
                $(".info").remove();

            }

            var data = 'id_elemento=' + id;
            $.ajax({
                url: 'Ajax/elementos_aplicacion.php',
                type: 'post',
                data: data,
                beforeSend: function () {
                },
                success: function (resp) {
                    console.log(resp);
                    ultimo_estado = "";
                    var sensores = JSON.parse(resp);
                    $("#infoline").append('<div id="info" class="info"></div>');

                    if (sensores == null) {
                        $("#info").remove();
                    } else {
                        for (i = 0; i < sensores.length; i++) {
                            if (sensores[i].tipo == 'control') {
                                idControl = sensores[i].id_nodo;
                                ultimo_estado = sensores[i].ultimo_estado;
                                p_fecha_ini = sensores[i].p_fecha_ini;
                                p_fecha_fin = sensores[i].p_fecha_fin;
                                fecha_now = sensores[i].fecha_now;
                                swOn = 1;
                            }
                            aplicaciones = "";
                            for (x = 0; x < sensores[i].id_aplicacion.length; x++) {
                                url = sensores[i].id_aplicacion[x].url;
                                icono = sensores[i].id_aplicacion[x].icono;
                                switch (sensores[i].id_aplicacion[x].url) {
                                    case 'http://200.24.229.186/lem/optimusV2/lemTranque.php':
                                        aplicaciones = aplicaciones + '<form  style="float:left"; method="post" target="" id="" action="' + url + '"><input  name="id_empresa" id="id_empresa" type="text" value="' + sensores[i].id_empresa + '" visible="false" style="display:none"><input  name="id_nodo" id="id_nodo" type="text" value="' + sensores[i].id_nodo + '" visible="false" style="display: none;"><input  name="equipo" id="equipo" type="text" value="' + sensores[i].id_equipo + '" visible="false" style="display: none;"><img onclick="enviarTimeline();" type=image; src="imagenes/' + icono + '" width="10px"></form>';
                                        break;
                                    case 'http://200.24.229.186/optimusV2/graficos.php':
                                        aplicaciones = aplicaciones + '<form  style="float:left"; method="post" target="ventanaForm" id="myform" action="' + url + '"><input  name="idsensor" id="idsensor" type="text" value="' + sensores[i].id_sensor + '" visible="false" style="display:none"><input  name="id_empresa" id="id_empresa" type="text" value="' + sensores[i].id_empresa + '" visible="false" style="display:none"><input  name="id_nodo" id="id_nodo" type="text" value="' + sensores[i].id_nodo + '" visible="false" style="display: none;"><input  name="subtipo" id="subtipo" type="text" value="' + sensores[i].nombre + '" visible="false" style="display: none;"><img onclick="enviarGrafico();" type=image src="imagenes/' + icono + '" width="10px"></form>';
                                        break;
                                    case 'http://200.24.229.186/optimusV2/timelinelem.php':
                                        aplicaciones = aplicaciones + '<form  method="post" target="ventanaForm2" id="myform2" action="' + url + '"><input  name="id_empresa" id="id_empresa" type="text" value="' + sensores[i].id_empresa + '" visible="false" style="display:none"><input  name="id_nodo" id="id_nodo" type="text" value="' + sensores[i].id_nodo + '" visible="false" style="display: none;"><input  name="equipo" id="equipo" type="text" value="' + sensores[i].id_equipo + '" visible="false" style="display: none;"><img onclick="enviarTimeline();" type=image src="imagenes/' + icono + '" width="10px"></form>';
                                        break;
                                    case 'http://200.24.229.186/optimusV2/riego.php':
                                        aplicaciones = aplicaciones + '<form  method="post" target="_blank id="myform" action="' + url + '"><input  name="id_empresa" id="id_empresa" type="text" value="' + sensores[i].id_empresa + '" visible="false" style="display:none"><input  name="id_nodo" id="id_nodo" type="text" value="' + sensores[i].id_nodo + '" visible="false" style="display: none;"><input  name="equipo" id="equipo" type="text" value="' + sensores[i].id_equipo + '" visible="false" style="display: none;"><input type=image src="imagenes/' + icono + '" width="10px"></form>';
                                        break;
                                    case 'http://200.24.229.186/optimusV2/caudalimetro.php':
                                        aplicaciones = aplicaciones + '<form  method="post" target="_blank id="myform" action="' + url + '"><input  name="id_empresa" id="id_empresa" type="text" value="' + sensores[i].id_empresa + '" visible="false" style="display:none"><input  name="id_nodo" id="id_nodo" type="text" value="' + sensores[i].id_nodo + '" visible="false" style="display: none;"><input  name="equipo" id="equipo" type="text" value="' + sensores[i].id_equipo + '" visible="false" style="display: none;"><input type=image src="imagenes/' + icono + '" width="10px"></form>';
                                        break;
                                }
                            }
                            $("#info").append('<table><tr><td style="width:150px;">' + sensores[i].descripcion + '</td><td style="width:120px">' + sensores[i].valor + ' ' + sensores[i].unidad + '</td><td>' + aplicaciones + '</td></tr></table>');



                        }
                        $("#info").prepend("<hr>");
                        $("#info").prepend('<div style="color: #515151;/* font-size: x-large; */background-color: #cbcbcb;border-radius: 3px;color: #333333;font-weight: bold;"">Tetra</div>');
                        $("#info").append("<hr>");
                        $("#info").append("<img src='imagenes/excel.png' style='float: right;width: 7%;top: -3px;position: relative;'>");
                        if (swOn == 1) {
                            $("#info").append('<div class="onoffswitch" style="left: 0%;"><input onclick="validarRiego(id,' + idControl + ')"type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked><label class="onoffswitch-label" for="myonoffswitch"><span class="onoffswitch-inner"></span><span class="onoffswitch-switch"></span></label></div>');




                            if (ultimo_estado == 3 || ultimo_estado == 2 || (ultimo_estado == 0 && ultimo_estado == 0 && p_fecha_ini < fecha_now && p_fecha_fin < fecha_now)) {
                                $("#myonoffswitch").prop("checked", true);
                                if (ultimo_estado == 3) {

                                }
                            }

                            if (ultimo_estado == 1 || (ultimo_estado == 0 && ultimo_estado == 0 && p_fecha_ini < fecha_now && p_fecha_fin > fecha_now)) {
                                $("#myonoffswitch").prop("checked", false);
                            }
                        }
                        $("#info").prepend('<img onclick="erasepopup()" style="float:right;width: 5%;/* height:5%; */cursor: pointer;margin: 1%;position: relative;" src="imagenes/cancelar.png">');
                        $("#info").draggable();
                        swOn = 0;
                        x = event.clientX;
                        y = event.clientY;
                        document.getElementById('info').style.left = x + "px";
                        document.getElementById('info').style.top = y + "px";
                        document.getElementById('info').style.position = 'absolute';
                    }
                }
            });

        }
        function erasepopup() {
            $(".info").remove();
        }
        function umbrales(value) {
            var id_empresa = <?php echo $id_empresa; ?>;
            var data = 'id_empresa=' + id_empresa;
            $.ajax({
                url: 'Ajax/sensoresEmpresa.php',
                type: 'post',
                data: data,
                //async:false,    
                beforeSend: function () {
                },
                success: function (resp) {
                    var sensores = JSON.parse(resp);
                    var id_elementos = [];
                    m = $('#padre').find('div').length
                    m = 100;
                    x = 0;
                    for (i = 0; i <= m; i++) {
                        if ($("#z_" + i + "")[0]) {
                            id_elementos[x] = i;
                            x++;
                        }
                    }
                    tipo = value;
                    var data = 'id_elementos=' + id_elementos + '&tipo=' + tipo;
                    $.ajax({
                        url: 'Ajax/umbrales.php',
                        type: 'post',
                        data: data,
                        //async:false,    
                        beforeSend: function () {
                        },
                        success: function (resp) {
                            $(".controlP").remove();
                            $(".sensorP").remove();
                            var elementos = JSON.parse(resp);
                            for (i = 0; i < elementos.length; i++) {
                                if (tipo != "control" && tipo != "general") {

                                    if (elementos[i].tipo == tipo) {
                                        valor = elementos[i].valor;
                                        for (m = 0; m < elementos.length; m++) {
                                            if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                                elementos[m].sw = 1;
                                            }
                                        }
                                        umbral_superior = elementos[i].umbral_superior;
                                        umbral_inferior = elementos[i].umbral_inferior;
                                        for (x = 1; x < productoFinal.length; x++) {
                                            id_img = "z_" + idelemento;
                                            idelemento = productoFinal[x].getIdelemento();
                                            if (idelemento == elementos[i].id_elemento) {
                                                var tipoObj = productoFinal[x].getTipo();

                                                if (valor < umbral_inferior) {
                                                    neutral = productoFinal[x].getImgDown();
                                                    alert(neutral);
                                                    productoFinal[x].cambiarUrl(neutral);
                                                    var y = document.getElementById("dom_" + idelemento);
                                                    y.remove(y.selectedIndex);
                                                    var div = productoFinal[x].crearDOM();
                                                    var nuevoElemento = $(div);
                                                    $('#padre').append(nuevoElemento);
                                                } else if (valor > umbral_superior) {
                                                    neutral = productoFinal[x].getImgUp();
                                                    productoFinal[x].cambiarUrl(neutral);
                                                    var y = document.getElementById("dom_" + idelemento);
                                                    y.remove(y.selectedIndex);
                                                    var div = productoFinal[x].crearDOM();
                                                    var nuevoElemento = $(div);
                                                    $('padre').append(nuevoElemento);
                                                } else {
                                                    neutral = productoFinal[x].getImgNeutral();
                                                    productoFinal[x].cambiarUrl(neutral);
                                                    var y = document.getElementById("dom_" + idelemento);
                                                    y.remove(y.selectedIndex);
                                                    var div = productoFinal[x].crearDOM();
                                                    var nuevoElemento = $(div);
                                                    $('#padre').append(nuevoElemento);
                                                }
                                                if (tipoObj == 2) {
                                                    var dom = "dom_" + elementos[i].id_elemento;
                                                    $("#" + dom).append("<div id='label' class='controlP' style='position:relative; z-index:9999;'><strong>" + valor + elementos[i].unidad + "</strong></div>");
                                                }
                                                 
                                            }
                                        }
                                    } else if (elementos[i].sw != 1) {
                                        for (x = 1; x < productoFinal.length; x++) {
                                            idelemento = productoFinal[x].getIdelemento();
                                            if (idelemento == elementos[i].id_elemento) {
                                                neutral = productoFinal[x].getImgOriginal();
                                                productoFinal[x].cambiarUrl(neutral);
                                                var y = document.getElementById("dom_" + idelemento);
                                                y.remove(y.selectedIndex);
                                                var div = productoFinal[x].crearDOM();
                                                var nuevoElemento = $(div);
                                                $('#padre').append(nuevoElemento);
                                            }
                                        }
                                    }                                   
                                } else if (tipo == "control") {

                                    if (elementos[i].tipo == tipo) {
                                        valor = elementos[i].valor;
                                        for (m = 0; m < elementos.length; m++) {
                                            if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                                elementos[m].sw = 1;
                                            }
                                        }
                                        umbral_superior = elementos[i].umbral_superior;
                                        umbral_inferior = elementos[i].umbral_inferior;
                                        for (x = 1; x < productoFinal.length; x++) {
                                            idelemento = productoFinal[x].getIdelemento();
                                            id_img = "z_" + idelemento;

                                            if (idelemento == elementos[i].id_elemento) {
                                                var tipoObj = productoFinal[x].getTipo();

                                                if (valor == "Detenido") {
                                                    neutral = productoFinal[x].getImgUp();
                                                    productoFinal[x].cambiarUrl(neutral);
                                                    var y = document.getElementById("dom_" + idelemento);
                                                    y.remove(y.selectedIndex);
                                                    var div = productoFinal[x].crearDOM();
                                                    var nuevoElemento = $(div);
                                                    $('#padre').append(nuevoElemento);
                                                } else if (valor == "Funcionando") {
                                                    neutral = productoFinal[x].getImgDown();
                                                    productoFinal[x].cambiarUrl(neutral);
                                                    var y = document.getElementById("dom_" + idelemento);
                                                    y.remove(y.selectedIndex);
                                                    var div = productoFinal[x].crearDOM();
                                                    var nuevoElemento = $(div);
                                                    $('#padre').append(nuevoElemento);
                                                }
                                                if (tipoObj == 2) {
                                                    var dom = "dom_" + elementos[i].id_elemento;
                                                    $("#" + dom).append("<div id='label' class='controlP' style='position:relative; z-index:9999;'><strong>" + valor + elementos[i].unidad + "</strong></div>");
                                                }
                                            }
                                        }
                                    } else if (elementos[i].sw != 1) {
                                        for (x = 1; x < productoFinal.length; x++) {
                                            idelemento = productoFinal[x].getIdelemento();
                                            if (idelemento == elementos[i].id_elemento) {
                                                neutral = productoFinal[x].getImgOriginal();
                                                productoFinal[x].cambiarUrl(neutral);
                                                var y = document.getElementById("dom_" + idelemento);
                                                y.remove(y.selectedIndex);
                                                var div = productoFinal[x].crearDOM();
                                                var nuevoElemento = $(div);
                                                $('#padre').append(nuevoElemento);
                                            }
                                        }
                                    }
                                } else if (tipo == "general") {
                                    if (elementos[i].prioridad == 1) {
                                        if (elementos[i].tipo == "control") {
                                            valor = elementos[i].valor;
                                            for (m = 0; m < elementos.length; m++) {
                                                if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                                    elementos[m].sw = 1;
                                                }
                                            }
                                            umbral_superior = elementos[i].umbral_superior;
                                            umbral_inferior = elementos[i].umbral_inferior;
                                            for (x = 1; x < productoFinal.length; x++) {
                                                idelemento = productoFinal[x].getIdelemento();
                                                dom = "dom_" + idelemento;
                                                id_img = "z_" + idelemento;
                                                if (idelemento == elementos[i].id_elemento) {
                                                    var tipoObj = productoFinal[x].getTipo();



                                                    if (valor == "Detenido") {
                                                        $("#" + id_img).css({
                                                            webkitfilter: "hue-rotate(10deg) brightness(300%)",
                                                            filter: "hue-rotate(97deg) brightness(180%)"
                                                        });
                                                    } else if (valor == "Funcionando") {
                                                        id_img = "z_" + idelemento;
                                                        $("#" + id_img).css({
                                                            webkitfilter: "hue-rotate(50deg) brightness(600%);",
                                                            filter: "hue-rotate(50deg) brightness(600%);"
                                                        });
                                                    }
                                                    if (tipoObj == 2) {
                                                        var dom = "dom_" + elementos[i].id_elemento;
                                                        $("#" + dom).append("<div id='label' class='controlP' style='position:relative; z-index:9999;'><strong>" + valor + elementos[i].unidad + "</strong></div>");
                                                    }
                                                }
                                            }
                                        } else {
                                            valor = elementos[i].valor;
                                            for (m = 0; m < elementos.length; m++) {
                                                if (elementos[i].id_elemento == elementos[m].id_elemento) {
                                                    elementos[m].sw = 1;
                                                }
                                            }


                                            umbral_superior = elementos[i].umbral_superior;
                                            umbral_inferior = elementos[i].umbral_inferior;
                                            for (x = 1; x < productoFinal.length; x++) {
                                                id_img = "z_" + idelemento;
                                                idelemento = productoFinal[x].getIdelemento();
                                                dom = "dom_" + idelemento;
                                                if (idelemento == elementos[i].id_elemento) {
                                                    var tipoObj = productoFinal[x].getTipo();

                                                    if (valor < umbral_inferior) {
                                                        neutral = productoFinal[x].getImgDown();
                                                        alert(neutral);
                                                        productoFinal[x].cambiarUrl(neutral);
                                                        var y = document.getElementById("dom_" + idelemento);
                                                        y.remove(y.selectedIndex);
                                                        var div = productoFinal[x].crearDOM();
                                                        var nuevoElemento = $(div);
                                                        $('#padre').append(nuevoElemento);
                                                    } else if (valor > umbral_superior) {
                                                        neutral = productoFinal[x].getImgUp();
                                                        productoFinal[x].cambiarUrl(neutral);
                                                        var y = document.getElementById("dom_" + idelemento);
                                                        y.remove(y.selectedIndex);
                                                        var div = productoFinal[x].crearDOM();
                                                        var nuevoElemento = $(div);
                                                        $('padre').append(nuevoElemento);
                                                    } else {
                                                        neutral = productoFinal[x].getImgNeutral();
                                                        productoFinal[x].cambiarUrl(neutral);
                                                        var y = document.getElementById("dom_" + idelemento);
                                                        y.remove(y.selectedIndex);
                                                        var div = productoFinal[x].crearDOM();
                                                        var nuevoElemento = $(div);
                                                        $('#padre').append(nuevoElemento);
                                                    }
                                                    if (tipoObj == 2) {
                                                        var dom = "dom_" + elementos[i].id_elemento;
                                                        $("#" + dom).append("<div id='label' class='controlP' style='position:relative; z-index:9999;'><strong>" + valor + elementos[i].unidad + "</strong></div>");
                                                    }
                                                }
                                            }
                                        }
                                    } else if (elementos[i].sw != 1) {
                                        for (x = 1; x < productoFinal.length; x++) {
                                            idelemento = productoFinal[x].getIdelemento();
                                            if (idelemento == elementos[i].id_elemento) {
                                                neutral = productoFinal[x].getImgOriginal();
                                                productoFinal[x].cambiarUrl(neutral);
                                                var y = document.getElementById("dom_" + idelemento);
                                                y.remove(y.selectedIndex);
                                                var div = productoFinal[x].crearDOM();
                                                var nuevoElemento = $(div);
                                                $('#padre').append(nuevoElemento);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            });
            llenarElemento();
        }
        function validarRiego(id, id_nodo) {
            var data = 'id_nodo=' + id_nodo;
            $.ajax({
                url: 'Ajax/semaforoRiego.php',
                type: 'post',
                data: data,
                async: false,
                beforeSend: function () {
                },
                success: function (resp) {
                    console.log(resp);
                    var sensores = JSON.parse(resp);
                    console.log(ultimo_estado = sensores[0].estado);
                    console.log(p_fecha_ini = sensores[0].p_fecha_ini);
                    console.log(p_fecha_fin = sensores[0].p_fecha_fin);
                    console.log(fecha_now = sensores[0].fecha_now);


                    if (!$('#' + id).prop('checked')) {
                        if (ultimo_estado == 3) {
                            alert("no puedes pasar a off hasta que parte el riego anterior");
                            $("#myonoffswitch").prop("checked", true);
                        } else {
                            var data = 'id_nodo=' + id_nodo;
                            $.ajax({
                                url: 'Ajax/agendarOn.php',
                                type: 'post',
                                data: data,
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (resp) {
                                    console.log(resp);
                                }
                            });
                        }
                    } else if ($('#' + id).prop('checked')) {
                        alert("paso a on");
                        if (ultimo_estado == 0 && ultimo_estado == 0 && p_fecha_ini < fecha_now && p_fecha_fin > fecha_now) {
                            alert("no puedes pasar a on hasta que parte el riego anterior");
                            $("#myonoffswitch").prop("checked", false);
                        } else {
                            var data = 'id_nodo=' + id_nodo;
                            $.ajax({
                                url: 'Ajax/agendarOff.php',
                                type: 'post',
                                data: data,
                                async: false,
                                beforeSend: function () {
                                },
                                success: function (resp) {
                                    console.log(resp);
                                }
                            });
                        }
                    }
                }
            });




            /*if(ultimo_estado == 3 ||  ultimo_estado == 2 || (ultimo_estado == 0 && ultimo_estado == 0 && p_fecha_ini < fecha_now && p_fecha_fin < fecha_now)){
             $("#myonoffswitch").prop("checked", true);
             }
             if(ultimo_estado == 1 || (ultimo_estado == 0 && ultimo_estado == 0 && p_fecha_ini < fecha_now && p_fecha_fin > fecha_now) ){
             $("#myonoffswitch").prop("checked", false);
             }
             
             
             
             
             
             if(!$('#'+id).prop('checked')) {
             var data = 'id_nodo=' + id_nodo;
             $.ajax({
             url: 'Ajax/agendarOn.php',
             type: 'post',
             data: data,
             async:false, 
             beforeSend: function () {
             },
             success: function (resp) {
             $("#myonoffswitch").attr("disabled", true);
             console.log(resp);
             }
             });
             }
             if($('#'+id).prop('checked')) {
             var data = 'id_nodo=' + id_nodo;
             $.ajax({
             url: 'Ajax/agendarOff.php',
             type: 'post',
             data: data,
             async:false, 
             beforeSend: function () {
             },
             success: function (resp) {
             $("#myonoffswitch").attr("disabled", true);
             console.log(resp);
             }
             });
             }*/
        }
        function logout() {
            $.ajax({
                url: 'Ajax/loginout.php',
                type: 'post',
                data: data,
                beforeSend: function () {
                },
                success: function (resp) {
                    console.log("resp");
                    window.location = "http://200.24.229.186/scada/login.html";
                }
            });
        }
    </script>
    <body>

        <header id="colophon">
            <label style="margin-top: -0.3%;margin-bottom: 0%;font-family:Segoe UI,Segoe,Tahoma,Arial,Verdana,sans-serif;">Lem Control</label>
            <div class="fit-content" >
                <ul name="select-choice-1" class="fit-content" id="select-choice-1" style="display: none">
                    <li style="margin-top: 0.8%;">
                        <input name="radio-view" id="radio-view-a" type="radio" data-theme="a" data-mini="true" onClick="setMes()" checked value="Mes"/><label for="radio-view-a">M</label> 
                        <input name="radio-view" id="radio-view-b" type="radio" data-theme="a" data-mini="true" onClick="setSemana()" value="Semana"/><label for="radio-view-b">S</label>
                        <input name="radio-view" id="radio-view-c" type="radio" data-theme="a" data-mini="true" onClick="setDia()" value="Dia"/><label for="radio-view-c">D</label>   
                    </li><li style="margin-top: 1.3%;"><label>|</label></li>
                    <li style="margin-top: 0.2%;">
                        <form id="asdasd">
                            <div style="float: left;">
                                <label for="date-1" >Desde:</label>
                                <input type="text" data-mini="true" data-clear-btn="true" onChange="abre_fecha_fin();" name="date-1" id="fecha_inicio" required>   
                                <label for="date-2" >Hasta:</label>
                                <input type="text" data-mini="true" style="line-height: 16px;" data-clear-btn="true" name="date-2" id="fecha_fin" required>

                            </div>
                            <div style="float: right; margin-left: 15px;margin-top: 4px;">
                                <input type="submit" id="save" onClick="setNow();" style="float: none;font-size: initial;"  class="classmenucerrar square green effect-2"  data-inline="true" value="Consultar" />                              
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
            <!--a href="" class="cerrar" data-role="button" data-theme="a" data-icon="delete" onclick="window.close();">Cerrar</a-->
        </header>
        <div id="titulo" style="text-align:center;">
            <div style="float: left;padding: 15px;">
                <img src="imagenes/blanco2.png" alt="" style="width: 199px;">
            </div>
            <div style="float: left;margin-top: 15px;">
                <label style="margin: -4px;position: relative;top: -4px;font-size: 29px;color: rgb(0, 202, 219);">|</label>
                <label style="font-family: Segoe UI,Segoe,Tahoma,Arial,Verdana,sans-serif;margin-left: 9px;color: rgb(148, 148, 148);position: relative;top: -6px;font-size: 1.2em;">Scada Miguel</label>
                <label style="font-family: Segoe UI,Segoe,Tahoma,Arial,Verdana,sans-serif;margin-left: 9px;color: rgb(148, 148, 148);position: relative;top: -6px;font-size: 1.2em;">
            </div>
            <form class="form-inline">
                <div class="form-group">
                    <select style="position: relative;top: 11px;right: -205%;height: 29px;" onchange="umbrales(value);" id="sensores" class="form-control"><option value="general">general</option>
                    </select>
                </div>
            </form>
            <img style="width:2.6%;position:relative;float:right;top:-24px;" src="imagenes/logout.png" onclick="logout();">
        </div>
        <div class="onoffswitch2" style="float: right; margin-top: 39%;">
            <input onclick="ventana();" type="checkbox" name="onoffswitch2" class="onoffswitch2-checkbox2" id="myonoffswitch2" checked>
            <label class="onoffswitch2-label2" for="myonoffswitch2"></label>
        </div>
        <div id="infoline"></div>
        <div style="width: 100%; height: 100%" id="padre"></div>
    </body>
</html>

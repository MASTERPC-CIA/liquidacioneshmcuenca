<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo tagcontent('div', '<strong> CONSOLIDADO DE RECETARIO INTEGRADO NO FACTURADO </strong>', array('style' => 'font-size:20;text-align:center'));
echo LineBreak(1, array('class' => 'clr'));

echo Open('form', array('method' => 'post', 'action' => base_url('liquidacioneshmcuenca/index/generar_fac'), 'class' => 'col-md-12'));
//    echo tagcontent('div', 'Seleccione el Cliente', array('id'=>'client_name_det','class'=>'col-md-4'));                                                
//echo input(array('type' => 'hidden', 'name' => 'client_id', 'id' => 'client_id_det'));
$text_inputs = array(
    '0' => array('name' => "fecha_desde_f", 'id' => "fecha_desde_f", 'type' => "text", 'value' => date('Y-m-d'), 'class' => "form-control input-sm datepicker", 'placeholder' => "Desde", 'style' => "width: 50%"),
    '1' => array('name' => "fecha_hasta_f", 'id' => "fecha_hasta_f", 'type' => "text", 'value' => date('Y-m-d'), 'class' => "form-control input-sm datepicker", 'placeholder' => "Hasta", 'style' => "width: 50%")
);
echo get_field_group('Fecha', $text_inputs, $class = 'col-md-6 form-group');

//IMPUT estado comprobantes
/* $combo_tipos = combobox(
  $lista_estados, array('label' => 'estado', 'value' => 'id', 'nombre' => 'estado'), array('name' => 'estado', 'class' => 'form-control'), true, '1', '-2');
  echo get_combo_group('Estado', $combo_tipos, $class = 'col-md-3 form-group'); */
echo tagcontent('button', 'Buscar facturas', array('id' => 'ajaxformbtn', 'data-target' => 'anticipos_search_out', 'class' => 'btn btn-primary btn-sm'));
echo Close('form');

/*echo Open('form', array('method' => 'post', 'action' => base_url('facturacionhospital/generar_factura/generar_fac'), 'class' => 'col-md-6'));
//    echo tagcontent('div', 'Seleccione el Cliente', array('id'=>'client_name','class'=>'col-md-8'));
//echo input(array('type' => 'hidden', 'name' => 'client_id', 'id' => 'client_id'));

echo input(array('name' => "fecha_desde_f", 'id' => "fecha_desde_f", 'type' => "hidden", 'value' => '', 'class' => "form-control input-sm datepicker", 'placeholder' => "Desde", 'style' => "width: 50%"));
echo input(array('name' => "fecha_hasta_f", 'id' => "fecha_hasta_f", 'type' => "hidden", 'value' => '', 'class' => "form-control input-sm datepicker", 'placeholder' => "Hasta", 'style' => "width: 50%"));

echo tagcontent('button', 'Generar factura', array('id' => 'ajaxformbtn', 'data-target' => 'anticipos_search_out', 'class' => 'btn btn-primary btn-sm'));
echo Close('form');*/

echo lineBreak2(1, array('class' => 'clr'));
echo tagcontent('div', '', array('id' => 'anticipos_search_out', 'class' => 'col-mc-12'));
?>



<script>
   /* $('#client_id').val('');
    var load_client = function (datum) {
        $('#client_name').html(datum.value).removeAttr('style');
        $('#client_name').attr('style', 'background:#d7ebf9; font-weight: bold;color:#000; font-size:14px');
//        $('#client_name_det').html(datum.value).removeAttr('style');        
//        $('#client_name_det').attr('style','background:#d7ebf9; font-weight: bold;color:#000; font-size:20px');

        $('#client_id').val(datum.ci);
        $('#client_id_det').val(datum.ci);
    };

    $.autosugest_search('#client_by_name_autosugest');*/
//    $.autosugest_search('#client_by_ci_autosugest');


    /*$(document).ready(function () {
        $("#fecha_desde_f").attr("value", $('#fecha_desde').val());
        $("#fecha_hasta_f").attr("value", $('#fecha_hasta').val());

        $("#fecha_desde").change(function () {
            var value = $('#fecha_desde').val();
            $("#fecha_desde_f").attr("value", value);
        });
        $("#fecha_hasta").change(function () {
            var value1 = $('#fecha_hasta').val();
            $("#fecha_hasta_f").attr("value", value1);
        });
    });*/
</script>  
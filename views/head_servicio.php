<?php
echo tagcontent('div', '', array('id' => 'print_solicitud', 'class' => 'col-md-12'));

echo Open('div', array('class' => 'panel panel-info'));
echo Open('div', array('class' => 'panel panel-heading'));
echo '<strong>SERVICIOS - OPCIONES DE BUSQUEDA</strong>';
echo Close('div');

echo Open('form', array('action' => base_url('liquidacioneshmcuenca/honorarios/honorarios_med/get_list_servicio'), 'method' => 'post'));

echo Open('div', array('class' => 'panel panel-body'));
echo Open('div', array('class' => 'col-md-3 form-group'));
echo Open('div', array('class' => 'input-group'));
echo tagcontent('span', 'Fecha: ', array('class' => 'input-group-addon'));
echo input(array('value' => '', 'data-provide' => 'datepicker', 'class' => 'form-control input-sm', 'id' => 'f_desde', 'name' => 'f_desde', 'placeholder' => 'Desde', 'style' => "width: 50%"));
echo input(array('value' => '', 'data-provide' => 'datepicker', 'class' => 'form-control input-sm', 'id' => 'f_hasta', 'name' => 'f_hasta', 'placeholder' => 'Hasta', 'style' => "width: 50%"));
echo Close('div');
echo Close('div');

//Boton buscar
echo Open('div', array('class' => 'col-md-3 form-group'));
echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> BUSCAR', array('id' => 'ajaxformbtn', 'data-target' => 'result_informes_out', 'class' => 'btn btn-primary'));
echo Close('div');
echo Close('div');
echo Close('div');

echo Close('form');

echo Open('div', array('class' => 'panel panel-success'));
echo Open('div', array('class' => 'panel panel-heading'));
echo '<strong>RESULTADOS DE BUSQUEDA</strong>';
echo Close('div');

echo Open('div', array('class' => 'panel panel-body'));
echo Open('div', array('class' => 'input-group col-md-12'));
echo tagcontent('div', '', array('id' => 'result_informes_out'));
echo Close('div');
echo Close('div');
?>
<script>
    //  document.getElementById("list_serv_informe").value = "<?php echo $id_doc; ?>";
    /*Enviamos los datos extraidos por el autosuggest a sus inputs correspondientes*/
    /*var load_cliente = function (datum) {
     console.log(datum);
     $('#ci_paciente').val(datum.ci);
     $('#id_paciente').val(datum.id);
     };
     $.autosugest_search('#paciente_id');*/
</script>
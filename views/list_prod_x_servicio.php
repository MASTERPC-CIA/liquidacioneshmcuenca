<?php
echo tagcontent('span','',array('us-spinner'=>"{radius:30, width:8, length: 10}"));

echo Open('form',array('name'=>'devoluciones_form','class'=>'form-horizontal','role'=>'form','novalidate'=>''));
    $text_inputs = array(
                        '0' => array('data-provide' => 'datepicker','class' => 'form-control','placeholder' => 'yyyy-MM-dd','style'=>'width:50%','ng-model'=>'fecha_ini.value','ng-required'=>'true'),
                        '1' => array('data-provide' => 'datepicker','class' => 'form-control','placeholder' => 'yyyy-MM-dd','style'=>'width:50%','ng-model'=>'fecha_fin.value','ng-required'=>'true')
                    );
    echo get_field_group('Fechas:', $text_inputs, $class = 'col-md-4 form-group has-warning');
    echo Open('div',array('class' => 'form-group col-sm-3'));
        echo Open('div',array('class' => 'input-group has-warning'));
            echo tagcontent('span','Servicio:',array('class'=>"input-group-addon", 'id'=>"basic-addon1"));
            $option2 = tagcontent('option','Seleccionar',array('value'=>"",'selected'=>''));
            echo tagcontent('select',$option2,array('class'=>"form-control", 'ng-options'=>"bodega.dep_gp_bodega_id as bodega.dep_gp_descripcion for bodega in bodegas", 'ng-model'=>"selectedBodega",'ng-required'=>'true'));
        echo Close('div');
    echo Close('div');
    
    echo tagcontent('button', ' Buscar', array('class' => 'btn btn-primary glyphicon glyphicon-search col-md-1', 'type' => 'button','ng-click'=>'consultarServ(fecha_ini.value,fecha_fin.value,selectedBodega)','ng-disabled'=>'devoluciones_form.$invalid'));
    echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Close('form');//Cierre del formulario

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px','ng-show'=>'true'));
//    echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
    echo Open('div', array('class'=>'col-md-12'));
        $this->load->view('common/hmc_head/encabezado_cuenca');
    echo Close('div');
    echo '<center><b>INVENTARIO VALORADO</b></center><BR>';
    echo '<b>SERVICIO: </b> {{nombre_bodega}} <b><BR>';
    echo '<b>PERIODO: </b> {{fecha_ini.value}} - {{fecha_fin.value}}<BR>';
    echo Open('table',array('border'=>'1','style'=>'font-family:monospace;font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
         echo Open('tr',array('style'=>'background-color:b0b0b0'));
            echo tagcontent('th', 'CODIGO', array('width'=>'10%'));
            echo tagcontent('th', 'NOMBRE', array('width'=>'30%'));
            echo tagcontent('th', 'COSTO', array('width'=>'5%'));
            echo tagcontent('th', 'PVP', array('width'=>'5%'));
            echo tagcontent('th', 'INICIAL', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            echo tagcontent('th', 'INGRESOS', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            echo tagcontent('th', 'DEVOLUCIONES', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            echo tagcontent('th', 'EGRESOS', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            echo tagcontent('th', 'EXISTENCIA', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
        echo Close('tr');
        echo Open('tr',array('ng-repeat'=>'p in list'));
            echo tagcontent('td', '{{ p.codigo }}');
            echo tagcontent('td', '{{ p.nombreUnico }}', array('width'=>'30%'));
            echo tagcontent('td', '{{ p.costopromediokardex | number:4 }}',array('align'=>'right', 'width'=>'5%'));
            echo tagcontent('td', '{{ p.pvp | number:4 }}',array('align'=>'right', 'width'=>'5%'));

            echo tagcontent('td', '{{ p.cant_inicial }}', array('align'=>'right','width'=>'5%'));
            echo tagcontent('td', '{{ p.tot_inicial | number:4}}', array('align'=>'right','width'=>'5%'));

            echo tagcontent('td', '{{ p.ingresos_cont }}', array('align'=>'right','width'=>'5%'));
            echo tagcontent('td', '{{ p.ingresos_tot | number:4}}', array('align'=>'right','width'=>'5%'));

            echo tagcontent('td', '{{ p.egre_ajuste_cont }}', array('align'=>'right','width'=>'5%'));
            echo tagcontent('td', '{{ p.egre_ajuste_tot | number:4}}', array('align'=>'right','width'=>'5%'));

            echo tagcontent('td', '{{ p.egre_ventas_cont }}', array('align'=>'right','width'=>'5%'));
            echo tagcontent('td', '{{ p.egre_ventas_tot | number:4}}', array('align'=>'right','width'=>'5%'));

            echo tagcontent('td', '{{ p.kardex_total }}', array('align'=>'right','width'=>'5%'));
            echo tagcontent('td', '{{ p.subtotal | number:4 }}',array('align'=>'right', 'width'=>'5%'));

        echo Close('tr'); 
        echo Open('tr',array('width'=>'10%'));
            echo tagcontent('td', '<b>TOTAL : </b>', array('colspan'=>4,'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_inicial | number:4 }}</b>', array('colspan'=>2, 'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_ingresos| number:4 }}</b>', array('colspan'=>2,'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_devol | number:4 }}</b>', array('colspan'=>2, 'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_ventas | number:4 }}</b>', array('colspan'=>2, 'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_final | number:4 }}</b>', array('colspan'=>2,'align'=>'right'));
        echo Close('tr');
    echo Close('table');
   
echo Close('div');
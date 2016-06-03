<?php
echo Open('form',array('name'=>'devoluciones_form','class'=>'form-horizontal','role'=>'form','novalidate'=>''));
    $text_inputs =  array(
                        '0' => array('data-provide' => 'datepicker', 'name' => 'fecha_ini', 'class' => 'form-control','placeholder' => 'yyyy-MM-dd','style'=>'width:50%','ng-model'=>'fecha_ini.value','ng-required'=>'true'),
                        '1' => array('data-provide' => 'datepicker', 'name' => 'fecha_fin', 'class' => 'form-control', 'placeholder' => 'yyyy-MM-dd','style'=>'width:50%','ng-model'=>'fecha_fin.value','ng-required'=>'true')
                    );
    echo get_field_group('Fechas:', $text_inputs, $class = 'col-md-4 form-group');
    echo Open('div',array('class' => 'form-group col-sm-3'));
        echo Open('div',array('class' => 'input-group has-warning'));
            echo tagcontent('span','Bodegas',array('class'=>"input-group-addon", 'id'=>"basic-addon1"));
            $option = tagcontent('option','Seleccionar',array('value'=>"",'selected'=>''));
            echo tagcontent('select',$option,array('class'=>"form-control", 'ng-options'=>"bodega.id as bodega.nombre for bodega in bodegas", 'ng-model'=>"selectedBodega"));
        echo Close('div');
    echo Close('div');
    echo tagcontent('button', '', array('class' => 'btn btn-primary glyphicon glyphicon-search col-sm-1', 'type' => 'button','ng-click'=>'consultar(selectedBodega)','ng-disabled'=>'devoluciones_form.$invalid'));
    echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-sm-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Close('form');
?><span us-spinner="{radius:30, width:8, length: 10}"></span><?php
echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px','ng-show'=>'facturas.length > 0'));
    echo '<b>BODEGA : {{nombre_bodega}}</b><BR>';
    echo '<b><CENTER>'.get_settings('RAZON_SOCIAL').'</CENTER></b><BR>';
    echo '<CENTER><b>RESUMEN DE DEVOLUCIONES</b><CENTER>';
    echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
        $thead=array('Nro. Devoluci&oacute;n','Detalle', 'Subtotal', 'IVA', 'Total');
        echo tablethead($thead);
        echo Open('tr',array('ng-repeat'=>'f in facturas'));
            echo tagcontent('td', '{{ f.id }}');
            //echo tagcontent('td', '{{ f.proveedor }}');
            echo tagcontent('td', '{{ f.observaciones }}');
            echo tagcontent('td', '{{ f.subtotal | number:2 }}', array('align'=>'right'));
            echo tagcontent('td', '{{ f.iva | number:2 }}', array('align'=>'right'));
            echo tagcontent('td', '{{ f.total | number:2 }}', array('align'=>'right'));
        echo Close('tr');
        echo Open('tr');
            echo Open('td',array('colspan'=>2,'align'=>'right'));
                echo '<b>TOTAL</b>';
            echo Close('td');
            echo tagcontent('td', '{{ suma.subtotal | number:2 }}', array('align'=>'right'));
            echo tagcontent('td', '{{ suma.iva | number:2 }}', array('align'=>'right'));
            echo tagcontent('td', '{{ suma.total | number:2 }}', array('align'=>'right'));
        echo Close('tr');
    echo Close('table');
     $this->load->view('footer_liq_farmacia');
echo Close('div');
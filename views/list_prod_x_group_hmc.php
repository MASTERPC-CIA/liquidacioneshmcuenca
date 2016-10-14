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
            echo tagcontent('span','Bodegas:',array('class'=>"input-group-addon", 'id'=>"basic-addon1"));
            $option2 = tagcontent('option','Seleccionar',array('value'=>"",'selected'=>''));
            echo tagcontent('select',$option2,array('class'=>"form-control", 'ng-options'=>"bodega.id as bodega.nombre for bodega in bodegas", 'ng-model'=>"selectedBodega",'ng-required'=>'true'));
        echo Close('div');
    echo Close('div');
    echo Open('div',array('class' => 'form-group col-sm-3'));
        echo Open('div',array('class' => 'input-group has-warning'));
            echo tagcontent('span','Grupos: ',array('class'=>"input-group-addon", 'id'=>"basic-addon1"));
            $option1 = tagcontent('option','Seleccionar',array('value'=>"",'selected'=>''));
            echo tagcontent('select',$option1,array('class'=>"form-control", 'ng-options'=>"grupos_farm.codigo as grupos_farm.nombre for grupos_farm in grupos_farm", 'ng-model'=>"selectedGrupoFarm",'ng-required'=>'true'));
        echo Close('div');
    echo Close('div');
    echo tagcontent('button', ' Buscar', array('class' => 'btn btn-primary glyphicon glyphicon-search col-md-1', 'type' => 'button','ng-click'=>'consultar(fecha_ini.value,fecha_fin.value,selectedBodega,selectedGrupoFarm)','ng-disabled'=>'devoluciones_form.$invalid'));
    echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Close('form');//Cierre del formulario

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px','ng-show'=>'true'));
    echo Open('div', array('class'=>'col-md-12'));
        $this->load->view('common/hmc_head/encabezado_cuenca');
    echo Close('div');
    echo '<center><b>INVENTARIO VALORADO</b></center><BR>';
    echo '<b>BODEGA: </b> {{nombre_bodega}}, <b> GRUPO: </b> {{nombre_grupo}}<BR>';
    echo '<b>PERIODO: </b> {{fecha_ini.value}} - {{fecha_fin.value}}<BR>';
    echo Open('table',array('border'=>'1','style'=>'font-family:monospace;font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
        
        echo Open('tr',array('style'=>'background-color:b0b0b0;text-align:center'));
            echo tagcontent('td', 'CODIGO', array('width'=>'8%','rowspan'=>'2'));
            echo tagcontent('td', 'NOMBRE', array('width'=>'32%','rowspan'=>'2'));
            echo tagcontent('td', 'SALDO ANTERIOR', array('colspan'=>'3'));
            echo tagcontent('td', 'INGRESOS', array('colspan'=>'3'));
            echo tagcontent('td', 'COMPRAS', array('colspan'=>'3'));
            echo tagcontent('td', 'DEVOLUCIONES', array('colspan'=>'3'));
            echo tagcontent('td', 'EGRESOS', array('colspan'=>'3'));
            echo tagcontent('td', 'SALDO ACTUAL', array('colspan'=>'3'));
        echo Close('tr');

        echo Open('tr',array('style'=>'background-color:b0b0b0;text-align:center'));
            echo tagcontent('td', 'CANT.', array('width'=>'3%'));
            echo tagcontent('td', 'PRECIO', array('width'=>'3%'));
            echo tagcontent('td', 'TOTAL', array('width'=>'4%'));
            echo tagcontent('td', 'CANT.', array('width'=>'3%'));
            echo tagcontent('td', 'PRECIO', array('width'=>'3%'));
            echo tagcontent('td', 'TOTAL', array('width'=>'4%'));
            echo tagcontent('td', 'CANT.', array('width'=>'3%'));
            echo tagcontent('td', 'PRECIO', array('width'=>'3%'));
            echo tagcontent('td', 'TOTAL', array('width'=>'4%'));
            echo tagcontent('td', 'CANT.', array('width'=>'3%'));
            echo tagcontent('td', 'PRECIO', array('width'=>'3%'));
            echo tagcontent('td', 'TOTAL', array('width'=>'4%'));
            echo tagcontent('td', 'CANT.', array('width'=>'3%'));
            echo tagcontent('td', 'PRECIO', array('width'=>'3%'));
            echo tagcontent('td', 'TOTAL', array('width'=>'4%'));
            echo tagcontent('td', 'CANT.', array('width'=>'3%'));
            echo tagcontent('td', 'PRECIO', array('width'=>'3%'));
            echo tagcontent('td', 'TOTAL', array('width'=>'4%'));
            
        echo Close('tr');
    
        echo Open('tr',array('ng-repeat'=>'p in list'));
            echo tagcontent('td', '{{ p.codigo }}');
            echo tagcontent('td', '{{ p.nombreUnico }}');

            echo tagcontent('td', '{{ p.cant_inicial }}', array('align'=>'right'));
            echo tagcontent('td', '{{ p.costo_prom_inicial | number:4 }}',array('align'=>'right'));
            echo tagcontent('td', '{{ p.tot_inicial | number:4}}', array('align'=>'right'));
            
            echo tagcontent('td', '{{ p.cant_aje }}', array('align'=>'right'));
            echo tagcontent('td', '{{ p.costo_prom_aje | number:4 }}',array('align'=>'right'));
            echo tagcontent('td', '{{ p.tot_aje | number:4}}', array('align'=>'right'));
            
            echo tagcontent('td', '{{ p.cant_comp }}', array('align'=>'right'));
            echo tagcontent('td', '{{ p.costo_prom_comp | number:4 }}',array('align'=>'right'));
            echo tagcontent('td', '{{ p.tot_comp | number:4}}', array('align'=>'right'));
            
            echo tagcontent('td', '{{ p.cant_ajs }}', array('align'=>'right'));
            echo tagcontent('td', '{{ p.costo_prom_ajs | number:4 }}',array('align'=>'right'));
            echo tagcontent('td', '{{ p.tot_ajs | number:4}}', array('align'=>'right'));
            
            echo tagcontent('td', '{{ p.cant_vent }}', array('align'=>'right'));
            echo tagcontent('td', '{{ p.costo_prom_vent | number:4 }}',array('align'=>'right'));
            echo tagcontent('td', '{{ p.tot_vent | number:4}}', array('align'=>'right'));
            
            echo tagcontent('td', '{{ p.cant_final }}', array('align'=>'right'));
            echo tagcontent('td', '{{ p.costo_prom_final | number:4 }}',array('align'=>'right'));
            echo tagcontent('td', '{{ p.tot_final | number:4}}', array('align'=>'right'));


        echo Close('tr'); 
        echo Open('tr');
            echo tagcontent('td', '<b>TOTAL : </b>', array('colspan'=>2,'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_inicial | number:4 }}</b>', array('colspan'=>3, 'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_ingresos| number:4 }}</b>', array('colspan'=>3,'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_compras | number:4 }}</b>', array('colspan'=>3, 'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_devol | number:4 }}</b>', array('colspan'=>3,'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_ventas | number:4 }}</b>', array('colspan'=>3,'align'=>'right'));
            echo tagcontent('td', '<b>{{ tot_final | number:4 }}</b>', array('colspan'=>3,'align'=>'right'));
        echo Close('tr');
    echo Close('table');
   
    
echo Close('div');
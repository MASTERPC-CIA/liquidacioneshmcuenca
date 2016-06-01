<?php
echo tagcontent('div','<strong style="font-size:20px;">LISTADO GENERAL DE INGRESOS Y DEVOLUCIONES</strong>',array('class'=>'col-md-12','style'=>'text-align:center;'));
echo lineBreak2(1, array('class'=>'clr'));
    echo Open('form', array('method'=>'post','action'=>  base_url().'liquidacioneshmcuenca/ingresos_devoluc/get_ingresos_devoluciones','style'=>'margin-top:10px')); 
    echo input(array('type'=>'hidden','name'=>'supplier_id','id'=>'supplier_id'));
    echo Open('div',array('class'=>'col-md-4 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'Fechas:', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_desde",'id'=>"f_desde", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_hasta",'id'=>"f_hasta", 'value'=>'', 'data-provide'=>"datepicker", 'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    
    $combo_bodega = combobox($bodega, array('label' => 'nombre', 'value' => 'id'), array('class' => 'form-control input-sm', 'name' => 'bodega_id'),TRUE,1);
    echo get_combo_group('Bodega', $combo_bodega, 'col-md-3 form-group has-warning');
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('type'=>'submit','id'=>'ajaxformbtn','data-target'=>'ingreso_devoluc_out','class'=>'btn btn-primary btn-sm'));
echo Close('form');

echo tagcontent('div', '', array('id'=>'ingreso_devoluc_out','class'=>'col-md-12'));
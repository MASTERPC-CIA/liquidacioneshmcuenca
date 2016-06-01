<?php
echo tagcontent('div','<strong style="font-size:20px;">REPORTE DE MOVIMIENTOS</strong>',array('class'=>'col-md-12','style'=>'text-align:center;'));
echo lineBreak2(1, array('class'=>'clr'));
    echo Open('form', array('method'=>'post','action'=>  base_url().'liquidacioneshmcuenca/liquid_recaudacion/liquidacion_recaud/get_facts_por_servicio','style'=>'margin-top:10px')); 
    echo input(array('type'=>'hidden','name'=>'supplier_id','id'=>'supplier_id'));
    echo Open('div',array('class'=>'col-md-3 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'Fechas: ', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_desde",'id'=>"fecha_desde", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_hasta",'id'=>"fecha_hasta", 'value'=>'', 'data-provide'=>"datepicker", 'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    
    $combo_depart = combobox($departamentos, array('label' => 'nombre', 'value' => 'idDepartamento'), array('class' => 'form-control input-sm', 'name' => 'depart_id'),TRUE);
    echo get_combo_group('Servicio: ', $combo_depart, 'col-md-3 form-group has-warning');
    
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('type'=>'submit','id'=>'ajaxformbtn','data-target'=>'res_fact_serv_out','class'=>'btn btn-primary btn-sm'));
echo Close('form');

echo tagcontent('div', '', array('id'=>'res_fact_serv_out','class'=>'col-md-12'));

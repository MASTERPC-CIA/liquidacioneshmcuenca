<?php
echo tagcontent('div','<strong style="font-size:20px;">HONORARIOS M&Eacute;DICOS</strong>',array('class'=>'col-md-12','style'=>'text-align:center;'));
echo lineBreak2(1, array('class'=>'clr'));
    echo Open('form', array('method'=>'post','action'=>  base_url().'liquidacioneshmcuenca/liquid_general/liquidacion_general/get_honor_medicos','style'=>'margin-top:10px')); 
    echo input(array('type'=>'hidden','name'=>'supplier_id','id'=>'supplier_id'));
    echo Open('div',array('class'=>'col-md-3 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'Fechas: ', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_desde",'id'=>"fecha_desde", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_hasta",'id'=>"fecha_hasta", 'value'=>'', 'data-provide'=>"datepicker", 'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    
    $combo_depart = combobox($servicios_bodega, array('label' => 'dep_gp_descripcion', 'value' => 'dep_gp_id_grupo'), array('class' => 'form-control input-sm', 'name' => 'depart_id'),TRUE);
    echo get_combo_group('Servicio: ', $combo_depart, 'col-md-3 form-group has-warning');
    
    //Se agrega la opción para poder filtrar por tipo de paciente
    
    $combo_tipo_cliente = combobox($tipos_cliente, array('label' => 'tipo', 'value' => 'idclientetipo'), array('class' => 'form-control input-sm', 'name' => 'tipocliente_id'),TRUE);
    echo get_combo_group('Tipo de paciente: ', $combo_tipo_cliente, 'col-md-3 form-group has-warning');
    
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('type'=>'submit','id'=>'ajaxformbtn','data-target'=>'res_honor_medicos_out','class'=>'btn btn-primary btn-sm'));
echo Close('form');

echo tagcontent('div', '', array('id'=>'res_honor_medicos_out','class'=>'col-md-12'));

<?php
echo tagcontent('div','<strong style="font-size:20px;">REPORTE ESTUDIO DE ANTIGUEDADES </strong>',array('class'=>'col-md-12','style'=>'text-align:center;'));
echo lineBreak2(1, array('class'=>'clr'));
echo Open('form', array('method'=>'post','action'=>  base_url().'liquidacioneshmcuenca/tesoreria/reporte_estudio_antig/get_reporte_estudio_antig','style'=>'margin-top:10px')); 
    echo input(array('type'=>'hidden','name'=>'supplier_id','id'=>'supplier_id'));
    echo Open('div',array('class'=>'col-md-3 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'Fechas: ', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_desde",'id'=>"fecha_desde", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_hasta",'id'=>"fecha_hasta", 'value'=>'', 'data-provide'=>"datepicker", 'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    
    $combo_aseguradoras = combobox($aseguradoras, array('label' => 'ase_nombre', 'value' => 'id'), array('class' => 'form-control input-sm', 'name' => 'aseg_id'),TRUE,1);
    echo get_combo_group('Aseguradora: ', $combo_aseguradoras, 'col-md-3 form-group has-warning');
    
    $combo_opciones_rep = combobox_array(array('Cliente','Servicio'), array('name'=>'tipo_rep','class'=>'form-control'), true);
    echo get_combo_group('Filtrar por:', $combo_opciones_rep, 'col-md-3 form-group has-warning');
    
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('type'=>'submit','id'=>'ajaxformbtn','data-target'=>'res_ing_diario_out','class'=>'btn btn-primary btn-sm'));
echo Close('form');

echo tagcontent('div', '', array('id'=>'res_ing_diario_out','class'=>'col-md-12'));

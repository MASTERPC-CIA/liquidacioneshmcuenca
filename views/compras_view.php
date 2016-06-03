<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
echo tagcontent('div','<strong style="font-size:20px;">LISTADO GENERAL DE COMPRAS</strong>',array('class'=>'col-md-12','style'=>'text-align:center;'));
echo lineBreak2(1, array('class'=>'clr'));
    echo Open('form', array('method'=>'post','action'=>  base_url().'liquidacionhospital/compras/crud_compras','style'=>'margin-top:10px')); 
    echo input(array('type'=>'hidden','name'=>'supplier_id','id'=>'supplier_id'));
    echo Open('div',array('class'=>'col-md-3 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'F. Ingreso', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_desde",'id'=>"fechacreaciond", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_hasta",'id'=>"fechacreacionh", 'value'=>'', 'data-provide'=>"datepicker", 'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    echo Open('div',array('class'=>'col-md-3 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'F. Emision', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_emi_desde",'id'=>"fechaemisiond", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_emi_hasta",'id'=>"fechaemisionh", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    echo Open('div',array('class'=>'col-md-3 form-group'));
        echo Open('div',array('class'=>'input-group has-warning'));
          echo tagcontent('span', 'F. Archivada', array('class'=>'input-group-addon'));
          echo input(array('name'=>"f_arch_desde",'id'=>"fechaarchivadad", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm",'placeholder'=>"Desde", 'style'=>"width: 50%"));
          echo input(array('name'=>"f_arch_hasta",'id'=>"fechaarchivadah", 'value'=>'', 'data-provide'=>"datepicker",'class'=>"form-control input-sm", 'placeholder'=>"Hasta", 'style'=>"width: 50%"));
        echo Close('div');
    echo Close('div');
    $combo_bodega = combobox($bodega, array('label' => 'nombre', 'value' => 'id'), array('class' => 'form-control input-sm', 'name' => 'bodega_id'),TRUE,1);
    echo get_combo_group('Bodega', $combo_bodega, 'col-md-2 form-group has-warning');
    
    echo tagcontent('button', '<span class="glyphicon glyphicon-search"></span> Buscar', array('type'=>'submit','id'=>'ajaxformbtn','data-target'=>'resumencomprasout','class'=>'btn btn-primary btn-sm'));
echo Close('form');

echo tagcontent('div', '', array('id'=>'resumencomprasout','class'=>'col-md-12'));

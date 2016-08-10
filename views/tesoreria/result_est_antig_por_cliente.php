<?php

/*Necesarias para exportar a excel*/
header('Content-Type: application/vnd.ms-excel');// Para trabajar con los navegadores IE y Opera 
header('Content-type: application/x-msexcel'); // Para trabajar con el resto de navegadores
header('Content-Disposition: attachment;filename="reporte_antig_cliente.xls"');
header('Cache-Control: max-age=0');
header('Expires: 0');

echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
echo '<CENTER><b>ESTUDIO DE ANTIGUEDAD DE SALDOS</b></CENTER><BR>';
echo '<b>Fecha desde: </b>'.$fecha_desde.' - <b> Fecha hasta: </b>'.$fecha_hasta;
echo LineBreak(2);
echo Open('table', array('border'=>'1', 'style'=>'width:100%'));
    $cont =0;
    echo Open('tr', array('align'=>'center'));
        echo tagcontent('td', '<b>MES</b>');
        echo tagcontent('td', '<b>C.I.</b>');
        echo tagcontent('td', '<b>APELLIDOS Y NOMBRES</b>');
        echo tagcontent('td', '<b>OFICIO</b>');
        echo tagcontent('td', '<b>V. COBRAR</b>');
        echo tagcontent('td', '<b>V. LIQUIDADO</b>');
        echo tagcontent('td', '<b>SALDO</b>');
        echo tagcontent('td', '<b>OBSERVACION</b>');

    echo Close('tr');
    
        if($campo_tipo['lista_clientes_plan']){
            foreach ($campo_tipo['lista_clientes_plan'][0] as $value){
                echo Open('tr', array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'left'));
                    echo tagcontent('td', $value->ci);
                    echo tagcontent('td', $value->nombres);
                    echo tagcontent('td', '');
                    echo tagcontent('td', number_format($value->pla_valor_aseguradora, get_settings('NUM_DECIMALES'), '.', ','));
                    echo tagcontent('td', number_format(0 , get_settings('NUM_DECIMALES'), '.', ','));
                    echo tagcontent('td', number_format($value->pla_valor_aseguradora, get_settings('NUM_DECIMALES'), '.', ','));
                    echo tagcontent('td', '');
                echo Close('tr');
            }
        }
        if($campo_tipo['lista_clientes_comp']){
            foreach ($campo_tipo['lista_clientes_comp'][0] as $value){
                echo Open('tr', array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'left'));
                    echo tagcontent('td', $value->ci);
                    echo tagcontent('td', $value->nombres);
                    echo tagcontent('td', '');
                    echo tagcontent('td', number_format($value->totalCompra, get_settings('NUM_DECIMALES'), '.', ','));
                    echo tagcontent('td', number_format(0 , get_settings('NUM_DECIMALES'), '.', ','));
                    echo tagcontent('td', number_format($value->totalCompra, get_settings('NUM_DECIMALES'), '.', ','));
                    echo tagcontent('td', '');
                echo Close('tr');
            }
        }
   
    echo Open('tr', array('align'=>'right', 'style'=>'font-weight:900'));

        echo tagcontent('td', '<b>TOTAL</b>', array('colspan'=>'4'));
        echo tagcontent('td', number_format($campo_tipo['total_aseg'], get_settings('NUM_DECIMALES'), '.', ','));
        echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
        echo tagcontent('td', number_format($campo_tipo['total_aseg'], get_settings('NUM_DECIMALES'), '.', ','));

    echo Close('tr');
    
    
echo Close('table');

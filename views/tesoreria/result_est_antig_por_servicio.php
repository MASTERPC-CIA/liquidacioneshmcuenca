<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));

    echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
    echo '<CENTER><b>ESTUDIO DE ANTIGUEDAD DE SALDOS</b></CENTER><BR>';
    echo '<b>Período: Desde: </b>'.$fecha_desde.' - <b> Hasta: </b>'.$fecha_hasta;
    echo LineBreak(2);
    echo Open('table', array('border'=>'1', 'style'=>'width:100%'));
        $cont =0;
        echo Open('tr', array('align'=>'center'));
            echo tagcontent('td', '<b>MES</b>');
            echo tagcontent('td', '<b>SERVICIO</b>');
            echo tagcontent('td', '<b>OFICIO</b>');
            echo tagcontent('td', '<b>V. COBRAR</b>');
            echo tagcontent('td', '<b>V. LIQUIDADO</b>');
            echo tagcontent('td', '<b>SALDO</b>');
            echo tagcontent('td', '<b>OBSERVACION</b>');
           
        echo Close('tr');

//        foreach ($campo_tipo as $value){
            echo Open('tr', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'left'));
                echo tagcontent('td', 'CONSULTA EXTERNA', array('align'=>'left'));
                echo tagcontent('td', '');
                echo tagcontent('td', number_format($campo_tipo['serv_cons_ext'][0]->tot_comp_serv, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0 , get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', '');
            echo Close('tr');
//        }
             echo Open('tr', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'left'));
                echo tagcontent('td', 'ODONTOLOGIA', array('align'=>'left'));
                echo tagcontent('td', '');
                echo tagcontent('td', number_format($campo_tipo['serv_odont'][0]->tot_comp_serv, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0 , get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', '');
            echo Close('tr');
            
             echo Open('tr', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'left'));
                echo tagcontent('td', 'HOSPITALIZACION', array('align'=>'left'));
                echo tagcontent('td', '');
                echo tagcontent('td', number_format($campo_tipo['serv_hospit'][0]->tot_val_aseg, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0 , get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', '');
            echo Close('tr');
            
             echo Open('tr', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'left'));
                echo tagcontent('td', 'EMERGENCIA', array('align'=>'left'));
                echo tagcontent('td', '');
                echo tagcontent('td', number_format($campo_tipo['serv_emerg'][0]->tot_val_aseg, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0 , get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
                echo tagcontent('td', '');
            echo Close('tr');
            
            
        echo Open('tr', array('align'=>'right', 'style'=>'font-weight:900'));
            
            echo tagcontent('td', '<b>TOTAL</b>', array('colspan'=>'3'));
            echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
            echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
            echo tagcontent('td', number_format(0, get_settings('NUM_DECIMALES'), '.', ','));
            
        echo Close('tr');
    echo Close('table');
echo Close('div');
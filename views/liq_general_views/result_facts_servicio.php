<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));

        echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
        echo '<CENTER><b>REPORTE DE MOVIMIENTOS</b></CENTER><BR>';
        echo '<b>PERIODO : </b><BR>';
        echo '<b>SERVICIO: </b><BR>';
    
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            echo Open('tr');
                echo tagcontent('th', 'ORD', array('width'=>'5%'));
                echo tagcontent('th', 'NRO. FACT.', array('width'=>'10%'));
                echo tagcontent('th', 'FECHA EM.', array('width'=>'10%'));
                echo tagcontent('th', 'TARIFA', array('width'=>'5%'));
                echo tagcontent('th', 'VALOR', array('width'=>'8%'));
                echo tagcontent('th', 'TIPO', array('width'=>'5%'));
                echo tagcontent('th', 'C.I.', array('width'=>'10%'));
                echo tagcontent('th', 'APELLIDOS Y NOMBRES', array('width'=>'30%'));
                echo tagcontent('th', 'MED.', array('width'=>'5%'));
                echo tagcontent('th', 'CONVENIO', array('width'=>'5%'));
                echo tagcontent('th', 'OBS.', array('width'=>'7%'));
            echo Close('tr');

    //        foreach ($array as $value) { //Para los ingresos
                echo Open('tr');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '', array('align'=>'right'));
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                echo Close('tr');
    //        }


            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL:</b>', array('colspan'=>'4', 'style'=>'text-align:right'));
                echo tagcontent('td', '', array('colspan'=>'7', 'style'=>'text-align:left'));
            echo Close('tr');
            
        echo Close('table');
echo Close('div');
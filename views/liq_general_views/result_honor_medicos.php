<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));

        echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
        echo '<CENTER><b>LIQUIDACIÓN DE ESTUDIOS ECOGRAFICOS</b></CENTER><BR>';
        echo '<b>PERIODO : </b><BR>';
        echo '<b>SERVICIO: </b><BR>';
        echo '<b>DR.: </b><BR><BR>';
    
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            echo Open('tr');
                echo tagcontent('th', 'ORD', array('width'=>'8%'));
                echo tagcontent('th', 'FECHA', array('width'=>'12%'));
                echo tagcontent('th', 'NOMBRE DEL PACIENTE', array('width'=>'30%'));
                echo tagcontent('th', 'SERV.', array('width'=>'7%'));
                echo tagcontent('th', 'GRADO', array('width'=>'7%'));
                echo tagcontent('th', 'ESTUDIO', array('width'=>'20%'));
                echo tagcontent('th', 'VALOR', array('width'=>'8%'));
                echo tagcontent('th', '25% DEL TOTAL', array('width'=>'8%'));
            echo Close('tr');

    //        foreach ($array as $value) { //Para los ingresos
                echo Open('tr');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '', array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'right'));
                echo Close('tr');
    //        }


            echo Open('tr');
                echo tagcontent('td', '<b>TOTALES:</b>', array('colspan'=>'6', 'style'=>'text-align:right'));
                echo tagcontent('td', '');
                echo tagcontent('td', '');
            echo Close('tr');
            
        echo Close('table');
echo Close('div');
<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));

        echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
        echo '<CENTER><b>CONSOLIDADO DE INGRESOS AL CONTADO Y CREDITO</b></CENTER><BR>';
        echo '<b>PERIODO: </b><BR>';
    
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            //Para las hojas de alta de civiles
            echo Open('tr');
                echo tagcontent('th', 'FECHA', array('width'=>'10%'));
                echo tagcontent('th', 'SERVICIO', array('width'=>'60%'));
                echo tagcontent('th', 'CONTADO', array('width'=>'10%'));
                echo tagcontent('th', 'CREDITO', array('width'=>'10%'));
                echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
                
            echo Close('tr');

    //        foreach ($array as $value) { //Para los ingresos
                echo Open('tr');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '');
                    echo tagcontent('td', '', array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'right'));
                echo Close('tr');
    //        }


            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL GENERAL:</b>', array('colspan'=>'2','style'=>'text-align:right'));
                echo tagcontent('td', '', array('style'=>'text-align:right'));
                echo tagcontent('td', '', array('style'=>'text-align:right'));
                echo tagcontent('td', '', array('style'=>'text-align:right'));
            echo Close('tr');
            
        echo Close('table');
    echo LineBreak(4);
    $this->load->view('liq_recaudacion_views/footer_recaudacion');
echo Close('div');
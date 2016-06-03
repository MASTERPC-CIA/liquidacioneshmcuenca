<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));
    echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
    echo '<b>PERIODO : </b><BR>';

    echo '<b>BODEGA : </b><BR>';
    echo '<CENTER><b>LIQUIDACION DE BOTICA VALORADA</b><CENTER>';
    echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
        $thead=array('TIPO','VENTAS SIN IVA','VENTAS CON IVA', 'IVA','UTILIDAD', 'TOTAL');
        echo tablethead($thead);
        
        echo Open('tr');
            echo tagcontent('td', 'INGRESOS', array('colspan'=>'6'));
        echo Close('tr');
        
        
//        foreach ($array as $value) {
            echo Open('tr');
                echo tagcontent('td', '');
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
            echo Close('tr');
//        }
        echo Open('tr');
            echo tagcontent('td', 'EGRESOS', array('colspan'=>'6'));
        echo Close('tr');
//        foreach ($array as $value) {
            echo Open('tr');
                echo tagcontent('td', '');
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
            echo Close('tr');
//        }
        echo Open('tr');
            echo tagcontent('td', '<CENTER><b>TOTALES</b></CENTER>', array('style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'.'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'.'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'.'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'.'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'.'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
        echo Close('tr');
    echo Close('table');
    
     $this->load->view('footer_liq_farmacia');
echo Close('div');
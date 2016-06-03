<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));
    echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
    echo 'FECHAS: hasta  <BR>';
    echo 'INVENTARIO DE PRODUCTOS: <BR>';
    echo '<CENTER><b>INVENTARIO DEL PERIODO</b><CENTER>';
    echo Open('table',array('border'=>'1','style'=>'font-family:monospace;font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
        echo Open('tr', array('style'=>'text-align:center;font-weight:600'));
            echo tagcontent('td', 'CODIGO', array('width'=>'10%', 'rowspan'=>'2'));
            echo tagcontent('td', 'NOMBRE', array('width'=>'30%', 'rowspan'=>'2'));
            echo tagcontent('td', 'SALDO ANTERIOR', array('width'=>'15%', 'colspan'=>'3'));
            echo tagcontent('td', 'INGRESOS', array('width'=>'15%', 'colspan'=>'3'));
            echo tagcontent('td', 'EGRESOS', array('width'=>'15%', 'colspan'=>'3'));
            echo tagcontent('td', 'SALDO ACTUAL', array('width'=>'15%', 'colspan'=>'3'));
        echo Close('tr');
    
        echo Open('tr',array('style'=>'background-color:b0b0b0'));
            //Saldo Anterior
            echo tagcontent('th', 'CANTIDAD', array('width'=>'5%'));
            echo tagcontent('th', 'PRECIO', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            //Ingresos
            echo tagcontent('th', 'CANTIDAD', array('width'=>'5%'));
            echo tagcontent('th', 'PRECIO', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            //Egresos
            echo tagcontent('th', 'CANTIDAD', array('width'=>'5%'));
            echo tagcontent('th', 'PRECIO', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
            //Saldo Actual
            echo tagcontent('th', 'CANTIDAD', array('width'=>'5%'));
            echo tagcontent('th', 'PRECIO', array('width'=>'5%'));
            echo tagcontent('th', 'TOTAL', array('width'=>'5%'));
        echo Close('tr');
        
//        foreach ($array as $value) {
            echo Open('tr',array('ng-repeat'=>'p in list'));
                echo tagcontent('td', '', array('width'=>'10%'));
                echo tagcontent('td', '', array('width'=>'30%'));
                
                //Saldo Anterior
                echo tagcontent('td', '',array('align'=>'right', 'width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                //Ingresos
                echo tagcontent('td', '',array('align'=>'right', 'width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                //Egresos
                echo tagcontent('td', '',array('align'=>'right', 'width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                //Saldo Actual
                echo tagcontent('td', '',array('align'=>'right', 'width'=>'5%'));
                echo tagcontent('td', '', array('align'=>'right','width'=>'5%'));
                echo tagcontent('td', '',array('align'=>'right', 'width'=>'5%'));
            echo Close('tr');
//        }
         
        echo Open('tr',array('width'=>'10%'));
            echo tagcontent('td', '<b>TOTAL : </b>', array('colspan'=>2,'align'=>'right'));
            echo tagcontent('td', '<b>Total anterior</b>', array('colspan'=>3, 'align'=>'right'));
            echo tagcontent('td', '<b>Total ingresos</b>', array('colspan'=>3,'align'=>'right'));
            echo tagcontent('td', '<b>Total egresos</b>', array('colspan'=>3, 'align'=>'right'));
            echo tagcontent('td', '<b>Total final</b>', array('colspan'=>3,'align'=>'right'));
        echo Close('tr');
    echo Close('table');
    
    echo Open('table',array('style'=>'font-family:monospace;background-color:white','width'=>'100%'));
        echo Open('tr', array('style'=>'text-align:center'));
            echo tagcontent('td', '..........................');
            echo tagcontent('td', '..........................');
            echo tagcontent('td', '..........................');
            echo tagcontent('td', '..........................');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center;font-weight:600'));
            echo tagcontent('td', 'ENCARGADO DE BODEGA');
            echo tagcontent('td', 'CONTADOR');
            echo tagcontent('td', 'JEFE FINANCIERO');
            echo tagcontent('td', 'DIRECTOR');
        echo Close('tr');
    echo Close('table');
echo Close('div');
<?php
echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta_ing_devoluc'));
echo Open('div',array('class'=>'col-md-12','id'=>'consulta_ing_devoluc','style'=>'font-size:12px'));
    echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
    echo '<CENTER><b>RESUMEN DE INGRESOS Y DEVOLUCIONES</b><CENTER><BR>';
    echo '<CENTER><b>'.$nombre_bodega.'</b></CENTER>';
    echo '<CENTER><b>PERIODO : Desde </b>'.$fecha_desde .'<b> hasta </b>'.$fecha_hasta.'</CENTER><br>';
    echo '<CENTER><b>RESUMEN DE INGRESOS</b><CENTER>';
    echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
       $thead=array('Nro. Ingreso','Proveedor','Detalle', 'Subtotal', 'IVA', 'Total');
        echo tablethead($thead);
        foreach ($ingresos['query'] as $val){
            echo Open('tr');
                echo tagcontent('td', $val->id);
                echo tagcontent('td', $val->proveedor);
                echo tagcontent('td', $val->observaciones);
                echo tagcontent('td', number_format($val->subtotal, 2, ',',''),array('align'=>'right'));
                echo tagcontent('td', number_format($val->iva, 2, ',',''),array('align'=>'right'));
                echo tagcontent('td', number_format($val->total, 2, ',',''), array('align'=>'right'));
            echo Close('tr');
        }
        echo Open('tr');
            echo Open('td',array('colspan'=>3,'align'=>'right'));
                echo '<b>TOTAL</b>';
            echo Close('td');
            echo tagcontent('td', number_format($ingresos['suma']->subtotal, 2, ',',''), array('align'=>'right'));
            echo tagcontent('td', number_format($ingresos['suma']->iva, 2, ',',''), array('align'=>'right'));
            echo tagcontent('td', number_format($ingresos['suma']->total, 2, ',',''), array('align'=>'right'));
        echo Close('tr');
    echo Close('table');
    
    echo Linebreak(1);
    
    echo '<CENTER><b>RESUMEN DE DEVOLUCIONES</b><CENTER>';
    echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
        $thead2=array('Nro. Devoluci&oacute;n','Detalle', 'Subtotal', 'IVA', 'Total');
        echo tablethead($thead2);
        foreach ($devoluciones['query2'] as $val){
            echo Open('tr');
                echo tagcontent('td', $val->id);
                echo tagcontent('td', $val->observaciones);
                echo tagcontent('td', number_format($val->subtotal, 2, ',',''),array('align'=>'right'));
                echo tagcontent('td', number_format($val->iva, 2, ',',''),array('align'=>'right'));
                echo tagcontent('td', number_format($val->total, 2, ',',''), array('align'=>'right'));
            echo Close('tr');
        }
        echo Open('tr');
            echo Open('td',array('colspan'=>3,'align'=>'right'));
                echo '<b>TOTAL</b>';
            echo Close('td');
            echo tagcontent('td', number_format($devoluciones['suma2']->subtotal, 2, ',',''), array('align'=>'right'));
            echo tagcontent('td', number_format($devoluciones['suma2']->iva, 2, ',',''), array('align'=>'right'));
            echo tagcontent('td', number_format($devoluciones['suma2']->total, 2, ',',''), array('align'=>'right'));
        echo Close('tr');
    echo Close('table'); 
    echo Linebreak(1); 
    echo tagcontent('div','<b>TOTAL SALDO A FAVOR: </b>'. $saldo_favor, array('style'=>'text-align:left') );
//    echo Open('div',array('class'=>'col-sm-12'));
//        echo '<b>TOTAL SALDO A FAVOR: </b>'. $saldo_favor;
//    echo Close('div'); 
   $this->load->view('footer_liq_farmacia');
echo Close('div');


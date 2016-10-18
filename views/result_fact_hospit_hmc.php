<?php
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:'.  get_settings('FONT_SIZE_FACT')));
    echo Open('div', array('class'=>'col-md-12'));
        $this->load->view('common/hmc_head/encabezado_cuenca');
    echo Close('div');

    echo '<CENTER><b>LIQUIDACION BOTICA VALORADA</b><CENTER>';
    echo '<b>PERIODO: </b>'.$fecha_desde.' - '.$fecha_hasta.'<BR>';
    echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
        $thead=array('TIPO','VENTAS SIN IVA','VENTAS CON IVA', 'IVA', 'TOTAL');
        echo tablethead($thead);
       
        foreach ($lista_tipos['lista'] as $value) {
            echo Open('tr');
                echo tagcontent('td', 'Paciente Tipo '.$value->tipoC->tipo);
                echo tagcontent('td', number_decimal($value->subtotal_0), array('align'=>'right'));
                echo tagcontent('td', number_decimal($value->subtotal_iva), array('align'=>'right'));
                echo tagcontent('td', number_decimal($value->iva), array('align'=>'right'));
//                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', number_decimal($value->valor_total), array('align'=>'right'));
            echo Close('tr');
        }
        echo Open('tr');
            echo tagcontent('td', '<CENTER><b>TOTALES</b></CENTER>', array('style'=>'font-size:14px','align'=>'right'));
            echo tagcontent('td', '<b>'. number_decimal($lista_tipos['total_iva_0']).'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'. number_decimal($lista_tipos['total_otro_iva']).'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'. number_decimal($lista_tipos['total_iva']).'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
//            echo tagcontent('td', '<b>'.'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
            echo tagcontent('td', '<b>'. number_decimal($lista_tipos['total']).'</b>', array('colspan'=>'1','style'=>'font-size:16px','align'=>'right'));
        echo Close('tr');
    echo Close('table');
    $this->load->view('footer_liq_farmacia');
echo Close('div');

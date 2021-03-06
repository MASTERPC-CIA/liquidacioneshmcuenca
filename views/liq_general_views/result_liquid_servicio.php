<?php
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));
    echo Open('div',array('class'=>'col-md-12'));
//        echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
        echo Open('div', array('class'=>'col-md-12'));
            $this->load->view('common/hmc_head/encabezado_cuenca');
        echo Close('div');
        echo '<CENTER><b>LIQUIDACION SERVICIO DE '.$nombreS.'</b></CENTER><BR>';
        echo '<b>PERIODO : </b>'.$fecha_desde.'  '.$fecha_hasta.'<BR>';
        
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            echo Open('tr');
                echo tagcontent('th', 'INGRESOS', array('colspan'=>'2', 'style'=>'text-align:center'));
            echo Close('tr');
            echo Open('tr');
                echo tagcontent('th', 'TIPO CLIENTE Y SERVICIO', array('width'=>'80%'));
                echo tagcontent('th', 'TOTAL', array('width'=>'20%'));
            echo Close('tr');
            $tot_ing=0;
            if($ing_facturas){
                foreach ($ing_facturas['list_ingresos'] as $value) {
                    echo Open('tr');
                        echo tagcontent('td', $value->descrip_ing);
                        echo tagcontent('td', number_decimal($value->valor_ing), array('align'=>'right'));
                    echo Close('tr');
                }
                $tot_ing +=$ing_facturas['tot_val'];
            }
//            if($ing_planillas){
//                foreach ($ing_planillas['list_ingresos'] as $value) {
//                    echo Open('tr');
//                        echo tagcontent('td', $value->descrip_ing);
//                        echo tagcontent('td', number_decimal($value->valor_ing), array('align'=>'right'));
//                    echo Close('tr');
//                }
//                $tot_ing +=$ing_planillas['tot_val'];
//            }
            echo Open('tr');
                echo tagcontent('th', 'TOTAL DE INGRESOS: ');
                echo tagcontent('td', number_decimal($tot_ing), array('align'=>'right'));
            echo Close('tr');
        echo Close('table');
        
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
//            echo Open('tr');
//                echo tagcontent('th', 'EGRESOS', array('colspan'=>'2', 'style'=>'text-align:center' ));
//            echo Close('tr');
            echo Open('tr');
                echo tagcontent('th', 'EGRESOS', array('width'=>'80%'));
                echo tagcontent('th', 'TOTAL', array('width'=>'20%'));
            echo Close('tr');
            

    //        foreach ($array as $value) {//Para los egresos
                echo Open('tr');
                    echo tagcontent('td', 'HABERES ESPECIALISTAS');
                    echo tagcontent('td', number_decimal($egr_fact), array('align'=>'right'));
                echo Close('tr');
    //        }

            echo Open('tr');
                echo tagcontent('th', 'TOTAL DE EGRESOS: ');
                echo tagcontent('td', '');
            echo Close('tr');
        echo Close('table');
    echo Close('div');
    echo LineBreak(2); 

    echo Open('div', array('class'=>'col-md-12'));
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));    
            echo Open('tr');
                echo tagcontent('th', 'BALANCE', array('colspan'=>'2', 'style'=>'text-align:center'));
            echo Close('tr');

            echo Open('tr');
                echo tagcontent('td', '<b>INGRESOS</b>', array('width'=>'80%'));
                echo tagcontent('td',number_decimal($tot_ing) , array('align'=>'right', 'width'=>'20%'));
            echo Close('tr');

            echo Open('tr');
                echo tagcontent('td', '<b>EGRESOS</b>');
                echo tagcontent('td', number_decimal($egr_fact), array('align'=>'right'));
            echo Close('tr');
            echo Open('tr');
                echo tagcontent('td', '<b>SALDO A FAVOR</b>');
                echo tagcontent('td',number_decimal($tot_ing)-number_decimal($egr_fact) , array('align'=>'right'));
            echo Close('tr');
        echo Close('table');
    echo Close('div');
    //Pie de página (Firmas)
    echo LineBreak(4);
    echo Open('div', array('class'=>'col-md-12'));
        echo Open('table',array('style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));    
            echo Open('tr', array('style'=>'text-align:center', 'height'=>'100px'));
                echo tagcontent('td', '<b>CONTADOR</b>', array('width'=>'50%'));
                echo tagcontent('td', '<b>RESPONSABLE</b>', array('width'=>'50%'));
            echo Close('tr');

            echo Open('tr', array('style'=>'text-align:center', 'height'=>'100px'));
                echo tagcontent('td', '<b>JEFE FINANCIERO</b>');
                echo tagcontent('td', '<b>JEFE DE LOGISTICA</b>');
            echo Close('tr');
            
            echo Open('tr', array('style'=>'text-align:center', 'height'=>'100px'));
                echo tagcontent('td', '<b>DIRECTOR</b>', array('colspan'=>'2'));
            echo Close('tr');
        echo Close('table');
    echo Close('div');
echo Close('div');
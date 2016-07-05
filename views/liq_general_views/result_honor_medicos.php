<?php
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>'font-size:16px'));

        echo Open('div', array('class'=>'col-md-12'));
            $this->load->view('common/hmc_head/encabezado_cuenca');
        echo Close('div');
        echo '<CENTER><b>LIQUIDACIÓN DE ESTUDIOS </b></CENTER><BR>';
        echo '<b>PERIODO: </b>'.$fecha_desde.' - '.$fecha_hasta.'<BR>';
        echo '<b>SERVICIO: </b>'.$nombreS.'<BR>';
        
    
        echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            echo Open('tr');
                echo tagcontent('th', 'ORD', array('width'=>'8%'));
                echo tagcontent('th', 'FECHA', array('width'=>'12%'));
                echo tagcontent('th', 'NOMBRE DEL PACIENTE', array('width'=>'30%'));
                echo tagcontent('th', 'SERV.', array('width'=>'7%'));
                echo tagcontent('th', 'GRADO', array('width'=>'7%'));
                echo tagcontent('th', 'ESTUDIO', array('width'=>'20%'));
                echo tagcontent('th', 'VALOR', array('width'=>'8%'));
                echo tagcontent('th', $porcent.'% DEL TOTAL', array('width'=>'8%'));
            echo Close('tr');
            $tot_val_serv=0;
            $total_porcet=0;
            if($clientes_fact){
                $cont=1;
               
                foreach ($clientes_fact as $value) { //Para los ingresos
                    $val_estudio_fact = ($value->valor*($porcent/100));
                    echo Open('tr');
                        echo tagcontent('td', $cont);
                        echo tagcontent('td', $value->fechaarchivada);
                        echo tagcontent('td', $value->nombres);
                        echo tagcontent('td', $value->tipo);
                        echo tagcontent('td', $value->abreviatura);
                        echo tagcontent('td', $value->nombreUnico);
                        echo tagcontent('td', number_decimal($value->valor), array('align'=>'right'));
                        echo tagcontent('td', number_decimal($val_estudio_fact), array('align'=>'right'));
                    echo Close('tr');
                    $tot_val_serv+=$value->valor;
                    $total_porcet += $val_estudio_fact;
                    $cont++;
                }
            }
            if($clientes_plan){
                foreach ($clientes_plan as $value) { //Para los ingresos
                    $val_estudio_pla = ($value->pdet_total*($porcent/100));
                    echo Open('tr');
                        echo tagcontent('td', $cont);
                        echo tagcontent('td', $value->pla_fecha_creacion);
                        echo tagcontent('td', $value->nombres);
                        echo tagcontent('td', $value->tipo);
                        echo tagcontent('td', $value->abreviatura);
                        echo tagcontent('td', $value->nombreUnico);
                        echo tagcontent('td', number_decimal($value->pdet_total), array('align'=>'right'));
                        echo tagcontent('td', number_decimal($val_estudio_pla), array('align'=>'right'));
                    echo Close('tr');
                    $tot_val_serv+=$value->pdet_total;
                    $total_porcet += $val_estudio_pla;
                    $cont++;
                }
            }
            echo Open('tr');
                echo tagcontent('td', '<b>TOTALES:</b>', array('colspan'=>'6', 'style'=>'text-align:right'));
                echo tagcontent('td', number_decimal($tot_val_serv), array('align'=>'right'));
                echo tagcontent('td', number_decimal($total_porcet), array('align'=>'right'));
            echo Close('tr');
            
        echo Close('table');
echo Close('div');
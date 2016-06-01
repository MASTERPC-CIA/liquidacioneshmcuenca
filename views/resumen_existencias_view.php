<?php

echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>  'font-size:'.get_settings('FONT_SIZE_FACT')));
    echo '<CENTER>'. get_settings('RAZON_SOCIAL') .'</CENTER>';
    echo '<CENTER>RESUMEN DE EXISTENCIAS DE FARMACIA</CENTER>';
    echo '<CENTER>'.$nombre_bodega.'</CENTER>';
    echo '<center>PERIODO : '.$desde .' - '.$hasta.'</center><br>';
    
    echo Open('table', array('class' => 'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            $thead = array('ORDEN', 'ITEMS', "EXIST. ANTERIOR", 'COMPRAS SIN IVA', 'COMPRAS CON IVA','TOT. EXIST. + COMPRAS','VENTAS SIN IVA', 'VENTAS CON IVA','VENTAS UTIL.','EXIST. ACTUAL');
            echo tablethead($thead);
            $sum_comp_ext=0;
            $sum_desc=0; $sum_ivadoce=0; $sum_ivacero=0; $sum_recargo=0; $sum_ice=0; $sum_total = 0 ;$sum_iva=0;
            $cont=1;
        
            if($list){
                
            
                foreach ($list as $data){

                    $comp_ext_ant = $data->grupo->tot_exist_anterior + $data->grupo->tot_comp_sin_iva;
                    echo Open('tr',array('style'=>'background-color:adadff'));

                        echo tagcontent('td',$cont);
                        echo tagcontent('td',$data->grupo->nombre);
                        echo tagcontent('td',$data->grupo->tot_exist_anterior,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$data->grupo->tot_comp_sin_iva,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$data->grupo->tot_comp_con_iva,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$comp_ext_ant,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$data->grupo->tot_vent_sin_iva,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$data->grupo->tot_vent_con_iva,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$data->grupo->tot_vent_utilidad,array('style'=>'text-align:right;'));
                        echo tagcontent('td',$data->grupo->tot_exist_actual,array('style'=>'text-align:right;'));
                    echo Close('tr');
                    $cont++;
                    $sum_comp_ext+=$comp_ext_ant;
    //                $sum_desc+=$data->descuentovalor; $sum_ivadoce+=$data->tarifadoceneto; $sum_ivacero+=$data->tarifaceroneto; 
    //                $sum_iva+=$data->iva; $sum_recargo+=$data->recargovalor; $sum_ice+=$data->iceval; $sum_total += $data->totalCompra;
                }
                echo Open('tr',array('style'=>'background-color:adadff'));
                    echo tagcontent('td','<strong>SUBTOTALES $ :</strong>',array('colspan'=>2,'style'=>'text-align:left;'));
                    echo tagcontent('td','<strong>'.$sum_ext_anterior.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_comp_sin_iva.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_comp_con_iva.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_comp_ext.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_vent_sin_iva.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_vent_con_iva.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_vent_utilidad.'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.$sum_ext_actual.'</strong>',array('style'=>'text-align:right;'));
                echo Close('tr');

                echo Open('tr');
    //                echo tagcontent('td','<strong>ENTREGADO A DEPENDENCIAS $ :</strong>',array('colspan'=>6,'style'=>'text-align:left;'));
    //                echo tagcontent('td','<strong>'.$sum_dep_sin_iva.'</strong>',array('style'=>'text-align:right;'));
    //                echo tagcontent('td','<strong>'.$sum_dep_con_iva.'</strong>',array('style'=>'text-align:right;'));
    //                echo tagcontent('td','<strong>'.$sum_dep_utilidad.'</strong>',array('style'=>'text-align:right;'));
    //                echo tagcontent('td','');

                    echo tagcontent('td','<strong>VALOR FIJO ENTREGADO A DEPENDENCIAS $ :</strong>',array('colspan'=>6,'style'=>'text-align:left;'));
                    echo tagcontent('td','<strong>'.get_settings('VALOR_FIJO_DEPENDENCIAS').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.get_settings('VALOR_FIJO_DEPENDENCIAS').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.get_settings('VALOR_FIJO_DEPENDENCIAS').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','');
                echo Close('tr');
            echo Close('table');
            }
        echo LineBreak(4, array('class'=>'clr'));
        
        echo Open('div',array('class'=>'col-md-12','style'=>'text-align:center'));
            echo tagcontent('span','<strong>'.$auxiliar_cont[0]->empleado.'</strong>' );
            echo LineBreak(1, array('class'=>'clr'));
            echo tagcontent('span','<strong>AUXILIAR DE CONTABILIDAD</strong>');
        echo Close('div');
        
echo Close('div'); 
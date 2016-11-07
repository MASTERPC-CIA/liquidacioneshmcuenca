<?php

echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>  'font-size:'.get_settings('FONT_SIZE_FACT')));
//    echo '<CENTER><B>'. get_settings('RAZON_SOCIAL') .'</B></CENTER><br><br>';
    echo Open('div', array('class'=>'col-md-12'));
        $this->load->view('common/hmc_head/encabezado_cuenca');
    echo Close('div');
    echo '<CENTER><B>RESUMEN DE EXISTENCIAS DE '.$nombre_bodega.'</B></CENTER>';
    echo '<CENTER><B>PERIODO:</B> '.$desde .' - '.$hasta.'</CENTER><br>';
    
    echo Open('table', array('class' => 'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            $thead = array('ORDEN', 'ITEMS', "EXIST. ANTERIOR", 'COMPRAS SIN IVA', 'COMPRAS CON IVA','INGR. SIN IVA', 'INGR. CON IVA','TOT. EXIST INGR.','DEV. SIN IVA', 'DEV. CON IVA','VENTAS SIN IVA', 'VENTAS CON IVA','VENTAS UTIL.','EXIST. ACTUAL');
            echo tablethead($thead);
            $sum_comp_ext=0;
            $sum_desc=0; $sum_ivadoce=0; $sum_ivacero=0; $sum_recargo=0; $sum_ice=0; $sum_total = 0 ;$sum_iva=0;
            $cont=1;
        
            if($list){
                
            
                foreach ($list as $data){

                    $comp_ext_ant = $data->grupo->tot_exist_anterior + $data->grupo->tot_comp_sin_iva+$data->grupo->tot_comp_con_iva+$data->grupo->tot_aj_ent_iva_cero+$data->grupo->tot_aj_ent_otro_iva;
                    echo Open('tr',array('style'=>'background-color:adadff'));

                        echo tagcontent('td',$cont);
                        echo tagcontent('td',$data->grupo->nombre);
                        echo tagcontent('td',number_format($data->grupo->tot_exist_anterior, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_comp_sin_iva, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_comp_con_iva, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_aj_ent_iva_cero, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_aj_ent_otro_iva, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($comp_ext_ant, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_aj_sal_iva_cero, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_aj_sal_otro_iva, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_vent_sin_iva, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_vent_con_iva, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_vent_utilidad, 4, '.', ''),array('style'=>'text-align:right;'));
                        echo tagcontent('td',number_format($data->grupo->tot_exist_actual, 4, '.', ''),array('style'=>'text-align:right;'));
                    echo Close('tr');
                    $cont++;
                    $sum_comp_ext+=$comp_ext_ant;
    //                $sum_desc+=$data->descuentovalor; $sum_ivadoce+=$data->tarifadoceneto; $sum_ivacero+=$data->tarifaceroneto; 
    //                $sum_iva+=$data->iva; $sum_recargo+=$data->recargovalor; $sum_ice+=$data->iceval; $sum_total += $data->totalCompra;
                }
                echo Open('tr',array('style'=>'background-color:adadff'));
                    echo tagcontent('td','<strong>SUBTOTALES $ :</strong>',array('colspan'=>2,'style'=>'text-align:left;'));
                    echo tagcontent('td','<strong>'.number_format($sum_ext_anterior, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_comp_sin_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_comp_con_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_ent_sin_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_ent_con_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_comp_ext, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_sal_sin_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_sal_con_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_vent_sin_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_vent_con_iva, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_vent_utilidad, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                    echo tagcontent('td','<strong>'.number_format($sum_ext_actual, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                echo Close('tr');

                echo Open('tr');
    //                echo tagcontent('td','<strong>ENTREGADO A DEPENDENCIAS $ :</strong>',array('colspan'=>6,'style'=>'text-align:left;'));
    //                echo tagcontent('td','<strong>'.$sum_dep_sin_iva.'</strong>',array('style'=>'text-align:right;'));
    //                echo tagcontent('td','<strong>'.$sum_dep_con_iva.'</strong>',array('style'=>'text-align:right;'));
    //                echo tagcontent('td','<strong>'.$sum_dep_utilidad.'</strong>',array('style'=>'text-align:right;'));
    //                echo tagcontent('td','');

                    echo tagcontent('td','<strong>VALOR FIJO ENTREGADO A DEPENDENCIAS $ :</strong>',array('colspan'=>13,'style'=>'text-align:left;'));
                    echo tagcontent('td','<strong>'.get_settings('VALOR_FIJO_DEPENDENCIAS').'</strong>',array('style'=>'text-align:right;'));
//                    echo tagcontent('td','<strong>'.get_settings('VALOR_FIJO_DEPENDENCIAS').'</strong>',array('style'=>'text-align:right;'));
//                    echo tagcontent('td','<strong>'.get_settings('VALOR_FIJO_DEPENDENCIAS').'</strong>',array('style'=>'text-align:right;'));
                echo Close('tr');
                $totales = $sum_ext_actual+get_settings('VALOR_FIJO_DEPENDENCIAS'); //Para presentar los totales de las facturas
                echo Open('tr');
                     echo tagcontent('td','<strong>TOTAL:</strong>',array('colspan'=>13,'style'=>'text-align:left;'));
                    echo tagcontent('td','<strong>'.number_format($totales, 4, '.', '').'</strong>',array('style'=>'text-align:right;'));
                echo Close('tr');
            echo Close('table');
            }
        echo LineBreak(4, array('class'=>'clr'));
        $this->load->view('footer_liq_farmacia');
echo Close('div'); 
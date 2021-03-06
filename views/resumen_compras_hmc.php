<?php

echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));
echo Open('div',array('class'=>'col-md-12','id'=>'consulta','style'=>  'font-size:'.get_settings('FONT_SIZE_FACT')));
    echo Open('div', array('class'=>'col-md-12'));
        $this->load->view('common/hmc_head/encabezado_cuenca');
    echo Close('div'); 
    echo '<CENTER><strong>LISTADO GENERAL COMPRAS DE '.$nombre_bodega.'</strong></CENTER>';
    echo '<CENTER><B>PERIODO:</B> '.$desde .' - '.$hasta.'</CENTER><br>';
   
        echo Open('table', array('class' => 'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
            $thead = array('Nro.', 'Nombre del Proveedor', 'Sub. IVA '.  get_settings('IVA').'%','Sub. IVA 0%','IVA '.  get_settings('IVA').'%', 'TOTAL INCL. IVA','Nro. RETENC');
            echo tablethead($thead);
            $sum_ivadoce=0; $sum_ivacero=0; $sum_total = 0 ;$sum_iva=0;
            foreach ($fact_data as $data){
                echo Open('tr',array('style'=>'background-color:ad adff'));
                    echo tagcontent('td',$data->pa_sriautorizaciondocs_establecimiento.'-'.$data->pa_sriautorizaciondocs_puntoemision.'-'.$data->noFacturaCompra);
                    echo tagcontent('td',$data->nombres.' '.$data->apellidos);
                    echo tagcontent('td',$data->tarifadoceneto,array('style'=>'text-align:right;'));
                    echo tagcontent('td',$data->tarifaceroneto,array('style'=>'text-align:right;'));
                    echo tagcontent('td',$data->iva,array('style'=>'text-align:right;'));
                    echo tagcontent('td',$data->totalCompra,array('style'=>'text-align:right;'));
                    echo tagcontent('td',$data->retencion_id,array('style'=>'text-align:right;'));
                echo Close('tr');
                $sum_ivadoce+=$data->tarifadoceneto; $sum_ivacero+=$data->tarifaceroneto; 
                $sum_iva+=$data->iva; $sum_total += $data->totalCompra;
            }
            echo Open('tr',array('style'=>'background-color:adadff'));

                echo tagcontent('td','<strong>Totales $ :</strong>',array('colspan'=>2,'style'=>'text-align:center;'));
                
                echo tagcontent('td','<strong>'.$sum_ivadoce.'</strong>',array('style'=>'text-align:right;'));
                echo tagcontent('td','<strong>'.$sum_ivacero.'</strong>',array('style'=>'text-align:right;'));
                echo tagcontent('td','<strong>'.$sum_iva.'</strong>',array('style'=>'text-align:right;'));
                echo tagcontent('td','<strong>'.$sum_total.'</strong>',array('style'=>'text-align:right;'));
                echo tagcontent('td','',array('style'=>'text-align:right;'));
            echo Close('tr');
        echo Close('table');
   
        echo LineBreak(4, array('class'=>'clr'));
        $this->load->view('footer_liq_farmacia');
echo Close('div'); 
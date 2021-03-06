<?php

echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>PLANILLA CIVILES SERVICIO '.$hospit_efectivo['nombre_servicio'].'</b>', array('style'=>'text-align:center', 'colspan'=>'5'));
    echo Close('tr');
    echo Open('tr');
        echo tagcontent('th', 'NRO. FACT.', array('width'=>'10%'));
        echo tagcontent('th', 'NOMBRES PACIENTE', array('width'=>'40%'));
        echo tagcontent('th', 'CONTADO', array('width'=>'10%'));
        echo tagcontent('th', 'CREDITO', array('width'=>'10%'));
        echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
    echo Close('tr');
    
    if($hospit_efectivo['clientes']){
     
        foreach ($hospit_efectivo['clientes'] as $cliente) { 
            echo Open('tr');
                echo tagcontent('td', $cliente->codigofactventa);
                echo tagcontent('td', $cliente->nombres);
                echo tagcontent('td', number_format($cliente->totalCompra, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                echo tagcontent('td', '', array('align'=>'right'));
                echo tagcontent('td', number_format($cliente->totalCompra, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
            echo Close('tr');
        }
    }
    
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$hospit_efectivo['nombre_servicio'].':</b>', array('style'=>'text-align:right', 'colspan'=>'2'));
        echo tagcontent('td', number_format($hospit_efectivo['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', '', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($hospit_efectivo['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');  

//Hospitalizados con aseguradora

echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>PLANILLAS DE HOSPITALIZACION PARA PACIENTES CON ASEGURADORA </b>', array('style'=>'text-align:center', 'colspan'=>'5'));
    echo Close('tr');
       
    if($hospit_credito['list_aseg']){
        foreach ($hospit_credito['list_aseg'] as $aseg) {
            echo Open('tr');
                echo tagcontent('td', '<b> ASEGURADORA: '.$aseg->aseg->ase_nombre.'</b>', array('style'=>'text-align:center', 'colspan'=>'5'));
            echo Close('tr');
            echo Open('tr');
                echo tagcontent('th', 'NRO. PLANILLA', array('width'=>'10%'));
                echo tagcontent('th', 'NOMBRES PACIENTE', array('width'=>'40%'));
                echo tagcontent('th', 'CONTADO', array('width'=>'10%'));
                echo tagcontent('th', 'CREDITO', array('width'=>'10%'));
                echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
            echo Close('tr');
            
            foreach ($aseg->clientes as $cliente) { 
                echo Open('tr');
                    echo tagcontent('td', $cliente->id);
                    echo tagcontent('td', $cliente->nombres);
                    echo tagcontent('td', number_format($cliente->pla_valor_paciente, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', number_format($cliente->pla_valor_aseguradora, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', number_format($cliente->pla_valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                echo Close('tr');
            }
            
            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL '.$aseg->aseg->ase_nombre.':</b>', array('style'=>'text-align:right', 'colspan'=>'2'));
                echo tagcontent('td', number_format($aseg->val_aseg_efect, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->val_aseg_cred, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->val_tot_aseg, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
            echo Close('tr');
        }
    }
    
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$hospit_credito['nombre_servicio'].':</b>', array('style'=>'text-align:right', 'colspan'=>'2'));
        echo tagcontent('td', number_format($hospit_credito['tot_serv_efect'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($hospit_credito['tot_serv_cred'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($hospit_credito['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');


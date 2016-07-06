<?php
//Para pacientes con servicio de emergencia que pagaron en efectivo
echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>PLANILLA CIVILES SERVICIO '.$desg_emerg_efectivo['nombre_servicio'].'</b>', array('style'=>'text-align:center', 'colspan'=>'5'));
    echo Close('tr');
    echo Open('tr');
        echo tagcontent('th', 'SERVICIO', array('width'=>'50%'));
        echo tagcontent('th', 'SUBTOTAL 0%', array('width'=>'10%'));
        echo tagcontent('th', 'SUBTOTAL '.  get_settings('IVA').'%', array('width'=>'10%'));
        echo tagcontent('th', 'IVA '.get_settings('IVA').'%', array('width'=>'10%'));
        echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
    echo Close('tr');
    
    if($desg_emerg_efectivo['list']){
     
        foreach ($desg_emerg_efectivo['list'] as $grupo) { 
        
            echo Open('tr');
                echo tagcontent('td', '<b>'.$grupo->grupo->nombre.'</b>', array('style'=>'text-align:left', 'colspan'=>'4'));
            echo Close('tr');

            foreach ($grupo->lista_marcas as $marca) {

                echo Open('tr');
                    echo tagcontent('td', $marca->marca->nombre);
                    echo tagcontent('td', number_format($marca->subtotal_0, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', number_format($marca->subtotal_iva, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', number_format($marca->iva, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                echo Close('tr');
            }
            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL '.$grupo->grupo->nombre.':</b>', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->val_iva_0, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->val_otro_iva, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->val_iva, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
            echo Close('tr');

        }

    }
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$desg_emerg_efectivo['nombre_servicio'].':</b>', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_efectivo['tot_serv_iva_0'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_efectivo['tot_serv_otro_iva'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_efectivo['tot_serv_iva'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_efectivo['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');   


//Emergencia con aseguradora

echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>PLANILLAS DE EMERGENCIA PARA PACIENTES CON ASEGURADORA</b>', array('style'=>'text-align:center', 'colspan'=>'5'));
    echo Close('tr');   
    if($desg_emerg_credito['list_aseg']){
        foreach ($desg_emerg_credito['list_aseg'] as $aseg) {
            echo Open('tr');
                echo tagcontent('td', '<b> ASEGURADORA: '.$aseg->aseg->ase_nombre.'</b>', array('style'=>'text-align:center', 'colspan'=>'5'));
            echo Close('tr');
            echo Open('tr');
                echo tagcontent('th', 'SERVICIO', array('width'=>'50%'));
                echo tagcontent('th', 'SUBTOTAL 0%', array('width'=>'10%'));
                echo tagcontent('th', 'SUBTOTAL '.  get_settings('IVA').'%', array('width'=>'10%'));
                echo tagcontent('th', 'IVA '.get_settings('IVA').'%', array('width'=>'10%'));
                echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
            echo Close('tr');
            
            foreach ($aseg->lista_grupos as $grupo) { 
        
                echo Open('tr');
                    echo tagcontent('td', '<b>'.$grupo->grupo->nombre.'</b>', array('style'=>'text-align:left', 'colspan'=>'5'));
                echo Close('tr');

                foreach ($grupo->lista_marcas as $marca) {

                    echo Open('tr');
                        echo tagcontent('td', $marca->marca->nombre);
                        echo tagcontent('td', number_format($marca->subtotal_0, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                        echo tagcontent('td', number_format($marca->subtotal_iva, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                        echo tagcontent('td', number_format($marca->iva, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                        echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo Close('tr');
                }
                echo Open('tr');
                    echo tagcontent('td', '<b>TOTAL '.$grupo->grupo->nombre.':</b>', array('style'=>'text-align:right'));
                    echo tagcontent('td', number_format($grupo->val_iva_0, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                    echo tagcontent('td', number_format($grupo->val_otro_iva, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                    echo tagcontent('td', number_format($grupo->val_iva, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                    echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo Close('tr');

            }
            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL '.$aseg->aseg->ase_nombre.':</b>', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->val_gp_iva_0, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->val_gp_otro_iva, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->val_gp_iva, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->valor_aseg, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
            echo Close('tr');
        }
     
        

    }
    
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$desg_emerg_credito['nombre_servicio'].':</b>', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_credito['total_serv_iva_0'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_credito['total_serv_otro_iva'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_credito['total_serv_iva'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($desg_emerg_credito['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');

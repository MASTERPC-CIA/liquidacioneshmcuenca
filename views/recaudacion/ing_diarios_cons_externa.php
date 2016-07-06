<?php
//Para clientes externos que pagaron en efectivo

echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>SERVICIO '.$efectivo_ext['nombre_servicio'].', PAGO EFECTIVO</b>', array('style'=>'text-align:center', 'colspan'=>'4'));
    echo Close('tr');
    echo Open('tr');
        echo tagcontent('th', 'SERVICIO', array('width'=>'50%'));
        echo tagcontent('th', 'CONTADO', array('width'=>'10%'));
        echo tagcontent('th', 'CREDITO', array('width'=>'10%'));
        echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
    echo Close('tr');
    
    if($efectivo_ext['list']){
     
        foreach ($efectivo_ext['list'] as $grupo) { 
        
            echo Open('tr');
                echo tagcontent('td', '<b>'.$grupo->grupo->nombre.'</b>', array('style'=>'text-align:left', 'colspan'=>'4'));
            echo Close('tr');

            foreach ($grupo->lista_marcas as $marca) {

                echo Open('tr');
                    echo tagcontent('td', $marca->marca->nombre);
                    echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'right'));
                    echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                echo Close('tr');
            }
            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL '.$grupo->grupo->nombre.':</b>', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', '', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
            echo Close('tr');

        }

    }
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$efectivo_ext['nombre_servicio'].':</b>', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($efectivo_ext['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', '', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($efectivo_ext['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');

echo '<CENTER><b>SERVICIO CONSULTA EXTERNA</b></CENTER><BR>';

//Para pacientes con servicio de consulta externa que pagaron en efectivo
echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>SERVICIO '.$efectivo['nombre_servicio'].', PAGO EFECTIVO</b>', array('style'=>'text-align:center', 'colspan'=>'4'));
    echo Close('tr');
    echo Open('tr');
        echo tagcontent('th', 'SERVICIO', array('width'=>'50%'));
        echo tagcontent('th', 'CONTADO', array('width'=>'10%'));
        echo tagcontent('th', 'CREDITO', array('width'=>'10%'));
        echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
    echo Close('tr');
    
    if($efectivo['list']){
     
        foreach ($efectivo['list'] as $grupo) { 
        
            echo Open('tr');
                echo tagcontent('td', '<b>'.$grupo->grupo->nombre.'</b>', array('style'=>'text-align:left', 'colspan'=>'4'));
            echo Close('tr');

            foreach ($grupo->lista_marcas as $marca) {

                echo Open('tr');
                    echo tagcontent('td', $marca->marca->nombre);
                    echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo tagcontent('td', '', array('align'=>'right'));
                    echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                echo Close('tr');
            }
            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL '.$grupo->grupo->nombre.':</b>', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', '', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
            echo Close('tr');

        }

    }
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$efectivo['nombre_servicio'].':</b>', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($efectivo['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', '', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($efectivo['total_servicio'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');  

//Para pacientes con servicio de consulta externa que pagaron a crédito

echo Open('table',array('class'=>'table table-striped table-condensed','border'=>'1','style'=>'font-size:'.get_settings('FONT_SIZE_FACT'),'width'=>'100%'));
    echo Open('tr');
        echo tagcontent('td', '<b>SERVICIO '.$credito['nombre_serv_credito'].', PAGO CRÉDITO</b>', array('style'=>'text-align:center', 'colspan'=>'4'));
    echo Close('tr');
       
    if($credito['list_aseg']){
        foreach ($credito['list_aseg'] as $aseg) {
            echo Open('tr');
                echo tagcontent('td', '<b> ASEGURADORA: '.$aseg->aseg->ase_nombre.'</b>', array('style'=>'text-align:center', 'colspan'=>'4'));
            echo Close('tr');
            echo Open('tr');
                echo tagcontent('th', 'SERVICIO', array('width'=>'50%'));
                echo tagcontent('th', 'CONTADO', array('width'=>'10%'));
                echo tagcontent('th', 'CREDITO', array('width'=>'10%'));
                echo tagcontent('th', 'TOTAL', array('width'=>'10%'));
            echo Close('tr');
            
            foreach ($aseg->lista_grupos as $grupo) { 
        
                echo Open('tr');
                    echo tagcontent('td', '<b>'.$grupo->grupo->nombre.'</b>', array('style'=>'text-align:left', 'colspan'=>'4'));
                echo Close('tr');

                foreach ($grupo->lista_marcas as $marca) {

                    echo Open('tr');
                        echo tagcontent('td', $marca->marca->nombre);
                        echo tagcontent('td', '', array('align'=>'right'));
                        echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                        echo tagcontent('td', number_format($marca->valor_total, get_settings('NUM_DECIMALES'), '.', ''), array('align'=>'right'));
                    echo Close('tr');
                }
                echo Open('tr');
                    echo tagcontent('td', '<b>TOTAL '.$grupo->grupo->nombre.':</b>', array('style'=>'text-align:right'));
                    echo tagcontent('td', '', array('style'=>'text-align:right'));
                    echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                    echo tagcontent('td', number_format($grupo->valor_grupo, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo Close('tr');

            }
            echo Open('tr');
                echo tagcontent('td', '<b>TOTAL '.$aseg->aseg->ase_nombre.':</b>', array('style'=>'text-align:right'));
                echo tagcontent('td', '', array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->valor_aseg, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
                echo tagcontent('td', number_format($aseg->valor_aseg, get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
            echo Close('tr');
        }
     
        

    }
    
    echo Open('tr');
        echo tagcontent('td', '<b>TOTAL SERVICIO '.$credito['nombre_serv_credito'].':</b>', array('style'=>'text-align:right'));
        echo tagcontent('td', '', array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($credito['total_serv_credito'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
        echo tagcontent('td', number_format($credito['total_serv_credito'], get_settings('NUM_DECIMALES'), '.', ''), array('style'=>'text-align:right'));
    echo Close('tr');
    
echo Close('table');


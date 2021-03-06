<?php
echo tagcontent('div','', array('id'=>'result_save_liq'));
echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button','data-target' => 'consulta'));

echo Open('form', array('method'=>'get', 'action'=>  base_url('liquidacioneshmcuenca/recaudacion/liquid_recaudacion/save_liq_ingresos_diarios/'.$fecha_desde.'/'.$fecha_hasta)));
    echo tagcontent('button', '<span class="glyphicon glyphicon-save"></span> Guardar', array('class' => 'btn btn-success col-md-1', 'id' => 'ajaxformbtn', 'data-target' => 'result_save_liq'));
    echo Open('div',array('class'=>'col-xs-12','id'=>'consulta','style'=>'font-size:16px'));
            echo '<CENTER><b>'.get_settings('RAZON_SOCIAL').'</b></CENTER><BR>';
            echo '<CENTER><b>INGRESOS DIARIOS</b></CENTER><BR>';
            echo '<b>Período: Desde: </b>'.$fecha_desde.' - <b> Hasta: </b>'.$fecha_hasta;
            echo LineBreak(2);
            
            //Carga la vista de los servicios de consulta externa y otros facturados a crédito y al contado
            $this->load->view('recaudacion/ing_diarios_cons_externa'); 

            echo '<CENTER><b>PLANILLAS SERVICIO EMERGENCIA</b></CENTER><BR>';
            //Carga la vista de los servicio se emergencia facturados al contado
            $this->load->view('recaudacion/ing_diarios_emergencia');
            echo '<CENTER><b>PLANILLAS SERVICIO HOSPITALIZACION</b></CENTER><BR>';
            //Carga la vista de los servicios de hospitalización facturados al contado
            $this->load->view('recaudacion/ing_diarios_hospitalizacion');
            echo LineBreak(4);
            $this->load->view('recaudacion/footer_recaudacion');
            echo LineBreak(2);
            echo '<CENTER><b>DESGLOSE DE INGRESOS DIARIOS PLANILLAS DE EMERGENCIA</b></CENTER><BR>';
            //Carga la vista del desglose de ingresos diarios por emergencia
            $this->load->view('recaudacion/desg_ing_diarios_emerg');
            echo '<CENTER><b>DESGLOSE DE INGRESOS DIARIOS PLANILLAS DE HOSPITALIZACION</b></CENTER><BR>';
            //Carga la vista del desglose de ingresos diarios por hospitalización
            $this->load->view('recaudacion/desg_ing_diarios_hospit');
            echo LineBreak(4);
            $this->load->view('recaudacion/footer_recaudacion');
    echo Close('div');
echo Close('form');
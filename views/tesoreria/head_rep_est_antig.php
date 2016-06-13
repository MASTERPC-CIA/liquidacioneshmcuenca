<?php

echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button', 'data-target' => 'consulta'));
echo tagcontent('a', '<span class="glyphicon glyphicon-export"></span> Exportar a Excel', array('href' => base_url('liquidacioneshmcuenca/tesoreria/reporte_estudio_antig/export_to_excel/' . $fecha_desde . '/' . $fecha_hasta . '/' . $id_aseg . '/' . $tipo), 'target' => '_blank', 'class' => 'btn btn-success btn-xm'));

echo Open('div', array('class' => 'col-md-12', 'id' => 'consulta', 'style' => 'font-size:16px'));
    if ($tipo == 'Cliente') {
        $this->load->view('tesoreria/result_est_antig_por_cliente');
    } elseif ($tipo == 'Servicio') {
        $this->load->view('tesoreria/result_est_antig_por_servicio');
    }
echo Close('div');


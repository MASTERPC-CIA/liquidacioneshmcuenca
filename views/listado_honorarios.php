<?php

/* Boton Imprimir */
//echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('id' => 'printbtn', 'data-target' => 'div_informes_list', 'class' => 'btn btn-default pull-left'));
/* METODO PARA EXPORTA A EXCEL */
if (!empty($data1) OR !empty($data2) OR !empty($data3) OR !empty($data4)):
echo tagcontent('a', '<span class="glyphicon glyphicon-export"></span> Exportar a Excel', array('href' => base_url('liquidacioneshmcuenca/index/export_honorarios_to_excel/' . $fecha_emision_desde . '/' . $fecha_emision_hasta),
    'method' => 'post', 'target' => '_blank', 'class' => 'btn btn-success btn-sm'));
endif;

echo lineBreak();
if (!empty($data1)):
    echo '<span class="pull-left"><strong>CONSULTA EXTERNA - TURNOS</strong></span>';
    echo Open('div', array('id' => 'div_informes_list', 'class' => 'col-md-12'));
    echo Open('table', array('id' => 'table_informe', 'class' => "table table-fixed-header"));
    echo '<thead>';
    echo '<th>Cedula</th>';
    echo '<th>Nombre</th>';
    echo '<th>Especialidad</th>';
    echo '<th>Medico</th>';
    echo '<th>Hospital</th>';
    echo '<th>Total</th>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($data1 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);

        echo tagcontent('td', "$ " . number_format($val->total * 0.7, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($val->total * 0.3, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($val->total, get_settings('NUM_DECIMALES')));
        echo Close('tr');
    }
    echo '</tbody>';
    echo '</table>';
    echo Close('div');
    echo LineBreak();
endif;

if (!empty($data2) OR ! empty($data4)):
    echo '<span class="pull-left"><strong>PROCEDIMIENTOS DE DIAGNOSTICO</strong></span>';
    echo Open('div', array('id' => 'div_informes_list', 'class' => 'col-md-12'));
    echo Open('table', array('id' => 'table_informe', 'class' => "table table-fixed-header"));
    echo '<thead>';
    echo '<th>Cedula</th>';
    echo '<th>Nombre</th>';
    echo '<th>Especialidad</th>';
    echo '<th>Medico</th>';
    echo '<th>Hospital</th>';
    echo '<th>Total</th>';
    echo '</thead>';
    echo '<tbody>';
//DATA 2 CARDIOLOGO - NEUROLOGO - DERMATOLOGO
//59	Neurología
//80	Cardiología 
//82	Dermatología
    foreach ($data2 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);
        if ($val->cargosempleado_id == 59 OR $val->cargosempleado_id == 80) {
            $tot = $val->total * 0.25;
        } else {
            $tot = $val->total;
        }

        echo tagcontent('td', "$ " . number_format($tot * 0.7, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($tot * 0.3, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($tot, get_settings('NUM_DECIMALES')));

        echo Close('tr');
    }
    foreach ($data4 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);
        if ($val->cargosempleado_id == 59 OR $val->cargosempleado_id == 80) {
            $tot = $val->total * 0.25;
        } else {
            $tot = $val->total;
        }

        echo tagcontent('td', "$ " . number_format($tot * 0.7, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($tot * 0.3, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($tot, get_settings('NUM_DECIMALES')));
        echo Close('tr');
    }
    echo '</tbody>';
    echo '</table>';
    echo Close('div');
    echo LineBreak();
endif;

if (!empty($data3)):
    echo '<span class="pull-left"><strong>PARTE OPERATORIO</strong></span>';
    echo Open('div', array('id' => 'div_informes_list', 'class' => 'col-md-12'));
    echo Open('table', array('id' => 'table_informe', 'class' => "table table-fixed-header"));
    echo '<thead>';
    echo '<th>Cedula</th>';
    echo '<th>Nombre</th>';
    echo '<th>Especialidad</th>';
    echo '<th>Medico</th>';
    echo '<th>Hospital</th>';
    echo '<th>Total</th>';
    echo '</thead>';

    echo '<tbody>';

    foreach ($data3 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);
        echo tagcontent('td', "$ " . number_format($val->total * 0.7, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($val->total * 0.3, get_settings('NUM_DECIMALES')));
        echo tagcontent('td', "$ " . number_format($val->total, get_settings('NUM_DECIMALES')));
        echo Close('tr');
    }

    echo '</tbody>';
    echo '</table>';
    echo Close('div');
endif;
?>



<?php

/* Boton Imprimir */
//echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('id' => 'printbtn', 'data-target' => 'div_informes_list', 'class' => 'btn btn-default pull-left'));
/* METODO PARA EXPORTA A EXCEL */
/* echo tagcontent('a', '<span class="glyphicon glyphicon-export"></span> Exportar a Excel', array('href' => base_url('laboratorio/index/export_informes_to_excel/' . $fecha_emision_desde . '/' . $fecha_emision_hasta),
  'method' => 'post', 'target' => '_blank', 'class' => 'btn btn-success btn-sm'));
 */
//echo lineBreak();
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
if (!empty($data1)):
    foreach ($data1 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);
        echo tagcontent('td', "$ " . $val->total * 0.7);
        echo tagcontent('td', "$ " . $val->total * 0.3);
        echo tagcontent('td', "$ " . $val->total);
        echo Close('tr');
    }
endif;
echo '</tbody>';
echo '</table>';
echo Close('div');

echo LineBreak();

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
//data 4 - dermatologia
if (!empty($data4)):
    foreach ($data4 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);
        echo tagcontent('td', "$ " . $val->total * 0.7);
        echo tagcontent('td', "$ " . $val->total * 0.3);
        echo tagcontent('td', "$ " . $val->total);
        echo Close('tr');
    }
endif;
echo '</tbody>';
echo '</table>';
echo Close('div');

echo LineBreak();

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
if (!empty($data3)):
    foreach ($data3 as $val) {
        echo Open('tr');
        echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
        echo tagcontent('td', $val->nom . ' ' . $val->ape);
        echo tagcontent('td', $val->especialidad);
        echo tagcontent('td', "$ " . $val->total * 0.7);
        echo tagcontent('td', "$ " . $val->total * 0.3);
        echo tagcontent('td', "$ " . $val->total);
        echo Close('tr');
    }
endif;
echo '</tbody>';
echo '</table>';
echo Close('div');
?>



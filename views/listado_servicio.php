<?php

/* Boton Imprimir */
//echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('id' => 'printbtn', 'data-target' => 'div_informes_list', 'class' => 'btn btn-default pull-left'));
/* METODO PARA EXPORTA A EXCEL */
/* if (!empty($data1)):
  echo tagcontent('a', '<span class="glyphicon glyphicon-export"></span> Exportar a Excel', array('href' => base_url('liquidacioneshmcuenca/index/export_honorarios_to_excel/' . $fecha_emision_desde . '/' . $fecha_emision_hasta),
  'method' => 'post', 'target' => '_blank', 'class' => 'btn btn-success btn-sm'));
  endif; */

echo lineBreak();

echo '<span class="pull-left"><strong>SERVICIOS</strong></span>';
echo Open('div', array('id' => 'div_informes_list', 'class' => 'col-md-12'));
echo Open('table', array('id' => 'table_informe', 'class' => "table table-fixed-header"));
echo '<thead>';
echo '<th width="30%">Paciente</th>';
echo '<th width="10%">Cedula</th>';
echo '<th width="20%">Nombre</th>';
echo '<th width="10%">Especialidad</th>';
echo '<th width="10%">Medico</th>';
echo '<th width="10%">Hospital</th>';
echo '<th width="10%">Total</th>';
echo '</thead>';
echo '<tbody>';
foreach ($data1 as $val) {
    echo Open('tr');
    echo tagcontent('td', $val->cedula_cli . ' / ' . $val->nom_cli . ' ' . $val->ape_cli);
    echo tagcontent('td', $val->PersonaComercio_cedulaRuc);
    echo tagcontent('td', $val->nom . ' ' . $val->ape);
    echo tagcontent('td', $val->especialidad);
    echo tagcontent('td', "$ " . number_format($val->total * 0.6, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', "$ " . number_format($val->total * 0.4, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', "$ " . number_format($val->total, get_settings('NUM_DECIMALES')));

    echo Close('tr');
}
echo '</tbody>';
echo '</table>';
echo Close('div');
echo LineBreak();
?>



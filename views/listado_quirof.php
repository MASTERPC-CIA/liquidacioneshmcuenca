<?php

/* Boton Imprimir */
//echo tagcontent('button', '<span class="glyphicon glyphicon-print"></span> Imprimir', array('id' => 'printbtn', 'data-target' => 'div_informes_list', 'class' => 'btn btn-default pull-left'));
/* METODO PARA EXPORTA A EXCEL */
/* if (!empty($data1)):
  echo tagcontent('a', '<span class="glyphicon glyphicon-export"></span> Exportar a Excel', array('href' => base_url('liquidacioneshmcuenca/index/export_honorarios_to_excel/' . $fecha_emision_desde . '/' . $fecha_emision_hasta),
  'method' => 'post', 'target' => '_blank', 'class' => 'btn btn-success btn-sm'));
  endif; */

echo lineBreak();

echo '<span class="pull-left"><strong>QUIROFANO</strong></span>';
echo Open('div', array('id' => 'div_informes_list', 'class' => 'col-md-12'));
echo Open('table', array('id' => 'table_informe', 'class' => "table table-fixed-header"));
echo '<thead>';
echo '<th width="15%">Paciente</th>';
echo '<th width="15%">Cirujano</th>';
echo '<th width="5%">Medico</th>';
echo '<th width="5%">Hospital</th>';
echo '<th width="10%">Anestesiologo</th>';
echo '<th width="5%">Medico</th>';
echo '<th width="10%">Ayudante 1</th>';
echo '<th width="5%">Medico</th>';
echo '<th width="5%">Hospital</th>';
echo '<th width="10%">Ayudante 2</th>';
echo '<th width="5%">Medico</th>';
echo '<th width="5%">Hospital</th>';
echo '<th width="5%">Total</th>';
echo '</thead>';
echo '<tbody>';
foreach ($data1 as $val) {
    if (!empty($val->ced2) && !empty($val->ced3)) {
        $tot_hos = $val->total * 0.3;
        $tot_cir = $val->total * 0.4;
        $tot_ayu1 = $tot_cir * 0.2;
        $tot_ayu2 = $tot_cir * 0.1;
    } else {
        if (!empty($val->ced2)) {
            $tot_hos = $val->total * 0.3;
            $tot_cir = $val->total * 0.4;
            $tot_ayu1 = $tot_cir * 0.3;
            $tot_ayu2 = 0;
        } else {
            $tot_hos = $val->total * 0.3;
            $tot_cir = $val->total * 0.7;
            $tot_ayu1 = 0;
            $tot_ayu2 = 0;
        }
    }

    echo Open('tr');
    echo tagcontent('td', $val->cedula_cli . ' / ' . $val->nom_cli . ' ' . $val->ape_cli);
    echo tagcontent('td', $val->PersonaComercio_cedulaRuc . ' / ' . $val->nom . ' ' . $val->ape);
    echo tagcontent('td', "$ " . number_format($tot_cir, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', "$ " . number_format($tot_hos, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', $val->ced1 . ' / ' . $val->nom1 . ' ' . $val->ape1);
    echo tagcontent('td', '');
    echo tagcontent('td', $val->ced2 . ' / ' . $val->nom2 . ' ' . $val->ape2);
    echo tagcontent('td', "$ " . number_format($tot_ayu1, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', "$ " . number_format($tot_hos, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', $val->ced3 . ' / ' . $val->nom3 . ' ' . $val->ape3);
    echo tagcontent('td', "$ " . number_format($tot_ayu2, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', "$ " . number_format($tot_hos, get_settings('NUM_DECIMALES')));
    echo tagcontent('td', "$ " . number_format($val->total, get_settings('NUM_DECIMALES')));

    echo Close('tr');
}
echo '</tbody>';
echo '</table>';
echo Close('div');
echo LineBreak();
?>



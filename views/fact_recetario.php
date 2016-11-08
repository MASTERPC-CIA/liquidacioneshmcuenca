<?php

//print_r($factura);

echo tagcontent('button', 'IMPRIMIR', array('name' => 'btnPrint', 'class' => 'btn btn-warning col-md-1', 'id' => 'printbtn', 'type' => 'button', 'data-target' => 'consulta'));

echo Open('div', array('id' => 'consulta', 'class' => 'col-xs-12'));

echo Open('div', array('id' => 'fact_docs_print_hmc', 'class' => 'col-md-12', 'style' => 'font-family:monospaced; font-size:' . get_settings('FONT_SIZE_FACT')));
/* este caso sin encabezado,  se da solo cuando no es electrónica, ya que se necesita imprimir el encabezado impreso */

$this->load->view('common/hmc_head/encabezado_cuenca');
echo tagcontent('div', '<strong> Consolidado de recetario integrado no facturado </strong>', array('style' => 'font-size:' . get_settings('FONT_SIZE_FACT') . ';text-align:center'));

echo LineBreak(1, array('class' => 'clr'));

echo Open('table', array('class' => 'table table-striped table-condensed', 'style' => 'font-family:monospaced;font-size:' . get_settings('FONT_SIZE_FACT')));
$thead = array(
    'Fecha',
    'Prescripciones (Usar Genericos)',
    'Cantidad',
    'P. Unitario',
    'P. Total'
);
echo tablethead($thead);
echo open("tr");
echo tagcontent("td", "");
echo close("tr");
foreach ($venta_det as $d) {
    echo Open('tr');
    echo tagcontent("td", $d->fechaCreacion);
    $name_producto = $this->generic_model->get_val_where('billing_producto', array('codigo' => $d->product_cod), 'nombreUnico', null, -1);
    $impuesto = $this->generic_model->get_val_where('bill_productoimpuestotarifa', array('producto_id' => $d->product_cod), 'impuestotarifa_id', null, -1);
    if (!empty($d->detalle)) {
        $name_producto = $name_producto . "\n" . $d->detalle;
    }
    if ($impuesto == 2) {
        $asterisco = '*';
    } else {
        $asterisco = '';
    }
    echo tagcontent('td', $d->product_cod . " " . $asterisco . " " . $name_producto);

    echo tagcontent('td', $d->itemcantidad);
    echo tagcontent('td', number_format($d->itemprecioneto, 3, '.', ''), array('align' => 'right'));
    echo tagcontent('td', number_format($d->itemprecioxcantidadneto, 3, '.', ''), array('align' => 'right'));
    echo Close('tr');
}

echo Close('table');
echo Close('div');

echo Open('div', array('id' => 'consulta', 'class' => 'col-xs-12'));
echo Open('div', array('class' => 'col-md-3 pull-right font16'));
echo input(array('type' => 'hidden', 'value' => number_format($factura[0]->subtotalBruto/* $totcart */, get_settings('NUM_DECIMALES')), 'id' => 'subtotal_bruto'));
echo Open('table', array('class' => 'table table-condensed'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Tarifa cero:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->tarifacerobruto, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', (get_settings("IVA") == 14 ) ? '<span class="pull-right">Tarifa catorce:</span>' : '<span class="pull-right">Tarifa doce:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->tarifadocebruto, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Subtotal:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->subtotalBruto, get_settings('NUM_DECIMALES')) . '</span>')); /* Subotal bruto - antes del descuento */
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Recargo:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->recargovalor, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Descuento:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->descuentovalor, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Subtotal neto:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->subtotalNeto, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">ICE:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->iceval, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">IVA:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->ivaval, get_settings('NUM_DECIMALES')) . '</span>'));
echo tagcontent('tr', tagcontent('td', '<span class="pull-right">Total:</span>')
        . tagcontent('td', '<span class="pull-right">' . number_format($factura[0]->totalCompra, 2) . '</span>'));
echo Close('table');
echo Close('div');

echo Close('div');

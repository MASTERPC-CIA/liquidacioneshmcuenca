<?php

echo Linebreak(2);
echo Open('div', array('class' => 'col-md-12', 'style' => 'text-align:center'));
    echo tagcontent('span', '<strong>' . $auxiliar_cont[0]->empleado . '</strong>');
    echo LineBreak(1, array('class' => 'clr'));
    echo tagcontent('span', '<strong>AUXILIAR DE CONTABILIDAD</strong>');
echo Close('div');

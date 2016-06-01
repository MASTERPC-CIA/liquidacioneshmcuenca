<?php

echo Open('table',array('style'=>'font-family:monospace;background-color:white','width'=>'100%'));
        echo Open('tr', array('style'=>'text-align:center'));
            echo tagcontent('td', '..........................');
            echo tagcontent('td', '..........................');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center;font-weight:600'));
            echo tagcontent('td', 'RECAUDADOR(A)');
            echo tagcontent('td', 'TESORERO(A)');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center', 'height'=>'100px'));

        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center'));
            echo tagcontent('td', '..........................');
            echo tagcontent('td', '..........................');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center'));
            echo tagcontent('td', '');
            echo tagcontent('td', '');
        echo Close('tr');
        echo Open('tr', array('style'=>'text-align:center;font-weight:600'));
            echo tagcontent('td', 'CONTADOR(A)');
            echo tagcontent('td', 'JEFE FINANCIERO');
        echo Close('tr');
    echo Close('table');

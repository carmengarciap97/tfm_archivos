<?php

function calcEntropy($px_dimensiones) {
    $entropy_array = array();

    foreach ($px_dimensiones as $px) {
        if ($px > 0) {
            $entropy = -$px * log($px, 2);
            $entropy_array[] = $entropy;
        }
    }

    return $entropy_array;
}

?> 

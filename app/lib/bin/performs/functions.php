<?php
function ordinal($cdnl){ 
    $test_c = abs($cdnl) % 10; 
    $ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th' 
            : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1) 
            ? 'th' : 'st' : 'nd' : 'rd' : 'th')); 
    return $cdnl.$ext; 
}

function getSourceCode($url)
{
    $lines = file($url);
    $output = "";
    foreach ($lines as $line_num => $line) {
        // loop thru each line and prepend line numbers
        $output.= "Line #&lt;b&gt;{$line_num}&lt;/b&gt; : " . htmlspecialchars($line) . "&lt;br&gt;\n";
    }
}

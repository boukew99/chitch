<?php
// 61 = 32 + 16 + 8 + 4 + 1 =  Variables + Environment + Modules + Configuration + General
// Define each flag with meaningful names
$INFO_FLAGS = [
    'GENERAL'       => 1,
    'CONFIGURATION' => 4,
    'MODULES'       => 8,
    'ENVIRONMENT'   => 16,
    'VARIABLES'     => 32
];

// Sum all flags using array_sum for clarity
phpinfo(array_sum($INFO_FLAGS));
?>

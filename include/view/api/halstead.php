<?php

# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once('../../chitch.php');

header('Content-Type: text/plain; charset=utf-8');

function halstead_metrics($code): array
{
    $tokens = token_get_all($code);

    $kw_ops = [
        T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_DO,
        T_SWITCH, T_CASE, T_DEFAULT, T_RETURN, T_ECHO, T_PRINT,
        T_FUNCTION, T_NEW, T_CLONE, T_THROW, T_TRY, T_CATCH, T_UNSET,
        T_LOGICAL_AND, T_LOGICAL_OR, T_BOOLEAN_AND, T_BOOLEAN_OR,
        T_IS_EQUAL, T_IS_NOT_EQUAL, T_IS_IDENTICAL, T_IS_NOT_IDENTICAL,
        T_INC, T_DEC, T_AND_EQUAL, T_OR_EQUAL, T_PLUS_EQUAL,
        T_MINUS_EQUAL, T_MUL_EQUAL, T_DIV_EQUAL, T_INSTANCEOF,
    ];
    $sym_ops = ['=', '+', '-', '*', '/', '.', '!', '?', ':',
                '<', '>', '==', '!=', '&&', '||', '=>', '->', '::'];

    $isOp = fn($t) => is_array($t) ? in_array($t[0], $kw_ops, true)
                                   : in_array($t,   $sym_ops, true);
    $isAnd = fn($t) => is_array($t) && in_array($t[0],
                 [T_VARIABLE, T_LNUMBER, T_DNUMBER,
                  T_CONSTANT_ENCAPSED_STRING, T_STRING], true);

    $val   = fn($t) => is_array($t) ? $t[1] : $t;

    $ops   = array_map($val, array_filter($tokens, $isOp));
    $ands  = array_map($val, array_filter($tokens, $isAnd));

    $n1 = count(array_unique($ops));      $N1 = count($ops);
    $n2 = count(array_unique($ands));     $N2 = count($ands);

    $n  = $n1 + $n2; # Vocabulary
    $N  = $N1 + $N2; # Length
    $V  = $n ? $N * log($n, 2) : 0; # Volume
    $D  = $n2 ? ($n1 / 2) * ($N2 / $n2) : 0; # Difficulty
    $E  = $V * $D; # Effort
    $T  = $E / 18;                        // seconds # Time to implement
    $B  = ($E**(2/3) / 3000); # / ($V / 3000); # Bugs

    return compact('n1','n2','N1','N2','n','N','V','D','E','T','B');
}
# Halstead Complexity = f (η1, η2, N1, N2)
# η1 = number of unique operators
# η2 = number of unique operands
# N1 = total number of operators
# N2 = total number of operands

function halstead_from_files(array $paths): array {
    $code = implode("\n", array_map('file_get_contents', $paths));
    return halstead_metrics($code);
}
function halsteadProject(array $paths): array {
    $results = array_map(
        fn($p) => halstead_metrics(file_get_contents($p)),
        $paths
    );

    $sum = fn($key) => array_sum(array_column($results, $key));

    return [
        'files'      => $files = count($results),
        'volume'     => $volume = $sum('V'),
        'effort'     => $effort = $sum('E'),
        'difficulty' => $effort / $volume, # average weighted difficulty for a bit
        'time'       => $sum('T'),
        'bugs'       => $effort ? ($effort ** (2/3)) / 3000 : 0,
        'avg_volume' => $sum('V') / count($results),
        'max_volume' => max(array_column($results, 'V')),
    ];
}
print_r(
    halsteadProject(
        $files = Chitch\getfiles('../../', '*.php')
    )
    );
print_r($files);

# [1] Maurice H. Halstead. Elements of Software Science. Elsevier, 1977
# [2] https://en.wikipedia.org/wiki/Halstead_complexity_measures

<?php
namespace Halstead;
require_once(__DIR__ . '/chitch.php');

function halstead_metrics(string $code): array
{
    ['operators' => $operators, 'operands' => $operands] = halstead_tokens($code);

    $unique_operators = count(array_unique($operators));
    $total_operators = count($operators);
    $unique_operands = count(array_unique($operands));
    $total_operands = count($operands);

    return halstead_original(
        $unique_operators,
        $unique_operands,
        $total_operators,
        $total_operands
    );
}

/**
 * The original Halstead complexity calculation using the four base numbers.
 *
 * @param int $uniqueOperators η1
 * @param int $uniqueOperands  η2
 * @param int $totalOperators  N1
 * @param int $totalOperands   N2
 * @return array
 */
function halstead_original(
    int $uniqueOperators,
    int $uniqueOperands,
    int $totalOperators,
    int $totalOperands
): array {
    $vocabulary = $uniqueOperators + $uniqueOperands;
    $length = $totalOperators + $totalOperands;
    $volume = $vocabulary > 0 ? $length * log($vocabulary, 2) : 0;
    $difficulty = $uniqueOperands > 0 ? ($uniqueOperators / 2) * ($totalOperands / $uniqueOperands) : 0;
    $effort = $volume * $difficulty;
    $time = $effort / 18;
    $bugs = ($effort ** (2/3)) / 3000;

    return [
        'unique_operators' => $uniqueOperators,
        'unique_operands' => $uniqueOperands,
        'total_operators' => $totalOperators,
        'total_operands' => $totalOperands,
        'vocabulary' => $vocabulary,
        'length' => $length,
        'volume' => $volume,
        'difficulty' => $difficulty,
        'effort' => $effort,
        'time' => $time,
        'bugs' => $bugs,
    ];
}

/**
 * Get the actual operators and operands from PHP code.
 *
 * @param string $code
 * @return array{operators: array, operands: array}
 */
function halstead_tokens(string $code): array
{
    $tokens = token_get_all($code);

    $keywordOperators = [
        T_IF, T_ELSE, T_ELSEIF, T_FOR, T_FOREACH, T_WHILE, T_DO,
        T_SWITCH, T_CASE, T_DEFAULT, T_RETURN, T_ECHO, T_PRINT,
        T_FUNCTION, T_NEW, T_CLONE, T_THROW, T_TRY, T_CATCH, T_UNSET,
        T_LOGICAL_AND, T_LOGICAL_OR, T_BOOLEAN_AND, T_BOOLEAN_OR,
        T_IS_EQUAL, T_IS_NOT_EQUAL, T_IS_IDENTICAL, T_IS_NOT_IDENTICAL,
        T_INC, T_DEC, T_AND_EQUAL, T_OR_EQUAL, T_PLUS_EQUAL,
        T_MINUS_EQUAL, T_MUL_EQUAL, T_DIV_EQUAL, T_INSTANCEOF,
    ];
    $symbolOperators = ['=', '+', '-', '*', '/', '.', '!', '?', ':',
                        '<', '>', '==', '!=', '&&', '||', '=>', '->', '::'];

    $isOperator = fn($t) => is_array($t)
        ? in_array($t[0], $keywordOperators, true)
        : in_array($t, $symbolOperators, true);

    $isOperand = fn($t) => is_array($t) && in_array($t[0], [
        T_VARIABLE, T_LNUMBER, T_DNUMBER,
        T_CONSTANT_ENCAPSED_STRING, T_STRING
    ], true);

    $value = fn($t) => is_array($t) ? $t[1] : $t;

    $operators = array_map($value, array_filter($tokens, $isOperator));
    $operands = array_map($value, array_filter($tokens, $isOperand));

    return [
        'operators' => $operators,
        'operands' => $operands,
    ];
}

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

# [1] Maurice H. Halstead. Elements of Software Science. Elsevier, 1977
# [2] https://en.wikipedia.org/wiki/Halstead_complexity_measures

// © 2025 Chitch Contributors, Licensed under the EUPL

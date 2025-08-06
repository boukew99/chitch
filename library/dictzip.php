<?php
/**
 * A dictionary-based compression library for Chitch.
 *
 * This file contains functions to compress and decompress strings using a
 * simple dictionary-based algorithm. It's designed to be efficient for
 * text with repeating substrings.
 *
 * © 2025 Chitch Contributors, Licensed under the EUPL
 */

namespace Chitch\DictZip;

/**
 * Compresses a string using a dictionary-based approach.
 *
 * It identifies repeating substrings, replaces them with shorter tokens,
 * and creates a dictionary for decompression. This method is effective
 * for text with significant repetition.
 *
 * @param string $input The raw string to compress.
 * @return string The compressed string, including the dictionary and data parts.
 */
function compress(string $input): string
{
    $minLen = 3;
    $maxLen = 20;
    $counts = [];

    // 🧠 Count all substrings to find candidates for the dictionary
    $len = strlen($input);
    for ($i = 0; $i < $len; $i++) {
        for ($l = $minLen; $l <= $maxLen && $i + $l <= $len; $l++) {
            $sub = substr($input, $i, $l);
            // Use ??= for more concise initialization
            $counts[$sub] ??= 0;
            $counts[$sub]++;
        }
    }

    // 🧮 Calculate the compression gain for each candidate
    $candidates = [];
    foreach ($counts as $sub => $count) {
        if ($count < 2) continue;
        $token = '__' . count($candidates) . '__';
        $tokenLen = strlen($token);
        $gain = (strlen($sub) - $tokenLen) * $count;
        $cost = strlen($token . '=' . $sub . "\n"); // Cost of storing the entry in the dictionary
        if ($gain > $cost) {
            $candidates[] = ['sub' => $sub, 'token' => $token, 'gain' => $gain - $cost];
        }
    }

    // 🧠 Sort by the biggest gain first to maximize compression
    usort($candidates, fn($a, $b) => $b['gain'] <=> $a['gain']);

    $dict = [];
    $replaced = $input;

    // Replace substrings with tokens, ensuring we don't replace parts of already replaced tokens
    foreach ($candidates as $cand) {
        if (strpos($replaced, $cand['sub']) !== false) {
            $replaced = str_replace($cand['sub'], $cand['token'], $replaced);
            $dict[$cand['token']] = $cand['sub'];
        }
    }

    // 🧾 Build the final output string with dictionary and data
    $output = "[DICT]\n";
    foreach ($dict as $token => $sub) {
        $output .= "$token=$sub\n";
    }
    $output .= "[DATA]\n" . $replaced;

    return $output;
}

/**
 * Decompresses a string that was compressed with the compress() function.
 *
 * It parses the dictionary from the input string and uses it to replace
 * tokens in the data part with their original substrings.
 *
 * @param string $input The compressed string.
 * @return string The original, decompressed string. Returns an empty string if input is invalid.
 */
function decompress(string $input): string
{
    // Check for the required parts
    if (!str_contains($input, "[DICT]\n") || !str_contains($input, "[DATA]\n")) {
        return ''; // Invalid format
    }

    [$dictPart, $dataPart] = explode("[DATA]\n", $input, 2);
    $lines = explode("\n", trim($dictPart));
    array_shift($lines); // remove [DICT]

    $reverseDict = [];
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            [$token, $word] = explode('=', $line, 2);
            $reverseDict[$token] = $word;
        }
    }

    $output = $dataPart;

    // Use strtr for a potentially more efficient replacement
    $output = strtr($output, $reverseDict);

    return $output;
}

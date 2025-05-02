<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

require ("../chitch.php");
use function Chitch\{write, tree};

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Filter each variable individually with defaults

  $log = '<tr>' . tree('td',
    fn($x) => $x,
    $iso8601Date = date('Y-m-d\TH:i:s\Z'),
    $resolutionX = filter_input(INPUT_POST, 'resolutionX', FILTER_VALIDATE_INT) ?? 0,
    $resolutionY = filter_input(INPUT_POST, 'resolutionY', FILTER_VALIDATE_INT) ?? 0,
    $pixelsRatio = filter_input(INPUT_POST, 'pixelsRatio', FILTER_VALIDATE_FLOAT) ?? 1.0,
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_SPECIAL_CHARS) ?? '',
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS) ?? '',
    $ref = filter_input(INPUT_POST, 'ref', FILTER_SANITIZE_SPECIAL_CHARS) ?? '',
    $views = filter_input(INPUT_POST, 'pageview', FILTER_VALIDATE_INT) ?? 0,
    $bot = filter_input(INPUT_POST, 'bot', FILTER_VALIDATE_BOOLEAN) ?? false ? 'Yes' : 'No',
    $firstload = filter_input(INPUT_POST, 'firstload', FILTER_VALIDATE_INT) ?? 0
);

  if (write('traffic', $log)) {
    http_response_code(200);
  } else {
    http_response_code(500);
  }
}

// Add cumulative data storage?

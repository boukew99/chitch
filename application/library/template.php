<?php
namespace Chitch\Template;


function templatepass(string $template, array $variables = []): string
{
    // Extract variables to local scope
    extract($variables, EXTR_SKIP);

    ob_start(); // 📦 grab all da echo'd stuff

    include(__DIR__ . "/../template/$template.php");

    return ob_get_clean();
}

function head(bool $analytics = false): string
{
    $check = php_sapi_name() === "cli-server" ? "<script defer src='/tool/check.js'></script>" : '';
    return templatepass('head', [
        'analytics' => $analytics,
        'check' => $check,
    ]);
}
function header(string $title = 'Chitch', string $description = 'A simple, fast and secure chat application.', $toc = false): string
{
    $session = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    return templatepass('header', [
        'title' => $title,
        'description' => $description,
        'session' => $session,
        'toc' => $toc,
    ]);
}

function foot(): string
{
    return templatepass('footer');
}

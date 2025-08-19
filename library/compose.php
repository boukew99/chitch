<?php // © Chitch Contributors 2025, Licensed under the EUPL.

declare(strict_types=1);
namespace HTMLCompose;

// creates an correct attribute
function attribute(string $name, string ...$values): string
{
    return "$name=\"" . implode(" ", $values) . "\"";
}

function wrap(string $wrap, string $content, string ...$attributes) : string
{
    $attributes = implode(" ", $attributes);
    return "<$wrap $attributes>$content</$wrap>";
}

function voidelement(string $tag, string ...$attributes): string
{
  $attributes = implode(" ", $attributes);
  return "<$tag $attributes />";

  # https://developer.mozilla.org/en-US/docs/Glossary/Void_element
}

/**
 * reverse bread-depth split tag resolver
 */
function tree(string $tag, int|float|string|bool ...$content): string
{
    return "<$tag>" . implode("<$tag>", $content);
}


function textlist(string $label, string $name, string ...$items) {
  return wrap('label', $label .
  voidelement('input', attribute('list', $name), attribute('name', $name)) .
    wrap('datalist', tree('option', ...$items), attribute('id', $name))
  );
}

function textinput(string $name, string $value) {
  return wrap('label', $name .
  voidelement('input', attribute('name', $name), attribute('value', $value))
  );
}

function textarea(string $label, string $name, string $value, int $rows = 8): string
{
    return wrap('label', $label .
        wrap('textarea', htmlspecialchars($value), attribute('name', $name), attribute('rows', (string)$rows))
    );
}

function checkbox(string $label, string $name, bool $checked = false): string
{
    return wrap('label', $label .
        voidelement('input', attribute('type', 'checkbox'), attribute('name', $name), $checked ? attribute('checked', 'checked') : '')
    );
}

function submit_button(string $label): string
{
    return wrap('button', $label);
}

function section(string $id, string $header, string ...$content) :string
{
    return wrap('section', wrap('h2', $header) . implode('', $content), attribute('id', $id));
}

function fileinput(string $name, string $label = 'File'): string
{
    return wrap('label', $label .
        voidelement('input', attribute('type', 'file'), attribute('name', $name))
    );
}

// with support for file uploads
function form(bool $post = false, string ...$content): string
{
    return wrap('form', implode('', $content), $post ? 'method="post"' : '');
}

function formwithupload(string $action, string ...$content): string
{
    return wrap('form', implode('', $content),
        attribute('method', 'post'),
        attribute('action', $action),
        attribute('enctype', 'multipart/form-data')
    );

    # https://stackoverflow.com/questions/15201976/file-uploading-using-get-method
}

function stylesheet(string ...$urls): string
{
    $stylelink = fn(string $url) => voidelement('link',
        attribute('rel', 'stylesheet'),
        attribute('href', $url)
    );
    return implode('', array_map($stylelink, $urls));
}

function p(string $content, string ...$attributes): string
{
    $attributes = implode(" ", $attributes);
    return $content ? "<p $attributes>" . $content : '';
}

function table(string $caption, array $headcolumns, array...$rows)
{
  return wrap('table',
    wrap('caption', $caption) .
    wrap('thead', tree('tr', tree('th', ...$headcolumns))) .
    (!empty($rows) ? wrap('tbody', tree('tr', ...expand('td', ...$rows))) : '')
    );
}
function expand(string $tag, array ...$rows): array
{
    return array_map(
        fn($row) => tree($tag, $row),
        ...$rows
    );
}

# https://google.github.io/styleguide/htmlcssguide.html

<!doctype html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="theme-color" media="(prefers-color-scheme: light)" content="#c0ceed">
<meta name="theme-color" media="(prefers-color-scheme: dark)" content="#10424b">

<link rel="stylesheet" href="/styles/chitch.css?v=1">
<link rel="icon" href="/app/icon.svg?1">
<?php //https://caniuse.com/?search=svg%20favicon ?>

<script defer src="/script/ensue.js"></script>

<meta name="generator" content="Chitch">
<script type="speculationrules">
{ "prerender": [{
    "source": "document",
    "where": {
        "and": [ {"href_matches": "/*"} ]},
        "eagerness": "moderate"
    }]
}
</script>
<?= $analytics ? '<script defer src="/script/visit.js"></script>' : '' ?>
<?= $check ?>

<?php
// compact('analytics', 'check');

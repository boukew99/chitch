<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

require('../chitch.php');

use function Chitch\{batch, read, write};

if (read('members') !== '') {
    header('HTTP/1.1 403 Forbidden');
    exit;
} elseif (!is_dir(Chitch\log_path())) {
    mkdir(Chitch\log_path());
}

if (read('install') === '') {
    $unique = bin2hex(random_bytes(32));
    write('install', $unique);

}

$installkey = read('install');

?>
<?= Chitch\head() ?>
<title>Chitch Install</title>

<header>
    <h1>Chitch Install</h1>
    <p>Check wether your system has the right installations for Chitch and create the first administrator. Refer to the <a href="https://developer.mozilla.org/en-US/docs/Glossary">MDN Glossary</a>, to lookup web terminology.
</header>
<main>

    <section id="compatibility">
        <h2>System Compatibility Check ✔️</h2>

        <h3>Operating System</h3>
        <p>Operating System is <?= PHP_OS === 'Linux' ? '' : 'NOT' ?> supported.

        <h3>PHP</h3>
        <p>The following are check for Chitch to work reliably. It exists of two parts, PHP and binaries. PHP is used by the whole system whereas the binaries are used sparingly and for specific pages. Therefore the PHP extensions are required for the whole system to work and the binaries are required for specific pages to work.
        <p>Chitch is compatible with PHP above the version 8.4.0
            <?=
            version_compare(phpversion(), '8.4.0', '<') ? ', which it is <strong>not</strong>' : ''
            ?>.
        <p>Using config: <?= PHP_CONFIG_FILE_PATH ?> and binary <?= PHP_BINARY ?>.


        <h3>PHP Extensions</h3>
        <p>For the names used here refer to the <a href="https://www.php.net/manual/en/extensions.membership.php">PHP Extensions List</a>

        <figure>
            <figcaption>PHP version & Modules Compatibility</figcaption>
            <dl><?= batch(
                    [
                        'Filter extension' => extension_loaded('filter'),
                        'Sessions extension' => extension_loaded('session'),
                        'Tokenizer extension' => extension_loaded('tokenizer'),
                        'Multibyte String extension' => extension_loaded('mbstring'),
                    ],
                    function ($suffices, $name) {
                        return $suffices ? "<dt>$name</dt><dd>installed YES</dd>" : "<dt>$name</dt><dd>not installed ❌</dd>";
                    }
                )
                ?></dl>
        </figure>
    </section>

    <section id="first-user">
        <h2>First user</h2>
        <p>In order to create the first user securely, follow these steps.
        <ol>
            <li>Create 'logs' folder in the directory where you zip file is (probably)</li>
            <li>Reload this page
            <li>Open newly generated file'logs/chitch.key'
            <li>Copy the first line code in that file.
            <li>Fill in the form underneath and insert the code.
            <li>
        </ol>
        <form method="post">
            <label>
                Alias / Username / Author:
                <input name="username" required>
            </label>
            <label>
                Installation key:
                <input type="password" name="key" required>
            </label>
            <label>
                Email:
                <input type="email" name="email" required>
            </label>
            <button type="submit">Create</button>
        </form>
        <?php
        $post = filter_input_array(INPUT_POST, [
            'key' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'email' => FILTER_VALIDATE_EMAIL | FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            'username' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
        ]) ?: [];

        $rightsize = sizeof($post) === 3;
        $unlock = $installkey === ($post['key'] ?? '');
        $success = $rightsize && $unlock;

        $messages = [
            !$rightsize => '<p>Please fill in all fields',
            !$unlock => '<p>Wrong key',
            $success => 'User created'
        ];

        echo implode('', array_intersect_key($messages, array_filter(array_keys($messages))));

        if ($success) {
            write('members', serialize([
                $post['username'] => [
                    'email' => password_hash($post['email'], PASSWORD_DEFAULT),
                    'groups' => ['administrator'],
                ]
            ]), LOCK_EX);

            mail(
                $post['email'],
                'Chitch Install',
                'You have been added as the first user to Chitch. Your username is ' . $post['username'] . ".",
                'From: login@chitch.org',
                '-f login@chitch.org'
            );
        }

        ?>
    </section>
</main>
<footer>
    <p>Thanks for installing Chitch.
    <?= Chitch\foot() ?>
</footer>

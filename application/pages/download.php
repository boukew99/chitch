<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/library/bootstrap.php');

use function Chitch\{read, write, authorize, validate_string, p};

session_start();
?>
<?= Chitch\head(analytics: true); ?>
<title>Downloads</title>

<header>
    <h1>Downloads 📥</h1>
    <p>Download builds of Chitch here!</p>
</header>
<main>

<section>
    <h2>Builds</h2>
<form method="post">
    <fieldset>
        <legend>Operating System (automatically detected):</legend>
        <label><input type="radio" name="system" value="windows.zip" id="os-windows">Windows</label>
        <label><input type="radio" name="system" value="linux.tar.xz" id="os-linux">Linux</label>
        <label><input type="radio" name="system" value="darwin.tar.gz" id="os-macos">macOS</label>
    </fieldset>

    <fieldset>
        <legend>Architecture (automatically detected ):</legend>
        <label><input type="radio" name="architecture" value="x86_64" id="arch-x86_64">x86_64</label>
        <label><input type="radio" name="architecture" value="arm64" id="arch-arm64">arm64</label>
    </fieldset>

    <p>Select the version you want, the top one is the latest stable version.
    <fieldset>
        <legend>Version:</legend>
        <label><input type="radio" name="version" value="0.3" checked>0.3</label>
        <label><input type="radio" name="version" value="0.2">0.2</label>
    </fieldset>

    <script>
    // Simple OS and architecture detection
    window.addEventListener('DOMContentLoaded', function() {
        const platform = navigator.platform.toLowerCase();
        const userAgent = navigator.userAgent.toLowerCase();

        // OS detection
        if (platform.includes('win')) {
            document.getElementById('os-windows').checked = true;
        } else if (platform.includes('mac') || userAgent.includes('mac')) {
            document.getElementById('os-macos').checked = true;
        } else if (platform.includes('linux') || userAgent.includes('linux')) {
            document.getElementById('os-linux').checked = true;
        }

        // Architecture detection
        if (userAgent.includes('arm') || userAgent.includes('aarch64')) {
            document.getElementById('arch-arm64').checked = true;
        } else if (userAgent.includes('x86_64') || userAgent.includes('win64') || userAgent.includes('amd64')) {
            document.getElementById('arch-x86_64').checked = true;
        }
    });
    </script>

    <h3>Demographic</h3>
    <p>These helps us understand our users.
    <p><fieldset>
        <legend>Continent of Residence (to determine server locations):</legend>
        <label><input type="radio" name="continent" value="eu">Europe</label>
        <label><input type="radio" name="continent" value="na">North America</label>
        <label><input type="radio" name="continent" value="sa">South America</label>
        <label><input type="radio" name="continent" value="as">Asia</label>
        <label><input type="radio" name="continent" value="af">Africa</label>
        <label><input type="radio" name="continent" value="au">Australia</label>

    </fieldset>


    <p><fieldset>
        <legend>You current version (to determine update path):</legend>
        <label><input type="radio" name="version" value="none">none</label>
        <label><input type="radio" name="version" value="0.1">0.1</label>
        <label><input type="radio" name="version" value="0.2">0.2</label>
    </fieldset>

    <p><label>Comment:
            <textarea name="comment"></textarea>
        </label>

    <p><label>I agree to <a href="https://interoperable-europe.ec.europa.eu/collection/eupl/eupl-text-eupl-12">the EUPL license</a> applicable to Chitch:
        <input type="checkbox" name="license" required>
    </label>

    <button>Submit</button>
</form>
</section>
<?php
$architecture = validate_string(INPUT_POST, 'architecture');
$system = validate_string(INPUT_POST, 'system');
if (
    $architecture  && $system
):
?>

    <section>
<p><a href="<?= $download_url = "https://github.com/boukew99/chitch/releases/download/0.2-stable/chitch-$architecture-$system" ?>">Chitch Download for <?= htmlspecialchars($architecture) ?> and <?= htmlspecialchars($system) ?> with version <?= htmlspecialchars($version) ?></a>.</p>
<?php
// add precalc size and hash
?>
        <p>Verify hash with <code>sha256sum</code> or <code>shasum -a 256</code>:
            <p><a href="https://interoperable-europe.ec.europa.eu/collection/eupl/guidelines-users-and-developers">EUPL Guidelines for users and developers</a>.
    </section>

<?php
endif
?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>

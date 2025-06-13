<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require("../library/chitch.php");

use function Chitch\{read, write, authorize, validate_string, p};

session_start();

// sha384sum cachi.tar.gz
$current = 'sha';
// ?current=$current_hash
// ?yourhash=$hash
?>
<?= Chitch\head(); ?>
<title>Downloads</title>
<script defer src="/api/visit.js"></script>

<header>
    <h1>Downloads 📥</h1>
    <p>Download builds of Chitch here!</p>
</header>
<main>

<section id="info">
    <h2>Version Information</h2>
    <p>Current Version: <code><em><?= substr($current, 0, 6) ?></em><?= substr($current, 6) ?></code>
    <p>File size: <code>0.2 MB</code>
    <p>Content validation (hash) <code>8a0cd86ef082d7b294b435736d8c39f5af0e283dc9a8218e75e0156b4b6cdd0d chitch.tar.xz</code>

    <p>The project code is distributed under the <strong>weak</strong> copyleft<a download href="bokuh.com/license">EUPL license</a>. It is offered with the intention to make the web infrastructure as a whole, <strong>more sustainable, accessible and increase wellbeing</strong> of its users. Next, it does not want to exclude welfare creation since web software can be an accessible business for countries with poor economics and business can help create <strong>more reliability around the system</strong>. Lastly, the framework is designed to be tailor-able and thus <strong>developers should be free</strong> to do so. Therefore, the project is offered with an EUPL License, which <strong>requires to include the license</strong>, thus enabling people to always <strong>find the source</strong> of the software. Some <a href="https://interoperable-europe.ec.europa.eu/collection/eupl/guidelines-users-and-developers">Guidelines for users and developers</a>.

</section>
<section>
    <h2>Builds</h2>
<form method="post">
    <label>Operating System
        <select name="system">
            <option value="linux.tar.xz">Linux</option>
            <option value="windows.zip">Windows</option>
            <option value="darwin.tar.gz">macOS</option>
        </select>
    </label>
    <label>
        Architecture
        <select name="architecture">
            <option value="x86_64">x86_64</option>
            <option value="arm64">arm64</option>
        </select>
    </label>
    <h3>Demographic</h3>
    <p>These helps us understand our users.
    <p><label>Continent of Residence (to determine server locations):
            <select name="continent">
                <option value="eu">Europe</option>
                <option value="na">North America</option>
                <option value="sa">South America</option>
                <option value="as">Asia</option>
                <option value="af">Africa</option>
                <option value="au">Australia</option>
            </select>
        </label>


    <p><label>You current version (to determine updata path):
            <select name="version">
                <option>none</option>
                <option>0.1</option>
            </select>
        </label>

    <p><label>Comment:
            <textarea name="comment"></textarea>
        </label>

    <p><label>I have carefully read and agree with <a href="https://interoperable-europe.ec.europa.eu/collection/eupl/eupl-text-eupl-12">the EUPL license</a>:
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
        <p>Your download should start soon...</p>
        <p>If not, <a href="<?= $download_url = "https://github.com/boukew99/chitch/releases/download/stable/chitch-$architecture-$system" ?>">click here</a>.</p>

        <script>
            setTimeout(() => {
            window.location.href = "<?php echo $download_url; ?>";
            }, 1000); // wait 1 sec to be kind cave wizard
        </script>
    </section>

    <?php
    endif
    ?>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>

<?php
# © 2025 Chitch-Maintainers, Licensed under the EUPL

require_once("../library/bootstrap.php");

use function Chitch\{chitchmail, read, validate_string};

session_start();

?>
<?= Chitch\head(); ?>
<title>Membership</title>
<meta name="robots" content="noindex">
<header>
    <h1>Authenticate</h1>
    <p>Start a private 🔐 sessions with the website and get permissions for exclusive access. You might want to bookmark this page for easier access.
    <?= Chitch\authstate() ?>
</header>
<main>

    <section id="login">
        <h2>Member Login 🔐</h2>

        <form method="post">
            <label>Username 🔒:

                <?php
                $username = validate_string(INPUT_POST, "username", 3, 16);
                //check if has correct pattern
                if (
                    $username &&
                    !preg_match('/^[a-z0-9_-]{3,16}$/', $username)
                ) {
                    $username = "";
                }
                ?>

                <input type="password" name="username" required pattern="^[a-z0-9_-]{3,16}$" value="<?= $username ??
                                                                                                        "" ?>" />
                <small>3-16 characters, lowercase letters, numbers, hyphens, and underscores only.</small>
            </label>

            <label>Member E-mail 🔑 :

                <?php $email = htmlspecialchars(
                    filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL)
                ); ?>

                <input type="email" name="email" required value="<?= $email ?>" />
            </label>

            <label>
                <input type="checkbox" name="remember" required>
                I understand I will stay signed in for the duration that this browser is open.
            </label>

            <button>Sign In</button>
        </form>
        <p><ins>
                <?php
                function sendkey(string $email, string $username)
                {
                    $generateToken = function ($length = 8) {
                        return bin2hex(random_bytes($length / 2)); // secure random token
                    };

                    $hashedmails = unserialize(read("members")[0]);

                    if (!isset($hashedmails[$username])) {
                        return false;
                    }

                    $user = $hashedmails[$username];

                    $match = password_verify($email, $user["email"]);

                    if ($match) {
                        $_SESSION["username"] = $username; //alias / author
                        $_SESSION["groups"] = $user["groups"];
                        $_SESSION["authenticated"] = $onetimekey = $generateToken();
                        # https://docs.hetzner.com/konsoleh/account-management/email/setting-up-an-email-account/
                        chitchmail(
                            $email,
                            "Membership Login Confirmation",
                            "Follow this link to confirm your login: https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]?key=$onetimekey"
                        );
                    }

                    return $match;
                }
                echo !$error && $email && $username && sendkey($email, $username)
                    ? "Check your email for a confirmation link."
                    : "Both email and username are required or invalid.";
                ?></ins>
    </section>

    <section>
        <h2>Email Key</h2>
        <p>
            If you have received a key in your email, please enter it here to authenticate your session.
        <form>
            <label>Key:
                <?php $key = validate_string(INPUT_GET, "key", 8, 8); ?>
                <input type="password" name="key" value="<?= $key ?>" />
            </label>

            <button>Submit</button>
        </form>
        <?php if (
            $key &&
            isset($_SESSION["authenticated"]) &&
            hash_equals($key, $_SESSION["authenticated"])
        ) {
            # Chitch\OWNER because session files may be shared on server
            $_SESSION["authorized"] = Chitch\OWNER;
            unset($_SESSION["authenticated"]);
            echo "<p><ins>You are now logged in.</ins>";
        } ?>
    </section>

</main>

<footer>
    <?= Chitch\foot() ?>
</footer>

  <?php
  # © 2025 Chitch-Maintainers, Licensed under the EUPL

  /**
   * Error code page feedback page
   * @see https://www.php.net/manual/en/function.http-response-code.php
   */

  $error = http_response_code() ?? 505;

  require('../chitch.php');


  $reports = match ($error) {
    403 => ['Forbidden 🌈', 'You do not have permission to access this page.', 'Login to access', 'Not all ways lead to Rome'],
    404 => ['Lost 💔', 'The page you are looking for does not exist.', 'Use the Home Index', 'Not all ways lead to Rome'],
    500 => ['Internal Server Error 🤕', 'An unexpected internal server error occurred.', 'Contact the site owner', 'This was not a planned meeting but here we still are unexpectedly.'],
    503 => ['Maintenance', 'This site (section) is under maintenance currently. Please be patient while the site gets upgraded.', 'Be patient', 'Not all ways lead to Rome'],
    default => ['Error', 'An unexpected error occurred.', 'Contact the site owner', 'Not all ways lead to Rome'],
  };
  [$title, $description, $fix, $quote] = $reports;

  ?>
  <?= Chitch\head(); ?>
  <title><?= $title ?></title>
  <script defer src="/visit.js"></script>
  <meta name="description" content="<?= $description ?>">
  <main>
    <header>
      <h1><?= $title ?></h1>
      <p><?= $description ?>
      <p>Fix: <?= $fix ?>
      <blockquote><?= $quote ?></blockquote>
      <p>Error code <code><?= $error ?></code>.
    </header>
  </main>

  <footer>
    <?= Chitch\foot() ?>
  </footer>

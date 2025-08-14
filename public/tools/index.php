<?php
// © 2025 Chitch Contributors, Licensed under the EUPL

chdir(dirname($_SERVER['DOCUMENT_ROOT']));
require_once('library/bootstrap.php');

?>
<?=Chitch\head('Chitch Server Status') ?>
<header>
    <h1>Chitch Server Status</h1>
    <p>Using PHP binary: <code><?= PHP_BINARY ?></code> and config <code><?= php_ini_loaded_file() ?></code>. PHP should automatically include the php.ini in the current working directory.
    <p>Document root <code><?= $_SERVER['DOCUMENT_ROOT'] ?></code> at address <code>http://<?= $_SERVER['HTTP_HOST'] ?>/</code></p>
    <p>My PID is <code><?= $pid = getmypid() ?></code></p>
    <p>Use your <strong>System Monitor</strong> or <strong>Task Manager</strong> to find and stop the server process, under the name <code>php*</code>(* can be anything).
    <figure>
        <figcaption>Included Files</figcaption>
        <ul>
            <?= Chitch\tree('li',
                fn($x) => basename($x),
                get_included_files())
            ?>
        </ul>
    </figure>
    <p>PHP Version: <code><?= PHP_VERSION ?></code></p>
    <p>PHP SAPI: <code><?= PHP_SAPI ?></code></p>
    <p>PHP Extensions: <code><?= implode(', ', get_loaded_extensions()) ?></code></p>
    <p>Platform: <code><?= PHP_OS ?></code></p>
    <p>Architecture: <code><?= php_uname('m') ?></code></p>
    <p>Workers: <?= getenv('PHP_CLI_SERVER_WORKERS')?>
    <p>Database directory exists: <code><?= is_dir('../database') ? 'yes' : 'no' ?></code>
    <p>Temp directory exists: <code><?= is_dir('../temp') ? 'yes' : 'no' ?></code>

    <p>Assets directory exists: <code><?= is_dir('../assets') ? 'yes' : 'no' ?></code>
    <p>Performance log file: <code><?= file_exists('../performance.csv') ? 'exists' : 'does not exist' ?></code>
    <p>Server log file: <code><?= file_exists('../server.log') ? 'exists' : 'does not exist' ?></code></p>
    <script>
    // Check if newer version available at chitch.org/version.txt
    (async () => {
        const currentVersion = '0.3'
        try {
            const resp = await fetch('http://chitch.org/api/version.txt', {cache: 'no-store'})
            if (!resp.ok) return
            const latest = (await resp.text()).trim()
            // Compare versions (simple lexicographical, adjust if needed)
            if (latest > currentVersion) {
                const updateUrl = 'https://chitch.org/download'
                const msg = `New version available: <strong>${latest}</strong> (current: ${currentVersion}) `
                    + `<a href="${updateUrl}" target="_blank">Update now</a>`
                const p = document.createElement('p')
                p.innerHTML = msg
                p.style.background = '#ff0'
                p.style.padding = '0.5em'
                p.style.borderRadius = '6px'
                p.style.fontWeight = 'bold'
                document.querySelector('header').appendChild(p)
            }
        } catch (e) {
            // Ignore errors
        }
    })()
    </script>
</header>

<main>
    <h2>More tools</h2>
<ul>
    <?= Chitch\tree('li',
        fn($x) => "<a href='$x'>$x</a>",
        glob('public/tools/*.php'))
    ?>
    <section>
    <h2>Libraries</h2>
    <p>These are namespace which hold functions which can be included in web pages.
    <?= Chitch\tree('li', fn($x) => "<code class='php'>require_once('<a href='/tools/editor?edit=$x'>$x</a>');</code>", glob('library/*.php')); ?>

</section>
    <section>

        <h2>Performance Log</h2>
        <?php
       function show_perf_log($filename =   '../performance.csv') {
    if (!file_exists($filename)) {
        echo "<p>No log found!</p>";
        return;
    }

    $data = [];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        list($ts, $uri, $time, $mem) = str_getcsv($line, ",", '"', "\\");
        $data[] = [
            'ts' => strtotime($ts),
            'uri' => $uri,
            'time' => (float)$time,
            'mem' => (int)$mem
        ];
    }

    // now simple graph — using canvas
    ?>
    <canvas id="perfGraph" width="600" height="200" style="border:1px solid #333;"></canvas>
    <script>
      const data = <?php echo json_encode($data); ?>;
      const canvas = document.getElementById('perfGraph');
      const ctx = canvas.getContext('2d');

      // Prepare data
      const times = data.map(d => d.time);
      const mems = data.map(d => d.mem);

      const maxTime = Math.max(...times);
      const maxMem = Math.max(...mems);

      const padding = 30;
      const w = canvas.width - padding * 2;
      const h = canvas.height - padding * 2;

      // clear canvas
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      // draw axis
      ctx.beginPath();
      ctx.moveTo(padding, padding);
      ctx.lineTo(padding, canvas.height - padding);
      ctx.lineTo(canvas.width - padding, canvas.height - padding);
      ctx.stroke();

      // function to map data point to canvas coords
      function mapX(i) {
        return padding + (i / (data.length - 1)) * w;
      }
      function mapYTime(val) {
        return canvas.height - padding - (val / maxTime) * h;
      }
      function mapYMem(val) {
        return canvas.height - padding - (val / maxMem) * h;
      }

      // draw time graph (red)
      ctx.strokeStyle = 'red';
      ctx.beginPath();
      data.forEach((d,i) => {
        const x = mapX(i);
        const y = mapYTime(d.time);
        if(i === 0) ctx.moveTo(x,y); else ctx.lineTo(x,y);
      });
      ctx.stroke();

      // draw mem graph (blue)
      ctx.strokeStyle = 'blue';
      ctx.beginPath();
      data.forEach((d,i) => {
        const x = mapX(i);
        const y = mapYMem(d.mem);
        if(i === 0) ctx.moveTo(x,y); else ctx.lineTo(x,y);
      });
      ctx.stroke();

      // legend
      ctx.fillStyle = 'red';
      ctx.fillRect(canvas.width - 120, 20, 10,10);
      ctx.fillStyle = 'black';
      ctx.fillText('Load Time (ms)', canvas.width - 100, 30);

      ctx.fillStyle = 'blue';
      ctx.fillRect(canvas.width - 120, 40, 10,10);
      ctx.fillStyle = 'black';
      ctx.fillText('Memory (bytes)', canvas.width - 100, 50);
    </script>
    <?php
}
        show_perf_log();
        ?>
    </section>

</main>

<footer>
<?=Chitch\foot()?>

</footer>
<?php

# https://www.php.net/manual/en/book.info.php

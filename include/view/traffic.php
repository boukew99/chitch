<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL

require('../chitch.php');

use function Chitch\{read};

?>
<?= Chitch\head(); ?>
<title>Traffic Analytics</title>
<meta name="robots" content="noindex">
<script defer src="/visit.js"></script>
<main>
    <header>
        <h1>Traffic Analytics by visitors</h1>
        <p>Shows traffic insights.
    </header>
    <table contenteditable="true">
        <thead>
            <tr>
                <th>Date</th>
                <th>Resolution X</th>
                <th>Resolution Y</th>
                <th>Pixels Ratio</th>
                <th>Location</th>
                <th>Title</th>
                <th>Ref</th>
                <th>Views</th>
                <th>Bot</th>
                <th>FirstLoad</th>
            </tr>
        </thead>
        <tbody class="chart">
            <?= read('traffic') ?>
        </tbody>
    </table>

    <p>Log count: <span id="logCount">0</span></p>
    <script>
        const rowCount = document.querySelector('.chart').querySelectorAll('tr').length;
        document.getElementById('logCount').textContent = rowCount;
    </script>

<svg id="resChart" width="500" height="400" viewBox="0 0 500 400"
     xmlns="http://www.w3.org/2000/svg" style="border:1px solid #ccc; font-family:monospace; font-size:10px;">
  <!-- axes -->
  <line x1="40" y1="40" x2="40" y2="360" stroke="black"/>
  <line x1="40" y1="360" x2="460" y2="360" stroke="black"/>

  <!-- dot group here -->
  <g id="dots"></g>
</svg>

<script>
(function () {
  const rows = document.querySelectorAll("tbody.chart tr");
  const dots = document.getElementById("dots");

  const w = 500, h = 400, pad = 40;
  const data = [];

  for (const row of rows) {
    const td = row.querySelectorAll("td");
    const x = parseInt(td[1]?.textContent.trim());
    const y = parseInt(td[2]?.textContent.trim());
    if (!isNaN(x) && !isNaN(y)) data.push({ x, y });
  }

  const maxX = Math.max(...data.map(d => d.x));
  const maxY = Math.max(...data.map(d => d.y));
  const scaleX = x => pad + (x / maxX) * (w - 2 * pad);
  const scaleY = y => h - pad - (y / maxY) * (h - 2 * pad);

  for (const point of data) {
    const c = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    c.setAttribute("cx", scaleX(point.x));
    c.setAttribute("cy", scaleY(point.y));
    c.setAttribute("r", 3);
    c.setAttribute("fill", "blue");
    dots.appendChild(c);
  }
})();
</script>


</main>

<footer>
    <?= Chitch\foot() ?>
</footer>

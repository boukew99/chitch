<p>&copy; Copyright 2025. All rights reserved, Chitch.org
    <img src='/styles/icon.svg' alt='site logo' width='16' height='16' />. Rendered today on <?= $date = date("g:i a") ?> in Germany. Hosted with green energy.
</p>

<p>~<?=$online = count(glob(session_save_path() . "/sess_*"));?> user(s) online!</p>

<p>
<a href="/map.php">Site Map</a>

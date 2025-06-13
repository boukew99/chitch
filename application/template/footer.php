
<p>&copy; Copyright 2025. All rights reserved, Chitch.org
    <img src='/icon.svg' alt='site logo' width='16' height='16' />. Rendered today on <?= $date = date("g:i a") ?> in Germany. Hosted with green energy.
</p>

<p>~<?=$online = count(glob(session_save_path() . "/sess_*"));?> user(s) online!</p>

<nav>
<ul>
    <li><a href='#'>Go to top</a>
    <li><a href='/'>Home</a>
    <li><a href='/news.php'>Blog / Articles</a>
    <li><a href='/guestbook.php'>Guestbook</a>
    <li><a href='/contact.php'>Contact</a>
    <li><a href='/traffic.php'>Site Traffic</a>
    <li><a href='/qa.php'>Frequently Asked Question</a>
    <li><a href='/download.php'>Downloads</a>
    <li><a href='/login.php'>Login</a>
</ul>
<ul>
    <li>Tools:
    <li><a href='/tool/assets.php'>/assets</a>
    <li><a href='/tool/bookmark.php'>/bookmark</a>
    <li><a href='/tool/code.php'>/code</a>
    <li><a href='/tool/disk.php'>/disk</a>
    <li><a href='/tool/editor.php'>/editor</a>
    <li><a href='/tool/logs.php'>/logs</a>
    <li><a href='/tool/lookup.php'>/lookup</a>
    <li><a href='/tool/patch.php'>/patch</a>
    <li><a href='/tool/php.php'>/php</a>
    <li><a href='/tool/reference.php'>/reference</a>
    <li><a href='/tool/review.php'>/review</a>
    <li><a href='/tool/test.php'>/test</a>
    <li><a href='/tool/users.php'>/users</a>
</ul>

</nav>
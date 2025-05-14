<?php
# Chitch © its Maintainers 2025, Licensed under the EUPL
require('../chitch.php');
?>
<?= Chitch\head(); ?>
<link rel="manifest" href="/manifest.json">
<title>Chitch Green Website Builder</title>
<script defer src="/visit.js"></script>
<meta
  content='Chitch is a new green software which aims to reduce redundancy in order to reduce the climate impact of websites on a large scale. This helps move the web to net-zero.'
  name='description' />
<script>
  if ("serviceWorker" in navigator) {
    await navigator.serviceWorker.register("service-worker.js");
  }
</script>
<style>
  main {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
  }

  section {
    background: #f9f9f9;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
  }

  section:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
  }

  blockquote {
    font-style: italic;
    color: #555;
    margin: 1rem 0;
    padding-left: 1rem;
    border-left: 4px solid #ccc;
  }

  h2 {
    color: #10424b;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
  }

  @media (prefers-color-scheme: dark) {
    section {
      background: #1e1e1e;
      border-color: #333;
      color: #ddd;
    }

    blockquote {
      color: #aaa;
      border-left-color: #555;
    }

    h2 {
      color: #c0ceed;
    }
  }
</style>

<header>
  <h1>Chitch</h1>
  <h2 class="gradientheading">Green Website builder</h2>
  <p>Chitch is a new green software which aims to reduce redundancy in order to reduce the climate impact of websites on
    a large scale. Make your website an eco-friendly website! This helps move the web to net-zero.
  <details>
    <summary>Table of Contents</summary>
    <nav id="toc"></nav>
  </details>
</header>

<main>

  <section id="green-software">
    <h2>Green Software</h2>
    <p>Chitch is green software. It is designed to be as efficient as possible, to reduce the climate impact of websites. This is done by reducing the amount of data that needs to be transferred, which in turn reduces the amount of energy that is used to transfer that data. This is important because the internet is a major source of greenhouse gas emissions, and reducing the amount of data that is transferred can help reduce those emissions. This moves the web to net-zero. Leave a lasting impression, not a footprint. 🌱
    <blockquote>Your website is so lightweight, it gets a standing ovation from the ozone layer.</blockquote>
  </section>

  <section id="features">
    <h2>Features</h2>
    <p>Chitch packs everything in a small self-contained zip file. Just extract and run. It includes by default:
    <ul>
      <li>Guestbook
      <li>Blog
      <li>Contact page
      <li>Login
      <li>Traffic Analytics
    </ul>
  </section>

  <section id="kiss">
    <h2>So simple it is almost stupid</h2>
    <p>Chitch removes so much redundancy that is so lightweight, that you might wonder how all the features are packed in there. Though simplicity means reliability and that is why in software they say <abbr title="Keep It Simple, Stupid">KISS</abbr>💋.
  </section>

  <section id="small-sized">
    <h2>Download in under 15 minutes</h2>
    <p>Into your mind that is. The token count sits around the 3600, which with an average reading speed of 238 words per minute should take about 15 minutes. The code itself is downloaded instantly, since it is under 500 kB. Halstead Complexity Measure predicts that this results in less bugs. And for security analyst this is a dream to audit.
    <blockquote>Even if your Wi-Fi is running on hamster power, this thing will stay zippy and sharp.</blockquote>
  </section>

  <section id="reliable-processes">
    <h2>Reliable Processes</h2>
    <p>Chitch builds upon the low-level simple, process per request, approach. Unlike modern techniques, this does not share data between requests, which makes it more reliable and secure. Furthermore, a process can be gracefully stopped without impacting the system, ensuring that an error for 1 client does not affect other clients. It also allows the system to go to sleep. This means if the system is not used, it does not consume any energy.
    <blockquote>Your website could run on a potato-powered server and still load faster than the average CMS.</blockquote>
  </section>

  <section id="low-traffic">
    <h2>Optimized for Low-Traffic</h2>
    <p>Chitch is designed specifically for low-traffic websites, typically experiencing between 1,000 and 10,000 visits per month. A key characteristic of these sites is significant server idle time, often upwards of 99%. Chitch addresses this efficiently by minimizing resource consumption when the server is inactive, leading to significant cost savings and a reduced environmental footprint.

    <p>Chitch achieves its high efficiency through its <strong>100% persistent architecture</strong>. All processes are "fire and forget". The system quickly and efficiently boots from disk on demand, eliminating the need for continuously running processes and minimizing resource consumption during idle periods. This approach reduces operational costs and energy consumption.
  </section>

  <section id="simple-visual-deployment">
    <h2>Simple Visual Deployment</h2>
    <p>Deployment of efficient and customizable websites is often done with the Command Line. However, this can be daunting for casual users and often requires a lot of implicit knowledge. Chitch aims to make this process visual and easy by allowing the deployment to be fully done via a File Browser. It does this by supporting the File Transfer Protocol, which one can use with their favorite FTP client or via a Web Interface. The upgrading is also as simple with zero downtime in most cases. Deploy without needing a PhD in tech.
    <blockquote>It’s so intuitive you’ll forget you’re building a site and think you’re just having a really productive conversation with your computer.</blockquote>
  </section>

  <section id="dx">
    <h2>Unique Developer Experience</h2>
    <p>Chitch bundles its own developer tools such an code editor, build script, change control and tests. These are build with and on top of the HTTP protocol. This allows for an unique developer experience, where the developer can use the same tools for both the server and the client. This makes it easier to develop and debug the website, as the developer does not have to switch between different tools and languages. Easy peasy! 🍋
    <blockquote>Your cat could paw at the keyboard and accidentally publish a stunning portfolio</blockquote>
  </section>

  <section id="mature-foundation">
    <h2>Mature Foundation</h2>
    <p>Chitch is built on top of mature technologies such as Apache, PHP, SVG and Brotli. These technologies have been around for a long time and are well-tested and reliable. This ensures that Chitch is stable and secure, and that it will continue to work well into the future. It also means that Chitch is compatible with a wide range of devices and browsers. Classic tech, reimagined modern magic. ✨🧙‍♂️
    <blockquote>Your uncle who still prints emails could build his own homepage in minutes.
    </blockquote>
  </section>

  <section id="baseline">
    <h2>Baseline Web browsers Compatibility</h2>
    <p>Chitch is a new technology and thus makes use of the latest features in the browser. By supporting <a href="https://web-platform-dx.github.io/web-features/">Baseline browsers</a> we ensure a wide compatibility across browsers, but also enable ourself to use new enhancements on the client side. This is done in a progressive manner wherever possible to support older browsers as well.
    <figure>
      <img src="/assets/baseline-wordmark.svg" alt="baseline logo">
    </figure>
  </section>

  <section id="license">
    <h2>European Union Public License</h2>
    <p>Chitch is licensed under the <abbr>EUPL</abbr>. It is a weak copyleft license used by public sector administrations and available in 22 European languages. It forms a basis for sharing and reusing software solutions Chitch provides. It protects contributors and promotes open collaboration. It also makes it attractive for public sectors to use and invest in. But also businesses can use it since the license allows integration with proprietary code files. More <a href="https://interoperable-europe.ec.europa.eu/collection/eupl/introduction-eupl-licence">legal and correct information is available in the EU site</a>
  </section>

  <section>
    <figure>
      <figcaption>Capybara Mascot</figcaption>
      <img src="/assets/capybara.svg" alt="Capybara Mascot" />
    </figure>

    <p>The capybara is known for its friendly nature on the internet. It lives peacefully among other animals and is often seen as a symbol of harmony and coexistence. Chitch reverberates that by deeply harmonizing with other external programs and systems and staying inclusive.
  </section>

  <section id="colophon">
    <h2>Colophon</h2>
    <p>Chitch was born in <a href="https://collaboratingwww.openstreetmap.org/?#map=8/52.312/5.070">the Netherlands</a>. Help prevent the Netherlands (lands below zero) from getting flooded by rising sea levels by reducing temperature risings at the Arctics.</p>
    <p>Very little CO2 was used to transfer this page. Measure it yourself with <a href="https://www.websitecarbon.com/website/chitch-org/">Website Carbon Calculator</a>.
    <p>Green energy is used to host Chitch!

      <img src="/assets/green-host.png" alt="This website runs on green hosting - verified by thegreenwebfoundation.org" width="200px" height="95px">

  </section>

  <section>
    <h2>Join the Green Web Movement</h2>
    <p>Although Chitch eventually wants to be accessible to casual users, for now it requires still some programmer knowledge to operate. You can start by
      <a href="https://github.com/boukew99/chitch">Downloading the project from Github!</a> (& Star the repo). Try out the code and contribute to help progress Chitch.
    <p>Chitch also is open to other kinds of contributions outside of code.
    <p>Share your website in the <a href"guestbook.php">Guestbook</a> if you made it with Chitch!
    <p>Read up on the <a href"https://w3c.github.io/sustainableweb-wsg/">Web Sustainability Guidelines</a>
  </section>
</main>
<footer>
  <?= Chitch\foot() ?>
</footer>

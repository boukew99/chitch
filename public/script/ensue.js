// © 2025 Chitch Contributors, Licensed under the EUPL

// Keywords to highlight
document.addEventListener("DOMContentLoaded", function () {
    function collectKeywords(dlId) {
        const dl = document.getElementById(dlId);
        if (!dl) return [];
        return Array.from(dl.querySelectorAll('dt')).map(dt => dt.textContent.trim());
    }

    function markKeywords(markup, keywords) {
        if (!keywords.length) return;
        const paragraphs = document.querySelectorAll('main p');
        if (!paragraphs.length) return;

        paragraphs.forEach(p => {
            let pHTML = p.innerHTML;
            keywords.forEach(keyword => {
                const regex = new RegExp(`\\b(${keyword})\\b`, 'g');
                pHTML = pHTML.replace(regex, `<${markup}>$1</${markup}>`);
            });
            p.innerHTML = pHTML;
        });
    }

    // Collect keywords and apply highlighting
    markKeywords('abbr', collectKeywords('abbr'));
    markKeywords('code', collectKeywords('code'));
});

// Indexer / Table of Contents for Section Navigation
document.addEventListener("DOMContentLoaded", function () {
    const toc = document.getElementById('toc');
    if (!toc) return;

    let headers = document.querySelectorAll('section h2, section h3, section h4, section h5, section h6');
    if (!headers.length) {
        toc.innerHTML = '<li>No sections found</li>';
        return;
    }

    let olStack = [toc];
    let linkedSections = new Set();

    headers.forEach(hx => {
        let level = parseInt(hx.tagName[1]) - 1;
        let section = hx.closest('section[id]');
        let li = document.createElement('li');

        if (section && !linkedSections.has(section.id)) {
            let a = document.createElement('a');
            a.href = `#${section.id}`;
            a.textContent = hx.textContent;
            li.appendChild(a);
            linkedSections.add(section.id);
        } else {
            li.textContent = hx.textContent;
        }

        while (olStack.length > level + 1) olStack.pop();
        let parentOl = olStack[olStack.length - 1];

        if (olStack.length < level + 1) {
            let newOl = document.createElement('ol');
            parentOl.appendChild(newOl);
            olStack.push(newOl);
        }

        olStack[olStack.length - 1].appendChild(li);
    });
});

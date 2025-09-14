// activate with ?test=1 in the URL
const isTesting = location.search.includes("test=1");

function isInternalLink(a) {
    return a.hostname === location.hostname;
}

function markBrokenLink(a, status) {
    a.style.border = "2px solid red";
    a.title = status ? `Broken link (HTTP ${status})` : "Broken link (unreachable)";
}

function checkLink(a) {
    fetch(a.href, { method: 'HEAD' })
        .then(res => {
            if (!res.ok) markBrokenLink(a, res.status);
        })
        .catch(() => markBrokenLink(a));
}

function checkAllLinks() {
    const anchors = document.querySelectorAll("a[href]");
    const internalLinks = Array.from(anchors).filter(isInternalLink);
    internalLinks.forEach(checkLink);
}

if (isTesting) {
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(checkAllLinks, 1000); // Give page moment of peace 🧘
    });
}

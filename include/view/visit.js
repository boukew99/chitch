// Chitch © its Maintainers 2025, Licensed under the EUPL

const scrollHandler = () => {
    let beacon = new FormData();

    beacon.append("resolutionX", window.innerWidth);
    beacon.append("resolutionY", window.innerHeight);
    beacon.append("pixelsRatio", window.devicePixelRatio);
    beacon.append("location", location.search);
    beacon.append("title", document.title);
    beacon.append("ref", document.referrer);
    beacon.append("pageview", location.pathname);
    beacon.append("bot", false);
    beacon.append("date", new Date().toISOString());
    beacon.append('firstload',
        (() => {
            function getTotalPageSizeKB() {
                const resources = performance.getEntriesByType('resource');
                const totalBytes = resources.reduce((sum, resource) => sum + (resource.transferSize || 0), 0);
                return (totalBytes / 1024).toFixed(2); // Convert to KB, keep decimals smooth
            }
            return getTotalPageSizeKB(); // Need to return the result
        })()
    );

    navigator.sendBeacon("/visit.php", beacon);

    window.removeEventListener("scroll", scrollHandler);
};


window.addEventListener("scroll", scrollHandler);


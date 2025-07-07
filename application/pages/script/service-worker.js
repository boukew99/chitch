// © 2025 Chitch-Maintainers, Licensed under the EUPL
// Establish a cache name
const cacheName = 'ChitchCache1';

self.addEventListener('fetch', async (event) => {
  const cache = await caches.open(cacheName);
  const cachedResponse = await cache.match(event.request.url);

  // if index page and network, then fetch fresh
  if (cachedResponse && (!event.request.url.includes('index') && navigator.onLine)) {
    return cachedResponse;
  }

  const fetchedResponse = await fetch(event.request);

  const textTypes = ['document', 'font', 'json', 'script', 'style', 'worker']; //php ?
  if (textTypes.includes(event.request.destination)) {
    cache.put(event.request, fetchedResponse.clone());
  }

  return fetchedResponse;
});

// 1 PWA per Domain, https://web.dev/articles/building-multiple-pwas-on-the-same-domain

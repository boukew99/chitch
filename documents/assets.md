# Assets Specification
Your sites needs assets like images, audio, video. This files are quite big and thus are better not stored in the codebase. These assets usually also do not change often. Therefore we can store them in a seperate (sub)domain. This subdomain can be referred to as an CDN (Content Delivery Network).

## CDN
The CDN can be a subdomain of your main domain. For example, `cdn.chitch.com`.You can use FTP to upload your assets to the CDN. Then assets can be accessed via the CDN via `https://cdn.chitch.com`. Thus all assets under this domain can be accessed via an URL from any computer. Such as `https://cdn.chitch.com/icon.png`.

## Logging
The assets URL's can logged in `documents/assets.log`. The format is: `URL HASH`. The URL to find the image and the HASH to verify it has not changed, if needed. This is stored in version control, effectively making it part of your history. `assets.php` automatically retrieves these URLS and displays them.

## Offline
If you have no internet than the assets cannot be accessed from the CDN. The fix for that is that you **cache** the assets. The browser automatically does that after the assets is downloaded a first time. Thus you only need to load the assets once before, with internet. You can do this for all assets on the `assets.php` page.

# Pinpoint — PHP location-link demo

A polished, localhost-ready PHP + MySQL demo for sharing a location link. When a visitor opens a link, the page explains the request and automatically asks the browser for location permission. Coordinates are stored **only if the visitor chooses Allow**; they can deny the prompt.

## Requirements
- PHP 8.0+ with PDO MySQL enabled
- MySQL/MariaDB (XAMPP, WAMP, Laragon, etc.)
- A modern browser

## Install on localhost
1. Copy the `location_link_demo` folder into your server web root, e.g. `C:\xampp\htdocs\location_link_demo`.
2. Start Apache and MySQL.
3. Check database credentials in `config.php` (`root` and blank password are common local defaults).
4. Open `http://localhost/location_link_demo/setup.php` once.
5. Sign in at `http://localhost/location_link_demo/login.php`.
6. Default local admin password: `ChangeThisPassword123!`.
7. **Delete `setup.php` after setup** and change `ADMIN_PASSWORD` in `config.php` before exposing the project outside localhost.

## How to test
1. Sign in and create a link.
2. Use **Copy link** in the links table.
3. Open the link in another browser tab on the same computer/phone. On localhost, browser geolocation is generally allowed; when hosted publicly, use HTTPS.
4. Choose Allow in the browser prompt. The request appears automatically after the page loads; no page button is required.
5. Return to the dashboard and open the Location reports section. Use **Open map** to view the coordinates.

## Important notes
- This demo does not silently locate visitors. Opening a link triggers the browser permission prompt, and coordinates are sent only after the visitor chooses Allow.
- IP-based geolocation is not implemented; it is approximate and should not be presented as an exact location.
- This starter is intended for local testing, not production deployment. Before public use, add rate limiting, stronger admin authentication, HTTPS, retention/deletion policies, and additional security hardening.
- Google Maps is used as a regular link; no Google Maps API key is needed.
- The Google Fonts import is optional; if offline, the page falls back to system sans-serif fonts.

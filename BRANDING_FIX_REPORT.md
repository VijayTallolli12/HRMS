# Branding Fix Report

## Root Cause

Branding values were being saved to `application_settings`, but the application shell was not reading from those settings when rendering.

The Settings module saved `app_title`, `company_name`, `logo`, and `favicon`, while the Blade layouts and sidebar still used static sources:

- `config('app.name')` for page titles and sidebar text.
- The default `<x-application-logo>` SVG on the login page.
- No dynamic favicon link in the main or guest layouts.
- No centralized service or view composer to expose saved branding globally.

This made the save operation succeed while the rendered UI continued using default Laravel/HRMS branding.

## Changes Made

- Added `App\Services\BrandingService`.
- Registered it as a singleton in `AppServiceProvider`.
- Added a global Blade view composer that shares:
  - `$branding['app_name']`
  - `$branding['company_name']`
  - `$branding['logo_url']`
  - `$branding['favicon_url']`
  - `$appName`
- Updated the main app layout to use dynamic browser title and favicon.
- Updated the guest/login layout to use dynamic browser title, favicon, logo, and company name.
- Updated the sidebar to use the uploaded logo and saved company name.
- Wrapped the sidebar in a Livewire component that listens for `branding-updated`, so branding changes repaint immediately after saving Settings.
- Added a browser head update listener for the page title and favicon after Settings saves.
- Updated payslip company header to use the saved company name.
- Refreshed branding state after Settings saves.
- Fixed broken image URLs by returning root-relative `/storage/...` paths instead of URLs tied to `APP_URL`.

## Storage Handling

Uploaded branding images are stored on Laravel's `public` disk under `settings/...`.

`BrandingService` verifies those paths on the `public` disk and resolves them as `/storage/...` public URLs. If a saved image path is missing or the file no longer exists, the service gracefully falls back:

- Logo: default inline application logo/sidebar icon.
- Favicon: `public/favicon.ico`.

## Expected Result

- Sidebar logo updates from uploaded logo.
- Sidebar company title updates from saved company name.
- Login page uses saved company logo and name.
- Browser tab title uses saved application title.
- Browser favicon uses saved favicon.
- Branding persists after refresh and logout/login.

## Commands Run

- `php artisan storage:link`
- `php artisan config:clear`
- `php artisan view:clear`
- `php artisan route:clear`

## Verification

- `php artisan test tests\Feature\BrandingRenderingTest.php`
- `php artisan test tests\Feature\SettingsFormTest.php`

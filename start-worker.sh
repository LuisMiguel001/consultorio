#!/bin/sh
set -e

php artisan schedule:work &

php artisan queue:work --tries=3 --timeout=90

# Run migrations
php artisan migrate:fresh

# Seed sample data
php artisan db:seed --class=SampleDataSeeder

# Or to run everything fresh
php artisan migrate:fresh --seed


# Git klonlama

git clone https://github.com/yunusemrekarakum/first_project_laravel.git

# env dosyasını kopyalama
cp .env.example .env

# sail kurulumu
composer require laravel/sail --dev

burada mysql mysql seçiyoruz

# Meilisearch anahtarları

SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://meilisearch:7700
MEILISEARCH_KEY=masterKey

# migrate etme
./vendor/bin/sail artisan migrate

# npm kurulumu ve başlatması

npm install
npm run build
npm run dev

# Eğer npm indirmede hata verirse bu komutları kullanıp tekrar deneyin

chown -R ${kullanıcı_adı}:${kullanıcı_adı} ./
chmod -R 775 ./

# RolePersmissionSeeder.php dosyasını çalıştır

./vendor/bin/sail artisan db:seed --class=RolePersmissionSeeder

# DatabaseSeeder.php dosyasını çalıştır

./vendor/bin/sail artisan db:seed --class=DatabaseSeeder

# scout çalıştır

 ./vendor/bin/sail artisan scout:import "App\Models\Product"

 # meilisearch filterlemeti aktifleştirmek için

 ./vendor/bin/sail artisan scout:sync-index-settings 

 ./vendor/bin/sail artisan scout:import "App\Models\Product"

 # contain filter aktifleştirmek
 curl   -X PATCH 'http://localhost:7700/experimental-features/'   -H 'Content-Type: application/json'   -H 'Authorization: Bearer masterKey'   --data-binary '{                                       
    "containsFilter": true
  }'
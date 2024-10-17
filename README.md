# Proje Kurulumu

### Git klonlama

``` bash
git clone https://github.com/yunusemrekarakum/first_project_laravel.git
```

### env dosyasını kopyalama
``` bash
cp .env.example .env
```

### sail kurulumu
``` bash
composer require laravel/sail --dev
```

### migrate etme
``` bash
sail artisan migrate
```

### seedleri çalıştırma
``` bash
sail artisan db:seed
```

### npm kurulumu ve başlatması

``` bash
npm install
```
``` bash
npm run dev
```

### meilisearch aktifleştirmek için

``` bash
sail artisan scout:sync-index-settings 
```

```bash
sail artisan scout:import "App\Models\Product"
```

 ### contain filter aktifleştirmek
 ```bash
 curl   -X PATCH 'http://localhost:7700/experimental-features/'   -H 'Content-Type: application/json'   -H 'Authorization: Bearer masterKey'   --data-binary '{                                       
    "containsFilter": true
  }'
```

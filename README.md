# Proje Kurulumu


### Git klonlama

```git clone https://github.com/yunusemrekarakum/first_project_laravel.git```

### env dosyasını kopyalama
```cp .env.example .env```

### sail kurulumu
```composer require laravel/sail --dev```

### migrate etme
```sail artisan migrate```

### seedleri çalıştırma
```sail artisan db:seed```

### npm kurulumu ve başlatması

```npm install```
```npm run dev```

### meilisearch aktifleştirmek için

```sail artisan scout:sync-index-settings ```

```sail artisan scout:import "App\Models\Product"```

 ### contain filter aktifleştirmek
 ```
 curl   -X PATCH 'http://localhost:7700/experimental-features/'   -H 'Content-Type: application/json'   -H 'Authorization: Bearer masterKey'   --data-binary '{                                       
    "containsFilter": true
  }'
```

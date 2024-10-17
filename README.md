<p align="center">
  <img src="https://cdn-edge.kwork.ru/pics/t3/56/31062053-65a23f500ddb1.jpg" alt="laravel + vuejs" width="500" />
</p>

<h1 align="center">Proje Hakkında</h1>

Proje laravel v11 ve Vuejs v3 ile yazılmıştır laravelde ve vuejsde kullanmış olduğum kütüphaneler ve sürümleri aşağıdaki tabloda.

## Laravel kütüphaneleri
| İsim | Sürüm     |
| :-------- | :------- |
| **Sanctum** | 4.0 |
| **Scout** | 11.0 |
| **Telescope** | 5.2 |
| **Meilisearch** | 1.10 |
| **Lighthouse** | 6.45 |
| **Spatie Medialibrary** | 11.9 |
| **Spatie Permission** | 6.9 |

## Vuejs kütüphaneleri
| İsim | Sürüm     |
| :-------- | :------- |
| **Fontawesome** | 6.6.0 |
| **bootstrap** | 5.3.3 |
| **Vee Validate** | 4.13.2 |
| **Vue Router** | 4.4.5 |
| **Vue Toastification** | 2.0 |
| **Vuex** | 4.0.2 |
| **Yup** | 1.4.0 |

## 1. Git klonlama

```bash
git clone https://github.com/yunusemrekarakum/first_project_laravel.git
```

## 2. env dosyasını kopyalama

```bash
cp .env.example .env
```

## 3. sail kurulumu

```bash
composer require laravel/sail --dev
```

## 4. migrate etme

```bash
sail artisan migrate
```

## 5. seedleri çalıştırma

```bash
sail artisan db:seed
```

## 6. npm kurulumu ve başlatması

```bash
npm install
```

```bash
npm run dev
```

## 7. meilisearch aktifleştirmek için

```bash
sail artisan scout:sync-index-settings
```

```bash
sail artisan scout:import "App\Models\Product"
```

## 8. contain filter aktifleştirmek

```bash
curl   -X PATCH 'http://localhost:7700/experimental-features/'   -H 'Content-Type: application/json'   -H 'Authorization: Bearer masterKey'   --data-binary '{
   "containsFilter": true
 }'

```
## admin panele giriş bilgileri
```
admin url: http://localhost/admin
```

```
email: admin@gmail.com
```
```
şifre: 123123
```


FROM php:8.2-fpm

# Güncellemeleri yap
RUN apt-get update -y

# Gerekli kütüphaneleri yükle
RUN apt-get install -y \
    libzip-dev \
    zip unzip \
    git \
    curl \
    libxml2-dev \
    libldap2-dev \
    libgd-dev \
    zlib1g-dev \
    libicu-dev g++ \
    libfreetype6-dev \
    libpng-dev \
    libjpeg-dev \
    libjpeg62-turbo-dev \
    nodejs \
    npm

# PHP uzantılarını yapılandır ve yükle
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && docker-php-ext-install gd
RUN docker-php-ext-install ldap
RUN docker-php-ext-install soap
RUN docker-php-ext-install zip
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install ctype
RUN docker-php-ext-install fileinfo
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install tokenizer
RUN npm install @vitejs/plugin-vue --save-dev

# Composer kurulumunu yap
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Node.js ve npm kurulumunu yap
RUN npm install -g n && n stable

# /var/www/html dizinine gerekli izinleri ver
RUN chown -R sail:sail /var/www/html

# Çalışma dizini olarak ayarla
WORKDIR /var/www/html

# Uygulama dosyalarını kopyala
COPY . .

# NPM bağımlılıklarını yükle
RUN npm install

# Vue.js uygulamasını derle
RUN npm run build

# Konteyner çalışırken başlatılacak komut
CMD ["php-fpm"]

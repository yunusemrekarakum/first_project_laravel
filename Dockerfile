FROM php:8.2-fpm


# Çalışma dizini olarak ayarla
WORKDIR /var/www/html

# /var/www/html dizinine gerekli izinleri ver
RUN chown -R sail:sail /var/www/html

# Uygulama dosyalarını kopyala
COPY . .

# Konteyner çalışırken başlatılacak komut
CMD ["php-fpm"]

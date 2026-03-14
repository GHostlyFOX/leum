# Инструкции по развёртыванию

## Настройка Nginx для загрузки файлов

При ошибке `413 Request Entity Too Large` при загрузке логотипов/файлов:

### 1. Настройка Nginx

Добавьте в конфигурацию сервера (обычно `/etc/nginx/sites-available/sbor.team`):

```nginx
server {
    # ... остальная конфигурация ...
    
    # Увеличиваем лимит загрузки файлов (по умолчанию 1M)
    client_max_body_size 10M;
    
    # Для Livewire загрузок
    location ~ ^/livewire/upload-file {
        client_max_body_size 10M;
        proxy_buffering off;
        proxy_request_buffering off;
        
        # ... остальная конфигурация PHP ...
    }
}
```

Перезапустите Nginx:
```bash
sudo nginx -t
sudo systemctl restart nginx
```

### 2. Настройка PHP

В файле `/etc/php/8.2/fpm/php.ini` (или вашей версии PHP):

```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

Перезапустите PHP-FPM:
```bash
sudo systemctl restart php8.2-fpm
```

### 3. Готовая конфигурация

См. полный пример конфигурации в `docs/nginx-config.conf`

---

## Применение миграций

```bash
# На сервере выполнить:
cd /var/www/sbor.team
php artisan migrate --force
```

## Очистка кэша

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan clear-compiled

# Перезапуск OPcache (если есть доступ)
sudo systemctl restart php8.3-fpm
# или
sudo service php8.3-fpm restart
```

## Или используйте скрипт clear-cache.php

Откройте в браузере: https://sbor.team/clear-cache.php

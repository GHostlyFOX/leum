# Инструкции по развёртыванию

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

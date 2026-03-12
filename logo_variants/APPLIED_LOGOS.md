# Применённые логотипы в проект

## 📁 Структура файлов

### Логотипы для шапки сайта
```
public/assets/images/brand/
├── logo-header.svg           # Основной логотип (светлая тема)
├── logo-header-light.svg     # Логотип для тёмной темы
├── logo-icon.svg             # Иконка без текста
└── logo.svg                  # Полный логотип с слоганом
```

### Favicon
```
public/assets/images/favicon/
├── favicon.svg               # Основной favicon (SVG)
└── apple-touch-icon.svg      # Иконка для iOS
```

## 🎨 Цветовая схема

- **Зелёный:** `#74bc1f` — основной акцентный цвет
- **Чёрный:** `#000000` — текст и детали
- **Белый:** `#ffffff` — фигуры игроков

## ✅ Выполненные изменения

### 1. Шапка сайта (app-header.blade.php)
- Обновлен логотип на SVG версию
- Высота логотипа: 50px
- Поддержка светлой и тёмной темы

### 2. Favicon (styles.blade.php, landing/styles.blade.php)
- Добавлен SVG favicon для современных браузеров
- Добавлен Apple Touch Icon для iOS
- Обновлён заголовок страницы

### 3. Темы
- **Светлая тема:** чёрный текст `sbor` + зелёный `.team`
- **Тёмная тема:** белый текст `sbor` + зелёный `.team`

## 📝 Примечание

Для полной совместимости со старыми браузерами (IE) рекомендуется создать ICO файл из SVG.
Можно использовать онлайн-конвертеры:
- https://convertio.ru/svg-ico/
- https://cloudconvert.com/svg-to-ico

Или команду ImageMagick:
```bash
convert favicon.svg favicon.ico
```

## 🖼️ Внешний вид

### Логотип в шапке:
```
🟢⚫⚪  sbor.team
👤👤👤
```

### Favicon (вкладка браузера):
```
🟢
⚫⚪⚪
```

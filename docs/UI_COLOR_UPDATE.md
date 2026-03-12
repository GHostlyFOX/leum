# Обновление цветовой гаммы интерфейса

## Анализ цветовой гаммы

На основе скриншота Settings и существующих шаблонов определена цветовая палитра:

| Роль | Цвет | HEX |
|------|------|-----|
| **Primary** | Зелёный | `#8fbd56` |
| **Primary Dark** | Тёмно-зелёный | `#6d9e3a` |
| **Primary Light** | Светло-зелёный | `#f0fdf4` |
| **Background** | Светло-серый | `#f8f9fa` |
| **Text** | Тёмно-серый | `#1f2937` |
| **Border** | Серый | `#e5e7eb` |

## Выполненные изменения

### 1. Главная страница (`resources/views/livewire/index.blade.php`)
- ✅ Onboarding header: `#6366f1` → `#8fbd56` (градиент)
- ✅ Step icon done: `#22c55e` → `#8fbd56`
- ✅ Step icon pending: `#9ca3af` → `#8fbd56` (иконка)
- ✅ Badge done: `#dcfce7/#16a34a` → `#f0fdf4/#6d9e3a`
- ✅ Done title: `#22c55e` → `#6d9e3a`
- ✅ Кнопки (invite, create-event): `#6366f1` → `#8fbd56`
- ✅ Hover кнопок: `#4f46e5` → `#6d9e3a`
- ✅ Outline кнопки: `#6366f1` → `#8fbd56`/`#6d9e3a`
- ✅ Hover outline: `#eef2ff` → `#f0fdf4`

### 2. Модальное окно приглашений (`resources/views/livewire/invite-modal.blade.php`)
- ✅ Tabs hover/active: `#6366f1` → `#8fbd56`
- ✅ Tabs underline: `#6366f1` → `#8fbd56`
- ✅ Input focus border: `#6366f1` → `#8fbd56`
- ✅ Input focus shadow: `rgba(99,102,241,0.1)` → `rgba(143,189,86,0.1)`
- ✅ Buttons (send, generate): `#6366f1` → `#8fbd56`
- ✅ Buttons hover: `#4f46e5` → `#6d9e3a`
- ✅ Disabled state: `#c7d2fe` → `#d1e7b7`

### 3. Страница присоединения (`resources/views/livewire/join-team.blade.php`)
- ✅ Card header gradient: `#6366f1/#8b5cf6` → `#8fbd56/#6d9e3a`
- ✅ Info icon: `#6366f1` → `#8fbd56`
- ✅ Join button: `#6366f1` → `#8fbd56`
- ✅ Join button hover: `#4f46e5` → `#6d9e3a`
- ✅ Disabled state: `#c7d2fe` → `#d1e7b7`
- ✅ Dashboard button: `#22c55e` → `#6d9e3a`

### 4. Лендинг (`resources/views/livewire/landing.blade.php`)
- ✅ Chart color: `#8b5cf6` → `#6d9e3a`

## Исключения

Лендинг (`header-main.blade.php`) уже использует правильную цветовую схему через CSS-переменные:
```css
--bs-primary: #8fbd56;
--primary-dark: #6d9e3a;
```

## Результат

Весь интерфейс теперь использует единую зелёную цветовую гамму, соответствующую:
- Логотипу (#74bc1f — близок к #8fbd56)
- Скриншоту Settings
- Спортивной тематике проекта

## Файлы для проверки

1. `/dashboard` — главная страница
2. `/join/{token}` — страница приглашения
3. Модальное окно приглашений (открыть через "Пригласить" на дашборде)
4. `/` — лендинг

---

*Обновлено: {{ date('Y-m-d') }}*

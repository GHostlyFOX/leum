# UI Guidelines — Дизайн-система sbor.team

## Цветовая палитра

### Основные цвета

| Цвет | HEX | Использование |
|------|-----|---------------|
| **Primary** | `#8fbd56` | Основной зелёный — кнопки, активные элементы, иконки |
| **Primary Dark** | `#6d9e3a` | Hover состояния, градиенты, акценты |
| **Primary Light** | `#f0fdf4` | Фон для выделенных блоков, hover на карточках |
| **Success** | `#22c55e` | Успешные действия (галочки, завершённые шаги) |
| **Background** | `#f8f9fa` | Фон страницы |
| **Card** | `#ffffff` | Фон карточек |
| **Text Primary** | `#1f2937` | Основной текст (заголовки) |
| **Text Secondary** | `#6b7280` | Вторичный текст (описания, подписи) |
| **Border** | `#e5e7eb` | Границы карточек, разделители |

### Цветовые CSS-переменные (для лендинга)

```css
:root {
    --bs-primary: #8fbd56;
    --bs-primary-rgb: 143, 189, 86;
    --primary-dark: #6d9e3a;
}
```

---

## Типографика

| Элемент | Размер | Вес | Цвет |
|---------|--------|-----|------|
| **H1** (Page Title) | 1.5rem (24px) | 700 | `#1f2937` |
| **H2** (Card Title) | 1.25rem (20px) | 600 | `#1f2937` |
| **H3** (Section) | 1.1rem (18px) | 600 | `#1f2937` |
| **Body** | 0.9rem (14px) | 400 | `#6b7280` |
| **Small** | 0.8rem (13px) | 400 | `#9ca3af` |
| **Label** | 0.88rem (14px) | 600 | `#374151` |

---

## Компоненты

### Кнопки

#### Primary Button
```css
.btn-primary-custom {
    background: #8fbd56;
    border: none;
    color: #fff;
    font-weight: 600;
    padding: 10px 22px;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: all 0.2s;
}
.btn-primary-custom:hover {
    background: #6d9e3a;
}
```

#### Outline Button
```css
.btn-outline-custom {
    background: #fff;
    border: 1.5px solid #8fbd56;
    color: #6d9e3a;
    font-weight: 600;
    padding: 8px 20px;
    border-radius: 10px;
}
.btn-outline-custom:hover {
    background: #f0fdf4;
}
```

#### Small Button
```css
.btn-sm-custom {
    padding: 6px 16px;
    font-size: 0.85rem;
    border-radius: 8px;
}
```

### Карточки

#### Стандартная карточка
```css
.card-custom {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 20px;
    transition: all 0.2s;
}
.card-custom:hover {
    border-color: #8fbd56;
    box-shadow: 0 4px 16px rgba(143, 189, 86, 0.08);
}
```

#### Карточка с тенью
```css
.card-shadow {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}
```

### Иконки

#### Иконка в круге (зелёная)
```css
.icon-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #8fbd56;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
}
```

#### Иконка в круге (светлая)
```css
.icon-circle-light {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f0fdf4;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #8fbd56;
}
```

### Статусные бейджи

```css
.badge-planned {
    background: #fef3c7;
    color: #92400e;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
}

.badge-active {
    background: #dcfce7;
    color: #16a34a;
    padding: 3px 10px;
    border-radius: 8px;
    font-size: 0.78rem;
    font-weight: 600;
}

.badge-done {
    background: #f0fdf4;
    color: #6d9e3a;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
}
```

---

## Формы

### Поля ввода

```css
.form-control-custom {
    border-radius: 10px;
    border: 1.5px solid #e5e7eb;
    padding: 10px 14px;
    font-size: 0.9rem;
}
.form-control-custom:focus {
    border-color: #8fbd56;
    box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.1);
}
```

### Чекбоксы

```css
input[type="checkbox"] {
    accent-color: #8fbd56;
}
```

---

## Layout

### Отступы

- **Card padding**: 20px-24px
- **Section gap**: 24px
- **Inner gap**: 12px-16px
- **Page padding**: 24px

### Grid

- Используем Bootstrap grid (row/col)
- Gap между карточками: 16px (g-3) или 24px (g-4)
- Карточки растягиваем на высоту (h-100)

---

## Градиенты

### Основной градиент (для шапок)
```css
.gradient-primary {
    background: linear-gradient(135deg, #8fbd56 0%, #6d9e3a 100%);
}
```

### Светлый градиент (для фонов)
```css
.gradient-light {
    background: linear-gradient(180deg, #f8faf5 0%, #fff 100%);
}
```

---

## Состояния

### Hover
- Кнопки: темнеют до `#6d9e3a`
- Карточки: рамка `#8fbd56`, тень
- Ссылки: цвет `#6d9e3a`

### Active
- Кнопки: ещё темнее или инсет-тень
- Меню: заливка `#8fbd56`, белый текст

### Disabled
- Opacity: 0.6
- Курсор: not-allowed

---

## Принципы

1. **Консистентность** — используем только цвета из палитры
2. **Иерархия** — Primary для главных действий, Outline для второстепенных
3. **Отступы** — используем кратные 4px (4, 8, 12, 16, 20, 24)
4. **Скругление** — 8px-12px для кнопок, 12px-16px для карточек
5. **Тени** — мягкие (0 4px 20px rgba(0,0,0,0.05)), не резкие

---

## Примеры использования

### Онбординг-шаг
```html
<div class="onboarding-step" style="{{ $done ? 'background: #f0fdf4;' : '' }}">
    <div class="step-icon {{ $done ? 'done' : 'pending' }}">
        <!-- иконка -->
    </div>
    <div class="step-body">
        <h6 class="{{ $done ? 'done-title' : '' }}">
            Заголовок шага
            @if($done)<span class="badge-done">Готово!</span>@endif
        </h6>
        <p>Описание шага</p>
    </div>
</div>
```

### Карточка команды
```html
<a href="#" class="team-card">
    <div class="team-avatar" style="background: #8fbd56;">А</div>
    <div class="team-info">
        <h6>Название команды</h6>
        <small>Описание</small>
    </div>
</a>
```

---

## Иконки (SVG)

Используем иконки из набора Feather Icons (24x24, stroke-width="2"):

```html
<!-- Пример иконки -->
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <polyline points="20 6 9 17 4 12"></polyline>
</svg>
```

---

*Последнее обновление: {{ date('Y-m-d') }}*

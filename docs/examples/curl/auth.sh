#!/bin/bash
# ============================================
# Аутентификация — примеры curl
# ============================================

BASE_URL="https://api.sbor.team/api/v1"
# BASE_URL="http://localhost:8000/api/v1"  # для локальной разработки

echo "=== Регистрация ==="
curl -X POST "${BASE_URL}/auth/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Иван",
    "last_name": "Петров",
    "email": "ivan@example.com",
    "password": "securePassword123",
    "password_confirmation": "securePassword123",
    "birth_date": "2010-05-15",
    "gender": "male",
    "role": "player"
  }'

echo -e "\n\n=== Вход ==="
curl -X POST "${BASE_URL}/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "login": "ivan@example.com",
    "password": "securePassword123"
  }'

# Сохраняем токен для последующих запросов
TOKEN="YOUR_ACCESS_TOKEN_HERE"

echo -e "\n\n=== Получить текущего пользователя ==="
curl -X GET "${BASE_URL}/me" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Обновление токена ==="
curl -X POST "${BASE_URL}/auth/refresh" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "refresh_token": "YOUR_REFRESH_TOKEN_HERE"
  }'

echo -e "\n\n=== Выход ==="
curl -X POST "${BASE_URL}/auth/logout" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Запрос сброса пароля ==="
curl -X POST "${BASE_URL}/auth/forgot-password" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "ivan@example.com"
  }'

echo -e "\n\n=== Сброс пароля ==="
curl -X POST "${BASE_URL}/auth/reset-password" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "ivan@example.com",
    "token": "RESET_TOKEN_FROM_EMAIL",
    "password": "newSecurePassword123",
    "password_confirmation": "newSecurePassword123"
  }'

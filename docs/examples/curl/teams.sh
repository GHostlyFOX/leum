#!/bin/bash
# ============================================
# Команды — примеры curl
# ============================================

BASE_URL="https://api.sbor.team/api/v1"
TOKEN="YOUR_ACCESS_TOKEN_HERE"

echo "=== Список команд клуба ==="
curl -X GET "${BASE_URL}/clubs/1/teams" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Создание команды ==="
curl -X POST "${BASE_URL}/clubs/1/teams" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "U-12 Мальчики",
    "description": "Основная команда подготовки U-12",
    "gender": "boys",
    "birth_year": 2012,
    "sport_type_id": 1
  }'

echo -e "\n\n=== Получение команды по ID ==="
curl -X GET "${BASE_URL}/teams/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Обновление команды ==="
curl -X PUT "${BASE_URL}/teams/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "U-12 Мальчики (обновлено)",
    "birth_year": 2013
  }'

echo -e "\n\n=== Удаление команды ==="
curl -X DELETE "${BASE_URL}/teams/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Добавление участника в команду ==="
curl -X POST "${BASE_URL}/teams/1/members" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "user_id": 5,
    "club_id": 1,
    "role_id": 1,
    "joined_at": "2024-01-15"
  }'

#!/bin/bash
# ============================================
# Справочники — примеры curl
# ============================================
# Публичные endpoint — не требуют авторизации

BASE_URL="https://api.squadup.ru/api/v1"

echo "=== Виды спорта ==="
curl -X GET "${BASE_URL}/refs/sport-types" \
  -H "Accept: application/json"

echo -e "\n\n=== Типы клубов ==="
curl -X GET "${BASE_URL}/refs/club-types" \
  -H "Accept: application/json"

echo -e "\n\n=== Роли пользователей ==="
curl -X GET "${BASE_URL}/refs/user-roles" \
  -H "Accept: application/json"

echo -e "\n\n=== Позиции игроков ==="
curl -X GET "${BASE_URL}/refs/positions?sport_type_id=1" \
  -H "Accept: application/json"

echo -e "\n\n=== Варианты ведущей ноги ==="
curl -X GET "${BASE_URL}/refs/dominant-feet" \
  -H "Accept: application/json"

echo -e "\n\n=== Типы родства ==="
curl -X GET "${BASE_URL}/refs/kinship-types" \
  -H "Accept: application/json"

echo -e "\n\n=== Типы событий матча ==="
curl -X GET "${BASE_URL}/refs/match-event-types" \
  -H "Accept: application/json"

echo -e "\n\n=== Страны ==="
curl -X GET "${BASE_URL}/refs/countries" \
  -H "Accept: application/json"

echo -e "\n\n=== Города ==="
curl -X GET "${BASE_URL}/refs/cities?country_id=1&search=Москва" \
  -H "Accept: application/json"

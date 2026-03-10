#!/bin/bash
# ============================================
# Матчи — примеры curl
# ============================================

BASE_URL="https://api.squadup.ru/api/v1"
TOKEN="YOUR_ACCESS_TOKEN_HERE"

echo "=== Список матчей ==="
curl -X GET "${BASE_URL}/matches?team_id=1&date_from=2024-03-01" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Создание матча ==="
curl -X POST "${BASE_URL}/matches" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_team_id": 1,
    "away_team_id": 2,
    "match_date": "2024-03-20",
    "start_time": "15:00",
    "venue_id": 1,
    "tournament_id": null,
    "referee_id": null,
    "notes": "Финальный матч группы"
  }'

echo -e "\n\n=== Получение матча по ID ==="
curl -X GET "${BASE_URL}/matches/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Начало матча ==="
curl -X POST "${BASE_URL}/matches/1/start" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Добавление события матча (гол) ==="
curl -X POST "${BASE_URL}/matches/1/events" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "event_type_id": 1,
    "match_minute": 23,
    "player_user_id": 10,
    "assistant_user_id": 11
  }'

echo -e "\n\n=== Добавление события матча (карточка) ==="
curl -X POST "${BASE_URL}/matches/1/events" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "event_type_id": 4,
    "match_minute": 45,
    "player_user_id": 10
  }'

echo -e "\n\n=== Установка состава ==="
curl -X PUT "${BASE_URL}/matches/1/lineup" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "players": [
      {
        "player_user_id": 10,
        "position_id": 1,
        "is_starter": true,
        "shirt_number": 7
      },
      {
        "player_user_id": 11,
        "position_id": 2,
        "is_starter": true,
        "shirt_number": 10
      },
      {
        "player_user_id": 12,
        "position_id": 3,
        "is_starter": false,
        "shirt_number": 15
      }
    ]
  }'

echo -e "\n\n=== Завершение матча ==="
curl -X POST "${BASE_URL}/matches/1/end" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "home_team_goals": 2,
    "away_team_goals": 1
  }'

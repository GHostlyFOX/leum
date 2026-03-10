#!/bin/bash
# ============================================
# Тренировки — примеры curl
# ============================================

BASE_URL="https://api.squadup.ru/api/v1"
TOKEN="YOUR_ACCESS_TOKEN_HERE"

echo "=== Список тренировок ==="
curl -X GET "${BASE_URL}/trainings?team_id=1&date_from=2024-03-01&date_to=2024-03-31" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Создание тренировки ==="
curl -X POST "${BASE_URL}/trainings" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "coach_id": 2,
    "club_id": 1,
    "team_id": 3,
    "training_date": "2024-03-15",
    "start_time": "15:30",
    "duration_minutes": 90,
    "venue_id": 1,
    "training_type_id": 1,
    "notes": "Техническая подготовка",
    "notify_parents": true,
    "require_rsvp": true
  }'

echo -e "\n\n=== Получение тренировки по ID ==="
curl -X GET "${BASE_URL}/trainings/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Обновление тренировки ==="
curl -X PUT "${BASE_URL}/trainings/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "start_time": "16:00",
    "venue_id": 2
  }'

echo -e "\n\n=== Отмена тренировки ==="
curl -X POST "${BASE_URL}/trainings/1/cancel" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "cancellation_reason": "Болезнь основного тренера"
  }'

echo -e "\n\n=== Отметка посещаемости ==="
curl -X PATCH "${BASE_URL}/trainings/1/attendance/5" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "attendance_status": "present"
  }'

echo -e "\n\n=== Отметка посещаемости — отсутствие с причиной ==="
curl -X PATCH "${BASE_URL}/trainings/1/attendance/5" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "attendance_status": "absent",
    "absence_reason": "Болезнь"
  }'

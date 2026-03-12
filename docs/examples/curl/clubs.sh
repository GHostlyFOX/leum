#!/bin/bash
# ============================================
# Клубы — примеры curl
# ============================================

BASE_URL="https://api.sbor.team/api/v1"
TOKEN="YOUR_ACCESS_TOKEN_HERE"

echo "=== Список клубов ==="
curl -X GET "${BASE_URL}/clubs?page=1&per_page=15" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Список клубов с фильтром ==="
curl -X GET "${BASE_URL}/clubs?search=ЦСКА&sport_type_id=1&country_id=1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Создание клуба ==="
curl -X POST "${BASE_URL}/clubs" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: multipart/form-data" \
  -H "Accept: application/json" \
  -F "name=ЦСКА Москва" \
  -F "description=Спортивный клуб по футболу" \
  -F "sport_type_id=1" \
  -F "club_type_id=1" \
  -F "country_id=1" \
  -F "city_id=1" \
  -F "address=ул. Ленина, 1" \
  -F "email=info@cska.ru" \
  -F "phones[]=+74951234567" \
  -F "phones[]=+74959876543"

echo -e "\n\n=== Получение клуба по ID ==="
curl -X GET "${BASE_URL}/clubs/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

echo -e "\n\n=== Обновление клуба ==="
curl -X PUT "${BASE_URL}/clubs/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Content-Type: multipart/form-data" \
  -H "Accept: application/json" \
  -F "name=ЦСКА Москва (обновлено)" \
  -F "description=Обновленное описание"

echo -e "\n\n=== Удаление клуба ==="
curl -X DELETE "${BASE_URL}/clubs/1" \
  -H "Authorization: Bearer ${TOKEN}" \
  -H "Accept: application/json"

#!/bin/bash
# Скрипт для исправления Livewire файлов - добавление корневого div

LIVEWIRE_DIR="resources/views/livewire"

echo "Fixing Livewire files for Livewire 3 compatibility..."

for file in $LIVEWIRE_DIR/*.php; do
    filename=$(basename "$file")
    
    # Проверяем, начинается ли файл с @extends
    if head -1 "$file" | grep -q "^@extends"; then
        # Проверяем, есть ли уже div сразу после @section('content')
        if ! grep -A 1 "@section('content')" "$file" | grep -q "<div"; then
            echo "Fixing: $filename"
            
            # Добавляем <div> после @section('content')
            sed -i "s/@section('content')/@section('content')\n<div>/g" "$file"
            
            # Добавляем </div> перед @endsection (перед @section('scripts'))
            sed -i "s/@endsection[[:space:]]*@section('scripts')/<\/div>\n@endsection\n@section('scripts')/g" "$file"
        fi
    fi
done

echo "Done!"

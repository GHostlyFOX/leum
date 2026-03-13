<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title" style="font-size: 1.5rem; font-weight: 700; color: #1f2937;">
                Импорт игроков
            </h2>
            <p class="text-secondary" style="color: #6b7280;">
                Массовая загрузка игроков из CSV/Excel файла
            </p>
        </div>
    </div>

    <!-- Выбор команды -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <label class="form-label" style="font-weight: 600; color: #374151; margin-bottom: 8px;">
                    Выберите команду <span style="color: #ef4444;">*</span>
                </label>
                <select wire:model="teamId" class="form-control-custom" style="width: 100%; border-radius: 10px; border: 1.5px solid #e5e7eb; padding: 10px 14px;">
                    <option value="">-- Выберите команду --</option>
                    @foreach($userTeams as $team)
                        <option value="{{ $team['id'] }}">{{ $team['name'] }} ({{ $team['club'] }})</option>
                    @endforeach
                </select>
                @error('teamId')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 4px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Шаблон и инструкции -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom" style="background: #f0fdf4; border: 1px solid #8fbd56; border-radius: 14px; padding: 20px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="icon-circle-light" style="width: 40px; height: 40px; border-radius: 50%; background: #f0fdf4; display: flex; align-items: center; justify-content: center; color: #8fbd56; margin-right: 12px;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                    </div>
                    <h5 style="margin: 0; color: #1f2937; font-weight: 600;">Шаблон для заполнения</h5>
                </div>
                <p style="color: #6b7280; margin-bottom: 16px;">
                    Скачайте шаблон, заполните данные игроков и загрузите файл обратно.<br>
                    <strong style="color: #8fbd56;">Обязательные поля:</strong> Фамилия, Имя
                </p>
                <button wire:click="downloadTemplate" class="btn-outline-custom" style="background: #fff; border: 1.5px solid #8fbd56; color: #6d9e3a; font-weight: 600; padding: 8px 20px; border-radius: 10px; cursor: pointer;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    Скачать шаблон CSV
                </button>
            </div>
        </div>
    </div>

    <!-- Загрузка файла -->
    @if(!$importResult)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <label class="form-label" style="font-weight: 600; color: #374151; margin-bottom: 12px; display: block;">
                    Загрузите файл
                </label>
                
                <div 
                    x-data="{ isUploading: false }"
                    x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false"
                    class="upload-area"
                    style="border: 2px dashed #e5e7eb; border-radius: 12px; padding: 40px; text-align: center; transition: all 0.2s;"
                    ondragover="this.style.borderColor='#8fbd56'; this.style.background='#f0fdf4';"
                    ondragleave="this.style.borderColor='#e5e7eb'; this.style.background='transparent';"
                >
                    <input 
                        type="file" 
                        wire:model="importFile" 
                        accept=".csv,.xlsx,.xls"
                        style="display: none;"
                        id="fileInput"
                    >
                    
                    <div onclick="document.getElementById('fileInput').click()" style="cursor: pointer;">
                        <div class="icon-circle" style="width: 60px; height: 60px; border-radius: 50%; background: #8fbd56; display: flex; align-items: center; justify-content: center; color: #fff; margin: 0 auto 16px;">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <p style="color: #1f2937; font-weight: 600; margin-bottom: 8px;">
                            Нажмите или перетащите файл сюда
                        </p>
                        <p style="color: #6b7280; font-size: 0.875rem; margin: 0;">
                            Поддерживаются форматы: CSV, Excel (.xlsx, .xls)<br>
                            Максимальный размер: 5 МБ
                        </p>
                    </div>

                    <div x-show="isUploading" style="margin-top: 16px;">
                        <div style="width: 100%; height: 4px; background: #e5e7eb; border-radius: 2px; overflow: hidden;">
                            <div style="width: 100%; height: 100%; background: #8fbd56; animation: pulse 1s infinite;"></div>
                        </div>
                        <p style="color: #6b7280; font-size: 0.875rem; margin-top: 8px;">Загрузка...</p>
                    </div>
                </div>

                @if($importFile)
                <div style="margin-top: 16px; padding: 12px; background: #f0fdf4; border-radius: 8px; display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#8fbd56" stroke-width="2" style="margin-right: 8px;">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <span style="color: #1f2937;">{{ $importFile->getClientOriginalName() }}</span>
                    </div>
                    <button wire:click="$set('importFile', null)" style="background: none; border: none; color: #6b7280; cursor: pointer;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
                @endif

                @error('importFile')
                    <div style="color: #ef4444; font-size: 0.875rem; margin-top: 12px;">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
    @endif

    <!-- Предпросмотр -->
    @if($showPreview && !empty($previewData))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <h5 style="color: #1f2937; font-weight: 600; margin-bottom: 16px;">Предпросмотр данных</h5>
                <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 16px;">
                    Первые 5 строк из файла:
                </p>
                
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead>
                            <tr style="background: #f0fdf4;">
                                @foreach($previewData[0] ?? [] as $header)
                                    <th style="border: 1px solid #8fbd56; padding: 8px; text-align: left; font-weight: 600;">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(array_slice($previewData, 1) as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td style="border: 1px solid #e5e7eb; padding: 8px;">{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 20px; display: flex; gap: 12px;">
                    <button 
                        wire:click="importPlayers" 
                        wire:loading.attr="disabled"
                        class="btn-primary-custom"
                        style="background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer;"
                    >
                        <span wire:loading.remove wire:target="importPlayers">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 6px;">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Импортировать
                        </span>
                        <span wire:loading wire:target="importPlayers">
                            Импорт...
                        </span>
                    </button>
                    <button 
                        wire:click="resetImport"
                        class="btn-outline-custom"
                        style="background: #fff; border: 1.5px solid #e5e7eb; color: #6b7280; font-weight: 600; padding: 8px 20px; border-radius: 10px; cursor: pointer;"
                    >
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Результат импорта -->
    @if($importResult)
    <div class="row">
        <div class="col-12">
            <div class="card-custom" style="background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; padding: 20px;">
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-circle" style="width: 50px; height: 50px; border-radius: 50%; background: #8fbd56; display: flex; align-items: center; justify-content: center; color: #fff; margin-right: 16px;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin: 0; color: #1f2937; font-weight: 600;">Импорт завершен</h4>
                        <p style="margin: 4px 0 0 0; color: #6b7280;">
                            Всего строк: {{ $importResult['total'] }} | 
                            Успешно: <span style="color: #22c55e; font-weight: 600;">{{ count($importResult['success']) }}</span> | 
                            Ошибок: <span style="color: #ef4444; font-weight: 600;">{{ count($importResult['errors']) }}</span>
                        </p>
                    </div>
                </div>

                @if(!empty($importResult['success']))
                <div style="margin-bottom: 20px;">
                    <h6 style="color: #22c55e; font-weight: 600; margin-bottom: 12px;">
                        Успешно импортированы:
                    </h6>
                    <div style="max-height: 200px; overflow-y: auto; background: #f8f9fa; border-radius: 8px; padding: 12px;">
                        @foreach($importResult['success'] as $item)
                            <div style="padding: 4px 0; border-bottom: 1px solid #e5e7eb;">
                                <span style="color: #6b7280; font-size: 0.875rem;">Строка {{ $item['row'] }}:</span>
                                <span style="color: #1f2937; font-weight: 500;">{{ $item['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($importResult['errors']))
                <div style="margin-bottom: 20px;">
                    <h6 style="color: #ef4444; font-weight: 600; margin-bottom: 12px;">
                        Ошибки импорта:
                    </h6>
                    <div style="max-height: 200px; overflow-y: auto; background: #fef2f2; border-radius: 8px; padding: 12px;">
                        @foreach($importResult['errors'] as $error)
                            <div style="padding: 8px 0; border-bottom: 1px solid #fecaca;">
                                <span style="color: #dc2626; font-size: 0.875rem; font-weight: 500;">Строка {{ $error['row'] }}:</span>
                                <ul style="margin: 4px 0 0 0; padding-left: 20px; color: #7f1d1d;">
                                    @foreach($error['errors'] as $msg)
                                        <li>{{ $msg }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div style="display: flex; gap: 12px;">
                    <a href="/club/team/{{ $teamId }}" class="btn-primary-custom" style="background: #8fbd56; border: none; color: #fff; font-weight: 600; padding: 10px 22px; border-radius: 10px; text-decoration: none; display: inline-block;">
                        Перейти к команде
                    </a>
                    <button wire:click="resetImport" class="btn-outline-custom" style="background: #fff; border: 1.5px solid #8fbd56; color: #6d9e3a; font-weight: 600; padding: 8px 20px; border-radius: 10px; cursor: pointer;">
                        Загрузить еще файл
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .btn-primary-custom:hover {
            background: #6d9e3a !important;
        }
        .btn-outline-custom:hover {
            background: #f0fdf4 !important;
        }
    </style>
</div>

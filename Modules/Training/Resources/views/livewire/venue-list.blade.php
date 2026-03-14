<div>
    <style>
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #111827;
            margin: 0;
        }
        .btn-primary {
            background: #8fbd56;
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary:hover {
            background: #6d9e3a;
            color: #fff;
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            padding: 12px 16px;
            width: 100%;
            box-sizing: border-box;
        }
        .form-control:focus {
            border-color: #8fbd56;
            box-shadow: 0 0 0 3px rgba(143, 189, 86, 0.1);
            outline: none;
        }
        .create-form {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        .form-actions {
            display: flex;
            gap: 12px;
        }
        .btn-cancel {
            background: #f3f4f6;
            border: none;
            color: #6b7280;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            cursor: pointer;
        }
        .btn-cancel:hover {
            background: #e5e7eb;
        }
        .venue-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .venue-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #8fbd56;
            position: relative;
        }
        .venue-name {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            margin: 0 0 12px 0;
        }
        .venue-info {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }
        .venue-address {
            color: #6b7280;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .venue-city {
            color: #9ca3af;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .venue-actions {
            display: flex;
            gap: 8px;
        }
        .btn-delete {
            background: #fee2e2;
            border: none;
            color: #dc2626;
            font-weight: 600;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .btn-delete:hover {
            background: #fecaca;
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .empty-icon {
            font-size: 48px;
            color: #d1d5db;
            margin-bottom: 16px;
        }
        .empty-text {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 24px;
        }
    </style>

    <div class="page-header">
        <div>
            <h1 class="page-title">Площадки</h1>
        </div>
        <div>
            <button wire:click="toggleCreateForm" class="btn-primary">
                <i class="fe fe-plus"></i> Добавить площадку
            </button>
        </div>
    </div>

    <!-- Create Form -->
    @if($showCreateForm)
        <div class="create-form">
            <h5 style="font-weight: 700; margin-bottom: 16px;">Новая площадка</h5>
            
            <div class="form-grid">
                <div>
                    <label class="form-label">Название <span style="color: #dc2626;">*</span></label>
                    <input type="text" wire:model="newVenueName" class="form-control" placeholder="Введите название">
                </div>
                <div>
                    <label class="form-label">Адрес <span style="color: #dc2626;">*</span></label>
                    <input type="text" wire:model="newVenueAddress" class="form-control" placeholder="Введите адрес">
                </div>
            </div>

            <div class="form-actions">
                <button wire:click="createVenue" class="btn-primary">
                    <i class="fe fe-save"></i> Сохранить
                </button>
                <button wire:click="toggleCreateForm" class="btn-cancel">
                    <i class="fe fe-x"></i> Отмена
                </button>
            </div>
        </div>
    @endif

    <!-- Venues List -->
    @if(count($venues) > 0)
        <div class="venue-grid">
            @foreach($venues as $venue)
                <div class="venue-card">
                    <h3 class="venue-name">{{ $venue['name'] }}</h3>
                    
                    <div class="venue-info">
                        <div class="venue-address">
                            <i class="fe fe-map-pin"></i>
                            <span>{{ $venue['address'] }}</span>
                        </div>
                        @if(!empty($venue['city']))
                            <div class="venue-city">
                                <i class="fe fe-home"></i>
                                <span>{{ $venue['city'] }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="venue-actions">
                        <button 
                            wire:click="deleteVenue({{ $venue['id'] }})" 
                            wire:confirm="Вы уверены, что хотите удалить эту площадку?"
                            class="btn-delete">
                            <i class="fe fe-trash-2"></i> Удалить
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fe fe-inbox"></i>
            </div>
            <p class="empty-text">Площадки ещё не добавлены</p>
            <button wire:click="toggleCreateForm" class="btn-primary">
                <i class="fe fe-plus"></i> Добавить первую площадку
            </button>
        </div>
    @endif
</div>

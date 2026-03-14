@extends('club::layouts.master')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('club.index') }}">Клуб</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('club.teams') }}">Команды</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('club.team.show', $team->id) }}">{{ $team->name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Редактирование</li>
                </ol>
            </nav>
            <h1 class="page-title fw-bold">Редактирование команды</h1>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('club.team.show', $team->id) }}" class="btn btn-outline-secondary">
                <i class="fe fe-arrow-left me-1"></i> Назад
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Основная информация</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('club.team.update', $team->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Название команды <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $team->name) }}"
                                   placeholder="Например: Столица 2015"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_year" class="form-label fw-semibold">Год рождения <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control @error('birth_year') is-invalid @enderror"
                                       id="birth_year"
                                       name="birth_year"
                                       value="{{ old('birth_year', $team->birth_year) }}"
                                       min="2000"
                                       max="{{ date('Y') }}"
                                       required>
                                @error('birth_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label fw-semibold">Пол <span class="text-danger">*</span></label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender"
                                        name="gender"
                                        required>
                                    <option value="boys" {{ old('gender', $team->gender) === 'boys' ? 'selected' : '' }}>Мальчики</option>
                                    <option value="girls" {{ old('gender', $team->gender) === 'girls' ? 'selected' : '' }}>Девочки</option>
                                    <option value="mixed" {{ old('gender', $team->gender) === 'mixed' ? 'selected' : '' }}>Смешанная</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="team_color" class="form-label fw-semibold">Цвет команды</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color"
                                       class="form-control form-control-color @error('team_color') is-invalid @enderror"
                                       id="team_color"
                                       name="team_color"
                                       value="{{ old('team_color', $team->team_color ?? '#8fbd56') }}"
                                       title="Выберите цвет команды">
                                <div class="rounded-3 d-flex align-items-center justify-content-center"
                                     id="color-preview"
                                     style="width: 48px; height: 48px; background: {{ $team->team_color ?? '#8fbd56' }};">
                                    <span class="text-white fw-bold fs-5">{{ mb_strtoupper(mb_substr($team->name, 0, 1)) }}</span>
                                </div>
                                <small class="text-muted">Предпросмотр аватара команды</small>
                            </div>
                            @error('team_color')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('club.team.show', $team->id) }}" class="btn btn-outline-secondary">
                                Отмена
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fe fe-save me-1"></i> Сохранить изменения
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Side info -->
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0">Информация</h5>
                </div>
                <div class="card-body px-4">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Клуб</small>
                        <span class="fw-semibold">{{ $team->club?->name ?? 'Не указан' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">ID команды</small>
                        <span class="fw-semibold">#{{ $team->id }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Создана</small>
                        <span class="fw-semibold">{{ $team->created_at?->format('d.m.Y') ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Обновляем превью цвета при выборе
    document.getElementById('team_color').addEventListener('input', function(e) {
        document.getElementById('color-preview').style.background = e.target.value;
    });
</script>
@endpush
@endsection

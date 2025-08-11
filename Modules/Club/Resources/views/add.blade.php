@extends('club::layouts.master')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Команды</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Клуб</li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Добавить клуб</a></li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body project-type-container projects p-4">
                            <h4 class="m-0" id="typeTitle">Добавить клуб</h4>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Показываем общие системные ошибки --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('club-save') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                @csrf
                <div class="row row-sm">
                    <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12">
                        <div class="card box-shadow-0">
                            <div class="card-header border-bottom">
                                <h3 class="card-title"><b>Информация о клубе</b></h3>
                            </div>
                            <div class="card-body project-list-table-container">
                                <div class="form-group">
                                    <label for="name" class="form-label"><b>Наименование клуба</b> <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" required placeholder="Наименование клуба">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="logo" class="form-label"><b>Логотип (изображение)</b></label>
                                    <input class="form-control file-input @error('logo') is-invalid @enderror" type="file" name="logo" id="logo" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label for="ref_type_club" class="form-label"><b>Вид клуба</b> <span class="text-danger">*</span></label>
                                    <select
                                        name="ref_type_club"
                                        id="ref_type_club"
                                        class="form-select @error('ref_type_club') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">— Выберите тип клуба —</option>
                                        @foreach($typeClubs as $typeClub)
                                            <option
                                                value="{{ $typeClub->id }}"
                                                @if(old('ref_type_club') == $typeClub->id || count($typeClubs) == 1) selected @endif
                                            >
                                                {{ $typeClub->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ref_type_club')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="ref_type_sport" class="form-label"><b>Вид спорта</b> <span class="text-danger">*</span></label>
                                    <select
                                        name="ref_type_sport"
                                        id="ref_type_sport"
                                        class="form-select @error('ref_type_sport') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">— Выберите вид спорта —</option>
                                        @foreach($typeSports as $typeSport)
                                            <option
                                                value="{{ $typeSport->id }}"
                                                @if(old('ref_type_sport') == $typeSport->id || count($typeSports) == 1) selected @endif
                                            >
                                                {{ $typeSport->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ref_type_sport')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-label"><b>Описание или слоган клуба</b></label>
                                    <div class="ql-wrapper ql-wrapper-demo">
                                        <div id="quillEditor">

                                        </div>
                                    </div>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12">
                        <div class="card box-shadow-0">
                            <div class="card-header border-bottom">
                                <h3 class="card-title"><b>Адрес и контакты</b></h3>
                            </div>
                            <div class="card-body project-list-table-container">
                                <div class="form-group">
                                    <label for="country" class="form-label"><b>Страна</b> <span class="text-danger">*</span></label>
                                    <select
                                        name="country"
                                        id="country"
                                        class="form-select @error('country') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">— Выберите страну —</option>
                                        @foreach($countries as $country)
                                            <option
                                                value="{{ $country->id }}"
                                                @if(old('country') == $country->id || count($countries) == 1) selected @endif
                                            >
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="city" class="form-label"><b>Город</b> <span class="text-danger">*</span></label>
                                    <select
                                        name="city"
                                        id="city"
                                        class="form-select @error('city') is-invalid @enderror"
                                        required
                                    >
                                        <option value="">— Выберите город —</option>
                                        @foreach($cities as $city)
                                            <option
                                                value="{{ $city->id }}"
                                                @if(old('city') == $city->id) selected @endif
                                            >
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address" class="form-label"><b>Адрес</b> <span class="text-danger">*</span></label>
                                    <input
                                        type="text"
                                        name="address"
                                        id="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address') }}"
                                        required
                                    >
                                    @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label"><b>E-Mail</b></label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}"
                                    >
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="input-group my-1">
                                        <div class="input-group-text bg-primary-transparent text-primary">
                                            Телефон:
                                        </div>
                                        @if (old('phones', '') != '')
                                            @foreach(old('phones', '') as $phone)
                                                <input class="form-control @error('phones') is-invalid @enderror" value="{{ $phone }}" name="phones[]" id="phoneMask" placeholder="(000) 000-00-00" type="text">
                                            @endforeach
                                        @else
                                            <input class="form-control @error('phones') is-invalid @enderror" value="" name="phones[]" id="phoneMask" placeholder="(000) 000-00-00" type="text">
                                        @endif
                                    </div>
                                    @error('phones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mt-3">
                                    <div>
                                        <button type="submit" class="btn btn-primary">Добавить</button>
                                        <a href="{{route('home')}}" class="btn btn-secondary">Отменить</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @section('scripts')
        <script src="{{asset('assets/plugins/quill/quill.min.js')}}"></script>
        <script src="{{asset('assets/js/form-editor2.js')}}"></script>
        <script src="{{asset('assets/plugins/input-mask/jquery.mask.min.js')}}"></script>
        <script src="{{asset('assets/js/form-elements.js')}}"></script>
    @endsection
@endsection

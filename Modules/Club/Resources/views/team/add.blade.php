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
            <div class="row row-sm">
                <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12">
                    <div class="card box-shadow-0">
                        <div class="card-header border-bottom">
                            <h3 class="card-title"><b>Информация о клубе</b></h3>
                        </div>
                        <div class="card-body project-list-table-container">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Наименование клуба</b></label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Наименование клуба">
                                </div>
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Логотип</b></label>
                                    <input class="form-control file-input" type="file" id="formFile">
                                </div>
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Вид клуба</b></label>
                                    <select class="form-select">
                                        <option value="1">Футбол</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Описание или слоган клуба</b></label>
                                    <div class="ql-wrapper ql-wrapper-demo">
                                        <div id="quillEditor">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-6 col-md-12 col-sm-12">
                    <div class="card box-shadow-0">
                        <div class="card-header border-bottom">
                            <h3 class="card-title"><b>Адрес и контакты</b></h3>
                        </div>
                        <div class="card-body project-list-table-container">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Страна</b></label>
                                    <input type="text" class="form-control" id="inputName" placeholder="Наименование клуба">
                                </div>
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Город</b></label>
                                    <input class="form-control file-input" type="file" id="formFile">
                                </div>
                                <div class="form-group">
                                    <label for="formFile" class="form-label"><b>Адрес</b></label>
                                    <input type="text" class="form-control" id="inputEmail2" placeholder="Адрес">
                                </div>
                                <div class="form-group">
                                    <label for="formFile" class="form-label">E-Mail</label>
                                    <input type="email" class="form-control" id="inputEmail2" placeholder="E-Mail">
                                </div>
                                <div class="form-group">
                                    <div class="input-group my-1">
                                        <div class="input-group-text bg-primary-transparent text-primary">
                                            Телефон:
                                        </div>
                                        <input class="form-control" id="phoneMask" placeholder="(000) 000-00-00" type="text">
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <div>
                                        <button type="submit" class="btn btn-primary">Добавить</button>
                                        <a href="{{route('home')}}" class="btn btn-secondary">Отменить</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    <script src="{{asset('assets/plugins/quill/quill.min.js')}}"></script>
    <script src="{{asset('assets/js/form-editor2.js')}}"></script>
    <script src="{{asset('assets/plugins/input-mask/jquery.mask.min.js')}}"></script>
    <script src="{{asset('assets/js/form-elements.js')}}"></script>
    @endsection
@endsection

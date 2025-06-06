@extends('club::layouts.master')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Клубы</h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Клуб</li>
                <li class="breadcrumb-item"><a href="javascript:void(0);">Список клубов</a></li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body project-type-container projects p-4">
                            <h4 class="m-0" id="typeTitle">Все клубы</h4>
                            <div class="d-sm-flex align-items-center">
                                <div class="btn-list mx-4">
                                    <a class="btn btn-primary" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn text-white" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M21.5,13h-8.0005493C13.2234497,13.0001831,12.9998169,13.223999,13,13.5v8.0005493C13.0001831,21.7765503,13.223999,22.0001831,13.5,22h8.0006104C21.7765503,21.9998169,22.0001831,21.776001,22,21.5v-8.0006104C21.9998169,13.2234497,21.776001,12.9998169,21.5,13z M21,21h-7v-7h7V21z M10.5,2H2.4993896C2.2234497,2.0001831,1.9998169,2.223999,2,2.5v8.0005493C2.0001831,10.7765503,2.223999,11.0001831,2.5,11h8.0006104C10.7765503,10.9998169,11.0001831,10.776001,11,10.5V2.4993896C10.9998169,2.2234497,10.776001,1.9998169,10.5,2z M10,10H3V3h7V10z M10.5,13H2.4993896C2.2234497,13.0001831,1.9998169,13.223999,2,13.5v8.0005493C2.0001831,21.7765503,2.223999,22.0001831,2.5,22h8.0006104C10.7765503,21.9998169,11.0001831,21.776001,11,21.5v-8.0006104C10.9998169,13.2234497,10.776001,12.9998169,10.5,13z M10,21H3v-7h7V21z M21.5,2h-8.0005493C13.2234497,2.0001831,12.9998169,2.223999,13,2.5v8.0005493C13.0001831,10.7765503,13.223999,11.0001831,13.5,11h8.0006104C21.7765503,10.9998169,22.0001831,10.776001,22,10.5V2.4993896C21.9998169,2.2234497,21.776001,1.9998169,21.5,2z M21,10h-7V3h7V10z"></path></svg>
                                    </a>
                                    <a class="btn btn-outline-primary" href="{{route('club-list')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M3.5,12C3.223877,12,3,12.223877,3,12.5S3.223877,13,3.5,13S4,12.776123,4,12.5S3.776123,12,3.5,12z M6.5,8h15C21.776123,8,22,7.776123,22,7.5S21.776123,7,21.5,7h-15C6.223877,7,6,7.223877,6,7.5S6.223877,8,6.5,8z M3.5,17C3.223877,17,3,17.223877,3,17.5S3.223877,18,3.5,18S4,17.776123,4,17.5S3.776123,17,3.5,17z M21.5,12h-15C6.223877,12,6,12.223877,6,12.5S6.223877,13,6.5,13h15c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,12,21.5,12z M3.5,7C3.223877,7,3,7.223877,3,7.5S3.223877,8,3.5,8S4,7.776123,4,7.5S3.776123,7,3.5,7z M21.5,17h-15C6.223877,17,6,17.223877,6,17.5S6.223877,18,6.5,18h15c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,17,21.5,17z"></path></svg>
                                    </a>
                                </div>
                                <nav class="nav card-body p-1 project-type">
                                    <a href="/club/team/add" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn text-white" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M16,11.5h-3.5V8c0-0.276123-0.223877-0.5-0.5-0.5S11.5,7.723877,11.5,8v3.5H8c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h3.5v3.5005493C11.5001831,16.2765503,11.723999,16.5001831,12,16.5h0.0006104C12.2765503,16.4998169,12.5001831,16.276001,12.5,16v-3.5H16c0.276123,0,0.5-0.223877,0.5-0.5S16.276123,11.5,16,11.5z M12,2C6.4771729,2,2,6.4771729,2,12s4.4771729,10,10,10c5.5202026-0.0062866,9.9937134-4.4797974,10-10C22,6.4771729,17.5228271,2,12,2z M12,21c-4.9705811,0-9-4.0294189-9-9s4.0294189-9,9-9c4.9682617,0.0056152,8.9943848,4.0317383,9,9C21,16.9705811,16.9705811,21,12,21z"></path></svg>
                                        Добавить клуб
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-sm-flex align-items-center">
                                                <div class="avatar mb-2 p-2 lh-1 mb-sm-0 avatar-md rounded-circle bg-primary me-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-icn text-white" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M4.2069702,12l5.1464844-5.1464844c0.1871338-0.1937866,0.1871338-0.5009155,0-0.6947021C9.1616211,5.9602051,8.8450928,5.9547119,8.6464844,6.1465454l-5.5,5.5c-0.000061,0-0.0001221,0.000061-0.0001221,0.0001221c-0.1951904,0.1951904-0.1951294,0.5117188,0.0001221,0.7068481l5.5,5.5C8.7401123,17.9474487,8.8673706,18.0001831,9,18c0.1325684,0,0.2597046-0.0526733,0.3533936-0.1464233c0.1953125-0.1952515,0.1953125-0.5118408,0.0001221-0.7070923L4.2069702,12z M20.8534546,11.6465454l-5.5-5.5c-0.1937256-0.1871948-0.5009155-0.1871948-0.6947021,0c-0.1986084,0.1918335-0.2041016,0.5083618-0.0122681,0.7069702L19.7930298,12l-5.1465454,5.1464844c-0.09375,0.09375-0.1464233,0.2208862-0.1464233,0.3534546C14.5,17.776062,14.723877,17.999939,15,18c0.1326294,0.0001221,0.2598267-0.0525513,0.3534546-0.1464844l5.5-5.5c0.000061-0.000061,0.0001221-0.000061,0.0001831-0.0001221C21.0487671,12.1581421,21.0487061,11.8416748,20.8534546,11.6465454z"></path></svg>
                                                </div>
                                                <div class="ms-1">
                                                    <h6 class="mb-1"> <a href="https://liga.itex.kz/project-details" class="float-start">ФК Столица</a> </h6>
                                                    <span class="text-muted border-end pe-2 fs-11 float-start mt-1">28 сотрудников</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <div class="d-flex align-items-center">
                                                <div class="stars-main me-3">
                                                    <i class="fa fa-star text-light star"></i>
                                                </div>
                                                <a href="#" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-start">
                                                    <a class="dropdown-item" href="#"><i class="fe fe-edit me-2"></i> Изменить</a>
                                                    <a class="dropdown-item" href="#"><i class="fe fe-trash me-2"></i> Удалить</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <p class="m-0 mb-2">Игроков</p>
                                            <div class="avatar-list avatar-list-stacked">
                                                <span class="avatar bradius cover-image" data-bs-image-src="https://liga.itex.kz/assets/images/users/9.jpg" style="background: url(&quot;https://liga.itex.kz/assets/images/users/9.jpg&quot;) center center;"></span>
                                                <span class="avatar bradius cover-image" data-bs-image-src="https://liga.itex.kz/assets/images/users/8.jpg" style="background: url(&quot;https://liga.itex.kz/assets/images/users/8.jpg&quot;) center center;"></span>
                                                <span class="avatar bradius cover-image" data-bs-image-src="https://liga.itex.kz/assets/images/users/11.jpg" style="background: url(&quot;https://liga.itex.kz/assets/images/users/11.jpg&quot;) center center;"></span>
                                                <span class="avatar bradius cover-image" data-bs-image-src="https://liga.itex.kz/assets/images/users/1.jpg" style="background: url(&quot;https://liga.itex.kz/assets/images/users/1.jpg&quot;) center center;"></span>
                                                <span class="avatar bradius cover-image" data-bs-image-src="https://liga.itex.kz/assets/images/users/6.jpg" style="background: url(&quot;https://liga.itex.kz/assets/images/users/6.jpg&quot;) center center;"></span>
                                                <span class="avatar bradius bg-primary">+15</span>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <p class="mb-0">
                                                <span class="text-muted d-block">Дата создания</span>
                                                <span class="text-danger">11 Nov 21</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

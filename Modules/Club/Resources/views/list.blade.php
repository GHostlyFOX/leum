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
                                    <a class="btn btn-outline-primary" href="{{route('home')}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M21.5,13h-8.0005493C13.2234497,13.0001831,12.9998169,13.223999,13,13.5v8.0005493C13.0001831,21.7765503,13.223999,22.0001831,13.5,22h8.0006104C21.7765503,21.9998169,22.0001831,21.776001,22,21.5v-8.0006104C21.9998169,13.2234497,21.776001,12.9998169,21.5,13z M21,21h-7v-7h7V21z M10.5,2H2.4993896C2.2234497,2.0001831,1.9998169,2.223999,2,2.5v8.0005493C2.0001831,10.7765503,2.223999,11.0001831,2.5,11h8.0006104C10.7765503,10.9998169,11.0001831,10.776001,11,10.5V2.4993896C10.9998169,2.2234497,10.776001,1.9998169,10.5,2z M10,10H3V3h7V10z M10.5,13H2.4993896C2.2234497,13.0001831,1.9998169,13.223999,2,13.5v8.0005493C2.0001831,21.7765503,2.223999,22.0001831,2.5,22h8.0006104C10.7765503,21.9998169,11.0001831,21.776001,11,21.5v-8.0006104C10.9998169,13.2234497,10.776001,12.9998169,10.5,13z M10,21H3v-7h7V21z M21.5,2h-8.0005493C13.2234497,2.0001831,12.9998169,2.223999,13,2.5v8.0005493C13.0001831,10.7765503,13.223999,11.0001831,13.5,11h8.0006104C21.7765503,10.9998169,22.0001831,10.776001,22,10.5V2.4993896C21.9998169,2.2234497,21.776001,1.9998169,21.5,2z M21,10h-7V3h7V10z"></path></svg>
                                    </a>
                                    <a class="btn btn-primary" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn text-white" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M3.5,12C3.223877,12,3,12.223877,3,12.5S3.223877,13,3.5,13S4,12.776123,4,12.5S3.776123,12,3.5,12z M6.5,8h15C21.776123,8,22,7.776123,22,7.5S21.776123,7,21.5,7h-15C6.223877,7,6,7.223877,6,7.5S6.223877,8,6.5,8z M3.5,17C3.223877,17,3,17.223877,3,17.5S3.223877,18,3.5,18S4,17.776123,4,17.5S3.776123,17,3.5,17z M21.5,12h-15C6.223877,12,6,12.223877,6,12.5S6.223877,13,6.5,13h15c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,12,21.5,12z M3.5,7C3.223877,7,3,7.223877,3,7.5S3.223877,8,3.5,8S4,7.776123,4,7.5S3.776123,7,3.5,7z M21.5,17h-15C6.223877,17,6,17.223877,6,17.5S6.223877,18,6.5,18h15c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,17,21.5,17z"></path></svg>
                                    </a>
                                </div>
                                <nav class="nav card-body p-1 project-type">
                                    <a href="{{route('club-add')}}" class="btn btn-primary">
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
    <div class="col-12 col-sm-12">
        <div class="card">
            <div class="card-body project-list-table-container">
                <div class="table-responsive">
                    <div id="project-table_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="dataTables_length" id="project-table_length">
                                    <label>
                                        <select name="project-table_length" aria-controls="project-table" class="form-select form-select-sm select2 select2-hidden-accessible" tabindex="-1" aria-hidden="true">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                        <span class="select2 select2-container select2-container--default" dir="ltr" style="width: 196.55px;">
                                            <span class="selection">
                                                <span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-labelledby="select2-project-table_length-nb-container">
                                                    <span class="select2-selection__rendered" id="select2-project-table_length-nb-container" title="10">10</span>
                                                    <span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span>
                                                </span>
                                            </span>
                                            <span class="dropdown-wrapper" aria-hidden="true"></span>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div id="project-table_filter" class="dataTables_filter">
                                    <label>
                                        <input type="search" class="form-control form-control-sm" placeholder="Search..." aria-controls="project-table">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="project-table" class="table text-nowrap mb-0 table-bordered border-top border-bottom project-list-main dataTable no-footer" role="grid" aria-describedby="project-table_info">
                                    <thead class="table-head">
                                    <tr role="row">
                                        <th class="bg-transparent border-bottom-0 sorting" tabindex="0" aria-controls="project-table" rowspan="1" colspan="1" aria-label="Title: activate to sort column ascending" style="width: 156.413px;">
                                            <strong>Наименование клуба</strong>
                                        </th>
                                        <th class="bg-transparent border-bottom-0 sorting" tabindex="0" aria-controls="project-table" rowspan="1" colspan="1" aria-label="Tasks: activate to sort column ascending" style="width: 40.1px;">
                                            <strong>Кол-во команд</strong>
                                        </th>
                                        <th class="bg-transparent border-bottom-0 sorting" tabindex="0" aria-controls="project-table" rowspan="1" colspan="1" aria-label="Date: activate to sort column ascending" style="width: 47.2375px;">
                                            <strong>Дата</strong>
                                        </th>
                                        <th class="bg-transparent border-bottom-0 sorting" tabindex="0" aria-controls="project-table" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending" style="width: 66.5125px;">
                                            <strong>Активность</strong>
                                        </th>
                                        <th class="bg-transparent border-bottom-0 no-btn sorting" tabindex="0" aria-controls="project-table" rowspan="1" colspan="1" aria-label="Action: activate to sort column ascending" style="width: 54px;">
                                            <strong>Действия</strong>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="table-body">
                                    @foreach($clubs as $club)
                                    <tr class="odd">
                                        <td>
                                            <h6 class="mb-0 fs-14 fw-semibold">{{ $club->name }}</h6>
                                        </td>
                                        <td class="text-muted fs-15 fw-semibold">0</td>
                                        <td class="text-muted fs-15 fw-semibold">{{ $club->created_at->format('d.m.Y') }}</td>
                                        <td>
                                            <span class="mb-0 mt-1 badge rounded-pill text-success bg-success-transparent">Работает</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-stretch">
                                                <a href="#" class="border br-5 px-2 py-1 text-muted d-flex align-items-center" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-start">
                                                    <a class="dropdown-item" href="#"><i class="fe fe-edit-2 me-2"></i> Изменить</a>
                                                    <a class="dropdown-item" href="#"><i class="fe fe-delete me-2"></i> Удалить</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                {{--<div class="dataTables_info" id="project-table_info" role="status" aria-live="polite">Showing 1 to 10 of 12 entries</div>--}}
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="project-table_paginate">
                                    {{ $clubs->links() }}
                                    {{--<ul class="pagination">
                                        <li class="paginate_button page-item previous disabled" id="project-table_previous">
                                            <a href="#" aria-controls="project-table" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                        </li>
                                        <li class="paginate_button page-item active">
                                            <a href="#" aria-controls="project-table" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                        </li>
                                        <li class="paginate_button page-item ">
                                            <a href="#" aria-controls="project-table" data-dt-idx="2" tabindex="0" class="page-link">2</a>
                                        </li>
                                        <li class="paginate_button page-item next" id="project-table_next">
                                            <a href="#" aria-controls="project-table" data-dt-idx="3" tabindex="0" class="page-link">Next</a>
                                        </li>
                                    </ul>--}}
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
    </div>
@endsection

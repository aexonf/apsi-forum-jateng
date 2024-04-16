@extends('components.elements.app')

@section('title', 'Simaku Admin - Pengawas')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Pengawas</h1>
            </div>

            @if (session('success') || session('error'))
                <div
                    class="alert {{ session('success') ? 'alert-success' : '' }} {{ session('error') ? 'alert-danger' : '' }} alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {{ session('success') }}
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-header">
                        <div class="w-100">
                            <div class="d-flex justify-content-between flex-wrap">
                                <div class="d-flex align-items-center flex-wrap">
                                    <button type="button" class="btn btn-icon icon-left btn-primary mr-2 mb-2"
                                        data-toggle="modal" data-target="#modal-create"><i class="fas fa-plus"></i>
                                        Tambah</button>
                                    <button type="button" class="btn btn-icon icon-left btn-primary mr-2 mb-2"
                                        data-toggle="modal" data-target="#modal-import"><i class="fas fa-upload"></i>
                                        Import</button>
                                    <form action="" method="get">
                                        @csrf
                                        @method('GET')
                                        <button type="submit" class="btn btn-icon icon-left btn-primary mr-2 mb-2"><i
                                                class="fas fa-download"></i>
                                            Export</button>
                                    </form>
                                </div>
                                <div class="d-flex align-items-center flex-wrap">
                                    <button type="button" class="btn btn-icon icon-left btn-info mr-2 mb-2"
                                        data-toggle="collapse" data-target="#section-filter"><i class="fas fa-filter"></i>
                                        Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="card-body">
                        <div class="collapse mb-3 pb-3 border-bottom show" id="section-filter">
                            <form class="needs-validation" novalidate="" method="GET"
                                action="{{ route("admin.supervisor.index") }}" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="form-group mb-2">
                                            <label class="mb-2">Level</label>
                                            <select class="form-control select2" id="level" name="level"
                                                required onchange="handleChangeFilter(this)">
                                                <option value=""></option>
                                                <option value="TK">TK</option>
                                                <option value="SD">SD</option>
                                                <option value="SMP">SMP</option>
                                                <option value="SMA">SMA</option>
                                                <option value="SMK">SMK</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route("admin.supervisor.index") }}" class="btn btn-danger ml-2">Reset</a>
                                    <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                                </div>
                            </form>
                        </div>
                        <div>
                            <table class="table table-striped table-bordered" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="min-width: 40px;">#</th>
                                        <th style="min-width: 240px;">Nomor ID / Nama</th>
                                        <th style="min-width: 160px;">Username</th>
                                        <th style="min-width: 160px;">Level</th>
                                        <th style="min-width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $supervisor)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="media">
                                                    @if ($supervisor->img_url)
                                                    <img alt="image" class="mr-3 rounded-circle" width="48"
                                                         src="{{ asset('storage/' . $supervisor->img_url) }}">
                                                    @else
                                                    <img alt="image" class="mr-3 rounded-circle" width="48"
                                                    src="{{  asset('img/avatar/avatar-1.png') }}">
                                                    @endif
                                                    <div class="media-body">
                                                        <div class="media-title">
                                                            {{ $supervisor->id_number }}</div>
                                                        <div class="text-job text-muted">
                                                            {{ $supervisor->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $supervisor->user->username }}</td>
                                            <td>
                                                <div
                                                    class="{{ $supervisor->level === 'TK'
                                                        ? 'text-primary'
                                                        : ($supervisor->level === 'SD'
                                                            ? 'text-success'
                                                            : ($supervisor->level === 'SMP'
                                                                ? 'text-warning'
                                                                : ($supervisor->level === 'SMA'
                                                                    ? 'text-danger'
                                                                    : 'text-black'))) }} fw-bold">
                                                    {{ $supervisor->level }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex items-center">
                                                    <button type="button" class="btn btn-icon btn-primary mr-2 mb-2"
                                                        data-toggle="modal" data-target="#modal-edit"
                                                        onclick="
                                                        $('#modal-edit #form-edit').attr('action', '{{ route('admin.supervisor.update', $supervisor->id) }}');
                                                        $('#modal-edit #form-edit #id_number').val('{{ $supervisor->id_number }}');
                                                        $('#modal-edit #form-edit #name').val('{{ $supervisor->name }}');
                                                        $('#modal-edit #form-edit #phone_number').val('{{ $supervisor->phone_number }}');
                                                        $('#modal-edit #form-edit #level').val('{{ $supervisor->level }}');
                                                        $('#modal-edit #form-edit #label').val('{{ $supervisor->label }}');
                                                        $('#modal-edit #form-edit #email').val('{{ $supervisor->email }}');
                                                        $('#modal-edit #form-edit #username').val('{{ $supervisor->user->username }}');
                                                        "><i
                                                            class="fas fa-edit"></i></button>
                                                    <button type="button" class="btn btn-icon btn-danger mr-2 mb-2"
                                                        data-toggle="modal" data-target="#modal-delete"
                                                        onclick="$('#modal-delete #form-delete').attr('action', 'supervisor/{{ $supervisor->id }}/delete')"><i
                                                            class="fas fa-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

{{-- modal create --}}
<div class="modal fade" id="modal-create" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengawas</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" method="POST" action="{{ route("admin.supervisor.create") }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group mb-2">
                        <label for="id_number">Nomor ID<span class="text-danger">*</span></label>
                        <input type="text" id="id_number" class="form-control" name="id_number" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="name">Nama<span class="text-danger">*</span></label>
                        <input type="text" id="name" class="form-control" name="name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="phone_number">Nomor Telepon<span class="text-danger">*</span></label>
                        <input type="text" id="phone_number" class="form-control" name="phone_number" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="level">Level<span class="text-danger">*</span></label>
                        <select class="form-control" name="level" id="level">
                            <option value="TK">TK</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="label">Label<span class="text-danger">*</span></label>
                        <input type="text" id="label" class="form-control" name="label" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="email">Email<span class="text-danger">*</span></label>
                        <input type="email" id="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="username">Username<span class="text-danger">*</span></label>
                        <input type="text" id="username" class="form-control" name="username" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password">Password<span class="text-danger">*</span></label>
                        <input type="password" id="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="image">Gambar<span class="text-danger">*</span></label>
                        <input type="file" id="image" class="form-control" name="image" >
                    </div>
                    <div class="mt-5 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    {{-- modal import --}}
    <div class="modal fade" id="modal-import" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Pengawas</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate="" method="POST" action="{{ route("admin.supervisor.import") }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="supervissor">File </label>
                            <input type="file" id="supervissor" class="form-control" name="supervissor" required>
                        </div>
                        <div>
                            <a href="{{ route("admin.supervisor.download.format") }}" class="btn btn-icon icon-left btn-info mr-2 mb-2"><i
                                    class="fas fa-download"></i>
                                Unduh Template</a>
                            {{-- <a href="#" class="btn btn-icon icon-left btn-info mr-2 mb-2"><i
                                    class="fas fa-circle-info"></i>
                                Unduh Instruksi Template</a> --}}
                        </div>
                        <div class="mt-5 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
   {{-- modal edit --}}
<div class="modal fade" id="modal-edit" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Pengawas</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit" class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-2">
                        <label for="id_number">Nomor ID<span class="text-danger">*</span></label>
                        <input type="text" id="id_number" class="form-control" name="id_number" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="name">Nama<span class="text-danger">*</span></label>
                        <input type="text" id="name" class="form-control" name="name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="phone_number">Nomor Telepon<span class="text-danger">*</span></label>
                        <input type="text" id="phone_number" class="form-control" name="phone_number" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="level">Level<span class="text-danger">*</span></label>
                        <select name="level" class="form-control" id="level">
                            <option value="TK">TK</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="SMK">SMK</option>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label for="label">Label<span class="text-danger">*</span></label>
                        <input type="text" id="label" class="form-control" name="label" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="email">Email<span class="text-danger">*</span></label>
                        <input type="email" id="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="username">Username<span class="text-danger">*</span></label>
                        <input type="text" id="username" class="form-control" name="username" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password">Password</label>
                        <input type="password" id="password" class="form-control" name="password">
                    </div>
                    <div class="form-group mb-2">
                        <label for="image">Gambar<span class="text-danger">*</span></label>
                        <input type="file" id="image" class="form-control" name="image">
                    </div>
                    <div class="mt-5 d-flex justify-content-end">
                        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    {{-- modal delete --}}
    <div class="modal fade" id="modal-delete" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Pengawas</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-delete" class="needs-validation" novalidate="" method="POST" action=""
                        enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')
                        <div class="mt-5 d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
                            <button type="submit" class="btn btn-danger ml-2">Kirim</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraries -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        const handleChangeFilter = (e) => {
            const currentURL = new URL(window.location.href);
            currentURL.searchParams.set(e.name, e.value);
            window.history.pushState({}, '', currentURL);
            location.reload();
        }
    </script>
@endpush

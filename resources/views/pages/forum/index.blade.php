@extends('components.elements.app')

@section('title', 'Dashboard - Apsi Forum')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Forum</h1>
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
                                action="{{ route('admin.forum.index') }}" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="form-group mb-2">
                                            <label class="mb-2">Status</label>
                                            <select class="form-control select2" id="status" name="status" required
                                                onchange="handleChangeFilter(this)">
                                                <option value="pending"
                                                    {{ request()->query('status') === 'pending' ? 'selected' : '' }}>
                                                    Menunggu</option>
                                                <option value="approved"
                                                    {{ request()->query('status') === 'approved' ? 'selected' : '' }}>
                                                    Disetujui</option>
                                                <option value="rejected"
                                                    {{ request()->query('status') === 'rejected' ? 'selected' : '' }}>
                                                    Ditolak</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.forum.index') }}" class="btn btn-danger ml-2">Reset</a>
                                    <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                                </div>
                            </form>
                        </div>
                        <div>
                            <table class="table table-striped table-bordered" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="min-width: 40px;">#</th>
                                        <th style="min-width: 240px;">Judul</th>
                                        <th style="min-width: 160px;">Pengawas</th>
                                        <th style="min-width: 160px;">Status</th>
                                        <th style="min-width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($forums as $index => $forum)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $forum->title }}</td>
                                            <td>{{ $forum->supervisor->name }}</td>
                                            <td>
                                                <div
                                                    class="badge {{ $forum->status === 'approved' ? 'badge-success' : ($forum->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                                    {{ $forum->status }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex items-center">
                                                    <a href="{{ route('admin.forum.detail', $forum->id) }}"
                                                        class="text-decoration-none"><i
                                                            class="fas fa-info-circle fa-lg"></i></a>
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
                    <h5 class="modal-title">Tambah Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate="" method="POST" action=""
                        enctype="multipart/form-data">
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
                            <label for="status">Status<span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active" selected>Active</option>
                                <option value="inactive">In Active</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nis">Nis </label>
                            <input type="text" id="nis" class="form-control" name="nis">
                        </div>
                        <div class="form-group mb-2">
                            <label for="username">Username<span class="text-danger">*</span></label>
                            <input type="text" id="username" class="form-control" name="username" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Password<span class="text-danger">*</span></label>
                            <input type="password" id="password" class="form-control" name="password" required>
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
                    <h5 class="modal-title">Import Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="needs-validation" novalidate="" method="POST" action=""
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="students">File </label>
                            <input type="file" id="students" class="form-control" name="students" required>
                        </div>
                        <div>
                            <a href="" class="btn btn-icon icon-left btn-info mr-2 mb-2"><i
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
                    <h5 class="modal-title">Ubah Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-edit" class="needs-validation" novalidate="" method="POST" action=""
                        enctype="multipart/form-data">
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
                            <label for="status">Status<span class="text-danger">*</span></label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">In Active</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="nis">Nis </label>
                            <input type="text" id="nis" class="form-control" name="nis">
                        </div>
                        <div class="form-group mb-2">
                            <label for="username">Username<span class="text-danger">*</span></label>
                            <input type="text" id="username" class="form-control" name="username" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Password</label>
                            <input type="password" id="password" class="form-control" name="password">
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

@extends('components.elements.app')

@section('title', 'Simaku Admin - Publication')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Publication</h1>
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
                                    <form action="{{ route("admin.supervisor.export") }}" method="get">
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
                        <div>
                            <table class="table table-striped table-bordered" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="min-width: 40px;">#</th>
                                        <th style="min-width: 160px;">Title</th>
                                        <th style="min-width: 160px;">File</th>
                                        <th style="min-width: 160px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $index => $publication)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $publication->title }}</td>
                                            <td>{{ $publication->file_url }}</td>

                                            <td>
                                                <div class="d-flex items-center">
                                                    <button type="button" class="btn btn-icon btn-primary mr-2 mb-2"
                                                        data-toggle="modal" data-target="#modal-edit"
                                                        onclick="
                                                        $('#modal-edit #form-edit').attr('action', '{{ route('admin.publication.update', $publication->id) }}');
                                                        $('#modal-edit #form-edit #title').val('{{ $publication->title }}');
                                                        $('#modal-edit #form-edit #file').val('{{ $publication->file }}');
                                                        "><i
                                                            class="fas fa-edit"></i></button>
                                                    <button type="button" class="btn btn-icon btn-danger mr-2 mb-2"
                                                        data-toggle="modal" data-target="#modal-delete"
                                                        onclick="$('#modal-delete #form-delete').attr('action', {{ route('admin.publication.delete', $publication->id) }})"><i
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
                <h5 class="modal-title">Tambah Publication</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate="" method="POST" action="{{ route("admin.publication.create") }}" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group mb-2">
                        <label for="title">Judul<span class="text-danger">*</span></label>
                        <input type="title" id="title" class="form-control" name="title" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="file">File<span class="text-danger">*</span></label>
                        <input type="file" id="file" class="form-control" name="file" >
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
                    <h5 class="modal-title">Import Publication</h5>
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
                <h5 class="modal-title">Ubah Publication</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-edit" class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group mb-2">
                        <label for="title">Judul<span class="text-danger">*</span></label>
                        <input type="title" id="title" class="form-control" name="title" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="file">File<span class="text-danger">*</span></label>
                        <input type="file" id="file" class="form-control" name="file" >
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
                    <h5 class="modal-title">Hapus Publication</h5>
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

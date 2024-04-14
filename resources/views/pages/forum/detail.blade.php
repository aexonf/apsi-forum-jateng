@extends('components.elements.app')

@section('title', 'Detail Forum')

@push('style')
    <!-- Tambahkan perpustakaan CSS tambahan yang diperlukan -->
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Detail Forum</h1>
            </div>

            @if (session('success') || session('error'))
                <div class="alert {{ session('success') ? 'alert-success' : 'alert-danger' }} alert-dismissible show fade">
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
                        <h2>Informasi Forum</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" id="title" value="{{ $data->title }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea type="text" class="form-control" id="content" readonly>{{ $data->content }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input type="text" class="form-control text-white @if($data->status == 'pending') bg-warning @elseif($data->status == 'approved') bg-success @elseif($data->status == 'rejected') bg-danger @endif" id="status" value="{{ $data->status }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h2>Terima atau Tolak Forum</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 d-flex">
                            <form action="{{ route("admin.forum.rejected", $id) }}" method="post">
                                @csrf
                                @method("PUT")
                                <button type="submit" class="btn btn-icon btn-danger mr-2 mb-2"> <i class="fa-solid fa-circle-xmark"></i></button>
                            </form>
                            <form action="{{ route("admin.forum.approved", $id) }}" method="post">
                                @csrf
                                @method("PUT")
                                <button type="submit" class="btn btn-icon btn-success mr-2 mb-2"> <i class="fa-solid fa-thumbs-up"></i></button>
                            </form>
                        </div>
                        {{-- filter --}}
                        {{-- <div class="collapse mb-3 pb-3 border-bottom show" id="section-filter">
                            <form class="needs-validation" novalidate="" method="GET" action="" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="form-group mb-2">
                                            <label class="mb-2">Tipe</label>
                                            <select class="form-control select2" name="type" onchange="handleChangeFilter(this)">
                                                <option value="pelanggaran" {{ request('type') == 'pelanggaran' ? 'selected' : '' }}>Pelanggaran</option>
                                                <option value="penebusan" {{ request('type') == 'penebusan' ? 'selected' : '' }}>Penebusan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('admin.active_student') }}" class="btn btn-danger ml-2">Reset</a>
                                    <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                                </div>
                            </form>
                        </div> --}}
                        <div>
                            <table class="table table-striped table-bordered" id="datatable">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width: 80px;">#</th>
                                        <th>Tanggal</th>
                                        <th>Pengawas</th>
                                        <th>Content</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->comments as $index => $value)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $value->created_at }}</td>
                                            <td>{{ $value->supervisors->name  }}</td>
                                            <td>{{ Str::limit($value->content, 35) }}</td>
                                            <td>
                                                <!-- Tombol Modal Detail -->
                                                <button type="button" class="btn btn-icon btn-info mr-2 mb-2" data-toggle="modal" data-target="#modal-detail" data-value="{{ $value->id }}" onclick="
                                                        $('#modal-detail #form-detail');
                                                        $('#modal-detail #form-detail #date').attr('value', '{{ $value->created_at }}');
                                                        $('#modal-detail #form-detail #suppervisor').attr('value', '{{ $value->supervisors->name }}');
                                                        $('#modal-detail #form-detail #content').text('{{ $value->content }}');
                                                        $('#modal-detail #form-detail').attr('action', '{{ route('admin.forum.comment.delete', $value->id) }}');
                                                        ">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- Modal Hapus --}}
        <div class="modal fade" id="modal-delete" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus {{ request('type') == 'pelanggaran' ? 'Pelanggaran' : 'Penebusan' }}</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-delete" class="needs-validation" novalidate="" method="POST" action="" enctype="multipart/form-data">
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
        <!-- Modal Detail -->
        <div class="modal fade" id="modal-detail" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Komentar Forum </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="form-detail" class="needs-validation" novalidate="" method="POST" action="">
                            @method('DELETE')
                            @csrf
                            <div class="form-group mb-2">
                                <label for="date">Dibuat pada<span class="text-danger">*</span></label>
                                <input type="text" id="date" class="form-control" name="date" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label for="suppervisor">Pengawas</label>
                                <input id="suppervisor" class="form-control" name="suppervisor" readonly>
                            </div>
                            <div class="form-group mb-2">
                                <label for="content">Content</label>
                                <textarea id="content" class="form-control" name="content" readonly></textarea>
                            </div>
                            <div class="form-group mb-2">
                                <button type="submit" class="btn btn-icon btn-danger mr-2 mb-2"><i class="fa-solid fa-trash"></i> Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Ekspor --}}
        <div class="modal fade" id="modal-export" data-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ekspor Pelanggaran Siswa</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form class="needs-validation" novalidate="" method="GET" action="" enctype="multipart/form-data">
                            @csrf
                            <div class="mt-5 d-flex justify-content-end">
                                <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-primary ml-2">Kirim</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Tambahkan perpustakaan dan skrip JS tambahan yang diperlukan -->
    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('library/datatables/media/js/dataTables.min.js') }}"></script>
    <script src="{{ asset('library/jquery-ui-dist/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/page/modules-datatables.js') }}"></script>
    <script src="{{ asset('library/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#datatable').DataTable();
        });
    </script>
@endpush

@extends('components.elements.app')

@section('title', 'Rate of Quality Product')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/select2/dist/css/select2.min.css') }}">
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Quality Product</h1>
            </div>

            @if (session('success') || session('error'))
                <div
                    class="alert {{ session('success') ? 'alert-success' : '' }} {{ session('error') ? 'alert-danger' : '' }} alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>x</span>
                        </button>
                        {{ session('success') }}
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="card">
                    <div class="card-body">
                        <form class="needs-validation" method="POST" action="{{ route('quality.create', $id) }}">
                            @csrf
                            @method('POST')
                            <div class="form-group mb-2 row">
                                <label class="col-2 col-form-label d-flex justify-content-center align-items-center mb-4"
                                    for="processed_amount">Processed Amount</label>
                                <input type="text" value="{{ $data->processed_amount }}" readonly
                                    class="form-control col-3 mb-4" name="processed_amount" id="processed_amount">
                                <p class="col-1 d-flex justify-content-center align-items-center mb-4">unit</p>
                                <label class="col-2 col-form-label d-flex justify-content-center align-items-center mb-4"
                                    for="total">Total</label>
                                <input type="text" class="form-control col-3 mb-4" name="total" id="total"
                                    readonly>
                                <label class="col-2 col-form-label d-flex justify-content-center align-items-center mb-4"
                                    for="defeat_amount">Defeat Amount</label>
                                <input type="text" class="form-control col-3 mb-4" name="defeat_amount"
                                    id="defeat_amount">
                                <p class="col-1 d-flex justify-content-center align-items-center mb-4">unit</p>


                                <h5 class="col-2 d-flex justify-content-center align-items-center mb-3">Rate of quality
                                    Product</h5>
                                <input type="text" class="form-control col-3 mb-4" name="rate_of_quality_product"
                                    id="rate_of_quality_product" readonly>
                                <p class="col-1 d-flex justify-content-center align-items-center mb-4">%</p>
                                <!-- <h4 class="col-12 d-flex justify-content-start align-items-center mb-4 text-danger">
                                    * Target presentase rate of quality = 85%
                                </h4>
                                <h3 class="col-12" id="target_presentase_rate"></h3> -->

                            </div>
                            <div class="mt-5 d-flex justify-content-between">
                                <div>
                                    <button type="button" onclick="hitung()" class="btn btn-primary ml-2"
                                        id="button-hitung">Hitung</button>
                                    <a href="{{ route('quality', $id) }}" class="btn btn-danger ml-2 ">Reset</a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary ml-2 " id="button-simpan"
                                        disabled>Simpan</button>
                        </form>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>Table</h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive overflow-x-auto">
                            <table class="table table-striped table-md">
                                <tbody>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Processed Amount</th>
                                        <th>Defeat Amount</th>
                                        <th>Rate of Quality</th>
                                        <th>Action</th>
                                    </tr>
                                    @if (session('success') && session('quality'))
                                        <?php $quality = session('quality'); ?>
                                        <tr>
                                            <td>
                                                {{ $quality->created_at }}
                                            </td>
                                            <td>
                                                {{ $quality->performance->processed_amount }}
                                            </td>
                                            <td>
                                                {{ $quality->defeat_amount }}
                                            </td>
                                            <td>
                                                {{ $quality->rate_of_quality_product }}
                                            </td>
                                            <td class="d-flex">
                                                <a href="{{ route('oee', $quality->id) }}" class="btn btn-success ml-2"
                                                    id="button-lanjut">Lanjut</a>
                                                <form action="{{ route('quality.delete', $quality->id) }}" method="post">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit" class="btn btn-danger ml-2 "
                                                        id="button-hapus-semua">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end my-5">
                    <a href="{{ route('performance', ['id' => 1]) }}" class="btn btn-primary ml-2"
                        id="button-simpan">Kembali</a>
                </div>
            </div>
    </div>
    </div>
    </section>
    </div>

    <script>
        function hitung() {
            // Get input values
            const processedAmount = parseFloat(document.getElementById('processed_amount').value) || 0;
            const defeatAmount = parseFloat(document.getElementById('defeat_amount').value) || 0;

            const total = processedAmount - defeatAmount;
            document.getElementById("total").value = total;

            // Calculate rate of quality product
            let rateOfQualityProduct = (processedAmount - defeatAmount) / processedAmount * 100;
            rateOfQualityProduct = rateOfQualityProduct;

            document.getElementById('rate_of_quality_product').value = Math.trunc(rateOfQualityProduct);


            // const badgeContainer = document.getElementById('target_presentase_rate');

            // // Clear previous badges
            // badgeContainer.innerHTML = '';

            // if (rateOfQualityProduct >= 85) {
            //     const badgeAman = document.createElement('h3');
            //     badgeAman.className = 'badge badge-success';
            //     badgeAman.innerText =
            //         'Pengguna waktu yang tersedia untuk kegiatan operasi mesin alat sudah mencukupi (Tinggi)';

            //     badgeContainer.appendChild(badgeAman);
            // } else {
            //     const badgeKurang = document.createElement('h3');
            //     badgeKurang.className = 'badge badge-danger';
            //     badgeKurang.innerText =
            //         'Pengguna waktu yang tersedia untuk kegiatan operasi mesin alat belum mencukupi (Kurang)';

            //     badgeContainer.appendChild(badgeKurang);
            // }
            // Enable the buttons
            document.getElementById('button-simpan').removeAttribute('disabled');
        }
    </script>

@endsection

@push('scripts')
    <script>
        const buttonHitung = $('#button-hitung');
        const buttonReset = $('#button-reset');
        const buttonSimpan = $('#button-simpan');
        const buttonLanjut = $('#button-lanjut');
    </script>
@endpush

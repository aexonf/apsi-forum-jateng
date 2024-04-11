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
                        <form class="needs-validation" method="POST" action="{{ route('quality.update', $id) }}">
                            @csrf
                            @method('POST')
                            <div class="form-group mb-2 row">
                                <h5 class="col-12 mb-6 text-center">Tanggal: {{ $data->updated_at }}</h5>
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
                                <input value="{{ $quality->defeat_amount }}" type="text" class="form-control col-3 mb-4"
                                    name="defeat_amount" id="defeat_amount">
                                <p class="col-1 d-flex justify-content-center align-items-center mb-4">unit</p>


                                <h5 class="col-2 d-flex justify-content-center align-items-center mb-3">Rate of quality
                                    Product</h5>
                                <input value="{{ $quality->rate_of_quality_product }}" type="text"
                                    class="form-control col-3 mb-4" name="rate_of_quality_product"
                                    id="rate_of_quality_product" readonly>
                                <p class="col-1 d-flex justify-content-center align-items-center mb-4">%</p>
                                <h4 class="col-12 d-flex justify-content-start align-items-center mb-4 text-danger">
                                    * Target presentase rate of quality = 85%
                                </h4>
                                <h3 class="col-12" id="target_presentase_rate"></h3>

                            </div>
                            <div class="mt-5 d-flex justify-content-between">
                                <div>
                                    {{--    <button type="button" onclick="hitung()" class="btn btn-primary ml-2"
                                        id="button-hitung">Hitung</button>
                                  <a href="{{ route('quality', $id) }}" class="btn btn-danger ml-2 ">Reset</a> --}}
                                </div>
                                <div>
                                    <form action="{{ route('quality.update', $data->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        {{-- <button type="submit" class="btn btn-primary ml-2" id="button-simpan"
                                        >Update</button> --}}
                                    </form>
                                    <a href="{{ route('oee.detail', ['id' => $id]) }}" class="btn btn-primary ml-2"
                                        id="button-simpan">Lanjut</a>
                                    <a href="{{ route('performance.detail', ['id' => $id]) }}" class="btn btn-primary ml-2"
                                        id="button-simpan">Kembali</a>
                        </form>

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


            const badgeContainer = document.getElementById('target_presentase_rate');

            // Clear previous badges
            badgeContainer.innerHTML = '';

            if (rateOfQualityProduct >= 85) {
                const badgeAman = document.createElement('h3');
                badgeAman.className = 'badge badge-success';
                badgeAman.innerText =
                    'Pengguna waktu yang tersedia untuk kegiatan operasi mesin alat sudah mencukupi (Tinggi)';

                badgeContainer.appendChild(badgeAman);
            } else {
                const badgeKurang = document.createElement('h3');
                badgeKurang.className = 'badge badge-danger';
                badgeKurang.innerText =
                    'Pengguna waktu yang tersedia untuk kegiatan operasi mesin alat belum mencukupi (Kurang)';

                badgeContainer.appendChild(badgeKurang);
            }
            // Enable the buttons
            document.getElementById('button-simpan').removeAttribute('disabled');
        }

        hitung()
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

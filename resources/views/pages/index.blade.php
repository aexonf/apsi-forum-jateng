@extends('components.elements.app')

@section('title', 'Admin - Dashboard')

@push('style')
    <!-- CSS Libraries -->
@endpush

@section('main')

    <div class="main-content mx-auto">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>

            <div class="section-body">
                <div class="row justify-content-around">
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Pengawas</h4>
                                </div>
                                <div class="card-body">
                                    {{ $total_supervisor }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Forum</h4>
                                </div>
                                <div class="card-body">
                                    {{ $total_discussion }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Publikasi</h4>
                                </div>
                                <div class="card-body">
                                    {{ $total_publication }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </section>
    </div>

@endsection

@push('scripts')
    @if (session('success'))
        <script>
            document.getElementById('route-admin').click();
        </script>
    @endif
@endpush

@extends('layouts.admin')

@section('content')

<div class="row">

    {{-- CARD ITEMS --}}
    <div class="col-md-3 mb-3">
        <div class="card-stats bg-primary">
            <h6 class="mb-0">ITEMS</h6>
            <h2 class="fw-bold">7</h2>
        </div>
    </div>

    {{-- CARD SUPPLIER --}}
    <div class="col-md-3 mb-3">
        <div class="card-stats bg-danger">
            <h6 class="mb-0">SUPPLIERS</h6>
            <h2 class="fw-bold">5</h2>
        </div>
    </div>

    {{-- CARD CUSTOMER --}}
    <div class="col-md-3 mb-3">
        <div class="card-stats bg-success">
            <h6 class="mb-0">CUSTOMERS</h6>
            <h2 class="fw-bold">3</h2>
        </div>
    </div>

    {{-- CARD USERS --}}
    <div class="col-md-3 mb-3">
        <div class="card-stats bg-warning">
            <h6 class="mb-0">USERS</h6>
            <h2 class="fw-bold">4</h2>
        </div>
    </div>

</div>

{{-- CHART --}}
<div class="card mt-4">
    <div class="card-header fw-bold">
        Produk Terlaris Bulan Ini
    </div>
    <div class="card-body">
        <canvas id="salesChart" height="120"></canvas>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('salesChart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mie Goreng', 'Nasi Goreng', 'Kwetiaw Goreng'],
            datasets: [{
                label: 'Jumlah Terjual',
                data: [5, 2, 4],
                borderWidth: 1
            }]
        }
    });
</script>
@endsection

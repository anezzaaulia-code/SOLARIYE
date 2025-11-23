@extends('layouts.app')
@section('title','POS')
@section('content')
<h3>POS - Kasir</h3>
<a href="{{ route('pesanan.create') }}" class="btn btn-primary">Buka Toolbox Pesanan</a>
<div class="row mt-3">
    @foreach($menus as $m)
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h5>{{ $m->nama }}</h5>
                <p>{{ number_format($m->harga) }}</p>
                <form method="POST" action="{{ route('pos.store') }}">
                    @csrf
                    <input type="hidden" name="items" value='[{"menu_id":{{ $m->id }},"jumlah":1}]'>
                    <input type="hidden" name="metode_bayar" value="tunai">
                    <button class="btn btn-success">Beli 1</button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

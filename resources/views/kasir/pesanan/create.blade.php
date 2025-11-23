@extends('layouts.app')
@section('title','Buat Pesanan')
@section('content')
<h3>Buat Pesanan</h3>
<form method="POST" action="{{ route('pesanan.store') }}">
    @csrf
    <div id="items-area">
        <div class="row mb-2">
            <div class="col"><select class="form-select item-menu">@foreach(\App\Models\Menu::where('status','tersedia')->get() as $m)<option value="{{ $m->id }}" data-harga="{{ $m->harga }}">{{ $m->nama }} - {{ number_format($m->harga) }}</option>@endforeach</select></div>
            <div class="col-2"><input class="form-control item-qty" type="number" value="1"></div>
            <div class="col-2"><button type="button" class="btn btn-danger remove-item">Hapus</button></div>
        </div>
    </div>
    <button type="button" id="add-item" class="btn btn-sm btn-secondary">Tambah Item</button>
    <div class="mt-3">
        <label>Total: </label> <span id="total">0</span>
    </div>
    <div class="mb-3 mt-3">
        <label>Metode Bayar</label>
        <select name="metode_bayar" class="form-select">
            <option value="tunai">Tunai</option>
            <option value="qris">QRIS</option>
            <option value="transfer">Transfer</option>
        </select>
    </div>
    <input type="hidden" name="items" id="items-json">
    <button class="btn btn-primary">Simpan Pesanan</button>
</form>

@push('scripts')
<script>
function recalc(){
    let total=0;
    document.querySelectorAll('#items-area .row').forEach(r=>{
        const menuSelect = r.querySelector('.item-menu');
        const qty = Number(r.querySelector('.item-qty').value||0);
        const harga = Number(menuSelect.selectedOptions[0].dataset.harga||0);
        total += qty*harga;
    });
    document.getElementById('total').innerText = total;
    const items = Array.from(document.querySelectorAll('#items-area .row')).map(r=>({
        menu_id: Number(r.querySelector('.item-menu').value),
        jumlah: Number(r.querySelector('.item-qty').value),
    }));
    document.getElementById('items-json').value = JSON.stringify(items);
}
document.getElementById('add-item').addEventListener('click', ()=>{
    const row = document.querySelector('#items-area .row').cloneNode(true);
    row.querySelector('.item-qty').value = 1;
    document.getElementById('items-area').appendChild(row);
    row.querySelector('.remove-item').onclick = ()=>{ row.remove(); recalc(); };
    row.querySelector('.item-menu').onchange = recalc;
    row.querySelector('.item-qty').oninput = recalc;
    recalc();
});
document.querySelectorAll('.remove-item').forEach(b=>b.onclick=()=>{b.closest('.row').remove(); recalc();});
document.querySelectorAll('.item-menu').forEach(i=>i.onchange=recalc);
document.querySelectorAll('.item-qty').forEach(i=>i.oninput=recalc);
recalc();
</script>
@endpush
@endsection

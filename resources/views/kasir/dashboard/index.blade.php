<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kasir POS - Bootstrap</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .wrapper { display: flex; height: 100%; width: 100%; }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            background-color: #212529;
            color: white;
            display: flex; flex-direction: column; flex-shrink: 0;
        }
        .sidebar .nav-link {
            color: #adb5bd; padding: 12px 20px; display: flex; align-items: center; gap: 10px; font-weight: 500; text-decoration: none;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white; background-color: #343a40; border-left: 4px solid #0d6efd;
        }
        .sidebar-footer { margin-top: auto; padding: 15px; border-top: 1px solid #343a40; }

        /* MAIN CONTENT */
        .main-panel { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; }
        .top-navbar {
            height: 60px; background: white; border-bottom: 1px solid #dee2e6;
            display: flex; align-items: center; justify-content: space-between; padding: 0 25px; flex-shrink: 0;
        }

        /* POS AREA */
        .pos-container { display: flex; flex-grow: 1; overflow: hidden; }
        
        /* MENU AREA (KIRI) */
        .menu-area { flex-grow: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; }
        
        /* TABS STYLE */
        .nav-pills .nav-link {
            color: #6c757d; font-weight: 600; border-radius: 20px; padding: 8px 25px; background: white; border: 1px solid #dee2e6; margin-right: 10px;
        }
        .nav-pills .nav-link.active {
            background-color: #0d6efd; color: white; border-color: #0d6efd;
        }

        /* CARD MENU */
        .card-menu {
            cursor: pointer; border: none; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: transform 0.2s;
        }
        .card-menu:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .card-menu img { height: 130px; object-fit: cover; border-top-left-radius: 0.375rem; border-top-right-radius: 0.375rem; }

        /* CART AREA (KANAN) */
        .cart-area {
            width: 380px; background: white; border-left: 1px solid #dee2e6; display: flex; flex-direction: column; box-shadow: -5px 0 15px rgba(0,0,0,0.03); flex-shrink: 0;
        }
        .cart-items { flex-grow: 1; overflow-y: auto; padding: 15px; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ced4da; border-radius: 10px; }
    </style>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">
        <div class="d-flex align-items-center justify-content-center" style="height: 60px; border-bottom: 1px solid #343a40;">
            <h4 class="m-0 fw-bold text-primary"><i class="bi bi-shop me-2"></i>APP KASIR</h4>
        </div>
        <nav class="mt-3 flex-grow-1">
            <a href="{{ route('kasir.dashboard') }}" class="nav-link active">
                <i class="bi bi-grid-fill"></i> Transaksi POS
            </a>
            <a href="{{ route('kasir.riwayat') }}" class="nav-link">
                <i class="bi bi-clock-history"></i> Riwayat
            </a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex gap-2 justify-content-center"><i class="bi bi-box-arrow-left"></i> Logout</button>
            </form>
        </div>
    </aside>

    <main class="main-panel">
        <header class="top-navbar shadow-sm">
            <div class="input-group" style="width: 300px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="searchMenu" class="form-control bg-light border-start-0" placeholder="Cari menu...">
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end lh-1 d-none d-sm-block">
                    <div class="fw-bold text-dark">{{ auth()->user()->name ?? 'Kasir' }}</div>
                    <small class="text-success"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> Online</small>
                </div>
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                    {{ substr(auth()->user()->name ?? 'K', 0, 1) }}
                </div>
            </div>
        </header>

        <div class="pos-container">
            
            <div class="menu-area bg-light">
                
                <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-makanan-tab" data-bs-toggle="pill" data-bs-target="#pills-makanan" type="button" role="tab">
                            <i class="bi bi-egg-fried me-2"></i>Makanan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-minuman-tab" data-bs-toggle="pill" data-bs-target="#pills-minuman" type="button" role="tab">
                            <i class="bi bi-cup-straw me-2"></i>Minuman
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-makanan" role="tabpanel">
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 g-3" id="containerMakanan">
                            @foreach ($menus as $menu)
                                {{-- LOGIKA: Tampilkan di tab Makanan jika kategori mengandung kata 'makan' --}}
                                @if(stripos($menu->kategori->nama ?? '', 'makan') !== false)
                                    <div class="col menu-item-col" data-name="{{ strtolower($menu->nama) }}">
                                        <div class="card card-menu h-100" onclick="addToCart({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})">
                                            @if($menu->foto)
                                                <img src="{{ asset('storage/'.$menu->foto) }}" class="card-img-top" alt="{{ $menu->nama }}">
                                            @else
                                                <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-muted" style="height: 130px;">
                                                    <small>No Image</small>
                                                </div>
                                            @endif
                                            <div class="card-body p-2 text-center">
                                                <h6 class="card-title fw-bold text-dark text-truncate mb-1">{{ $menu->nama }}</h6>
                                                <p class="card-text text-primary fw-bold mb-0">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pills-minuman" role="tabpanel">
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 g-3" id="containerMinuman">
                            @foreach ($menus as $menu)
                                {{-- LOGIKA: Tampilkan di tab Minuman jika kategori mengandung kata 'minum' --}}
                                @if(stripos($menu->kategori->nama ?? '', 'minum') !== false)
                                    <div class="col menu-item-col" data-name="{{ strtolower($menu->nama) }}">
                                        <div class="card card-menu h-100" onclick="addToCart({{ $menu->id }}, '{{ $menu->nama }}', {{ $menu->harga }})">
                                            @if($menu->gambar)
                                                <img src="{{ asset('storage/'.$menu->gambar) }}" class="card-img-top" alt="{{ $menu->nama }}">
                                            @else
                                                <div class="bg-secondary bg-opacity-10 d-flex align-items-center justify-content-center text-muted" style="height: 130px;">
                                                    <small>No Image</small>
                                                </div>
                                            @endif
                                            <div class="card-body p-2 text-center">
                                                <h6 class="card-title fw-bold text-dark text-truncate mb-1">{{ $menu->nama }}</h6>
                                                <p class="card-text text-primary fw-bold mb-0">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div> </div>

            <div class="cart-area">
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cart3 me-2"></i>Pesanan</h5>
                    <button class="btn btn-sm text-danger fw-bold" onclick="clearCart()">Reset</button>
                </div>

                <div class="cart-items" id="cartList">
                    <div class="text-center text-muted mt-5">
                        <i class="bi bi-bag-x display-4 opacity-50"></i>
                        <p class="mt-2 small">Belum ada item dipilih</p>
                    </div>
                </div>

                <div class="p-3 bg-light border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Total Bayar</span>
                        <span class="fs-4 fw-bold text-primary" id="totalDisplay">Rp 0</span>
                    </div>
                    <button id="btnCheckout" class="btn btn-primary w-100 py-2 fw-bold" onclick="showPaymentModal()" disabled>
                        <i class="bi bi-wallet2 me-2"></i> Bayar Sekarang
                    </button>
                </div>
            </div>

        </div>
    </main>
</div>

<div class="modal fade" id="waModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Struk WhatsApp</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-3">Klik tombol untuk mengirim struk via WhatsApp.</p>
                <a id="waButton" href="#" target="_blank" class="btn btn-success w-100 fw-bold py-2">
                    <i class="bi bi-whatsapp"></i> Kirim Struk via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="paymentModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">Pelanggan</label>
                        <input type="text" id="namaPelanggan" class="form-control" placeholder="Umum">
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small text-uppercase fw-bold">No. WA</label>
                        <input type="text" id="nomorWA" class="form-control" placeholder="08...">
                    </div>
                </div>
                <div class="card bg-primary text-white text-center mb-3 p-3 border-0">
                    <span class="small text-white-50 text-uppercase fw-bold">Total Tagihan</span>
                    <h2 class="fw-bold m-0" id="modalTotalDisplay">Rp 0</h2>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Metode Pembayaran</label>
                    <select class="form-select" id="metodePembayaran">
                        <option value="tunai">Tunai / Cash</option>
                        <option value="qris">QRIS</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small text-uppercase fw-bold">Uang Diterima (Rp)</label>
                    <input type="text" id="jumlahBayar" class="form-control form-control-lg fw-bold text-primary" placeholder="0">
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <span class="fw-bold text-muted">Kembalian:</span>
                    <span class="fs-5 fw-bold text-secondary" id="kembalianDisplay">Rp 0</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success fw-bold px-4" id="btnSimpanTransaksi">Proses Transaksi</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    let cart = [];
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    const waModal = new bootstrap.Modal(document.getElementById('waModal'));
    const formatIDR = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

    function addToCart(id, nama, harga) {
        let item = cart.find(c => c.id === id);
        if (item) { item.qty++; } else { cart.push({ id, nama, harga, qty: 1 }); }
        renderCart();
    }

    function renderCart() {
        let total = 0;
        let html = '';
        const list = document.getElementById('cartList');
        
        if (cart.length === 0) {
            list.innerHTML = `<div class="text-center text-muted mt-5"><i class="bi bi-bag-x display-4 opacity-50"></i><p class="mt-2 small">Keranjang Kosong</p></div>`;
            document.getElementById('totalDisplay').innerText = 'Rp 0';
            document.getElementById('btnCheckout').disabled = true;
            return;
        }

        cart.forEach((item, i) => {
            total += item.harga * item.qty;
            html += `
                <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-2">
                    <div class="flex-grow-1">
                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">${item.nama}</div>
                        <div class="text-primary small fw-bold">${formatIDR(item.harga * item.qty)}</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary" onclick="updateQty(${i}, -1)">-</button>
                            <button class="btn btn-outline-secondary disabled fw-bold text-dark" style="width: 30px; opacity: 1;">${item.qty}</button>
                            <button class="btn btn-outline-secondary" onclick="updateQty(${i}, 1)">+</button>
                        </div>
                        <button class="btn btn-sm text-danger" onclick="removeItem(${i})"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
        });

        list.innerHTML = html;
        document.getElementById('totalDisplay').innerText = formatIDR(total);
        document.getElementById('btnCheckout').disabled = false;
    }

    function updateQty(idx, val) {
        cart[idx].qty += val;
        if (cart[idx].qty <= 0) cart.splice(idx, 1);
        renderCart();
    }
    
    function removeItem(idx) {
        cart.splice(idx, 1);
        renderCart();
    }
    
    function clearCart() {
        if(confirm('Hapus semua?')) { cart = []; renderCart(); }
    }

    // SEARCH FUNCTION (Support Tabs)
    document.getElementById('searchMenu').addEventListener('keyup', function() {
        let val = this.value.toLowerCase();
        
        // Cari di semua tab (Makanan & Minuman)
        let items = document.querySelectorAll('.menu-item-col');
        items.forEach(el => {
            let name = el.getAttribute('data-name');
            if(name.includes(val)) {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        });
    });

    // Modal & Payment Logic
    function showPaymentModal() {
        if(cart.length === 0) return;
        const total = cart.reduce((acc, item) => acc + (item.harga * item.qty), 0);
        document.getElementById('modalTotalDisplay').innerText = formatIDR(total);
        document.getElementById('jumlahBayar').value = '';
        document.getElementById('kembalianDisplay').innerText = 'Rp 0';
        document.getElementById('kembalianDisplay').className = 'fs-5 fw-bold text-secondary';
        paymentModal.show();
        setTimeout(() => document.getElementById('jumlahBayar').focus(), 500);
    }

    document.getElementById('jumlahBayar').addEventListener('input', function() {
        const total = cart.reduce((acc, item) => acc + (item.harga * item.qty), 0);
        const bayar = Number(this.value.replace(/\D/g, '')); 
        const kembalianEl = document.getElementById('kembalianDisplay');

        if(bayar >= total) {
            kembalianEl.innerText = formatIDR(bayar - total);
            kembalianEl.className = 'fs-5 fw-bold text-success';
        } else {
            kembalianEl.innerText = "Kurang " + formatIDR(total - bayar);
            kembalianEl.className = 'fs-5 fw-bold text-danger';
        }
    });

    document.getElementById('btnSimpanTransaksi').addEventListener('click', function() {
        const total = cart.reduce((acc, item) => acc + (item.harga * item.qty), 0);
        const bayar = Number(document.getElementById('jumlahBayar').value.replace(/\D/g, ''));

        if(document.getElementById('metodePembayaran').value === 'tunai' && bayar < total) {
            alert('Uang pembayaran kurang!');
            return;
        }

        const btn = this;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menyimpan...';
        btn.disabled = true;

        fetch("{{ route('kasir.pos.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                pelanggan: document.getElementById('namaPelanggan').value || 'Umum',
                nomor_wa: document.getElementById('nomorWA').value,
                kasir_id: "{{ auth()->id() }}",
                items: cart,
                metode: document.getElementById('metodePembayaran').value,
                bayar: bayar
            })
        })
        .then(res => res.json())
        .then(res => {
            btn.innerHTML = 'Proses Transaksi';
            btn.disabled = false;
            
            if(res.success) {
                cart = [];
                renderCart();
                paymentModal.hide();

                // Cek apakah ada link WhatsApp
                if(res.wa_link) {
                    document.getElementById('waButton').href = res.wa_link;
                    waModal.show();
                } else {
                    alert('Transaksi Berhasil!');
                }
            } else {
                alert('Gagal: ' + res.message);
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = 'Proses Transaksi';
            btn.disabled = false;
            alert('Error server.');
        });
    });
</script>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Misth - Sayuran Hidroponik Segar</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary: #2d8653;
            --primary-dark: #1a4731;
            --primary-light: #4caf50;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Navbar scroll effect */
        .navbar-transparent { background: transparent !important; transition: background 0.3s; }
        .navbar-scrolled { background: white !important; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-scrolled .navbar-brand, .navbar-scrolled .nav-link { color: var(--primary-dark) !important; }
        .navbar-transparent .navbar-brand, .navbar-transparent .nav-link { color: white !important; }
        
        .navbar-scrolled .btn-outline-light {
            color: var(--primary) !important;
            border-color: var(--primary) !important;
        }
        .navbar-scrolled .btn-outline-light:hover {
            background-color: var(--primary) !important;
            color: white !important;
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(135deg, #1a4731 0%, #2d8653 50%, #4caf50 100%);
            position: relative;
            overflow: hidden;
            color: white;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.05) 0%, transparent 50%),
                              radial-gradient(circle at 80% 20%, rgba(255,255,255,0.05) 0%, transparent 50%);
        }

        /* Product card hover */
        .product-card { transition: transform 0.2s, box-shadow 0.2s; border: none; border-radius: 12px; overflow: hidden; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .product-img { height: 200px; object-fit: cover; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); }
        .placeholder-img { height: 200px; background: linear-gradient(135deg, #e8f5e9, #c8e6c9); display: flex; align-items: center; justify-content: center; color: var(--primary-dark); font-weight: 600; font-size: 1.25rem; text-align: center; padding: 1rem; }

        /* Feature card */
        .feature-card { border: none; border-radius: 12px; padding: 2rem; text-align: center; transition: box-shadow 0.2s; height: 100%; }
        .feature-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .feature-icon { font-size: 2.5rem; margin-bottom: 1rem; color: var(--primary); }
    </style>
</head>
<body>

    <!-- 1. Navbar -->
    <nav id="mainNavbar" class="navbar navbar-expand-lg navbar-transparent fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="{{ route('landing') }}">
                <i class="bi bi-flower2 fs-5 me-2"></i> Misth
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 fw-medium">Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 2. Hero Section -->
    <section class="hero-section min-vh-100 d-flex align-items-center">
        <div class="container position-relative z-1">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8 col-md-10 mt-5 pt-5">
                    <span class="badge bg-light text-success rounded-pill px-3 py-2 mb-4 fs-6">
                        <i class="bi bi-patch-check-fill fs-6 me-1"></i> 100% Hidroponik Segar
                    </span>
                    <h1 class="display-4 fw-bold mb-4">Sayuran Hidroponik Segar, Langsung dari Kebun ke Mejamu</h1>
                    <p class="lead mb-5 opacity-75">
                        Nikmati sayuran berkualitas tinggi yang ditanam dengan teknologi hidroponik modern. Bebas pestisida berbahaya, kaya nutrisi, dan dipanen segar setiap hari.
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="#produk" class="btn btn-light text-success rounded-pill px-4 py-3 fw-bold shadow-sm">
                            Lihat Produk Kami
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4 py-3 fw-bold">
                            Masuk ke Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Keunggulan Section -->
    <section class="py-5 bg-white">
        <div class="container my-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon"><i class="bi bi-flower1"></i></div>
                        <h4 class="fw-bold mb-3">Hidroponik Modern</h4>
                        <p class="text-muted mb-0">Ditanam tanpa tanah menggunakan sistem NFT & DFT untuk mengoptimalkan penyerapan nutrisi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon"><i class="bi bi-droplet-half"></i></div>
                        <h4 class="fw-bold mb-3">Tanpa Pestisida Kimia</h4>
                        <p class="text-muted mb-0">Aman dikonsumsi langsung, ditanam dalam greenhouse tertutup sehingga bebas dari residu berbahaya.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card">
                        <div class="feature-icon"><i class="bi bi-truck"></i></div>
                        <h4 class="fw-bold mb-3">Panen Harian</h4>
                        <p class="text-muted mb-0">Dipanen segar setiap hari, dan langsung dikirim agar sampai ke tangan Anda dalam kondisi terbaik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Produk Unggulan Section -->
    <section id="produk" class="py-5 bg-light">
        <div class="container my-5">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-dark">Produk Segar Kami</h2>
                <p class="text-muted">Hasil panen terbaik hari ini, siap untuk meja makan Anda.</p>
            </div>
            
            <div class="row g-4">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <div class="card product-card h-100 shadow-sm">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" class="card-img-top product-img" alt="{{ $product->name }}">
                            @else
                                <div class="card-img-top placeholder-img">
                                    {{ $product->name }}
                                </div>
                            @endif
                            
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title fw-bold mb-0">{{ $product->name }}</h5>
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill">
                                        Grade {{ optional($product->harvest)->quality_grade ?? 'A' }}
                                    </span>
                                </div>
                                
                                <p class="text-muted small mb-3">
                                    <i class="bi bi-flower1 text-success me-1"></i> 
                                    {{ optional(optional($product->harvest)->planting)->plantType->name ?? 'Sayuran Hidroponik' }}
                                </p>
                                
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <h4 class="fw-bold text-success mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h4>
                                    <span class="badge bg-secondary rounded-pill">Sisa Stok: {{ $product->stock }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-basket text-muted" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mt-3">Produk Segera Hadir</h4>
                        <p class="text-muted">Belum ada produk yang tersedia saat ini. Silakan cek kembali nanti.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. CTA Section -->
    <section class="py-5 text-center text-white" style="background: linear-gradient(135deg, #1a4731 0%, #2d8653 100%);">
        <div class="container my-5">
            <h2 class="fw-bold mb-3">Siap Memesan Sayuran Segar?</h2>
            <p class="lead mb-4 opacity-75">Masuk ke akun Anda dan mulai berbelanja sayuran hidroponik berkualitas tinggi.</p>
            <a href="{{ route('login') }}" class="btn btn-light text-success rounded-pill px-5 py-3 fw-bold shadow">
                Masuk Sekarang <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </section>

    <!-- 6. Footer -->
    <footer class="py-4 text-center text-white" style="background-color: #1a1a2e;">
        <div class="container">
            <p class="mb-1">© 2026 Misth. Semua hak dilindungi.</p>
            <p class="text-muted small mb-0">Dari kebun hidroponik kami, langsung ke meja makanmu. <i class="bi bi-flower2 fs-6 ms-1"></i></p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('mainNavbar');
            if (window.scrollY > 50) {
                navbar.classList.remove('navbar-transparent');
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.add('navbar-transparent');
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Smooth scroll untuk anchor
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>

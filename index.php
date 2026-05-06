<?php
session_start();
include 'theme.php';
initTheme();

$theme = getTheme();
$isDark = $theme === 'dark';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RS Hermana Makassar - Rumah Sakit Digital Terdepan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
    <style>
        :root {
            --color-purple: #7c3aed;
            --color-purple-dark: #6d28d9;
            --color-purple-light: #a78bfa;
        }
        
        .btn-purple {
            @apply bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-xl transition duration-300 transform hover:scale-105 active:scale-95;
        }

        .btn-purple-outline {
            @apply border-2 border-purple-600 text-purple-600 dark:text-purple-400 dark:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900 font-semibold py-2 px-6 rounded-xl transition duration-300;
        }

        .hero-gradient {
            background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        }

        .dark .hero-gradient {
            background: linear-gradient(135deg, #5b21b6 0%, #4c1d95 100%);
        }

        .card-shadow {
            @apply shadow-lg hover:shadow-2xl transition duration-300 transform hover:scale-105;
        }

        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .divGambar {
          margin: 7%;
          justify-content: center;
        }
        .divGambar img {
          border-radius: 20px;
          width: 100%;
          height: auto;
          transition: border-radius 0.5s cubic-bezier(0.4, 0, 0.2, 1), 
                      transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
                      will-change: border-radius, transform;
          backface-visibility: hidden;
        }
                .divGambar img {
          border-radius: 20px;
          width: 100%;
          height: auto;
          /* Menambahkan transisi agar perubahan radius terasa smooth */
          transition: border-radius 0.4s ease, transform 0.4s ease;
        }
        
        .divGambar img:hover {
          /* Nilai radius yang ingin dicapai saat hover (misal: lebih bulat atau kotak) */
          border-radius: 60px; 
          /* Opsional: sedikit scale up agar efek lebih dinamis */
          transform: scale(1.03);
        }

        .divGambar p {
          font-weight: 500;
          font-family: Segoe UI;
          margin: 20px;
          font-size: 15px;
        }
    </style>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-white text-gray-900'; ?> transition duration-300">

    <!-- Navbar -->
    <nav class="<?php echo $isDark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'; ?> border-b sticky top-0 z-50 shadow-md">
        <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-xl">🏥</span>
                </div>
                <div>
                    <h1 class="font-bold text-xl text-purple-600">RS Hermana</h1>
                    <p class="text-xs <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Makassar</p>
                </div>
            </div>

            <div class="hidden md:flex gap-8 items-center">
                <a href="#tentang" class="<?php echo $isDark ? 'hover:text-purple-400' : 'hover:text-purple-600'; ?> transition font-medium">Tentang</a>
                <a href="#fasilitas" class="<?php echo $isDark ? 'hover:text-purple-400' : 'hover:text-purple-600'; ?> transition font-medium">Fasilitas</a>
                <a href="#alamat" class="<?php echo $isDark ? 'hover:text-purple-400' : 'hover:text-purple-600'; ?> transition font-medium">Alamat</a>
            </div>

            <div class="flex items-center gap-4">
                <!-- Theme Toggle -->
                <button onclick="toggleTheme()" id="themeBtn" class="p-2 rounded-lg <?php echo $isDark ? 'bg-gray-700 text-yellow-400' : 'bg-gray-200 text-gray-600'; ?> hover:scale-110 transition">
                    <?php echo $isDark ? '☀️' : '🌙'; ?>
                </button>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="btn-purple text-sm">Dashboard</a>
                    <a href="logout.php" class="text-red-600 hover:text-red-700 font-semibold">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn-purple text-sm">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient text-white py-20 md:py-32 px-4 fade-in">
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">Selamat Datang di RS Hermana Makassar</h2>
                <p class="text-lg md:text-xl mb-8 opacity-90">Rumah sakit berbasis teknologi AI terdepan di Sulawesi Selatan. Memberikan pelayanan kesehatan berkualitas dengan inovasi terkini.</p>
                <div class="flex gap-4 flex-wrap">
                    <a href="#fasilitas" class="bg-white text-purple-600 font-bold py-3 px-8 rounded-xl hover:shadow-lg transition transform hover:scale-105">Jelajahi Layanan</a>
                    <a href="login.php" class="border-2 border-white text-white font-bold py-3 px-8 rounded-xl hover:bg-white hover:text-purple-600 transition transform hover:scale-105">Masuk Sekarang</a>
                </div>
            </div>
            <div class="hidden md:flex justify-center">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8 text-center">
                    <div class="text-6xl mb-4">🏥</div>
                    <p class="text-lg opacity-90">Fasilitas Modern & Terpadu</p>
                </div>
            </div>
        </div>
    </section>
    
    <div id="gambar" class="divGambar">
      <img src="rsHermana.webp" alt="fotoRS">
      <center>
        <p>Rumah Sakit Hermana Makassar</p>
      </center>
    </div>

    <!-- Tentang RS -->
    <section id="tentang" class="py-16 md:py-24 px-4 <?php echo $isDark ? 'bg-gray-800' : 'bg-gray-50'; ?>">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-3xl md:text-4xl font-bold text-center mb-12 text-purple-600">Tentang RS Hermana Makassar</h3>
            <div class="grid md:grid-cols-2 gap-12">
                <div class="<?php echo $isDark ? 'bg-gray-700' : 'bg-white'; ?> p-8 rounded-2xl card-shadow">
                    <h4 class="text-2xl font-bold mb-4 text-purple-600">Profil Rumah Sakit</h4>
                    <p class="<?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> leading-relaxed mb-4">RS Hermana Makassar adalah institusi kesehatan modern yang berdedikasi untuk memberikan pelayanan terbaik kepada masyarakat Sulawesi Selatan.</p>
                    <p class="<?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> leading-relaxed">Dengan dukungan teknologi AI terkini dan tenaga medis profesional, kami berkomitmen menjadi rumah sakit digital terdepan di Indonesia.</p>
                </div>
                <div class="<?php echo $isDark ? 'bg-gray-700' : 'bg-white'; ?> p-8 rounded-2xl card-shadow">
                    <h4 class="text-2xl font-bold mb-4 text-purple-600">Visi & Misi</h4>
                    <p class="<?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> mb-4"><strong>Visi:</strong> Menjadi rumah sakit digital terdepan di Sulawesi Selatan dengan standar kesehatan internasional.</p>
                    <p class="<?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?>"><strong>Misi:</strong> Memberikan layanan kesehatan berkualitas tinggi, inovatif, dan terjangkau untuk semua kalangan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas & Layanan -->
    <section id="fasilitas" class="py-16 md:py-24 px-4 <?php echo $isDark ? 'bg-gray-900' : 'bg-white'; ?>">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-3xl md:text-4xl font-bold text-center mb-4 text-purple-600">Keunggulan & Fasilitas Terbaik</h3>
            <p class="text-center <?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-12 max-w-2xl mx-auto">Kami menyediakan berbagai layanan kesehatan unggulan dengan teknologi terkini untuk memenuhi kebutuhan Anda.</p>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Fasilitas Card 1 -->
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-8 rounded-xl card-shadow border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">👶</div>
                    <h4 class="text-xl font-bold mb-3 text-purple-600">Klinik Tumbuh Kembang</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Layanan pemeriksaan dan monitoring tumbuh kembang anak dengan dokter spesialis pediatri berpengalaman.</p>
                </div>

                <!-- Fasilitas Card 2 -->
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-8 rounded-xl card-shadow border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">🦷</div>
                    <h4 class="text-xl font-bold mb-3 text-purple-600">Gigi Spesialistik</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Pelayanan kesehatan gigi lengkap dengan peralatan modern dan dokter gigi spesialis bersertifikat internasional.</p>
                </div>

                <!-- Fasilitas Card 3 -->
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-8 rounded-xl card-shadow border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">🔬</div>
                    <h4 class="text-xl font-bold mb-3 text-purple-600">Operasi Bedah AI</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Teknologi operasi bedah berbantu AI dengan presisi tinggi dan tingkat keberhasilan maksimal.</p>
                </div>

                <!-- Fasilitas Card 4 -->
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-8 rounded-xl card-shadow border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">🔊</div>
                    <h4 class="text-xl font-bold mb-3 text-purple-600">USG 4D</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Peralatan ultrasonografi 4D terbaru untuk pemeriksaan janin dan organ internal dengan detail yang jelas.</p>
                </div>

                <!-- Fasilitas Card 5 -->
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-8 rounded-xl card-shadow border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">🏨</div>
                    <h4 class="text-xl font-bold mb-3 text-purple-600">Rawat Inap Premium</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Berbagai pilihan kamar rawat inap dari IGD hingga VVIP dengan fasilitas lengkap dan nyaman.</p>
                </div>

                <!-- Fasilitas Card 6 -->
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-8 rounded-xl card-shadow border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">💊</div>
                    <h4 class="text-xl font-bold mb-3 text-purple-600">Farmasi Terintegrasi</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Sistem farmasi digital otomatis dengan resep elektronik dan pengiriman obat ke ruang pasien.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Alamat & Maps -->
    <section id="alamat" class="py-16 md:py-24 px-4 <?php echo $isDark ? 'bg-gray-800' : 'bg-gray-50'; ?>">
        <div class="max-w-6xl mx-auto">
            <h3 class="text-3xl md:text-4xl font-bold text-center mb-12 text-purple-600">Lokasi RS Hermana</h3>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="<?php echo $isDark ? 'bg-gray-700' : 'bg-white'; ?> p-8 rounded-2xl card-shadow">
                    <h4 class="text-2xl font-bold mb-6 text-purple-600">Alamat Lengkap</h4>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">JALAN</p>
                            <p class="text-lg font-semibold">Jl. Desa Amegakure</p>
                        </div>
                        <div>
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">KOTA</p>
                            <p class="text-lg font-semibold">Makassar City</p>
                        </div>
                        <div>
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">PROVINSI</p>
                            <p class="text-lg font-semibold">South Sulawesi, Indonesia</p>
                        </div>
                        <div class="pt-4 border-t <?php echo $isDark ? 'border-gray-600' : 'border-gray-200'; ?>">
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> mb-2">KONTAK EMERGENCY</p>
                            <p class="text-lg font-bold text-purple-600">☎️ 0895-3246-70592</p>
                        </div>
                    </div>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-700' : 'bg-white'; ?> p-4 rounded-2xl card-shadow overflow-hidden">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3973.8123456789!2d119.40!3d-5.14!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbee2b8b8b8b8b9%3A0x1234567890abcdef!2sJl.%20Desa%20Amegakure%2C%20Makassar!5e0!3m2!1sid!2sid!4v1234567890" 
                        width="100%" 
                        height="300" 
                        style="border:0; border-radius: 8px;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-green-950 text-white py-16 px-4">
        <div class="max-w-6xl mx-auto">
            <div class="grid md:grid-cols-3 gap-12 mb-12">
                <!-- Company Info -->
                <div>
                    <h4 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span class="text-2xl">🏥</span> RS Hermana
                    </h4>
                    <p class="text-green-200 mb-4">Rumah sakit digital terdepan di Sulawesi Selatan dengan standar internasional.</p>
                    <p class="text-sm text-green-300"><strong>PT Medika Loka Manajemen</strong></p>
                </div>

                <!-- Contact Info -->
                <div>
                    <h4 class="text-xl font-bold mb-4">📞 Kontak</h4>
                    <div class="space-y-3">
                        <p class="flex items-center gap-2">
                            <span>📱</span>
                            <a href="https://wa.me/0895324670592" class="hover:text-green-200 transition">WhatsApp: 0895-3246-70592</a>
                        </p>
                        <p class="flex items-center gap-2">
                            <span>📷</span>
                            <a href="https://instagram.com/mazisasmara" target="_blank" class="hover:text-green-200 transition">Instagram: @mazisasmara</a>
                        </p>
                    </div>
                </div>

                <!-- Download Apps -->
                <div>
                    <h4 class="text-xl font-bold mb-4">📲 Download Aplikasi</h4>
                    <p class="text-green-200 mb-4">Unduh "Halo Hermana" untuk akses mudah:</p>
                    <div class="flex gap-3">
                        <a href="#" class="bg-gray-800 hover:bg-gray-700 p-3 rounded-lg transition flex items-center justify-center">
                            <span class="text-2xl">🎮</span>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-gray-700 p-3 rounded-lg transition flex items-center justify-center">
                            <span class="text-2xl">🍎</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-green-800 pt-8">
                <p class="text-center text-green-300">© 2026 Rumah Sakit Hermana. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            fetch('?action=toggle_theme')
                .then(res => res.json())
                .then(data => {
                    const isDark = data.theme === 'dark';
                    document.documentElement.classList.toggle('dark');
                    document.body.classList.toggle('dark:bg-gray-900');
                    document.body.classList.toggle('dark:text-white');
                    document.getElementById('themeBtn').innerHTML = isDark ? '☀️' : '🌙';
                    document.getElementById('themeBtn').classList.toggle('dark:bg-gray-700');
                    document.getElementById('themeBtn').classList.toggle('dark:text-yellow-400');
                    location.reload();
                });
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                if (this.getAttribute('href') !== '#') {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
        });
    </script>
</body>
</html>

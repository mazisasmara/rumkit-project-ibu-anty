<?php
session_start();
include 'config.php';
include 'theme.php';
include 'header_dashboard.php';

initTheme();
checkSession('pasien');

$theme = getTheme();
$isDark = $theme === 'dark';
$userId = getUserId();

// Get user info
$userQuery = "SELECT * FROM users WHERE id = $userId";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

// Get active registrations
$regQuery = "SELECT r.*, k.tipe_kamar, k.harga_per_hari 
             FROM registrasi_rawat r 
             JOIN kamar k ON r.kamar_id = k.id 
             WHERE r.user_id = $userId AND r.status = 'aktif'";
$regResult = mysqli_query($conn, $regQuery);

// Get active prescriptions
$prescQuery = "SELECT * FROM resep WHERE user_id = $userId AND status = 'aktif' LIMIT 5";
$prescResult = mysqli_query($conn, $prescQuery);

// Get doctors count
$dokterCount = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM dokter"));

// Get services count
$serviceCount = 6;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            margin-left: 0;
        }

        @media (min-width: 1024px) {
            body {
                margin-left: 256px;
            }
        }
    </style>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Dashboard Pasien"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h2>
            <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">Kelola kesehatan Anda dengan mudah melalui portal digital kami.</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-purple-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Registrasi Aktif</p>
                        <p class="text-3xl font-bold"><?php echo mysqli_num_rows($regResult); ?></p>
                    </div>
                    <span class="text-3xl">🛏️</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-blue-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Resep Aktif</p>
                        <p class="text-3xl font-bold"><?php echo mysqli_num_rows($prescResult); ?></p>
                    </div>
                    <span class="text-3xl">💊</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Dokter Tersedia</p>
                        <p class="text-3xl font-bold"><?php echo $dokterCount; ?></p>
                    </div>
                    <span class="text-3xl">👨‍⚕️</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-red-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Layanan RS</p>
                        <p class="text-3xl font-bold"><?php echo $serviceCount; ?></p>
                    </div>
                    <span class="text-3xl">🏥</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-4">⚡ Akses Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="pelayanan.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">👨‍⚕️</div>
                    <p class="font-semibold">Lihat Dokter</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Daftar dokter & jadwal</p>
                </a>

                <a href="rawat_inap.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">🛏️</div>
                    <p class="font-semibold">Rawat Inap</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Registrasi kamar rawat</p>
                </a>

                <a href="farmasi.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">💊</div>
                    <p class="font-semibold">Farmasi</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Cek & minta obat</p>
                </a>

                <a href="rekam_medis.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">📋</div>
                    <p class="font-semibold">Rekam Medis</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Lihat riwayat medis</p>
                </a>

                <a href="akun.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">⚙️</div>
                    <p class="font-semibold">Akun Saya</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Ubah data pribadi</p>
                </a>
            </div>
        </div>

        <!-- Active Registrations -->
        <?php if (mysqli_num_rows($regResult) > 0): ?>
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-4">🛏️ Registrasi Rawat Inap Aktif</h3>
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="<?php echo $isDark ? 'bg-gray-700' : 'bg-gray-100'; ?>">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Tipe Kamar</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal Masuk</th>
                                <th class="px-6 py-3 text-left font-semibold">Kondisi</th>
                                <th class="px-6 py-3 text-left font-semibold">Harga/Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            mysqli_data_seek($regResult, 0);
                            while ($reg = mysqli_fetch_assoc($regResult)): 
                            ?>
                            <tr class="<?php echo $isDark ? 'border-b border-gray-700 hover:bg-gray-700' : 'border-b border-gray-200 hover:bg-gray-50'; ?>">
                                <td class="px-6 py-4"><span class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-semibold"><?php echo htmlspecialchars($reg['tipe_kamar']); ?></span></td>
                                <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime($reg['tgl_masuk'])); ?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($reg['kondisi']); ?></td>
                                <td class="px-6 py-4">Rp <?php echo number_format($reg['harga_per_hari'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Recent Activity -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-4">📋 Informasi Akun</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg">
                    <h4 class="text-lg font-bold mb-4 text-purple-600">Data Pribadi</h4>
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Nama</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($user['nama']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">NIK</p>
                            <p class="font-semibold font-mono"><?php echo htmlspecialchars($user['nik']); ?></p>
                        </div>
                        <div>
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Alamat</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($user['alamat'] ?? '-'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg">
                    <h4 class="text-lg font-bold mb-4 text-purple-600">Status Sistem</h4>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Terdaftar Sejak</p>
                            <p class="font-semibold"><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></p>
                        </div>
                        <div class="flex justify-between">
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Status Akun</p>
                            <p class="font-semibold text-green-600">✅ Aktif</p>
                        </div>
                        <div class="mt-4">
                            <a href="akun.php" class="text-purple-600 hover:text-purple-700 font-semibold">Ubah Data →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            fetch('?action=toggle_theme')
                .then(res => res.json())
                .then(data => {
                    location.reload();
                });
        }
    </script>
</body>
</html>

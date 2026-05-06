<?php
session_start();
include 'config.php';
include 'theme.php';
include 'header_dashboard.php';

initTheme();
checkSession('dokter');

$theme = getTheme();
$isDark = $theme === 'dark';
$userId = getUserId();

// Get dokter info
$dokterQuery = "SELECT d.*, u.nama FROM dokter d 
                JOIN users u ON d.user_id = u.id 
                WHERE d.user_id = $userId";
$dokterResult = mysqli_query($conn, $dokterQuery);
$dokter = mysqli_fetch_assoc($dokterResult);

// Get total pasien rawat inap
$totalPasienQuery = "SELECT COUNT(*) as total FROM registrasi_rawat WHERE status = 'aktif'";
$totalPasienResult = mysqli_query($conn, $totalPasienQuery);
$totalPasien = mysqli_fetch_assoc($totalPasienResult)['total'];

// Get resep pending
$resepPendingQuery = "SELECT COUNT(*) as total FROM resep WHERE dokter_id = $userId AND status = 'aktif'";
$resepPendingResult = mysqli_query($conn, $resepPendingQuery);
$resepPending = mysqli_fetch_assoc($resepPendingResult)['total'];

// Get permintaan obat pending
$permintaanPendingQuery = "SELECT COUNT(*) as total FROM permintaan_obat WHERE status = 'pending'";
$permintaanPendingResult = mysqli_query($conn, $permintaanPendingQuery);
$permintaanPending = mysqli_fetch_assoc($permintaanPendingResult)['total'];

// Get recent patients
$recentPasienQuery = "SELECT r.*, u.nama, k.tipe_kamar FROM registrasi_rawat r 
                      JOIN users u ON r.user_id = u.id 
                      JOIN kamar k ON r.kamar_id = k.id 
                      WHERE r.status = 'aktif' 
                      ORDER BY r.created_at DESC LIMIT 5";
$recentPasienResult = mysqli_query($conn, $recentPasienQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - RS Hermana</title>
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
    
    <?php renderDashboardHeader("Dashboard Dokter"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang, <?php echo htmlspecialchars($dokter['nama']); ?>! 👋</h2>
            <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-4">Kelola pasien dan resep digital dengan efisien.</p>
            <div class="flex gap-3 text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">
                <span>🏥 Poli: <strong><?php echo htmlspecialchars($dokter['poli']); ?></strong></span>
                <span>•</span>
                <span>🩺 Spesialis: <strong><?php echo htmlspecialchars($dokter['spesialis']); ?></strong></span>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-purple-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Pasien Rawat Inap</p>
                        <p class="text-3xl font-bold"><?php echo $totalPasien; ?></p>
                    </div>
                    <span class="text-3xl">🛏️</span>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-blue-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Resep Aktif</p>
                        <p class="text-3xl font-bold"><?php echo $resepPending; ?></p>
                    </div>
                    <span class="text-3xl">📋</span>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-green-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Permintaan Obat</p>
                        <p class="text-3xl font-bold"><?php echo $permintaanPending; ?></p>
                    </div>
                    <span class="text-3xl">💊</span>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-red-600">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Status Sistem</p>
                        <p class="text-lg font-bold text-green-600">✅ Aktif</p>
                    </div>
                    <span class="text-3xl">🟢</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-4">⚡ Akses Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="monitoring_pasien.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">👥</div>
                    <p class="font-semibold">Monitoring Pasien</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Lihat data pasien rawat</p>
                </a>

                <a href="rekam_medis_input.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">📋</div>
                    <p class="font-semibold">Input Rekam Medis</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Buat rekam medis pasien</p>
                </a>

                <a href="resep_digital.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">📋</div>
                    <p class="font-semibold">Input Resep Digital</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Buat resep elektronik</p>
                </a>

                <a href="apotek_stok.php" class="<?php echo $isDark ? 'bg-gray-800 hover:bg-gray-700' : 'bg-white hover:shadow-lg'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">📦</div>
                    <p class="font-semibold">Manajemen Apotek</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Kelola stok obat</p>
                </a>

                <a href="logout.php" class="<?php echo $isDark ? 'bg-red-900 hover:bg-red-800' : 'bg-red-50 hover:bg-red-100'; ?> p-6 rounded-xl transition cursor-pointer">
                    <div class="text-3xl mb-3">🚪</div>
                    <p class="font-semibold">Logout</p>
                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Keluar dari sistem</p>
                </a>
            </div>
        </div>

        <!-- Pasien Rawat Inap Terbaru -->
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-4">👥 Pasien Rawat Inap Aktif</h3>
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="<?php echo $isDark ? 'bg-gray-700' : 'bg-gray-100'; ?>">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Nama Pasien</th>
                                <th class="px-6 py-3 text-left font-semibold">Kamar</th>
                                <th class="px-6 py-3 text-left font-semibold">Kondisi</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (mysqli_num_rows($recentPasienResult) > 0) {
                                while ($pasien = mysqli_fetch_assoc($recentPasienResult)): 
                            ?>
                            <tr class="<?php echo $isDark ? 'border-b border-gray-700 hover:bg-gray-700' : 'border-b border-gray-200 hover:bg-gray-50'; ?>">
                                <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($pasien['nama']); ?></td>
                                <td class="px-6 py-4"><span class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-semibold"><?php echo htmlspecialchars($pasien['tipe_kamar']); ?></span></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($pasien['kondisi']); ?></td>
                                <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime($pasien['tgl_masuk'])); ?></td>
                            </tr>
                            <?php 
                                endwhile; 
                            } else {
                                echo '<tr><td colspan="4" class="px-6 py-4 text-center ' . ($isDark ? 'text-gray-400' : 'text-gray-500') . '">Tidak ada pasien rawat inap aktif</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 p-6 rounded-2xl">
            <h4 class="font-bold text-blue-700 dark:text-blue-200 mb-2">💡 Informasi Penting</h4>
            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                <li>• Selalu periksa riwayat pasien sebelum memberikan resep</li>
                <li>• Konfirmasi ketersediaan obat di apotek sebelum menulis resep</li>
                <li>• Update status pasien secara berkala untuk monitoring yang akurat</li>
                <li>• Hubungi apotek jika ada permintaan obat urgent</li>
            </ul>
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

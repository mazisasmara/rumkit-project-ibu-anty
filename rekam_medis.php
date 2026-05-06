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

// Get rekam medis
$rekamQuery = "SELECT rm.*, u.nama as nama_dokter FROM rekam_medis rm 
               JOIN users u ON rm.dokter_id = u.id 
               WHERE rm.user_id = $userId 
               ORDER BY rm.tanggal_pemeriksaan DESC";
$rekamResult = mysqli_query($conn, $rekamQuery);

// Get statistik kesehatan
$totalPemeriksaanQuery = "SELECT COUNT(*) as total FROM rekam_medis WHERE user_id = $userId";
$totalPemeriksaanResult = mysqli_query($conn, $totalPemeriksaanQuery);
$totalPemeriksaan = mysqli_fetch_assoc($totalPemeriksaanResult)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekam Medis - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Rekam Medis"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">📋 Rekam Medis Saya</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Lihat riwayat lengkap pemeriksaan dan hasil konsultasi medis Anda.</p>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-purple-600">
                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Total Pemeriksaan</p>
                <p class="text-3xl font-bold"><?php echo $totalPemeriksaan; ?></p>
            </div>

            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-blue-600">
                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Pemeriksaan Terbaru</p>
                <p class="text-lg font-bold">
                    <?php 
                    mysqli_data_seek($rekamResult, 0);
                    if ($rekam = mysqli_fetch_assoc($rekamResult)) {
                        echo date('d/m/Y', strtotime($rekam['tanggal_pemeriksaan']));
                        mysqli_data_seek($rekamResult, 0);
                    } else {
                        echo '-';
                    }
                    ?>
                </p>
            </div>

            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-green-600">
                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-sm mb-1">Status</p>
                <p class="text-lg font-bold text-green-600">✅ Aktif</p>
            </div>
        </div>

        <!-- Rekam Medis List -->
        <div>
            <h3 class="text-2xl font-bold mb-6">📝 Riwayat Pemeriksaan</h3>
            
            <?php if (mysqli_num_rows($rekamResult) > 0): ?>
                <div class="space-y-6">
                    <?php 
                    mysqli_data_seek($rekamResult, 0);
                    while ($rekam = mysqli_fetch_assoc($rekamResult)): 
                    ?>
                    <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-purple-600 to-blue-600 p-6 text-white">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="text-2xl font-bold">📅 <?php echo date('d MMMM Y', strtotime($rekam['tanggal_pemeriksaan'])); ?></h4>
                                    <p class="text-sm opacity-90">Pemeriksaan oleh: <strong><?php echo htmlspecialchars($rekam['nama_dokter']); ?></strong></p>
                                </div>
                                <span class="<?php echo $rekam['status'] === 'aktif' ? 'bg-green-500' : 'bg-gray-400'; ?> text-white px-4 py-2 rounded-full text-sm font-bold">
                                    <?php echo ucfirst($rekam['status']); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-8 space-y-6">
                            <!-- Keluhan & Diagnosa -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="<?php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?> p-6 rounded-xl">
                                    <h5 class="font-bold text-purple-600 mb-3">🤒 Keluhan Utama</h5>
                                    <p class="<?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?>"><?php echo htmlspecialchars($rekam['keluhan']); ?></p>
                                </div>

                                <div class="<?php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?> p-6 rounded-xl">
                                    <h5 class="font-bold text-blue-600 mb-3">🩺 Diagnosa</h5>
                                    <p class="<?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?>"><?php echo htmlspecialchars($rekam['diagnosa']); ?></p>
                                </div>
                            </div>

                            <!-- Vital Signs -->
                            <div>
                                <h5 class="font-bold text-red-600 mb-4">❤️ Tanda Vital</h5>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                    <div class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?} p-4 rounded-lg text-center">
                                        <p class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} mb-1">Tekanan Darah</p>
                                        <p class="font-bold text-lg"><?php echo htmlspecialchars($rekam['tekanan_darah'] ?? '-'); ?></p>
                                        <p class="text-xs {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?}">mmHg</p>
                                    </div>

                                    <div class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?} p-4 rounded-lg text-center">
                                        <p class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} mb-1">Suhu Tubuh</p>
                                        <p class="font-bold text-lg"><?php echo htmlspecialchars($rekam['suhu_tubuh'] ?? '-'); ?></p>
                                        <p class="text-xs {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?}">Celsius</p>
                                    </div>

                                    <div class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?} p-4 rounded-lg text-center">
                                        <p class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} mb-1">Denyut Nadi</p>
                                        <p class="font-bold text-lg"><?php echo htmlspecialchars($rekam['denyut_nadi'] ?? '-'); ?></p>
                                        <p class="text-xs {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?}">bpm</p>
                                    </div>

                                    <div class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?} p-4 rounded-lg text-center">
                                        <p class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} mb-1">Berat Badan</p>
                                        <p class="font-bold text-lg"><?php echo htmlspecialchars($rekam['berat_badan'] ?? '-'); ?></p>
                                        <p class="text-xs {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?}">kg</p>
                                    </div>

                                    <div class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?} p-4 rounded-lg text-center">
                                        <p class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} mb-1">Tinggi Badan</p>
                                        <p class="font-bold text-lg"><?php echo htmlspecialchars($rekam['tinggi_badan'] ?? '-'); ?></p>
                                        <p class="text-xs {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?}">cm</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Catatan Khusus -->
                            <?php if (!empty($rekam['catatan_khusus'])): ?>
                            <div class="{{php echo $isDark ? 'bg-yellow-900' : 'bg-yellow-50'; ?} border border-yellow-400 dark:border-yellow-700 p-6 rounded-xl">
                                <h5 class="font-bold text-yellow-700 dark:text-yellow-300 mb-2">📌 Catatan Khusus</h5>
                                <p class="{{php echo $isDark ? 'text-yellow-200' : 'text-yellow-800'; ?}"><?php echo htmlspecialchars($rekam['catatan_khusus']); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- Footer -->
                            <div class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} border-t {{php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?} pt-4">
                                <p>Dibuat: <?php echo date('d/m/Y H:i', strtotime($rekam['created_at'])); ?></p>
                                <?php if ($rekam['updated_at'] !== $rekam['created_at']): ?>
                                <p>Diupdate: <?php echo date('d/m/Y H:i', strtotime($rekam['updated_at'])); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} rounded-2xl shadow-lg p-12 text-center">
                    <p class="text-4xl mb-4">📋</p>
                    <p class="{{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} text-lg">Belum ada rekam medis.</p>
                    <p class="{{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} text-sm mt-2">Rekam medis akan muncul setelah Anda melakukan pemeriksaan dengan dokter.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info Box -->
        <div class="mt-12 {{php echo $isDark ? 'bg-blue-900' : 'bg-blue-50'; ?} border border-blue-200 dark:border-blue-700 p-6 rounded-2xl">
            <h4 class="font-bold text-blue-700 dark:text-blue-200 mb-3">💡 Informasi Penting</h4>
            <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-2">
                <li>✓ Rekam medis Anda pribadi dan rahasia sesuai dengan standar privasi kesehatan</li>
                <li>✓ Data vital (tekanan darah, suhu, dll) diukur oleh tenaga medis profesional</li>
                <li>✓ Simpan rekam medis ini sebagai referensi untuk pemeriksaan berikutnya</li>
                <li>✓ Hubungi dokter jika ada pertanyaan tentang hasil pemeriksaan Anda</li>
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

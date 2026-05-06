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
$success = '';
$error = '';

// Process input rekam medis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = sanitize($_POST['user_id']);
    $tanggal_pemeriksaan = sanitize($_POST['tanggal_pemeriksaan']);
    $keluhan = sanitize($_POST['keluhan']);
    $diagnosa = sanitize($_POST['diagnosa']);
    $tekanan_darah = sanitize($_POST['tekanan_darah']);
    $suhu_tubuh = sanitize($_POST['suhu_tubuh']);
    $denyut_nadi = sanitize($_POST['denyut_nadi']);
    $berat_badan = sanitize($_POST['berat_badan']);
    $tinggi_badan = sanitize($_POST['tinggi_badan']);
    $catatan_khusus = sanitize($_POST['catatan_khusus']);

    if (empty($user_id) || empty($tanggal_pemeriksaan) || empty($keluhan) || empty($diagnosa)) {
        $error = 'Field keluhan dan diagnosa harus diisi!';
    } else {
        $query = "INSERT INTO rekam_medis (user_id, dokter_id, tanggal_pemeriksaan, keluhan, diagnosa, tekanan_darah, suhu_tubuh, denyut_nadi, berat_badan, tinggi_badan, catatan_khusus, status) 
                  VALUES ($user_id, $userId, '$tanggal_pemeriksaan', '$keluhan', '$diagnosa', '$tekanan_darah', '$suhu_tubuh', '$denyut_nadi', '$berat_badan', '$tinggi_badan', '$catatan_khusus', 'aktif')";
        
        if (mysqli_query($conn, $query)) {
            $success = '✅ Rekam medis berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan rekam medis. Coba lagi!';
        }
    }
}

// Get pasien untuk dropdown
$pasienQuery = "SELECT DISTINCT u.id, u.nama FROM users u 
                WHERE u.role = 'pasien' 
                ORDER BY u.nama";
$pasienResult = mysqli_query($conn, $pasienQuery);

// Get rekam medis list
$rekamListQuery = "SELECT rm.*, u.nama as nama_pasien FROM rekam_medis rm 
                   JOIN users u ON rm.user_id = u.id 
                   WHERE rm.dokter_id = $userId 
                   ORDER BY rm.tanggal_pemeriksaan DESC LIMIT 10";
$rekamListResult = mysqli_query($conn, $rekamListQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Rekam Medis - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Input Rekam Medis"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">📋 Input Rekam Medis Digital</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Buat dan kelola rekam medis digital pasien dengan mudah.</p>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Form Input -->
            <div class="lg:col-span-1">
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg p-8 sticky top-20">
                    <h3 class="text-2xl font-bold mb-6 text-purple-600">➕ Buat Rekam Medis</h3>

                    <?php if (!empty($success)): ?>
                        <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                            ⚠️ <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <!-- Pasien -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Pilih Pasien *</label>
                            <select name="user_id" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                                <option value="">-- Pilih Pasien --</option>
                                <?php 
                                mysqli_data_seek($pasienResult, 0);
                                while ($pasien = mysqli_fetch_assoc($pasienResult)): 
                                ?>
                                <option value="<?php echo $pasien['id']; ?>"><?php echo htmlspecialchars($pasien['nama']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Tanggal Pemeriksaan -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Tanggal Pemeriksaan *</label>
                            <input type="date" name="tanggal_pemeriksaan" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                        </div>

                        <!-- Keluhan -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Keluhan Utama *</label>
                            <textarea name="keluhan" placeholder="Jelaskan keluhan pasien" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" required></textarea>
                        </div>

                        <!-- Diagnosa -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Diagnosa *</label>
                            <textarea name="diagnosa" placeholder="Tuliskan diagnosa medis" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3" required></textarea>
                        </div>

                        <!-- Tanda Vital Section -->
                        <div class="mb-6 pb-6 border-b <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?>">
                            <p class="font-bold text-purple-600 mb-4">❤️ Tanda Vital</p>

                            <div class="mb-4">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-xs font-bold mb-1">Tekanan Darah</label>
                                <input type="text" name="tekanan_darah" placeholder="120/80" class="w-full px-3 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>

                            <div class="mb-4">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-xs font-bold mb-1">Suhu Tubuh</label>
                                <input type="text" name="suhu_tubuh" placeholder="36.5°C" class="w-full px-3 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>

                            <div class="mb-4">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-xs font-bold mb-1">Denyut Nadi</label>
                                <input type="text" name="denyut_nadi" placeholder="72 bpm" class="w-full px-3 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>

                            <div class="mb-4">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-xs font-bold mb-1">Berat Badan</label>
                                <input type="text" name="berat_badan" placeholder="65 kg" class="w-full px-3 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>

                            <div class="mb-4">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-xs font-bold mb-1">Tinggi Badan</label>
                                <input type="text" name="tinggi_badan" placeholder="170 cm" class="w-full px-3 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-600">
                            </div>
                        </div>

                        <!-- Catatan Khusus -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Catatan Khusus</label>
                            <textarea name="catatan_khusus" placeholder="Catatan atau instruksi khusus untuk pasien" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3"></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 active:scale-95">
                            💾 Simpan Rekam Medis
                        </button>
                    </form>
                </div>
            </div>

            <!-- Riwayat Rekam Medis -->
            <div class="lg:col-span-2">
                <h3 class="text-2xl font-bold mb-6">📋 Riwayat Rekam Medis</h3>
                
                <div class="space-y-4">
                    <?php 
                    if (mysqli_num_rows($rekamListResult) > 0) {
                        while ($rekam = mysqli_fetch_assoc($rekamListResult)): 
                    ?>
                    <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-xl shadow-lg border-l-4 border-purple-600">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Pasien</p>
                                <h4 class="text-lg font-bold"><?php echo htmlspecialchars($rekam['nama_pasien']); ?></h4>
                                <p class="text-xs <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">📅 <?php echo date('d/m/Y', strtotime($rekam['tanggal_pemeriksaan'])); ?></p>
                            </div>
                            <span class="<?php echo $rekam['status'] === 'aktif' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'; ?> px-3 py-1 rounded-full text-sm font-bold">
                                <?php echo ucfirst($rekam['status']); ?>
                            </span>
                        </div>

                        <div class="mb-3">
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Keluhan</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($rekam['keluhan']); ?></p>
                        </div>

                        <div class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-50'; ?} p-3 rounded-lg">
                            <p class="text-sm {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Diagnosa</p>
                            <p class="font-semibold"><?php echo htmlspecialchars($rekam['diagnosa']); ?></p>
                        </div>

                        <?php if (!empty($rekam['tekanan_darah'])): ?>
                        <div class="mt-3 pt-3 border-t {{php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?>">
                            <p class="text-xs {{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?}} mb-2">Tanda Vital: <strong><?php echo htmlspecialchars($rekam['tekanan_darah']); ?></strong> | <strong><?php echo htmlspecialchars($rekam['suhu_tubuh']); ?></strong> | <strong><?php echo htmlspecialchars($rekam['denyut_nadi']); ?></strong></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php 
                        endwhile;
                    } else {
                        echo '<div class="text-center py-12"><p class="' . ($isDark ? 'text-gray-400' : 'text-gray-500') . '">Belum ada rekam medis yang dibuat.</p></div>';
                    }
                    ?>
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

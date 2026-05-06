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

// Process resep input
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = sanitize($_POST['user_id']);
    $obat_id = sanitize($_POST['obat_id']);
    $dosis = sanitize($_POST['dosis']);
    $durasi = sanitize($_POST['durasi']);
    $catatan = sanitize($_POST['catatan']);

    if (empty($user_id) || empty($obat_id) || empty($dosis) || empty($durasi)) {
        $error = 'Semua field harus diisi!';
    } else {
        $query = "INSERT INTO resep (user_id, dokter_id, obat_id, dosis, durasi, catatan, status) 
                  VALUES ($user_id, $userId, $obat_id, '$dosis', $durasi, '$catatan', 'aktif')";
        
        if (mysqli_query($conn, $query)) {
            $success = '✅ Resep berhasil ditambahkan!';
        } else {
            $error = 'Gagal menambahkan resep. Coba lagi!';
        }
    }
}

// Get pasien untuk dropdown
$pasienQuery = "SELECT DISTINCT u.id, u.nama FROM users u 
                WHERE u.role = 'pasien' 
                ORDER BY u.nama";
$pasienResult = mysqli_query($conn, $pasienQuery);

// Get obat untuk dropdown
$obatQuery = "SELECT * FROM obat WHERE stok > 0 ORDER BY nama_obat";
$obatResult = mysqli_query($conn, $obatQuery);

// Get resep list
$resepListQuery = "SELECT r.*, u.nama as nama_pasien, o.nama_obat FROM resep r 
                   JOIN users u ON r.user_id = u.id 
                   JOIN obat o ON r.obat_id = o.id 
                   WHERE r.dokter_id = $userId 
                   ORDER BY r.created_at DESC";
$resepListResult = mysqli_query($conn, $resepListQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resep Digital - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Resep Digital"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">📋 Input Resep Digital</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Buat dan kelola resep elektronik untuk pasien.</p>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Form Input Resep -->
            <div class="lg:col-span-1">
                <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} rounded-2xl shadow-lg p-8 sticky top-20">
                    <h3 class="text-2xl font-bold mb-6 text-purple-600">➕ Tambah Resep</h3>

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
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Pilih Pasien</label>
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

                        <!-- Obat -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Pilih Obat</label>
                            <select name="obat_id" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                                <option value="">-- Pilih Obat --</option>
                                <?php 
                                mysqli_data_seek($obatResult, 0);
                                while ($obat = mysqli_fetch_assoc($obatResult)): 
                                ?>
                                <option value="<?php echo $obat['id']; ?>"><?php echo htmlspecialchars($obat['nama_obat']); ?> (Stok: <?php echo $obat['stok']; ?>)</option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Dosis -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Dosis</label>
                            <input 
                                type="text" 
                                name="dosis" 
                                placeholder="Contoh: 3x sehari 1 tablet" 
                                class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                required
                            >
                        </div>

                        <!-- Durasi -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Durasi (Hari)</label>
                            <input 
                                type="number" 
                                name="durasi" 
                                min="1" 
                                placeholder="Jumlah hari" 
                                class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                required
                            >
                        </div>

                        <!-- Catatan -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Catatan (Opsional)</label>
                            <textarea 
                                name="catatan" 
                                placeholder="Catatan khusus" 
                                class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                rows="3"
                            ></textarea>
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 active:scale-95">
                            ✏️ Buat Resep
                        </button>
                    </form>
                </div>
            </div>

            <!-- Daftar Resep -->
            <div class="lg:col-span-2">
                <h3 class="text-2xl font-bold mb-6">📋 Riwayat Resep Saya</h3>
                <div class="space-y-4">
                    <?php 
                    if (mysqli_num_rows($resepListResult) > 0) {
                        while ($resep = mysqli_fetch_assoc($resepListResult)): 
                    ?>
                    <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} p-6 rounded-xl shadow-lg border-l-4 border-purple-600">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Pasien</p>
                                <h4 class="text-lg font-bold"><?php echo htmlspecialchars($resep['nama_pasien']); ?></h4>
                            </div>
                            <span class="<?php echo $resep['status'] === 'aktif' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'; ?> px-3 py-1 rounded-full text-sm font-bold">
                                <?php echo ucfirst($resep['status']); ?>
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Obat</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($resep['nama_obat']); ?></p>
                            </div>
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Dosis</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($resep['dosis']); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Durasi</p>
                                <p class="font-semibold"><?php echo $resep['durasi']; ?> Hari</p>
                            </div>
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Tanggal</p>
                                <p class="font-semibold"><?php echo date('d/m/Y', strtotime($resep['created_at'])); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($resep['catatan'])): ?>
                        <div class="mt-3 pt-3 border-t <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?>">
                            <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Catatan</p>
                            <p class="text-sm font-semibold"><?php echo htmlspecialchars($resep['catatan']); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php 
                        endwhile;
                    } else {
                        echo '<div class="text-center py-12"><p class="' . ($isDark ? 'text-gray-400' : 'text-gray-500') . '">Belum ada resep yang dibuat.</p></div>';
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

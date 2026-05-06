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
$success = '';
$error = '';

// Process permintaan obat
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'minta_obat') {
    $obat_id = sanitize($_POST['obat_id']);
    $jumlah = sanitize($_POST['jumlah']);
    $alasan = sanitize($_POST['alasan']);

    if (empty($obat_id) || empty($jumlah)) {
        $error = 'Obat dan jumlah harus diisi!';
    } else {
        $query = "INSERT INTO permintaan_obat (user_id, obat_id, jumlah, alasan, status) 
                  VALUES ($userId, $obat_id, $jumlah, '$alasan', 'pending')";
        
        if (mysqli_query($conn, $query)) {
            $success = '✅ Permintaan obat berhasil dikirim! Status: Pending Persetujuan';
        } else {
            $error = 'Gagal mengirim permintaan. Coba lagi!';
        }
    }
}

// Get obat list
$obatQuery = "SELECT * FROM obat WHERE stok > 0 ORDER BY nama_obat";
$obatResult = mysqli_query($conn, $obatQuery);

// Get user resep
$prescQuery = "SELECT r.*, o.nama_obat FROM resep r 
               JOIN obat o ON r.obat_id = o.id 
               WHERE r.user_id = $userId AND r.status = 'aktif'";
$prescResult = mysqli_query($conn, $prescQuery);

// Get permintaan history
$permQuery = "SELECT p.*, o.nama_obat FROM permintaan_obat p 
              JOIN obat o ON p.obat_id = o.id 
              WHERE p.user_id = $userId 
              ORDER BY p.created_at DESC LIMIT 10";
$permResult = mysqli_query($conn, $permQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmasi - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Farmasi"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">💊 Farmasi RS Hermana</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Cek ketersediaan obat dan ajukan permintaan obat digital.</p>

        <!-- Tabs Navigation -->
        <div class="flex gap-4 mb-8 border-b <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?>">
            <button class="tab-btn px-6 py-3 font-semibold text-purple-600 border-b-2 border-purple-600" onclick="switchTab(event, 'daftar')">
                📋 Daftar Obat
            </button>
            <button class="tab-btn px-6 py-3 font-semibold <?php echo $isDark ? 'text-gray-400 hover:text-gray-300' : 'text-gray-600 hover:text-gray-700'; ?>" onclick="switchTab(event, 'resep')">
                📄 Resep Saya
            </button>
            <button class="tab-btn px-6 py-3 font-semibold <?php echo $isDark ? 'text-gray-400 hover:text-gray-300' : 'text-gray-600 hover:text-gray-700'; ?>" onclick="switchTab(event, 'history')">
                📅 Riwayat Permintaan
            </button>
        </div>

        <!-- Tab: Daftar Obat -->
        <div id="tab-daftar" class="tab-content">
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

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Form Minta Obat -->
                <div class="lg:col-span-1">
                    <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg p-8 sticky top-20">
                        <h3 class="text-2xl font-bold mb-6 text-purple-600">🩺 Minta Obat</h3>

                        <form method="POST" action="">
                            <input type="hidden" name="action" value="minta_obat">

                            <!-- Pilih Obat -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Pilih Obat</label>
                                <select name="obat_id" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                                    <option value="">-- Pilih Obat --</option>
                                    <?php 
                                    mysqli_data_seek($obatResult, 0);
                                    while ($obat = mysqli_fetch_assoc($obatResult)): 
                                    ?>
                                    <option value="<?php echo $obat['id']; ?>">
                                        <?php echo htmlspecialchars($obat['nama_obat']); ?> (Stok: <?php echo $obat['stok']; ?>)
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Jumlah -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Jumlah (Strip/Botol)</label>
                                <input type="number" name="jumlah" min="1" placeholder="Masukkan jumlah" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                            </div>

                            <!-- Alasan -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Alasan (Opsional)</label>
                                <textarea name="alasan" placeholder="Jelaskan kebutuhan Anda" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="3"></textarea>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 active:scale-95">
                                📨 Kirim Permintaan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Daftar Obat Tersedia -->
                <div class="lg:col-span-2">
                    <div class="grid gap-4">
                        <?php 
                        mysqli_data_seek($obatResult, 0);
                        while ($obat = mysqli_fetch_assoc($obatResult)): 
                        ?>
                        <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-xl shadow-lg border-l-4 border-purple-600">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">💊 <?php echo htmlspecialchars($obat['kategori']); ?></p>
                                    <h4 class="text-xl font-bold"><?php echo htmlspecialchars($obat['nama_obat']); ?></h4>
                                </div>
                                <span class="bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200 px-3 py-1 rounded-full text-sm font-bold">
                                    Stok: <?php echo $obat['stok']; ?>
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Jenis</p>
                                    <p class="font-semibold"><?php echo htmlspecialchars($obat['jenis']); ?></p>
                                </div>
                                <div>
                                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Harga</p>
                                    <p class="font-semibold text-purple-600">Rp <?php echo number_format($obat['harga'], 0, ',', '.'); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>

                    <?php if (mysqli_num_rows($obatResult) === 0): ?>
                    <div class="text-center py-12">
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Tidak ada obat tersedia saat ini.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tab: Resep Saya -->
        <div id="tab-resep" class="tab-content hidden">
            <div class="space-y-4">
                <?php if (mysqli_num_rows($prescResult) > 0): ?>
                    <?php while ($presc = mysqli_fetch_assoc($prescResult)): ?>
                    <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-xl shadow-lg border-l-4 border-blue-600">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Nama Obat</p>
                                <h4 class="text-xl font-bold"><?php echo htmlspecialchars($presc['nama_obat']); ?></h4>
                            </div>
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-bold">
                                Aktif
                            </span>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Dosis</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($presc['dosis']); ?></p>
                            </div>
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Durasi</p>
                                <p class="font-semibold"><?php echo $presc['durasi']; ?> Hari</p>
                            </div>
                            <div>
                                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Catatan</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($presc['catatan'] ?? '-'); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="text-center py-12">
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Anda belum memiliki resep aktif.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab: Riwayat Permintaan -->
        <div id="tab-history" class="tab-content hidden">
            <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="<?php echo $isDark ? 'bg-gray-700' : 'bg-gray-100'; ?>">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold">Obat</th>
                                <th class="px-6 py-3 text-left font-semibold">Jumlah</th>
                                <th class="px-6 py-3 text-left font-semibold">Status</th>
                                <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            while ($perm = mysqli_fetch_assoc($permResult)): 
                                $statusColor = $perm['status'] === 'disetujui' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 
                                             ($perm['status'] === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-200' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200');
                            ?>
                            <tr class="<?php echo $isDark ? 'border-b border-gray-700 hover:bg-gray-700' : 'border-b border-gray-200 hover:bg-gray-50'; ?>">
                                <td class="px-6 py-4"><?php echo htmlspecialchars($perm['nama_obat']); ?></td>
                                <td class="px-6 py-4"><?php echo $perm['jumlah']; ?> Strip</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $statusColor; ?>">
                                        <?php echo ucfirst($perm['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4"><?php echo date('d/m/Y H:i', strtotime($perm['created_at'])); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <?php if (mysqli_num_rows($permResult) === 0): ?>
                <div class="text-center py-12 px-6">
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Belum ada riwayat permintaan obat.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function switchTab(e, tab) {
    // 1. Sembunyikan semua konten tab
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
    });

    // 2. Reset semua gaya tombol (hapus warna ungu/border bawah)
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-purple-600', 'border-b-2', 'border-purple-600');
        // Kembalikan ke warna abu-abu sesuai tema
        if (document.body.classList.contains('dark')) {
            btn.classList.add('text-gray-400');
        } else {
            btn.classList.add('text-gray-600');
        }
    });

    // 3. Tampilkan konten tab yang diklik
    const activeTab = document.getElementById('tab-' + tab);
    if (activeTab) {
        activeTab.classList.remove('hidden');
    }

    // 4. Ubah tampilan tombol yang sedang aktif menjadi ungu
    const currentBtn = e.currentTarget;
    currentBtn.classList.add('text-purple-600', 'border-b-2', 'border-purple-600');
    currentBtn.classList.remove('text-gray-400', 'text-gray-600');
}


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

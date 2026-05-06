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

// Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kamar_id = sanitize($_POST['kamar_id']);
    $tgl_masuk = sanitize($_POST['tgl_masuk']);
    $kondisi = sanitize($_POST['kondisi']);

    if (empty($kamar_id) || empty($tgl_masuk) || empty($kondisi)) {
        $error = 'Semua field harus diisi!';
    } else {
        $query = "INSERT INTO registrasi_rawat (user_id, kamar_id, tgl_masuk, status, kondisi) 
                  VALUES ($userId, $kamar_id, '$tgl_masuk', 'aktif', '$kondisi')";
        
        if (mysqli_query($conn, $query)) {
            $success = '✅ Registrasi rawat inap berhasil! Silahkan hubungi resepsionis untuk konfirmasi.';
        } else {
            $error = 'Gagal melakukan registrasi. Coba lagi!';
        }
    }
}

// Get kamar list
$kamarQuery = "SELECT * FROM kamar ORDER BY harga_per_hari";
$kamarResult = mysqli_query($conn, $kamarQuery);

// Get user registrations
$regQuery = "SELECT r.*, k.tipe_kamar, k.harga_per_hari FROM registrasi_rawat r 
             JOIN kamar k ON r.kamar_id = k.id 
             WHERE r.user_id = $userId 
             ORDER BY r.created_at DESC";
$regResult = mysqli_query($conn, $regQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Rawat Inap - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Registrasi Rawat Inap"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">Registrasi Rawat Inap</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Daftar untuk menginap di RS Hermana dengan berbagai pilihan kamar.</p>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Form Registrasi -->
            <div class="lg:col-span-1">
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg p-8 sticky top-20">
                    <h3 class="text-2xl font-bold mb-6 text-purple-600">📋 Form Registrasi</h3>

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
                        <!-- Tipe Kamar -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Pilih Tipe Kamar</label>
                            <select name="kamar_id" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                                <option value="">-- Pilih Kamar --</option>
                                <?php 
                                mysqli_data_seek($kamarResult, 0);
                                while ($kamar = mysqli_fetch_assoc($kamarResult)): 
                                ?>
                                <option value="<?php echo $kamar['id']; ?>">
                                    <?php echo htmlspecialchars($kamar['tipe_kamar']); ?> (Rp <?php echo number_format($kamar['harga_per_hari'], 0, ',', '.'); ?>/hari)
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Tanggal Masuk -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                        </div>

                        <!-- Kondisi Kesehatan -->
                        <div class="mb-6">
                            <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Kondisi Kesehatan</label>
                            <textarea name="kondisi" placeholder="Jelaskan kondisi kesehatan Anda" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" rows="4" required></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 active:scale-95">
                            ✅ Daftar Sekarang
                        </button>
                    </form>
                </div>
            </div>

            <!-- Daftar Kamar & History -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Tipe Kamar -->
                <div>
                    <h3 class="text-2xl font-bold mb-6">🛏️ Pilihan Kamar</h3>
                    <div class="grid gap-4">
                        <?php 
                        mysqli_data_seek($kamarResult, 0);
                        while ($kamar = mysqli_fetch_assoc($kamarResult)): 
                        ?>
                        <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-xl shadow-lg border-l-4 border-purple-600">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="text-xl font-bold"><?php echo htmlspecialchars($kamar['tipe_kamar']); ?></h4>
                                <span class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-bold">
                                    <?php echo $kamar['tersedia']; ?> Tersedia
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                <div>
                                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Harga/Hari</p>
                                    <p class="font-bold text-lg text-purple-600">Rp <?php echo number_format($kamar['harga_per_hari'], 0, ',', '.'); ?></p>
                                </div>
                                <div>
                                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Kapasitas</p>
                                    <p class="font-bold text-lg"><?php echo $kamar['kapasitas']; ?> Tempat</p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Riwayat Registrasi -->
                <div>
                    <h3 class="text-2xl font-bold mb-6">📅 Riwayat Registrasi</h3>
                    <?php if (mysqli_num_rows($regResult) > 0): ?>
                    <div class="space-y-4">
                        <?php while ($reg = mysqli_fetch_assoc($regResult)): ?>
                        <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-xl shadow-lg">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Tipe Kamar</p>
                                    <p class="font-bold text-lg"><?php echo htmlspecialchars($reg['tipe_kamar']); ?></p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-bold 
                                    <?php echo $reg['status'] === 'aktif' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'; ?>">
                                    <?php echo ucfirst($reg['status']); ?>
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                                <div>
                                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Tanggal Masuk</p>
                                    <p class="font-semibold"><?php echo date('d/m/Y', strtotime($reg['tgl_masuk'])); ?></p>
                                </div>
                                <div>
                                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Harga/Hari</p>
                                    <p class="font-semibold">Rp <?php echo number_format($reg['harga_per_hari'], 0, ',', '.'); ?></p>
                                </div>
                            </div>

                            <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm"><?php echo htmlspecialchars($reg['kondisi']); ?></p>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-8">
                        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Belum ada riwayat registrasi.</p>
                    </div>
                    <?php endif; ?>
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

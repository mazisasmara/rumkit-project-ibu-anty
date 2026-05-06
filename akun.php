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

// Get user data
$userQuery = "SELECT * FROM users WHERE id = $userId";
$userResult = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_assoc($userResult);

// Update data pribadi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_data') {
    $nama = sanitize($_POST['nama']);
    $alamat = sanitize($_POST['alamat']);

    if (empty($nama) || empty($alamat)) {
        $error = 'Semua field harus diisi!';
    } else {
        $query = "UPDATE users SET nama = '$nama', alamat = '$alamat' WHERE id = $userId";
        if (mysqli_query($conn, $query)) {
            $success = '✅ Data pribadi berhasil diperbarui!';
            $_SESSION['nama'] = $nama;
            // Refresh user data
            $userResult = mysqli_query($conn, $userQuery);
            $user = mysqli_fetch_assoc($userResult);
        } else {
            $error = 'Gagal memperbarui data. Coba lagi!';
        }
    }
}

// Ganti password
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ganti_password') {
    $old_password = sanitize($_POST['old_password']);
    $new_password = sanitize($_POST['new_password']);
    $confirm_password = sanitize($_POST['confirm_password']);

    if (empty($old_password) || empty($new_password)) {
        $error = 'Password lama dan baru harus diisi!';
    } elseif ($old_password !== $user['password']) {
        $error = 'Password lama tidak sesuai!';
    } elseif ($new_password !== $confirm_password) {
        $error = 'Password baru tidak cocok!';
    } elseif (strlen($new_password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $query = "UPDATE users SET password = '$new_password' WHERE id = $userId";
        if (mysqli_query($conn, $query)) {
            $success = '✅ Password berhasil diubah!';
            $user['password'] = $new_password;
        } else {
            $error = 'Gagal mengubah password. Coba lagi!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Manajemen Akun"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">⚙️ Manajemen Akun</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Kelola data pribadi dan keamanan akun Anda.</p>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Menu Sidebar -->
            <div class="lg:col-span-1">
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden sticky top-20">
                    <div class="space-y-2 p-4">
                        <button onclick="switchTab('profil')" class="menu-btn w-full text-left px-4 py-3 rounded-lg bg-purple-600 text-white font-semibold transition" data-tab="profil">
                            👤 Data Pribadi
                        </button>
                        <button onclick="switchTab('keamanan')" class="menu-btn w-full text-left px-4 py-3 rounded-lg <?php echo $isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'; ?> font-semibold transition" data-tab="keamanan">
                            🔒 Keamanan
                        </button>
                        <button onclick="switchTab('riwayat')" class="menu-btn w-full text-left px-4 py-3 rounded-lg <?php echo $isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'; ?> font-semibold transition" data-tab="riwayat">
                            📋 Riwayat Akun
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-2">
                <!-- Tab: Data Pribadi -->
                <div id="tab-profil" class="tab-content">
                    <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold mb-6 text-purple-600">👤 Data Pribadi</h3>

                        <?php if (!empty($success)): ?>
                            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
                                <?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error) && strpos($error, 'lama') !== false): ?>
                            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                                ⚠️ <?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="action" value="update_data">

                            <!-- Nama -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Nama Lengkap</label>
                                <input 
                                    type="text" 
                                    name="nama" 
                                    value="<?php echo htmlspecialchars($user['nama']); ?>" 
                                    class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                    required
                                >
                            </div>

                            <!-- NIK (Read Only) -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Nomor Identitas (NIK)</label>
                                <input 
                                    type="text" 
                                    value="<?php echo htmlspecialchars($user['nik']); ?>" 
                                    class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600 text-gray-400' : 'bg-gray-100 border-gray-300 text-gray-500'; ?> border rounded-lg cursor-not-allowed" 
                                    readonly
                                    disabled
                                >
                                <p class="text-xs <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> mt-1">NIK tidak dapat diubah</p>
                            </div>

                            <!-- Alamat -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Alamat</label>
                                <textarea 
                                    name="alamat" 
                                    class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                    rows="4" 
                                    required
                                ><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg transition transform hover:scale-105 active:scale-95"
                            >
                                💾 Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tab: Keamanan -->
                <div id="tab-keamanan" class="tab-content hidden">
                    <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold mb-6 text-purple-600">🔒 Keamanan Akun</h3>

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
                            <input type="hidden" name="action" value="ganti_password">

                            <!-- Password Lama -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Password Lama</label>
                                <input 
                                    type="password" 
                                    name="old_password" 
                                    placeholder="Masukkan password lama Anda" 
                                    class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                    required
                                >
                            </div>

                            <!-- Password Baru -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Password Baru</label>
                                <input 
                                    type="password" 
                                    name="new_password" 
                                    placeholder="Minimal 6 karakter" 
                                    class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                    required
                                >
                            </div>

                            <!-- Konfirmasi Password Baru -->
                            <div class="mb-6">
                                <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Konfirmasi Password Baru</label>
                                <input 
                                    type="password" 
                                    name="confirm_password" 
                                    placeholder="Ulangi password baru Anda" 
                                    class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                                    required
                                >
                            </div>

                            <!-- Submit Button -->
                            <button 
                                type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition transform hover:scale-105 active:scale-95"
                            >
                                Ganti Password
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tab: Riwayat Akun -->
                <div id="tab-riwayat" class="tab-content hidden">
                    <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg p-8">
                        <h3 class="text-2xl font-bold mb-6 text-purple-600">📋 Riwayat Akun</h3>

                        <div class="space-y-4">
                            <div class="border-b <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?> pb-4">
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Nama Pengguna</p>
                                <p class="font-semibold"><?php echo htmlspecialchars($user['username']); ?></p>
                            </div>

                            <div class="border-b <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?> pb-4">
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Jenis Akun</p>
                                <p class="font-semibold uppercase"><?php echo $user['role']; ?></p>
                            </div>

                            <div class="border-b <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?> pb-4">
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Terdaftar Sejak</p>
                                <p class="font-semibold"><?php echo date('d MMMM Y H:i', strtotime($user['created_at'])); ?></p>
                            </div>

                            <div class="border-b <?php echo $isDark ? 'border-gray-700' : 'border-gray-200'; ?> pb-4">
                                <p class="text-sm <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Status Akun</p>
                                <p class="font-semibold text-green-600">✅ Aktif</p>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mt-6">
                                <p class="text-sm text-blue-700 dark:text-blue-300">
                                    💡 <strong>Tip Keamanan:</strong> Selalu gunakan password yang kuat dan jangan bagikan informasi akun Anda kepada siapapun.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.menu-btn').forEach(btn => {
                btn.classList.remove('bg-purple-600', 'text-white');
                btn.classList.add('<?php echo $isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'; ?>');
            });

            // Show selected tab
            document.getElementById('tab-' + tab).classList.remove('hidden');
            event.target.classList.add('bg-purple-600', 'text-white');
            event.target.classList.remove('<?php echo $isDark ? 'hover:bg-gray-700' : 'hover:bg-gray-100'; ?>');
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

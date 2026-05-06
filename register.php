<?php
session_start();
include 'config.php';
include 'theme.php';
initTheme();

$error = '';
$success = '';
$theme = getTheme();
$isDark = $theme === 'dark';

// Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nik = sanitize($_POST['nik']);
    $nama = sanitize($_POST['nama']);
    $alamat = sanitize($_POST['alamat']);
    $password = sanitize($_POST['password']);
    $confirm_password = sanitize($_POST['confirm_password']);

    if (empty($nik) || empty($nama) || empty($alamat) || empty($password)) {
        $error = 'Semua field harus diisi!';
    } elseif (strlen($nik) !== 16) {
        $error = 'NIK harus 16 digit!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak cocok!';
    } else {
        // Check if NIK already exists
        $check = mysqli_query($conn, "SELECT id FROM users WHERE nik = '$nik'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'NIK sudah terdaftar!';
        } else {
            $query = "INSERT INTO users (username, password, role, nama, nik, alamat) 
                      VALUES ('$nik', '$password', 'pasien', '$nama', '$nik', '$alamat')";
            
            if (mysqli_query($conn, $query)) {
                $success = 'Pendaftaran berhasil! Silahkan <a href="login.php" class="font-bold text-purple-600">login di sini</a>';
            } else {
                $error = 'Terjadi kesalahan saat mendaftar. Coba lagi!';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - RS Hermana Makassar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .register-card {
            box-shadow: 0 10px 40px rgba(124, 58, 237, 0.1);
        }
        
        .dark .register-card {
            box-shadow: 0 10px 40px rgba(124, 58, 237, 0.3);
        }

        input:focus {
            @apply outline-none ring-2 ring-purple-600 ring-offset-2;
        }

        .dark input:focus {
            @apply ring-offset-gray-900;
        }
    </style>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gradient-to-br from-purple-50 to-blue-50'; ?> min-h-screen flex items-center justify-center px-4 py-8">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600 rounded-2xl mb-4">
                <span class="text-3xl">🏥</span>
            </div>
            <h1 class="text-3xl font-bold text-purple-600">RS Hermana</h1>
            <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mt-2">Daftar Akun Baru</p>
        </div>

        <!-- Theme Toggle -->
        <div class="flex justify-center mb-6">
            <button onclick="toggleTheme()" id="themeBtn" class="p-3 rounded-full <?php echo $isDark ? 'bg-gray-800 text-yellow-400' : 'bg-white text-gray-600'; ?> hover:scale-110 transition shadow-md">
                <?php echo $isDark ? '☀️' : '🌙'; ?>
            </button>
        </div>

        <!-- Register Card -->
        <div class="register-card <?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl p-8 mb-6">
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
                    ✅ <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- NIK Field -->
                <div class="mb-4">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Nomor Identitas (NIK)</label>
                    <input 
                        type="text" 
                        name="nik" 
                        placeholder="Masukkan 16 digit NIK" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        maxlength="16"
                        required
                    >
                </div>

                <!-- Nama Field -->
                <div class="mb-4">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="nama" 
                        placeholder="Masukkan nama lengkap Anda" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        required
                    >
                </div>

                <!-- Alamat Field -->
                <div class="mb-4">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Alamat</label>
                    <textarea 
                        name="alamat" 
                        placeholder="Masukkan alamat lengkap Anda" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        rows="3"
                        required
                    ></textarea>
                </div>

                <!-- Password Field -->
                <div class="mb-4">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Minimal 6 karakter" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        required
                    >
                </div>

                <!-- Confirm Password Field -->
                <div class="mb-6">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="confirm_password" 
                        placeholder="Ulangi password Anda" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 active:scale-95"
                >
                    ✏️ Daftar Sekarang
                </button>
            </form>
        </div>

        <!-- Login Link -->
        <div class="text-center <?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">
            <p>Sudah punya akun? <a href="login.php" class="text-purple-600 hover:text-purple-700 font-bold">Masuk di sini</a></p>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-4">
            <a href="index.php" class="<?php echo $isDark ? 'text-gray-400 hover:text-gray-300' : 'text-gray-600 hover:text-gray-700'; ?> transition">← Kembali ke Beranda</a>
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

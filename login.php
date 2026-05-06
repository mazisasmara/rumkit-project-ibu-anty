<?php
session_start();
include 'config.php';
include 'theme.php';
initTheme();

$error = '';
$theme = getTheme();
$isDark = $theme === 'dark';

// Process login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = sanitize($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Username dan password tidak boleh kosong!';
    } else {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            // Simple password check (plain text)
            if ($password === $user['password']) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['nama'] = $user['nama'];
                
                if ($user['role'] === 'dokter') {
                    redirect('dashboard_dokter.php');
                } else {
                    redirect('dashboard_pasien.php');
                }
            } else {
                $error = 'Password salah!';
            }
        } else {
            $error = 'Username tidak ditemukan!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RS Hermana Makassar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .login-card {
            box-shadow: 0 10px 40px rgba(124, 58, 237, 0.1);
        }
        
        .dark .login-card {
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
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gradient-to-br from-purple-50 to-blue-50'; ?> min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-600 rounded-2xl mb-4">
                <span class="text-3xl">🏥</span>
            </div>
            <h1 class="text-3xl font-bold text-purple-600">RS Hermana</h1>
            <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mt-2">Masuk ke Portal Kesehatan Anda</p>
        </div>

        <!-- Theme Toggle -->
        <div class="flex justify-center mb-6">
            <button onclick="toggleTheme()" id="themeBtn" class="p-3 rounded-full <?php echo $isDark ? 'bg-gray-800 text-yellow-400' : 'bg-white text-gray-600'; ?> hover:scale-110 transition shadow-md">
                <?php echo $isDark ? '☀️' : '🌙'; ?>
            </button>
        </div>

        <!-- Login Card -->
        <div class="login-card <?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl p-8 mb-6">
            
            <?php if (!empty($error)): ?>
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg mb-6">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Username/NIK Field -->
                <div class="mb-6">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Username (NIK)</label>
                    <input 
                        type="text" 
                        name="username" 
                        placeholder="Masukkan NIK Anda (16 digit)" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        required
                    >
                    <p class="text-xs <?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> mt-1">Gunakan nomor identitas Anda</p>
                </div>

                <!-- Password Field -->
                <div class="mb-6">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Masukkan password Anda" 
                        class="w-full px-4 py-3 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg transition"
                        required
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit" 
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg transition transform hover:scale-105 active:scale-95"
                >
                    🔓 Masuk Sekarang
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full <?php echo $isDark ? 'border-gray-700' : 'border-gray-300'; ?> border-t"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="<?php echo $isDark ? 'bg-gray-800 text-gray-400' : 'bg-white text-gray-500'; ?> px-2">atau</span>
                </div>
            </div>

            <!-- Demo Credentials -->
            <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg mb-6">
                <p class="text-sm font-bold text-blue-700 dark:text-blue-300 mb-3">🧪 Akun Demo untuk Testing:</p>
                <div class="space-y-2 text-sm">
                    <p class="text-blue-600 dark:text-blue-400"><strong>Pasien:</strong> 1234567890123456 / password123</p>
                    <p class="text-blue-600 dark:text-blue-400"><strong>Dokter:</strong> 1111111111111111 / dokter123</p>
                </div>
            </div>
        </div>

        <!-- Register Link -->
        <div class="text-center <?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?>">
            <p>Belum punya akun? <a href="register.php" class="text-purple-600 hover:text-purple-700 font-bold">Daftar di sini</a></p>
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

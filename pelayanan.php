<?php
session_start();
include 'config.php';
include 'theme.php';
include 'header_dashboard.php';

initTheme();
checkSession('pasien');

$theme = getTheme();
$isDark = $theme === 'dark';

// Get doctors with details
$dokterQuery = "SELECT d.*, u.nama FROM dokter d 
                JOIN users u ON d.user_id = u.id 
                WHERE u.role = 'dokter'";
$dokterResult = mysqli_query($conn, $dokterQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelayanan & Dokter - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Pelayanan & Dokter"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">Dokter & Layanan RS Hermana</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Temukan dokter spesialis terbaik kami dengan jadwal praktik lengkap.</p>

        <!-- Layanan Unggulan -->
        <div class="mb-12">
            <h3 class="text-2xl font-bold mb-6">🏥 Layanan Unggulan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-purple-600">
                    <div class="text-4xl mb-4">👶</div>
                    <h4 class="text-xl font-bold mb-2">Klinik Tumbuh Kembang</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm">Pemeriksaan dan monitoring tumbuh kembang anak dengan dokter spesialis pediatri.</p>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-blue-600">
                    <div class="text-4xl mb-4">🦷</div>
                    <h4 class="text-xl font-bold mb-2">Gigi Spesialistik</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm">Pelayanan kesehatan gigi lengkap dengan peralatan modern dan spesialis bersertifikat.</p>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-green-600">
                    <div class="text-4xl mb-4">🔬</div>
                    <h4 class="text-xl font-bold mb-2">Operasi Bedah AI</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm">Teknologi operasi bedah berbantu AI dengan presisi tinggi dan aman.</p>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-pink-600">
                    <div class="text-4xl mb-4">🔊</div>
                    <h4 class="text-xl font-bold mb-2">USG 4D</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm">Ultrasonografi 4D untuk pemeriksaan janin dan organ internal dengan detail jelas.</p>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-yellow-600">
                    <div class="text-4xl mb-4">🏨</div>
                    <h4 class="text-xl font-bold mb-2">Rawat Inap Premium</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm">Berbagai pilihan kamar dari IGD hingga VVIP dengan fasilitas lengkap.</p>
                </div>

                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> p-6 rounded-2xl shadow-lg border-l-4 border-red-600">
                    <div class="text-4xl mb-4">💊</div>
                    <h4 class="text-xl font-bold mb-2">Farmasi Terintegrasi</h4>
                    <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> text-sm">Sistem farmasi digital dengan resep elektronik dan pengiriman obat otomatis.</p>
                </div>
            </div>
        </div>

        <!-- Daftar Dokter -->
        <div>
            <h3 class="text-2xl font-bold mb-6">👨‍⚕️ Tim Dokter Kami</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php while ($dokter = mysqli_fetch_assoc($dokterResult)): ?>
                <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition">
                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 h-32 relative">
                        <div class="absolute bottom-0 right-6 transform translate-y-1/2">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center text-4xl">
                                👨‍⚕️
                            </div>
                        </div>
                    </div>

                    <div class="pt-16 px-6 pb-6">
                        <h4 class="text-2xl font-bold mb-1"><?php echo htmlspecialchars($dokter['nama']); ?></h4>
                        <p class="text-purple-600 font-semibold mb-4"><?php echo htmlspecialchars($dokter['spesialis']); ?></p>

                        <div class="space-y-2 mb-6 <?php echo $isDark ? 'text-gray-300' : 'text-gray-600'; ?>">
                            <div class="flex items-center gap-2">
                                <span>🏥</span>
                                <p><strong>Poli:</strong> <?php echo htmlspecialchars($dokter['poli']); ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>⏰</span>
                                <p><strong>Jam:</strong> <?php echo htmlspecialchars($dokter['jam_praktik']); ?></p>
                            </div>
                        </div>

                        <button class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                            Pesan Konsultasi →
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>

            <?php if (mysqli_num_rows($dokterResult) === 0): ?>
            <div class="text-center py-12">
                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?> text-lg">Belum ada dokter terdaftar saat ini.</p>
            </div>
            <?php endif; ?>
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

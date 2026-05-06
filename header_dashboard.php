<?php
// header_dashboard.php - Component header untuk semua halaman dashboard

function renderDashboardHeader($pageTitle = "Dashboard") {
    global $isDark, $theme;
    $isDark = getTheme() === 'dark';
    
    echo <<<HTML
    <nav class="${ $isDark ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200' } border-b sticky top-0 z-40 shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">🏥</span>
                </div>
                <div>
                    <h1 class="font-bold text-lg text-purple-600">RS Hermana</h1>
                    <p class="text-xs ${ $isDark ? 'text-gray-400' : 'text-gray-500' }">$pageTitle</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button onclick="toggleTheme()" id="themeBtn" class="p-2 rounded-lg ${ $isDark ? 'bg-gray-700 text-yellow-400' : 'bg-gray-200 text-gray-600' } hover:scale-110 transition">
                    ${ $isDark ? '☀️' : '🌙' }
                </button>

                <div class="hidden md:flex items-center gap-3">
                    <div class="text-right">
                        <p class="font-semibold text-sm">{$_SESSION['nama']}</p>
                        <p class="text-xs ${ $isDark ? 'text-gray-400' : 'text-gray-500' }">{$_SESSION['role']}</p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        {$_SESSION['nama'][0]}
                    </div>
                </div>

                <a href="logout.php" class="text-red-600 hover:text-red-700 font-semibold hover:scale-110 transition">
                    🚪
                </a>
            </div>
        </div>
    </nav>
    HTML;
}

function renderSidebar($currentPage = '') {
    global $isDark;
    
    $links = [];
    if ($_SESSION['role'] === 'pasien') {
        $links = [
            ['url' => 'dashboard_pasien.php', 'label' => '🏠 Dashboard', 'icon' => '📊'],
            ['url' => 'pelayanan.php', 'label' => '🏥 Pelayanan & Dokter', 'icon' => '👨‍⚕️'],
            ['url' => 'rawat_inap.php', 'label' => '🛏️ Registrasi Rawat Inap', 'icon' => '🛏️'],
            ['url' => 'farmasi.php', 'label' => '💊 Farmasi', 'icon' => '💊'],
            ['url' => 'rekam_medis.php', 'label' => '📋 Rekam Medis', 'icon' => '📋'],
            ['url' => 'akun.php', 'label' => '⚙️ Manajemen Akun', 'icon' => '⚙️'],
        ];
    } else {
        $links = [
            ['url' => 'dashboard_dokter.php', 'label' => '🏠 Dashboard', 'icon' => '📊'],
            ['url' => 'monitoring_pasien.php', 'label' => '👥 Monitoring Pasien', 'icon' => '👥'],
            ['url' => 'rekam_medis_input.php', 'label' => '📋 Input Rekam Medis', 'icon' => '📋'],
            ['url' => 'resep_digital.php', 'label' => '📋 Resep Digital', 'icon' => '📋'],
            ['url' => 'apotek_stok.php', 'label' => '📦 Manajemen Apotek', 'icon' => '📦'],
        ];
    }

    echo '<div class="' . ($isDark ? 'bg-gray-800' : 'bg-gray-50') . ' min-h-screen w-64 p-6 hidden lg:block fixed left-0 top-16">';
    echo '<div class="space-y-2">';

    foreach ($links as $link) {
        $isActive = strpos($currentPage, $link['url']) !== false;
        $activeClass = $isActive ? 'bg-purple-600 text-white' : ($isDark ? 'text-gray-300 hover:bg-gray-700' : 'text-gray-700 hover:bg-gray-100');
        echo '<a href="' . $link['url'] . '" class="block px-4 py-3 rounded-lg transition ' . $activeClass . '">' . $link['label'] . '</a>';
    }

    echo '</div></div>';
}
?>

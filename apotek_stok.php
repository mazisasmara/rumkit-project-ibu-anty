<?php
session_start();
include 'config.php';
include 'theme.php';
include 'header_dashboard.php';

initTheme();
checkSession('dokter');

$theme = getTheme();
$isDark = $theme === 'dark';
$success = '';
$error = '';

// Process update stok
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $obat_id = sanitize($_POST['obat_id']);
    
    if ($_POST['action'] === 'add_stok') {
        $jumlah = sanitize($_POST['jumlah']);
        if (empty($jumlah) || $jumlah <= 0) {
            $error = 'Jumlah harus lebih dari 0!';
        } else {
            $query = "UPDATE obat SET stok = stok + $jumlah WHERE id = $obat_id";
            if (mysqli_query($conn, $query)) {
                $success = '✅ Stok berhasil ditambahkan!';
            }
        }
    } elseif ($_POST['action'] === 'reduce_stok') {
        $jumlah = sanitize($_POST['jumlah']);
        if (empty($jumlah) || $jumlah <= 0) {
            $error = 'Jumlah harus lebih dari 0!';
        } else {
            $query = "UPDATE obat SET stok = stok - $jumlah WHERE id = $obat_id AND stok >= $jumlah";
            if (mysqli_query($conn, $query)) {
                $success = '✅ Stok berhasil dikurangi!';
            } else {
                $error = 'Stok tidak mencukupi!';
            }
        }
    }
}

// Get semua obat
$obatQuery = "SELECT * FROM obat ORDER BY nama_obat";
$obatResult = mysqli_query($conn, $obatQuery);

// Get stok summary
$stokLowQuery = "SELECT COUNT(*) as total FROM obat WHERE stok < 10";
$stokLowResult = mysqli_query($conn, $stokLowQuery);
$stokLow = mysqli_fetch_assoc($stokLowResult)['total'];

$totalObatQuery = "SELECT COUNT(*) as total FROM obat";
$totalObatResult = mysqli_query($conn, $totalObatQuery);
$totalObat = mysqli_fetch_assoc($totalObatResult)['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Apotek - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Manajemen Apotek"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">📦 Manajemen Stok Apotek</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Kelola ketersediaan obat dan monitor stok di apotek RS Hermana.</p>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} p-6 rounded-2xl shadow-lg border-l-4 border-purple-600">
                <p class="{{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} text-sm mb-1">Total Obat</p>
                <p class="text-3xl font-bold"><?php echo $totalObat; ?></p>
            </div>
            <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} p-6 rounded-2xl shadow-lg border-l-4 border-red-600">
                <p class="{{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} text-sm mb-1">Stok Rendah</p>
                <p class="text-3xl font-bold text-red-600"><?php echo $stokLow; ?></p>
            </div>
            <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} p-6 rounded-2xl shadow-lg border-l-4 border-green-600">
                <p class="{{php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?} text-sm mb-1">Status Sistem</p>
                <p class="text-lg font-bold text-green-600">✅ Aktif</p>
            </div>
        </div>

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

        <!-- Tabel Obat -->
        <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="{{php echo $isDark ? 'bg-gray-700' : 'bg-gray-100'; ?}">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">No</th>
                            <th class="px-6 py-3 text-left font-semibold">Nama Obat</th>
                            <th class="px-6 py-3 text-left font-semibold">Jenis</th>
                            <th class="px-6 py-3 text-left font-semibold">Kategori</th>
                            <th class="px-6 py-3 text-left font-semibold">Stok</th>
                            <th class="px-6 py-3 text-left font-semibold">Harga</th>
                            <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        mysqli_data_seek($obatResult, 0);
                        while ($obat = mysqli_fetch_assoc($obatResult)): 
                            $stokColor = $obat['stok'] < 10 ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-200' : 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200';
                        ?>
                        <tr class="{{php echo $isDark ? 'border-b border-gray-700 hover:bg-gray-700' : 'border-b border-gray-200 hover:bg-gray-50'; ?}">
                            <td class="px-6 py-4 font-semibold"><?php echo $no++; ?></td>
                            <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($obat['nama_obat']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($obat['jenis']); ?></td>
                            <td class="px-6 py-4"><span class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 px-3 py-1 rounded-full text-sm"><?php echo htmlspecialchars($obat['kategori']); ?></span></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-bold <?php echo $stokColor; ?>">
                                    <?php echo $obat['stok']; ?> <?php echo htmlspecialchars($obat['jenis']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-semibold">Rp <?php echo number_format($obat['harga'], 0, ',', '.'); ?></td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openModal('add', <?php echo $obat['id']; ?>, '<?php echo htmlspecialchars($obat['nama_obat']); ?>')" class="text-green-600 hover:text-green-700 font-semibold mr-3">
                                    ➕ Tambah
                                </button>
                                <button onclick="openModal('reduce', <?php echo $obat['id']; ?>, '<?php echo htmlspecialchars($obat['nama_obat']); ?>')" class="text-red-600 hover:text-red-700 font-semibold">
                                    ➖ Kurangi
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Update Stok -->
    <div id="stokModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="{{php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?} rounded-2xl max-w-md w-full p-8">
            <h3 class="text-2xl font-bold mb-6 text-purple-600" id="modalTitle">📦 Update Stok</h3>

            <form method="POST" action="">
                <input type="hidden" name="action" id="modalAction">
                <input type="hidden" name="obat_id" id="obat_id">

                <!-- Nama Obat (Read Only) -->
                <div class="mb-6">
                    <label class="block {{php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?} text-sm font-bold mb-2">Nama Obat</label>
                    <input 
                        type="text" 
                        id="nama_obat" 
                        class="w-full px-4 py-2 {{php echo $isDark ? 'bg-gray-700 border-gray-600 text-gray-400' : 'bg-gray-100 border-gray-300 text-gray-500'; ?} border rounded-lg cursor-not-allowed" 
                        readonly disabled
                    >
                </div>

                <!-- Jumlah -->
                <div class="mb-6">
                    <label class="block {{php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?} text-sm font-bold mb-2">Jumlah</label>
                    <input 
                        type="number" 
                        name="jumlah" 
                        min="1" 
                        placeholder="Masukkan jumlah" 
                        class="w-full px-4 py-2 {{php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?} border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                        required
                    >
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" id="submitBtn" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        💾 Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 {{php echo $isDark ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-200 hover:bg-gray-300'; ?} font-bold py-2 px-4 rounded-lg transition">
                        ❌ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(action, obatId, namaObat) {
            document.getElementById('modalAction').value = action === 'add' ? 'add_stok' : 'reduce_stok';
            document.getElementById('obat_id').value = obatId;
            document.getElementById('nama_obat').value = namaObat;
            
            const title = action === 'add' ? '➕ Tambah Stok' : '➖ Kurangi Stok';
            document.getElementById('modalTitle').textContent = title;
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.textContent = action === 'add' ? '➕ Tambah' : '➖ Kurangi';
            submitBtn.classList.remove('bg-purple-600', 'hover:bg-purple-700', 'bg-red-600', 'hover:bg-red-700');
            submitBtn.classList.add(action === 'add' ? 'bg-green-600' : 'bg-red-600');
            submitBtn.classList.add(action === 'add' ? 'hover:bg-green-700' : 'hover:bg-red-700');
            
            document.getElementById('stokModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('stokModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('stokModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

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

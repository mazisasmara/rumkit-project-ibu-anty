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

// Update status pasien
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $registrasi_id = sanitize($_POST['registrasi_id']);
    $kondisi = sanitize($_POST['kondisi']);
    $status = sanitize($_POST['status']);

    $query = "UPDATE registrasi_rawat SET kondisi = '$kondisi', status = '$status' WHERE id = $registrasi_id";
    if (mysqli_query($conn, $query)) {
        $success = '✅ Status pasien berhasil diupdate!';
    }
}

// Get semua pasien rawat inap
$pasienQuery = "SELECT r.*, u.nama, u.nik, k.tipe_kamar FROM registrasi_rawat r 
                JOIN users u ON r.user_id = u.id 
                JOIN kamar k ON r.kamar_id = k.id 
                ORDER BY r.created_at DESC";
$pasienResult = mysqli_query($conn, $pasienQuery);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Pasien - RS Hermana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="<?php echo $isDark ? 'dark bg-gray-900 text-white' : 'bg-gray-50 text-gray-900'; ?>">
    
    <?php renderDashboardHeader("Monitoring Pasien"); ?>

    <div class="max-w-6xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-bold mb-2">👥 Dashboard Monitoring Pasien</h2>
        <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-600'; ?> mb-8">Pantau kondisi dan status pasien rawat inap secara real-time.</p>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg mb-6">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Tabel Pasien -->
        <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="<?php echo $isDark ? 'bg-gray-700' : 'bg-gray-100'; ?>">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">No</th>
                            <th class="px-6 py-3 text-left font-semibold">Nama Pasien</th>
                            <th class="px-6 py-3 text-left font-semibold">NIK</th>
                            <th class="px-6 py-3 text-left font-semibold">Kamar</th>
                            <th class="px-6 py-3 text-left font-semibold">Kondisi</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Tanggal Masuk</th>
                            <th class="px-6 py-3 text-center font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        while ($pasien = mysqli_fetch_assoc($pasienResult)): 
                            $statusColor = $pasien['status'] === 'aktif' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                        ?>
                        <tr class="<?php echo $isDark ? 'border-b border-gray-700 hover:bg-gray-700' : 'border-b border-gray-200 hover:bg-gray-50'; ?>">
                            <td class="px-6 py-4 font-semibold"><?php echo $no++; ?></td>
                            <td class="px-6 py-4 font-semibold"><?php echo htmlspecialchars($pasien['nama']); ?></td>
                            <td class="px-6 py-4 font-mono text-sm"><?php echo htmlspecialchars($pasien['nik']); ?></td>
                            <td class="px-6 py-4"><span class="bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-200 px-3 py-1 rounded-full text-sm font-semibold"><?php echo htmlspecialchars($pasien['tipe_kamar']); ?></span></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($pasien['kondisi']); ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold <?php echo $statusColor; ?>">
                                    <?php echo ucfirst($pasien['status']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4"><?php echo date('d/m/Y', strtotime($pasien['tgl_masuk'])); ?></td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="openModal(<?php echo $pasien['id']; ?>, '<?php echo htmlspecialchars($pasien['nama']); ?>', '<?php echo htmlspecialchars($pasien['kondisi']); ?>', '<?php echo $pasien['status']; ?>')" class="text-purple-600 hover:text-purple-700 font-semibold">
                                    ✏️ Edit
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if (mysqli_num_rows($pasienResult) === 0): ?>
            <div class="text-center py-12">
                <p class="<?php echo $isDark ? 'text-gray-400' : 'text-gray-500'; ?>">Tidak ada pasien rawat inap.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Edit Pasien -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="<?php echo $isDark ? 'bg-gray-800' : 'bg-white'; ?> rounded-2xl max-w-md w-full p-8">
            <h3 class="text-2xl font-bold mb-6 text-purple-600">📝 Update Status Pasien</h3>

            <form method="POST" action="">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="registrasi_id" id="registrasi_id">

                <!-- Nama Pasien (Read Only) -->
                <div class="mb-6">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Nama Pasien</label>
                    <input 
                        type="text" 
                        id="nama_pasien" 
                        class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600 text-gray-400' : 'bg-gray-100 border-gray-300 text-gray-500'; ?> border rounded-lg cursor-not-allowed" 
                        readonly disabled
                    >
                </div>

                <!-- Kondisi -->
                <div class="mb-6">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Kondisi Kesehatan</label>
                    <textarea 
                        name="kondisi" 
                        id="kondisi" 
                        class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" 
                        rows="4" 
                        required
                    ></textarea>
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="block <?php echo $isDark ? 'text-gray-300' : 'text-gray-700'; ?> text-sm font-bold mb-2">Status</label>
                    <select name="status" id="status" class="w-full px-4 py-2 <?php echo $isDark ? 'bg-gray-700 border-gray-600' : 'bg-gray-50 border-gray-300'; ?> border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600" required>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition">
                        💾 Simpan
                    </button>
                    <button type="button" onclick="closeModal()" class="flex-1 <?php echo $isDark ? 'bg-gray-700 hover:bg-gray-600' : 'bg-gray-200 hover:bg-gray-300'; ?> font-bold py-2 px-4 rounded-lg transition">
                        ❌ Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, nama, kondisi, status) {
            document.getElementById('registrasi_id').value = id;
            document.getElementById('nama_pasien').value = nama;
            document.getElementById('kondisi').value = kondisi;
            document.getElementById('status').value = status;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
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

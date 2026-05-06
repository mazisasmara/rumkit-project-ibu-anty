<?php
// theme.php - Utility untuk Dark Mode Toggle

function initTheme() {
    if (!isset($_SESSION['theme'])) {
        $_SESSION['theme'] = 'light';
    }
}

function getTheme() {
    return $_SESSION['theme'] ?? 'light';
}

function toggleTheme() {
    $_SESSION['theme'] = ($_SESSION['theme'] === 'light') ? 'dark' : 'light';
}

function getThemeClass($light, $dark) {
    return (getTheme() === 'dark') ? $dark : $light;
}

// API untuk toggle tema via AJAX
if (isset($_GET['action']) && $_GET['action'] === 'toggle_theme') {
    session_start();
    toggleTheme();
    header('Content-Type: application/json');
    echo json_encode(['theme' => getTheme()]);
    exit;
}
?>

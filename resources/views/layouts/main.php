<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($page_title) ?> - Admin Panel</title>
    <!-- Estilos específicos para el admin -->
</head>
<body>
    <aside class="admin-sidebar">
        <h2>PasteX Pro Admin</h2>
        <nav>
            <a href="/admin">Dashboard</a>
            <a href="/admin/pastes">Pastes</a>
            <a href="/admin/users">Users</a>
            <a href="/admin/settings">Settings</a>
        </nav>
    </aside>
    <main class="admin-content">
        <h1><?= htmlspecialchars($page_title) ?></h1>
        <?= $content ?? '' ?>
    </main>
</body>
</html>
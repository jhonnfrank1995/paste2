<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>PasteX Pro Admin</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts: Inter (Modern UI Standard) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        // Configuración de Tailwind para el tema Vapor Pro
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0070F3', // Vercel/Stripe Blue
                        'primary-dark': '#005ed1',
                        'bg-main': '#F4F7FA', // Fondo general, gris claro
                        'bg-card': '#FFFFFF', // Fondo de tarjetas
                        'text-default': '#1E293B', // Gris muy oscuro
                        'success': '#10B981', // Emerald Green
                        'danger': '#EF4444', // Red
                        'muted': '#64748B', // Gris tenue
                    },
                    fontFamily: {
                        'sans': ['Inter', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
                        'xl-soft': '0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -4px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-bg-main text-text-default">
    <!-- Barra Lateral (Sidebar) -->
    <aside class="fixed top-0 left-0 h-full w-64 bg-bg-card z-10 shadow-xl-soft">
        <div class="px-8 py-6 border-b border-gray-100">
             <a href="/admin" class="text-xl font-extrabold text-text-default flex items-center">
                <img src="/assets/img/logo.svg" alt="PasteX Pro Logo" class="h-6 mr-2">
                PasteX <span class="text-primary ml-1">Pro</span>
            </a>
        </div>
        <nav class="pt-4 px-4">
            <ul>
                <h6 class="text-xs uppercase font-semibold text-muted pl-4 mb-2">Main</h6>
                <li class="relative">
                    <a class="flex items-center text-sm py-3 px-4 font-semibold rounded-lg transition duration-150 ease-in-out hover:bg-bg-main <?= str_contains($_SERVER['REQUEST_URI'], '/admin/p') || $_SERVER['REQUEST_URI'] === '/admin' ? 'bg-bg-main text-primary' : 'text-text-default' ?>" href="/admin">
                        <i class="fas fa-chart-line w-4 h-4 mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="relative">
                    <a class="flex items-center text-sm py-3 px-4 font-semibold rounded-lg transition duration-150 ease-in-out hover:bg-bg-main <?= str_contains($_SERVER['REQUEST_URI'], '/admin/pastes') ? 'bg-bg-main text-primary' : 'text-text-default' ?>" href="/admin/pastes">
                        <i class="fas fa-code w-4 h-4 mr-3"></i>
                        <span>Manage Pastes</span>
                    </a>
                </li>
                 <li class="relative">
                    <a class="flex items-center text-sm py-3 px-4 font-semibold rounded-lg transition duration-150 ease-in-out hover:bg-bg-main <?= str_contains($_SERVER['REQUEST_URI'], '/admin/users') ? 'bg-bg-main text-primary' : 'text-text-default' ?>" href="/admin/users">
                        <i class="fas fa-users w-4 h-4 mr-3"></i>
                        <span>Manage Users</span>
                    </a>
                </li>
                <li class="relative">
                    <a class="flex items-center text-sm py-3 px-4 font-semibold rounded-lg transition duration-150 ease-in-out hover:bg-bg-main <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'bg-bg-main text-danger' : 'text-text-default' ?>" href="/admin/reports">
                        <i class="fas fa-flag w-4 h-4 mr-3 text-danger"></i>
                        <span>Abuse Reports</span>
                    </a>
                </li>

                <hr class="my-4 border-gray-100">
                <h6 class="text-xs uppercase font-semibold text-muted pl-4 mb-2">Settings</h6>
                 <li class="relative">
                    <a class="flex items-center text-sm py-3 px-4 font-semibold rounded-lg transition duration-150 ease-in-out hover:bg-bg-main <?= str_contains($_SERVER['REQUEST_URI'], '/admin/settings') ? 'bg-bg-main text-primary' : 'text-text-default' ?>" href="/admin/settings">
                        <i class="fas fa-cog w-4 h-4 mr-3"></i>
                        <span>Site Settings</span>
                    </a>
                </li>
                 <li class="relative">
                    <a class="flex items-center text-sm py-3 px-4 font-semibold rounded-lg transition duration-150 ease-in-out hover:bg-bg-main text-muted" href="/">
                        <i class="fas fa-arrow-right-from-bracket w-4 h-4 mr-3"></i>
                        <span>Exit Admin</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div class="relative ml-64 flex flex-col flex-1">
        <!-- Barra de Navegación Superior -->
        <header class="bg-bg-card shadow-soft p-4 flex justify-between items-center sticky top-0 z-10 border-b border-gray-100">
            <h1 class="text-xl font-bold text-text-default"><?= htmlspecialchars($page_title) ?></h1>
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-muted">Welcome, <?= htmlspecialchars($user['email'] ?? 'Admin') ?></span>
                <!-- Se podría añadir un dropdown de usuario aquí -->
            </div>
        </header>

        <!-- Contenido Principal -->
        <main class="p-6">
            <!-- Alerta de éxito/error -->
            <?php if (!empty($success_message)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6 shadow-soft" role="alert"><?= htmlspecialchars($success_message) ?></div>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6 shadow-soft" role="alert"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <!-- El contenido de la vista específica se inyecta aquí -->
            <?= $content ?? '' ?>
        </main>
        
        <!-- Footer -->
        <footer class="mt-auto p-4 text-center text-sm text-muted border-t border-gray-100">
            &copy; <?= date('Y') ?> PasteX Pro | Crafted with <span class="text-danger">&hearts;</span>
        </footer>
    </div>
</body>
</html>
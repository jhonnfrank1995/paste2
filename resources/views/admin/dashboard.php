<?php $page_title = 'Dashboard'; ?>
<?php ob_start(); ?>

<!-- Fila de Tarjetas de Estadísticas (Stat Cards) -->
<div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-2 gap-6">
    <!-- Card 1: Total Pastes -->
    <div class="card p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="icon-shape bg-warning text-white shadow-argon">
                    <i class="fas fa-paste"></i>
                </div>
            </div>
            <div class="flex-1 ml-5">
                <h5 class="text-slate-400 uppercase font-bold text-xs">Total Pastes</h5>
                <span class="font-semibold text-xl text-slate-700"><?= number_format($stats['total_pastes'] ?? 0) ?></span>
            </div>
        </div>
    </div>

    <!-- Card 2: Total Users -->
    <div class="card p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="icon-shape bg-pink-500 text-white shadow-argon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="flex-1 ml-5">
                <h5 class="text-slate-400 uppercase font-bold text-xs">Total Users</h5>
                <span class="font-semibold text-xl text-slate-700"><?= number_format($stats['total_users'] ?? 0) ?></span>
            </div>
        </div>
    </div>
    
    <!-- Card 3: Pastes (24h) -->
    <div class="card p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="icon-shape bg-green-500 text-white shadow-argon">
                    <i class="fas fa-plus"></i>
                </div>
            </div>
            <div class="flex-1 ml-5">
                <h5 class="text-slate-400 uppercase font-bold text-xs">New Pastes (24h)</h5>
                <span class="font-semibold text-xl text-slate-700"><?= number_format($stats['pastes_last_24h'] ?? 0) ?></span>
            </div>
        </div>
    </div>
    
    <!-- Card 4: System Health -->
    <div class="card p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="icon-shape bg-info text-white shadow-argon">
                    <i class="fas fa-server"></i>
                </div>
            </div>
            <div class="flex-1 ml-5">
                <h5 class="text-slate-400 uppercase font-bold text-xs">System Health</h5>
                <span class="font-semibold text-xl text-green-500">OK</span>
            </div>
        </div>
    </div>
</div>

<!-- Área de Gráficos y Tablas -->
<div class="flex flex-wrap mt-4">
    <div class="w-full xl:w-8/12 mb-12 xl:mb-0 px-4">
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold text-base text-slate-700">Analytics (Coming Soon)</h3>
            </div>
            <div class="p-4">
                <div class="relative h-96">
                    <div class="flex items-center justify-center h-full bg-slate-50 rounded-lg">
                        <p class="text-slate-400">Chart.js integration will be here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full xl:w-4/12 px-4"><?php $page_title = 'Dashboard'; ?>
<?php ob_start(); ?>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pastes</p>
                <h3 class="mt-1 text-3xl font-bold text-gray-900"><?= number_format($stats['total_pastes'] ?? 0) ?></h3>
            </div>
            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                <i class="fas fa-paste fa-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Users</p>
                <h3 class="mt-1 text-3xl font-bold text-gray-900"><?= number_format($stats['total_users'] ?? 0) ?></h3>
            </div>
            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                <i class="fas fa-users fa-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
         <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">New Pastes (24h)</p>
                <h3 class="mt-1 text-3xl font-bold text-gray-900"><?= number_format($stats['pastes_last_24h'] ?? 0) ?></h3>
            </div>
            <div class="p-3 rounded-full bg-green-100 text-green-600">
                <i class="fas fa-plus fa-lg"></i>
            </div>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
         <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">System Status</p>
                <h3 class="mt-1 text-3xl font-bold text-green-600">Online</h3>
            </div>
            <div class="p-3 rounded-full bg-red-100 text-red-600">
                <i class="fas fa-heart-pulse fa-lg"></i>
            </div>
        </div>
    </div>
</div>

<div class="mt-8 bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-4 mb-4">Site Analytics</h3>
    <div class="flex items-center justify-center h-80 bg-gray-50 rounded-lg">
        <p class="text-gray-400">Analytics Chart Coming Soon...</p>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layouts/main.php'; ?>
        <div class="card">
             <div class="card-header">
                <h3 class="font-semibold text-base text-slate-700">Recent Activity</h3>
            </div>
            <div class="p-4">
                 <div class="relative h-96">
                    <div class="flex items-center justify-center h-full bg-slate-50 rounded-lg">
                        <p class="text-slate-400">Activity feed will be here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layouts/main.php'; ?>
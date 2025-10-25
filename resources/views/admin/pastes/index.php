<?php $page_title = 'Manage Pastes'; ?>
<?php ob_start(); ?>

<div class="flex flex-wrap mt-4">
    <div class="w-full mb-12 px-4">
        <div class="card relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded">
            <div class="card-header rounded-t mb-0 px-4 py-3 border-0">
                <div class="flex flex-wrap items-center">
                    <div class="relative w-full px-4 max-w-full flex-grow flex-1"><h3 class="font-semibold text-base text-slate-700">All Pastes (Total: <?= $totalPastes ?? 0 ?>)</h3></div>
                </div>
            </div>
            <div class="block w-full overflow-x-auto">
                <table class="items-center w-full bg-transparent border-collapse">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left text-slate-500">Title / ID</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left text-slate-500">Author</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left text-slate-500">Visibility</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left text-slate-500">Created At</th>
                            <th class="px-6 align-middle border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pastes)): ?>
                            <tr><td colspan="6" class="text-center p-8 text-slate-500">No pastes found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($pastes as $paste): ?>
                                <tr>
                                    <th class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left"><span class="font-bold text-slate-700 block"><?= htmlspecialchars($paste['title'] ?: 'Untitled') ?></span><span class="font-mono text-slate-500 text-xs"><?= htmlspecialchars($paste['id']) ?></span></th>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"><?= htmlspecialchars($paste['user_email'] ?? 'Anonymous') ?></td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"><?= ucfirst($paste['visibility']) ?></td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4"><?= date('Y-m-d H:i', strtotime($paste['created_at'])) ?></td>
                                    <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                                        <div class="flex items-center justify-end">
                                            <a href="/admin/pastes/edit/<?= htmlspecialchars($paste['id']) ?>" class="bg-blue-500 text-white text-xs font-bold uppercase px-3 py-1 rounded outline-none mr-2">Edit</a>
                                            <form method="POST" action="/admin/pastes/delete/<?= htmlspecialchars($paste['id']) ?>" onsubmit="return confirm('Are you sure you want to delete this paste?');">
                                                <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
                                                <button type="submit" class="bg-red-500 text-white text-xs font-bold uppercase px-3 py-1 rounded outline-none">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-header rounded-b py-4 border-0">
                <nav class="flex justify-between items-center text-sm">
                    <p class="text-slate-500">Page <?= $currentPage ?> of <?= $totalPages ?></p>
                    <div>
                        <a href="?page=<?= max(1, $currentPage - 1) ?>" class="px-3 py-1 rounded <?= $currentPage <= 1 ? 'text-slate-300' : 'text-slate-600 hover:bg-slate-100' ?>">Previous</a>
                        <a href="?page=<?= min($totalPages, $currentPage + 1) ?>" class="px-3 py-1 rounded <?= $currentPage >= $totalPages ? 'text-slate-300' : 'text-slate-600 hover:bg-slate-100' ?>">Next</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
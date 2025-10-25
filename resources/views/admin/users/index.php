<?php $page_title = 'Manage Users'; ?>
<?php ob_start(); ?>

<div class="card bg-bg-card p-6 rounded-xl shadow-xl-soft border border-gray-100">
    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
        <h3 class="text-lg font-semibold text-text-default">User Management</h3>
        <p class="text-sm text-muted">Total Users: <?= htmlspecialchars($totalUsers ?? 0) ?></p>
    </div>
    
    <div class="overflow-x-auto mt-4">
        <table class="w-full text-sm text-left text-text-default">
            <thead class="text-xs uppercase text-muted border-b border-gray-100">
                <tr>
                    <th scope="col" class="px-4 py-3 font-semibold">User</th>
                    <th scope="col" class="px-4 py-3 font-semibold">Role</th>
                    <th scope="col" class="px-4 py-3 font-semibold">Pastes Count</th>
                    <th scope="col" class="px-4 py-3 font-semibold">Registered At</th>
                    <th scope="col" class="px-4 py-3 text-right font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="5" class="text-center p-6 text-muted">No users found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-b border-gray-100 hover:bg-bg-main transition duration-100">
                            <td class="px-4 py-4 font-medium flex items-center">
                                <img src="https://www.gravatar.com/avatar/<?= md5(strtolower(trim($user['email']))) ?>?s=32&d=mp" class="h-8 w-8 rounded-full border mr-3" alt="Avatar">
                                <span class="font-bold text-text-default"><?= htmlspecialchars($user['email']) ?></span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                    <?= $user['role'] === 'admin' ? 'bg-indigo-100 text-indigo-600' : '' ?>
                                    <?= $user['role'] === 'moderator' ? 'bg-amber-100 text-amber-600' : '' ?>
                                    <?= $user['role'] === 'user' ? 'bg-gray-100 text-muted' : '' ?>
                                ">
                                    <?= ucfirst(htmlspecialchars($user['role'])) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-muted">N/A (Future Stat)</td>
                            <td class="px-4 py-4 text-muted"><?= date('Y-m-d H:i', strtotime($user['created_at'])) ?></td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <a href="/admin/users/edit/<?= $user['id'] ?>" class="text-primary hover:text-primary-dark mr-3 transition duration-100" title="Edit"><i class="fas fa-edit"></i></a>
                                <button class="text-danger hover:text-red-700 transition duration-100" title="Delete" onclick="alert('Delete user functionality coming soon!')"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Paginación -->
    <div class="p-4 border-t border-gray-100 flex justify-between items-center text-sm">
        <p class="text-muted">Page <?= $currentPage ?> of <?= $totalPages ?></p>
        <div class="space-x-2">
            <a href="?page=<?= max(1, $currentPage - 1) ?>" class="px-3 py-1 rounded-lg border text-muted hover:bg-bg-main transition duration-150">Previous</a>
            <a href="?page=<?= min($totalPages, $currentPage + 1) ?>" class="px-3 py-1 rounded-lg border text-primary hover:bg-bg-main transition duration-150">Next</a>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layouts/main.php'; ?>
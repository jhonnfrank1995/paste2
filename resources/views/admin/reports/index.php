<?php $page_title = 'Abuse Reports'; ?>
<?php ob_start(); ?>

<div class="card shadow-xl">
    <div class="p-5 border-b">
        <h3 class="text-lg font-semibold text-gray-700">Open Abuse Reports</h3>
        <p class="text-sm text-gray-500 mt-1">Review and take action on pastes reported by the community.</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Reported Paste</th>
                    <th scope="col" class="px-6 py-3">Reason</th>
                    <th scope="col" class="px-6 py-3">Reporter Note</th>
                    <th scope="col" class="px-6 py-3">Reported At</th>
                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($reports)): ?>
                    <tr class="bg-white border-b">
                        <td colspan="5" class="px-6 py-4 text-center text-gray-400">
                            <div class="py-8">
                                <i class="fas fa-check-circle fa-3x text-green-400"></i>
                                <p class="mt-4 font-semibold">No open reports. Great job!</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reports as $report): ?>
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                <a href="/p/<?= htmlspecialchars($report['paste_id']) ?>" target="_blank" class="text-indigo-600 hover:underline" title="<?= htmlspecialchars($report['paste_title']) ?>">
                                    <?= htmlspecialchars($report['paste_id']) ?>
                                </a>
                            </th>
                            <td class="px-6 py-4">
                                <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-red-600 bg-red-200">
                                    <?= htmlspecialchars($report['reason']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="max-w-xs truncate" title="<?= htmlspecialchars($report['note']) ?>">
                                    <?= htmlspecialchars($report['note'] ?: 'N/A') ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <?= date('Y-m-d H:i', strtotime($report['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="font-medium text-green-600 hover:underline">Resolve</a>
                                <a href="#" class="font-medium text-red-600 hover:underline ml-4">Delete Paste</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
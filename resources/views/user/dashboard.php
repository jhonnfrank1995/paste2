<?php $page_title = 'My Dashboard'; ?>
<?php ob_start(); ?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>My Pastes</h1>
        <a href="/new" class="button button-primary">Create New Paste</a>
    </div>

    <div class="pastes-table-container">
        <?php if (empty($pastes)): ?>
            <div class="empty-state">
                <p>You haven't created any pastes yet.</p>
                <a href="/new" class="button">Create your first one!</a>
            </div>
        <?php else: ?>
            <table class="pastes-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Language</th>
                        <th>Visibility</th>
                        <th>Created</th>
                        <th>Views</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pastes as $paste): ?>
                        <tr>
                            <td>
                                <a href="/p/<?= htmlspecialchars($paste['id']) ?>" class="paste-title">
                                    <?= htmlspecialchars($paste['title'] ?: 'Untitled') ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($paste['language']) ?></td>
                            <td>
                                <span class="visibility-badge <?= htmlspecialchars($paste['visibility']) ?>">
                                    <?= ucfirst(htmlspecialchars($paste['visibility'])) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($paste['created_at'])) ?></td>
                            <td><?= htmlspecialchars($paste['views_count']) ?></td>
                            <td class="actions">
                                <a href="/p/<?= htmlspecialchars($paste['id']) ?>/edit" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="/p/<?= htmlspecialchars($paste['id']) ?>/delete" title="Delete" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<style>
/* Estilos para el dashboard de usuario */
.dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.pastes-table-container { background: #fff; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); }
.pastes-table { width: 100%; border-collapse: collapse; }
.pastes-table th, .pastes-table td { padding: 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
.pastes-table th { font-weight: 600; color: #6b7280; font-size: 0.8rem; text-transform: uppercase; }
.paste-title { font-weight: 600; color: var(--primary-color); text-decoration: none; }
.visibility-badge { padding: 0.2rem 0.6rem; border-radius: 99px; font-size: 0.75rem; font-weight: 700; }
.visibility-badge.public { background-color: #dbeafe; color: #1e40af; }
.visibility-badge.unlisted { background-color: #e5e7eb; color: #374151; }
.visibility-badge.private { background-color: #fee2e2; color: #991b1b; }
.actions a { color: #6b7280; margin: 0 0.5rem; text-decoration: none; }
.actions a:hover { color: var(--primary-color); }
</style>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
<?php $page_title = 'A Modern, Secure Pastebin'; ?>
<?php ob_start(); ?>
<div class="hero">
    <h1>Share Code & Text Instantly</h1>
    <p>PasteX Pro is a modern, secure, and open-source platform for developers to share snippets.</p>
    <a href="/new" class="button button-primary">Create a New Paste</a>
</div>

<div class="recent-pastes">
    <h2>Recent Public Pastes</h2>
    <?php if (empty($recentPastes)): ?>
        <p>No public pastes yet. Be the first!</p>
    <?php else: ?>
        <ul>
            <?php foreach ($recentPastes as $paste): ?>
                <li>
                    <a href="/p/<?= htmlspecialchars($paste['id']) ?>">
                        <?= htmlspecialchars($paste['title'] ?: 'Untitled') ?>
                    </a>
                    <span>(<?= htmlspecialchars($paste['language']) ?>)</span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layouts/app.php'; ?>
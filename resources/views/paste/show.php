<?php $page_title = $paste['title'] ?: 'View Paste'; ?>
<?php ob_start(); ?>
<div class="paste-header">
<h1><?= htmlspecialchars($paste['title'] ?: 'Untitled') ?></h1>
<div class="paste-meta">
<span>Language: <?= htmlspecialchars($paste['language']) ?></span> |
<span>Created: <?= date('M j, Y H:i', strtotime($paste['created_at'])) ?></span>
</div>
<div class="paste-actions">
<button id="copy-button" class="button">Copy</button>
<a href="/raw/<?= htmlspecialchars($paste['id']) ?>" class="button">Raw</a>
<a href="/new" class="button">Fork</a>
</div>
</div>
<pre><code id="paste-content" class="language-<?= htmlspecialchars($paste['language']) ?>">
<?= htmlspecialchars($paste['content']) ?>
</code></pre>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
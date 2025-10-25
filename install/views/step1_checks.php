<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PasteX Pro Installer - Step 1: Server Checks</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="installer-container">
        <header class="installer-header">
            <h1>Welcome to PasteX Pro!</h1>
            <p>Step 1 of 5: Server Requirements</p>
        </header>
        <main class="installer-content">
            <h2>Checking System Requirements...</h2>
            <p>Before we begin, we need to make sure your server is ready for PasteX Pro.</p>
            <ul class="checks-list">
                <?php foreach ($checks['checks'] as $check): ?>
                <li>
                    <span class="check-status <?= $check['status'] ? 'ok' : 'error' ?>">
                        <?= $check['status'] ? '✓' : '✗' ?>
                    </span>
                    <span class="check-label"><?= htmlspecialchars($check['label']) ?></span>
                    <?php if (!$check['status'] && !empty($check['message'])): ?>
                        <span class="check-message"><?= htmlspecialchars($check['message']) ?></span>
                    <?php endif; ?>
                </li>
                <?php endforeach; ?>
            </ul>
        </main>
        <footer class="installer-footer">
            <form action="index.php?step=2" method="POST">
                 <input type="hidden" name="from_step" value="1">
                 <button type="submit" class="button" <?= $all_ok ? '' : 'disabled' ?>>
                    Next: Database Setup &rarr;
                 </button>
            </form>
        </footer>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PasteX Pro Installer - Complete!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="installer-container">
        <header class="installer-header">
            <h1>Installation Complete!</h1>
            <p>Step 5 of 5: All Done!</p>
        </header>
        <main class="installer-content text-center">
            <div class="complete-icon">🎉</div>
            <h2>Congratulations!</h2>
            <p>PasteX Pro has been successfully installed on your server. You're ready to go!</p>
            
            <div class="error-message" style="background-color: #fffbe6; color: #a16207; border-color: #fde68a;">
                <strong>CRITICAL SECURITY WARNING:</strong><br>
                For your security, you MUST now delete the <strong>/install</strong> directory from your server.
            </div>

        </main>
        <footer class="installer-footer" style="justify-content: center; gap: 20px;">
            <a href="<?= htmlspecialchars($site_url) ?>" class="button">Go to Your Site</a>
            <a href="<?= htmlspecialchars($site_url) ?>/login" class="button" style="background: var(--dark-color);">Go to Admin Login</a>
        </footer>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PasteX Pro Installer - Step 3: Site & Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="installer-container">
        <header class="installer-header">
            <h1>Site & Admin Setup</h1>
            <p>Step 3 of 5: Final Configuration</p>
        </header>
        <main class="installer-content">
            <h2>Final Details</h2>
            <p>Now, let's set up your site's name and create the main administrator account.</p>

            <form action="index.php?step=4" method="POST">
                 <input type="hidden" name="from_step" value="3">
                <div class="form-group">
                    <label for="name">Site Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($data['name'] ?? 'PasteX Pro') ?>" required>
                </div>
                 <div class="form-group">
                    <label for="url">Site URL</label>
                    <input type="text" id="url" name="url" value="<?= htmlspecialchars($data['url']) ?>" required>
                </div>
                <hr style="margin: 30px 0; border: 1px solid #f0e9fe;">
                <h2>Administrator Account</h2>
                <div class="form-group">
                    <label for="admin_email">Admin Email</label>
                    <input type="email" id="admin_email" name="admin_email" required>
                </div>
                <div class="form-group">
                    <label for="admin_password">Admin Password</label>
                    <input type="password" id="admin_password" name="admin_password" required>
                </div>
                <div class="installer-footer" style="padding: 0; background: none; border: none;">
                    <button type="submit" class="button">Start Installation &rarr;</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PasteX Pro Installer - Step 2: Database Setup</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="installer-container">
        <header class="installer-header">
            <h1>Database Configuration</h1>
            <p>Step 2 of 5: Database Connection</p>
        </header>
        <main class="installer-content">
            <h2>Enter Your Database Details</h2>
            <p>PasteX Pro needs a database to store all its data. Please provide your connection details below.</p>
            
            <?php if ($error): ?>
                <div class="error-message"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="index.php?step=3" method="POST">
                <input type="hidden" name="from_step" value="2">
                <div class="form-group">
                    <label for="host">Database Host</label>
                    <input type="text" id="host" name="host" value="<?= htmlspecialchars($data['host'] ?? '127.0.0.1') ?>" required>
                </div>
                <div class="form-group">
                    <label for="port">Port</label>
                    <input type="text" id="port" name="port" value="<?= htmlspecialchars($data['port'] ?? '3306') ?>" required>
                </div>
                <div class="form-group">
                    <label for="name">Database Name</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($data['name'] ?? 'pastex') ?>" required>
                </div>
                <div class="form-group">
                    <label for="user">Username</label>
                    <input type="text" id="user" name="user" value="<?= htmlspecialchars($data['user'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="pass">Password</label>
                    <input type="password" id="pass" name="pass" value="<?= htmlspecialchars($data['pass'] ?? '') ?>">
                </div>
                 <div class="form-group">
                    <input type="checkbox" id="create_db" name="create_db" value="1" checked>
                    <label for="create_db">Attempt to create database if it doesn't exist</label>
                </div>
                <div class="installer-footer" style="padding: 0; background: none; border: none;">
                    <button type="submit" class="button">Next: Site &amp; Admin Setup &rarr;</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
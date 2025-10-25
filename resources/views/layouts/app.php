<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-g">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>PasteX Pro</title>
    <link rel="stylesheet" href="/assets/css/theme-light.css" id="theme-stylesheet">
    <!-- Cargar highlight.js y sus estilos -->
    <link rel="stylesheet" href="/assets/vendor/highlight-styles/github.min.css">
    <script src="/assets/vendor/highlight.min.js"></script>
</head>
<body>
    <?php include __DIR__ . '/../partials/header.php'; ?>

    <main class="container">
        <?php if (!empty($success_message)): ?>
            <div class="alert success"><?= htmlspecialchars($success_message) ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert error"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- El contenido de la vista específica se inyectará aquí -->
        <?= $content ?? '' ?>
    </main>

    <?php include __DIR__ . '/../partials/footer.php'; ?>
    <script src="/assets/js/app.js"></script>
</body>
</html>
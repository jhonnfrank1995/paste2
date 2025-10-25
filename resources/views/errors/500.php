<?php http_response_code(404); $page_title = 'Page Not Found'; ?>
<?php ob_start(); ?>
<div class="error-page">
    <h1>404</h1>
    <h2>Page Not Found</h2>
    <p>Sorry, the page you are looking for could not be found. It might have been moved, deleted, or it never existed.</p>
    <a href="/" class="button">Go to Homepage</a>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
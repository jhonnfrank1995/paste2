<?php $page_title = 'Login'; ?>
<?php ob_start(); ?>
<div class="auth-container">
    <h1>Login to your Account</h1>
    <form action="/login" method="POST">
        <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <button type="submit" class="button button-primary">Login</button>
    </form>
    <p>Don't have an account? <a href="/register">Register here</a>.</p>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
<?php $page_title = 'Create an Account'; ?>
<?php ob_start(); ?>
<div class="auth-container">
    <h1>Create an Account</h1>
    <form action="/register" method="POST">
        <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
            <?php if (isset($errors['email'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['email'][0]) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
             <?php if (isset($errors['password'])): ?>
                <span class="error-text"><?= htmlspecialchars($errors['password'][0]) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="button button-primary">Register</button>
    </form>
    <p>Already have an account? <a href="/login">Login here</a>.</p>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
<?php $page_title = 'Password Protected Paste'; ?>
<?php ob_start(); ?>
<div class="password-prompt-container">
    <h1><span class="icon">🔒</span> This Paste is Protected</h1>
    <p>Please enter the password to view this paste.</p>
    
    <form action="/p/<?= htmlspecialchars($paste['id']) ?>/unlock" method="POST">
        <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autofocus>
        </div>
        <button type="submit" class="button button-primary">Unlock</button>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
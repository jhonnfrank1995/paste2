<?php $page_title = 'New Paste'; ?>
<?php ob_start(); ?>
<h1>Create a New Paste</h1>
<form action="/pastes" method="POST" class="paste-form">
    <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
    
    <div class="form-group">
        <label for="title">Title (Optional)</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($old['title'] ?? '') ?>">
    </div>

    <div class="form-group">
        <label for="content">Content</label>
        <?php if(isset($errors['content'])): ?><span class="error-text"><?= $errors['content'][0] ?></span><?php endif; ?>
        <textarea id="content" name="content" rows="20" required><?= htmlspecialchars($old['content'] ?? '') ?></textarea>
    </div>

    <div class="form-options">
        <div class="form-group">
            <label for="language">Syntax Highlighting</label>
            <select id="language" name="language">
                <option value="plaintext">Plain Text</option>
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="html">HTML</option>
                <option value="css">CSS</option>
                <option value="sql">SQL</option>
                <option value="python">Python</option>
            </select>
        </div>
        <div class="form-group">
            <label for="visibility">Visibility</label>
            <select id="visibility" name="visibility">
                <option value="public">Public</option>
                <option value="unlisted">Unlisted</option>
                <?php if (isset($user)): ?><option value="private">Private</option><?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="expiration">Expiration</label>
            <select id="expiration" name="expiration">
                <option value="never">Never</option>
                <option value="burn_after_read">Burn After Read</option>
                <option value="10m">10 Minutes</option>
                <option value="1h">1 Hour</option>
                <option value="1d">1 Day</option>
                <option value="1w">1 Week</option>
            </select>
        </div>
    </div>

    <button type="submit" class="button button-primary">Create Paste</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/app.php'; ?>
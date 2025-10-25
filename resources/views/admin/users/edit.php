<?php $page_title = 'Edit User'; ?>
<?php ob_start(); ?>
<div class="card">
    <form method="POST" action="/admin/users/update/<?= htmlspecialchars($user['id']) ?>">
        <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
        <div class="card-header">
            <h3 class="card-title">Editing User: <?= htmlspecialchars($user['email']) ?></h3>
            <div class="space-x-2">
                <a href="/admin/users" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <div>
                    <label class="form-label">Role</label>
                    <select name="role" class="form-input form-select">
                        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>User</option>
                        <option value="moderator" <?= $user['role'] == 'moderator' ? 'selected' : '' ?>>Moderator</option>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
            </div>
            <div>
                 <label class="form-label">New Password</label>
                 <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current password">
            </div>
             <div>
                <label class="form-label">Bio</label>
                <textarea name="bio" rows="3" class="form-input"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            </div>
        </div>
    </form>
</div>
<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layouts/main.php'; ?>
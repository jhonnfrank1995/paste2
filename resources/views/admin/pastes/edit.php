<?php $page_title = 'Edit Paste'; ?>
<?php ob_start(); ?>

<div class="flex flex-wrap">
    <div class="w-full px-4">
        <div class="card relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg">
             <form method="POST" action="/admin/pastes/update/<?= htmlspecialchars($paste['id']) ?>">
                <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
                <div class="px-6 py-6">
                    <div class="flex justify-between items-center mb-8">
                        <h6 class="text-slate-700 text-xl font-bold">Edit Paste <span class="font-mono text-base text-slate-500"><?= htmlspecialchars($paste['id']) ?></span></h6>
                        <div>
                             <a href="/admin/pastes" class="bg-slate-500 text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1">
                                Back to List
                            </a>
                            <button class="bg-primary text-white font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1" type="submit">
                                Save Changes
                            </button>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Title</label>
                                <input type="text" name="title" class="border-0 px-3 py-3 text-slate-600 bg-white rounded text-sm shadow w-full" value="<?= htmlspecialchars($paste['title'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="w-full lg:w-3/12 px-4">
                             <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Language</label>
                                <input type="text" name="language" class="border-0 px-3 py-3 text-slate-600 bg-white rounded text-sm shadow w-full" value="<?= htmlspecialchars($paste['language'] ?? 'plaintext') ?>">
                            </div>
                        </div>
                        <div class="w-full lg:w-3/12 px-4">
                             <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Visibility</label>
                                <select name="visibility" class="border-0 px-3 py-3 text-slate-600 bg-white rounded text-sm shadow w-full">
                                    <option value="public" <?= $paste['visibility'] == 'public' ? 'selected' : '' ?>>Public</option>
                                    <option value="unlisted" <?= $paste['visibility'] == 'unlisted' ? 'selected' : '' ?>>Unlisted</option>
                                    <option value="private" <?= $paste['visibility'] == 'private' ? 'selected' : '' ?>>Private</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2">Content</label>
                                <textarea name="content" rows="20" class="border-0 px-3 py-3 text-slate-600 bg-white rounded text-sm shadow w-full font-mono"><?= htmlspecialchars($paste['content'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/../layouts/main.php'; ?>
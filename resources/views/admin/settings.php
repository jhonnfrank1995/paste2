<?php $page_title = 'Site Settings'; ?>
<?php ob_start(); ?>

<div class="flex flex-wrap">
    <div class="w-full px-4">
        <div class="card relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded-lg">
            <div class="px-6 py-6">
                <div class="text-center mb-8">
                    <h6 class="text-slate-500 text-sm font-bold">PasteX Pro Configuration</h6>
                </div>
                
                <form action="/admin/settings" method="POST">
                    <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
                    
                    <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">
                        General Settings
                    </h6>
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="site_name">
                                    Site Name
                                </label>
                                <input type="text" id="site_name" name="site_name" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" value="<?= htmlspecialchars($settings['site_name'] ?? 'PasteX Pro') ?>">
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="default_theme">
                                    Default Theme
                                </label>
                                <select id="default_theme" name="default_theme" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                                    <option value="light" <?= ($settings['default_theme'] ?? 'light') == 'light' ? 'selected' : '' ?>>Light</option>
                                    <option value="dark" <?= ($settings['default_theme'] ?? 'light') == 'dark' ? 'selected' : '' ?>>Dark</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-6 border-b-1 border-slate-300">

                    <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">
                        User & Paste Settings
                    </h6>
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="allow_guest_pastes">
                                    Allow Guest Pastes
                                </label>
                                <select id="allow_guest_pastes" name="allow_guest_pastes" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                                    <option value="1" <?= ($settings['allow_guest_pastes'] ?? '1') == '1' ? 'selected' : '' ?>>Enabled</option>
                                    <option value="0" <?= ($settings['allow_guest_pastes'] ?? '1') == '0' ? 'selected' : '' ?>>Disabled (login required)</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 px-4">
                             <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="enable_user_registration">
                                    Enable User Registration
                                </label>
                                <select id="enable_user_registration" name="enable_user_registration" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150">
                                    <option value="1" <?= ($settings['enable_user_registration'] ?? '1') == '1' ? 'selected' : '' ?>>Enabled</option>
                                    <option value="0" <?= ($settings['enable_user_registration'] ?? '1') == '0' ? 'selected' : '' ?>>Disabled</option>
                                </select>
                            </div>
                        </div>
                        <div class="w-full lg:w-6/12 px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="max_paste_size_kb">
                                    Max Paste Size (KB)
                                </label>
                                <input type="number" id="max_paste_size_kb" name="max_paste_size_kb" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150" value="<?= htmlspecialchars($settings['max_paste_size_kb'] ?? '2048') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="mt-6 border-b-1 border-slate-300">
                    
                    <h6 class="text-slate-400 text-sm mt-3 mb-6 font-bold uppercase">
                        Legal Content
                    </h6>
                    <div class="flex flex-wrap">
                        <div class="w-full px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="tos_content">
                                    Terms of Service Content
                                </label>
                                <textarea id="tos_content" name="tos_content" rows="4" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"><?= htmlspecialchars($settings['tos_content'] ?? '') ?></textarea>
                            </div>
                        </div>
                         <div class="w-full px-4">
                            <div class="relative w-full mb-3">
                                <label class="block uppercase text-slate-600 text-xs font-bold mb-2" for="privacy_policy_content">
                                    Privacy Policy Content
                                </label>
                                <textarea id="privacy_policy_content" name="privacy_policy_content" rows="4" class="border-0 px-3 py-3 placeholder-slate-300 text-slate-600 bg-white rounded text-sm shadow focus:outline-none focus:ring w-full ease-linear transition-all duration-150"><?= htmlspecialchars($settings['privacy_policy_content'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <button class="bg-primary text-white active:bg-blue-600 text-sm font-bold uppercase px-6 py-3 rounded shadow hover:shadow-lg outline-none focus:outline-none mr-1 mb-1 w-full sm:w-auto" type="submit">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); ?>
<?php include __DIR__ . '/layouts/main.php'; ?>
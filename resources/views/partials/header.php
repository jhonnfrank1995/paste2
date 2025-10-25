<header class="main-header">
    <div class="container">
        <a href="/" class="logo">
            <img src="/assets/img/logo.svg" alt="PasteX Pro Logo" height="30">
        </a>
        <nav class="main-nav">
            <a href="/new">New Paste</a>
            
            <?php if (isset($user) && is_array($user)): ?>
                
                <?php
                // Línea de depuración temporal. Borrar después.
                // Muestra la estructura del usuario en sesión para ver si el rol está presente.
                // echo '<pre style="font-size: 10px; color: red;">'; print_r($user); echo '</pre>';
                ?>

                <?php // Comprobación robusta para el rol de administrador
                if (isset($user['role']) && $user['role'] === 'admin'): ?>
                    <a href="/admin" style="color: #db2777; font-weight: bold; animation: pulse 2s infinite;">Admin Panel</a>
                <?php endif; ?>
                
                <a href="/user/dashboard">My Dashboard</a>
                
                <form action="/logout" method="POST" style="display: inline;">
                    <input type="hidden" name="_csrf" value="<?= $csrf_token ?>">
                    <button type="submit" class="nav-button">Logout</button>
                </form>

            <?php else: ?>
                <a href="/login">Login</a>
                <a href="/register">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<style>
@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>
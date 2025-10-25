<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PasteX Pro Installer - Step 4: Installing...</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="installer-container">
        <header class="installer-header">
            <h1>Installation in Progress</h1>
            <p>Step 4 of 5: Please wait...</p>
        </header>
        <main class="installer-content">
            <h2>Working its magic...</h2>
            <p>PasteX Pro is now being installed. Please do not close this window.</p>
            
            <ul id="install-progress">
                <li id="task-env" class="pending">Writing configuration file...</li>
                <li id="task-db" class="pending">Setting up database tables...</li>
                <li id="task-seed" class="pending">Populating initial data...</li>
                <li id="task-admin" class="pending">Creating administrator account...</li>
            </ul>

            <div id="error-container" class="error-message" style="display: none;"></div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tasks = ['env', 'db', 'seed', 'admin'];
            
            async function runInstallation() {
                try {
                    document.getElementById('task-env').classList.add('running');
                    
                    const response = await fetch('index.php?step=ajax_install');
                    const result = await response.json();

                    if (result.success) {
                        // Mark all as done
                        tasks.forEach(task => {
                            const el = document.getElementById(`task-${task}`);
                            el.classList.remove('running', 'pending');
                            el.classList.add('done');
                        });
                        
                        // Redirect to final step
                        setTimeout(() => {
                            window.location.href = 'index.php?step=5';
                        }, 1000);

                    } else {
                        throw new Error(result.message);
                    }
                } catch (error) {
                    tasks.forEach(task => {
                        const el = document.getElementById(`task-${task}`);
                        el.classList.remove('running');
                        el.classList.add('error');
                    });
                    const errorContainer = document.getElementById('error-container');
                    errorContainer.textContent = 'An error occurred: ' + error.message;
                    errorContainer.style.display = 'block';
                }
            }
            
            runInstallation();
        });
    </script>
</body>
</html>
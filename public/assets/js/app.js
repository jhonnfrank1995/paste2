// PasteX Pro - Lógica Principal de Frontend
document.addEventListener('DOMContentLoaded', () => {
    // Inicializar el resaltado de sintaxis en las páginas de visualización
    const codeBlocks = document.querySelectorAll('pre code');
    if (codeBlocks.length > 0 && typeof hljs !== 'undefined') {
        codeBlocks.forEach((block) => {
            hljs.highlightElement(block);
        });
    }

    // Lógica para copiar al portapapeles
    const copyButton = document.getElementById('copy-button');
    if (copyButton) {
        copyButton.addEventListener('click', () => {
            const content = document.getElementById('paste-content').innerText;
            navigator.clipboard.writeText(content).then(() => {
                copyButton.innerText = 'Copied!';
                setTimeout(() => { copyButton.innerText = 'Copy'; }, 2000);
            }, (err) => {
                alert('Failed to copy text.');
            });
        });
    }

    // Lógica para el selector de tema
    // ...
});
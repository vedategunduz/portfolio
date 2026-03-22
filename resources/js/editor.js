import Quill from 'quill';
import 'quill/dist/quill.snow.css';

export function initEditor(selector) {
    const container = document.querySelector(selector);
    if (!container) return null;

    if (container.dataset.editorInitialized === '1') {
        return null;
    }

    const quill = new Quill(container, {
        theme: 'snow',
        placeholder: 'İçeriğinizi yazın...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    });

    // Sync with hidden textarea
    const textarea = container.nextElementSibling;
    if (textarea && textarea.tagName === 'TEXTAREA') {
        // Set initial content
        if (textarea.value) {
            quill.root.innerHTML = textarea.value;
        }

        // Sync on text change
        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
            textarea.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }

    container.dataset.editorInitialized = '1';

    return quill;
}

// Initialize all editors on page
export function initAllEditors() {
    const locales = document.querySelectorAll('[data-editor-locale]');
    locales.forEach(el => {
        const locale = el.getAttribute('data-editor-locale');
        initEditor(`[data-editor-content="${locale}"]`);
    });
}

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
        // Load HTML through Quill so the document model matches the editor; SILENT avoids
        // firing autosave "input" events during hydration (innerHTML assignment can thrash).
        if (textarea.value) {
            const delta = quill.clipboard.convert({ html: textarea.value, text: '' });
            quill.setContents(delta, Quill.sources.SILENT);
        }
        textarea.value = quill.root.innerHTML;

        quill.on('text-change', (delta, oldDelta, source) => {
            textarea.value = quill.root.innerHTML;
            if (source === Quill.sources.SILENT) {
                return;
            }
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
    window.dispatchEvent(new CustomEvent('autosave:editors-ready'));
}

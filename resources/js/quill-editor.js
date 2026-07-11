import Quill from 'quill';

function initQuillEditors() {
    document.querySelectorAll('[data-quill]').forEach((wrapper) => {
        const editorEl = wrapper.querySelector('[data-quill-editor]');
        const textarea = wrapper.querySelector('[data-quill-input]');

        if (! editorEl || ! textarea) {
            return;
        }

        const quill = new Quill(editorEl, {
            theme: 'snow',
            placeholder: wrapper.dataset.placeholder || '',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                ],
            },
            formats: ['bold', 'italic', 'underline', 'strike', 'list'],
        });

        if (textarea.value) {
            quill.clipboard.dangerouslyPasteHTML(textarea.value);
        }

        quill.on('text-change', () => {
            textarea.value = quill.root.innerHTML;
        });

        const form = wrapper.closest('form');

        if (form) {
            form.addEventListener('submit', (e) => {
                textarea.value = quill.root.innerHTML;

                if (quill.getText().trim().length === 0) {
                    e.preventDefault();
                    editorEl.classList.add('ring-2', 'ring-rose-500', 'rounded-lg');
                    quill.focus();
                }
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', initQuillEditors);

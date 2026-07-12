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
            form.addEventListener('submit', () => {
                textarea.value = quill.root.innerHTML;
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', initQuillEditors);

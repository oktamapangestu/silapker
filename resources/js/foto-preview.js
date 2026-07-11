function initFotoPicker() {
    document.querySelectorAll('[data-foto-picker]').forEach((wrapper) => {
        const input = wrapper.querySelector('[data-foto-input]');
        const preview = wrapper.querySelector('[data-foto-preview]');
        const addBtn = wrapper.querySelector('[data-foto-add]');

        if (! input || ! preview || ! addBtn) {
            return;
        }

        let files = [];

        function syncInput() {
            const dt = new DataTransfer();
            files.forEach((file) => dt.items.add(file));
            input.files = dt.files;
        }

        function render() {
            preview.querySelectorAll('[data-foto-item]').forEach((el) => el.remove());

            files.forEach((file, index) => {
                const item = document.createElement('div');
                item.dataset.fotoItem = '';
                item.className = 'relative h-20 w-20';

                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.className = 'h-20 w-20 rounded-lg object-cover border border-slate-200';
                img.onload = () => URL.revokeObjectURL(img.src);

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'absolute -top-1.5 -right-1.5 h-5 w-5 flex items-center justify-center rounded-full bg-rose-600 text-white text-xs leading-none hover:bg-rose-700';
                removeBtn.textContent = '×';
                removeBtn.setAttribute('aria-label', 'Hapus foto ini');
                removeBtn.addEventListener('click', () => {
                    files.splice(index, 1);
                    syncInput();
                    render();
                });

                item.appendChild(img);
                item.appendChild(removeBtn);
                preview.insertBefore(item, addBtn);
            });
        }

        addBtn.addEventListener('click', () => input.click());

        input.addEventListener('change', () => {
            files = files.concat(Array.from(input.files));
            syncInput();
            render();
        });
    });
}

document.addEventListener('DOMContentLoaded', initFotoPicker);

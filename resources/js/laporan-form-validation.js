import Swal from 'sweetalert2';

function keteranganKosong(form) {
    const quillInput = form.querySelector('[data-quill-input]');

    if (! quillInput) {
        return false;
    }

    return quillInput.value.replace(/<[^>]*>/g, '').trim().length === 0;
}

function hitungFotoAkhir(form) {
    const fotoBaru = Array.from(form.querySelectorAll('[data-foto-input]'))
        .reduce((total, input) => total + input.files.length, 0);

    const fotoLamaTersisa = form.querySelectorAll('[data-foto-existing]:not(:checked)').length;

    return fotoBaru + fotoLamaTersisa;
}

function initLaporanFormValidation() {
    document.querySelectorAll('form').forEach((form) => {
        const adaQuill = form.querySelector('[data-quill-input]');
        const adaFotoPicker = form.querySelector('[data-foto-input]');

        if (! adaQuill && ! adaFotoPicker) {
            return;
        }

        form.addEventListener('submit', (e) => {
            const kosongKeterangan = keteranganKosong(form);
            const kosongFoto = adaFotoPicker ? hitungFotoAkhir(form) === 0 : false;

            if (! kosongKeterangan && ! kosongFoto) {
                return;
            }

            e.preventDefault();

            let text = 'Foto kegiatan wajib diisi, minimal 1 foto.';

            if (kosongKeterangan && kosongFoto) {
                text = 'Keterangan kegiatan dan foto kegiatan wajib diisi.';
            } else if (kosongKeterangan) {
                text = 'Keterangan kegiatan wajib diisi.';
            }

            Swal.fire({
                icon: 'warning',
                title: 'Lengkapi laporan',
                text,
                confirmButtonText: 'Oke',
                confirmButtonColor: '#4f46e5',
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', initLaporanFormValidation);

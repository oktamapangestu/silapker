const MONTH_NAMES = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
];

function pad(n) {
    return n.toString().padStart(2, '0');
}

function formatDisplay(dateStr) {
    const [y, m, d] = dateStr.split('-').map(Number);

    return `${pad(d)} ${MONTH_NAMES[m - 1].slice(0, 3)} ${y}`;
}

function initLaporanDatePicker() {
    const root = document.getElementById('date-picker');
    if (! root) {
        return;
    }

    const toggleBtn = document.getElementById('date-picker-toggle');
    const calendar = document.getElementById('date-picker-calendar');
    const hiddenInput = document.getElementById('date-picker-value');
    const daysContainer = document.getElementById('date-picker-days');
    const label = document.getElementById('date-picker-label');
    const apiUrl = root.dataset.api;
    const today = root.dataset.today;

    let selected = hiddenInput.value || today;
    let [viewYear, viewMonth] = selected.split('-').map(Number);
    viewMonth -= 1;

    const cache = {};

    function monthKey(y, m) {
        return `${y}-${pad(m + 1)}`;
    }

    async function fetchMonth(y, m) {
        const key = monthKey(y, m);

        if (cache[key]) {
            return cache[key];
        }

        const res = await fetch(`${apiUrl}?bulan=${key}`, { headers: { Accept: 'application/json' } });
        const data = await res.json();
        cache[key] = new Set(data.tanggal_ada_laporan || []);

        return cache[key];
    }

    function showLoading() {
        daysContainer.innerHTML = '';

        const wrapper = document.createElement('div');
        wrapper.className = 'col-span-7 flex flex-col items-center justify-center gap-2 py-6 text-sm text-slate-500';
        wrapper.innerHTML = `
            <span class="h-6 w-6 rounded-full border-2 border-slate-300 border-t-indigo-600 animate-spin"></span>
            <span>Sedang mengambil data tanggal...</span>
        `;
        daysContainer.appendChild(wrapper);
    }

    async function render() {
        label.textContent = `${MONTH_NAMES[viewMonth]} ${viewYear}`;
        showLoading();

        const hasReport = await fetchMonth(viewYear, viewMonth);
        const firstDay = new Date(viewYear, viewMonth, 1).getDay();
        const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

        daysContainer.innerHTML = '';

        for (let i = 0; i < firstDay; i++) {
            daysContainer.appendChild(document.createElement('span'));
        }

        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${viewYear}-${pad(viewMonth + 1)}-${pad(d)}`;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = d;
            btn.className = 'h-8 w-8 rounded-full text-sm flex items-center justify-center transition';

            if (dateStr < today) {
                btn.className += hasReport.has(dateStr)
                    ? ' bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                    : ' bg-rose-100 text-rose-700 hover:bg-rose-200';
            } else if (dateStr === today) {
                btn.className += ' bg-slate-100 text-slate-900 font-semibold hover:bg-slate-200';
            } else {
                btn.className += ' text-slate-400 hover:bg-slate-100';
            }

            if (dateStr === selected) {
                btn.className += ' ring-2 ring-indigo-500';
            }

            btn.addEventListener('click', () => {
                selected = dateStr;
                hiddenInput.value = dateStr;
                toggleBtn.textContent = formatDisplay(dateStr);
                calendar.classList.add('hidden');
                root.closest('form').submit();
            });

            daysContainer.appendChild(btn);
        }
    }

    toggleBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        calendar.classList.toggle('hidden');

        if (! calendar.classList.contains('hidden')) {
            render();
        }
    });

    calendar.querySelectorAll('[data-nav]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            viewMonth += parseInt(btn.dataset.nav, 10);

            if (viewMonth < 0) {
                viewMonth = 11;
                viewYear -= 1;
            } else if (viewMonth > 11) {
                viewMonth = 0;
                viewYear += 1;
            }

            render();
        });
    });

    calendar.addEventListener('click', (e) => e.stopPropagation());
    document.addEventListener('click', () => calendar.classList.add('hidden'));
}

document.addEventListener('DOMContentLoaded', initLaporanDatePicker);

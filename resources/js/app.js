import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const revealElements = document.querySelectorAll('.reveal');

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                threshold: 0.15,
            }
        );

        revealElements.forEach((el) => observer.observe(el));
    } else {
        revealElements.forEach((el) => (el.style.opacity = 1));
    }

    const paymentTable = document.querySelector('#payment-table');
    const paginationContainer = document.querySelector('#payment-pagination');

    if (paymentTable && paginationContainer) {
        const rows = Array.from(paymentTable.querySelectorAll('tbody tr'));
        const rowsPerPage = 6;
        let currentPage = 0;
        const totalPages = Math.max(1, Math.ceil(rows.length / rowsPerPage));

        const renderPage = (page) => {
            currentPage = page;
            rows.forEach((row, index) => {
                const start = page * rowsPerPage;
                const end = start + rowsPerPage;
                row.style.display = index >= start && index < end ? '' : 'none';
            });
        };

        const renderPagination = () => {
            paginationContainer.innerHTML = '';
            for (let i = 0; i < totalPages; i += 1) {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = i + 1;
                button.classList.toggle('active-page', i === currentPage);
                button.disabled = i === currentPage;
                button.addEventListener('click', () => {
                    renderPage(i);
                    renderPagination();
                });
                paginationContainer.appendChild(button);
            }
        };

        renderPage(0);
        renderPagination();
    }
});

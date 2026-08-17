import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const revealElements = document.querySelectorAll('[data-reveal]');

if (revealElements.length) {
    revealElements.forEach((element) => {
        element.style.setProperty('--reveal-delay', `${element.dataset.revealDelay || 0}ms`);
    });

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px' });

    revealElements.forEach((element) => revealObserver.observe(element));
}

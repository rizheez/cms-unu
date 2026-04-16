const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.1 },
);

document.querySelectorAll('[data-reveal]').forEach((element) => revealObserver.observe(element));

const countObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting || !(entry.target instanceof HTMLElement)) {
                return;
            }

            const target = Number(entry.target.dataset.count ?? 0);
            const suffix = entry.target.dataset.suffix ?? '';
            const duration = 1200;
            const startTime = performance.now();

            const animate = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                const value = Math.floor(target * eased);

                entry.target.textContent = `${value.toLocaleString('id-ID')}${suffix}`;

                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };

            requestAnimationFrame(animate);
            countObserver.unobserve(entry.target);
        });
    },
    { threshold: 0.4 },
);

document.querySelectorAll('[data-count]').forEach((element) => countObserver.observe(element));

window.addEventListener('scroll', () => {
    document.getElementById('main-header')?.classList.toggle('scrolled', window.scrollY > 50);
});

document.querySelector('[data-mobile-menu-button]')?.addEventListener('click', () => {
    document.querySelector('[data-mobile-menu]')?.classList.toggle('hidden');
});

document.querySelectorAll('nav a').forEach((link) => {
    if (link instanceof HTMLAnchorElement && link.href === window.location.href) {
        link.classList.add('is-active');
    }
});

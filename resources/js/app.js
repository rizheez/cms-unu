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

const announcementModal = document.querySelector('[data-announcement-modal]');

if (announcementModal instanceof HTMLElement) {
    const closeButton = announcementModal.querySelector('[data-announcement-close]');
    const slides = Array.from(announcementModal.querySelectorAll('[data-announcement-slide]'));
    const previousButton = announcementModal.querySelector('[data-announcement-prev]');
    const nextButton = announcementModal.querySelector('[data-announcement-next]');
    const counter = announcementModal.querySelector('[data-announcement-counter]');
    const storageKey = announcementModal.dataset.announcementKey ?? 'announcement-popup';
    const hasSeenAnnouncement = sessionStorage.getItem(storageKey) === 'dismissed';
    let currentSlide = 0;

    const showSlide = (index) => {
        currentSlide = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            slide.hidden = slideIndex !== currentSlide;
        });

        if (counter instanceof HTMLElement) {
            counter.textContent = `${currentSlide + 1} / ${slides.length}`;
        }
    };

    const closeAnnouncement = () => {
        announcementModal.hidden = true;
        document.body.style.overflow = '';
        sessionStorage.setItem(storageKey, 'dismissed');
    };

    if (!hasSeenAnnouncement) {
        showSlide(0);
        announcementModal.hidden = false;
        document.body.style.overflow = 'hidden';

        if (closeButton instanceof HTMLElement) {
            closeButton.focus();
        }
    }

    previousButton?.addEventListener('click', () => showSlide(currentSlide - 1));
    nextButton?.addEventListener('click', () => showSlide(currentSlide + 1));
    closeButton?.addEventListener('click', closeAnnouncement);
    announcementModal.addEventListener('click', (event) => {
        if (event.target === announcementModal) {
            closeAnnouncement();
        }
    });
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !announcementModal.hidden) {
            closeAnnouncement();
        }

        if (event.key === 'ArrowLeft' && !announcementModal.hidden && slides.length > 1) {
            showSlide(currentSlide - 1);
        }

        if (event.key === 'ArrowRight' && !announcementModal.hidden && slides.length > 1) {
            showSlide(currentSlide + 1);
        }
    });
}

const lightboxTriggers = document.querySelectorAll('[data-lightbox-src]');

if (lightboxTriggers.length > 0) {
    const lightbox = document.createElement('div');
    const image = document.createElement('img');
    const closeButton = document.createElement('button');

    lightbox.className = 'image-lightbox';
    lightbox.hidden = true;
    lightbox.setAttribute('role', 'dialog');
    lightbox.setAttribute('aria-modal', 'true');

    closeButton.type = 'button';
    closeButton.textContent = 'x';
    closeButton.setAttribute('aria-label', 'Tutup gambar');

    lightbox.append(image, closeButton);
    document.body.append(lightbox);

    const closeLightbox = () => {
        lightbox.hidden = true;
        image.removeAttribute('src');
        image.removeAttribute('alt');
        document.body.style.overflow = '';
    };

    lightboxTriggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            if (!(trigger instanceof HTMLElement)) {
                return;
            }

            image.src = trigger.dataset.lightboxSrc ?? '';
            image.alt = trigger.dataset.lightboxAlt ?? '';
            lightbox.hidden = false;
            document.body.style.overflow = 'hidden';
            closeButton.focus();
        });
    });

    closeButton.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (event) => {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !lightbox.hidden) {
            closeLightbox();
        }
    });
}

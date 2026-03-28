// ── CUSTOM CURSOR ──
const cursor = document.getElementById('cursor');
const cursorRing = document.getElementById('cursorRing');
let mx = 0, my = 0, rx = 0, ry = 0;

document.addEventListener('mousemove', e => {
  mx = e.clientX; my = e.clientY;
  cursor.style.left = mx + 'px';
  cursor.style.top = my + 'px';
});

function animateCursorRing() {
  rx += (mx - rx) * 0.12;
  ry += (my - ry) * 0.12;
  cursorRing.style.left = rx + 'px';
  cursorRing.style.top = ry + 'px';
  requestAnimationFrame(animateCursorRing);
}
animateCursorRing();

const hoverEls = document.querySelectorAll('a, button, .project-card, .service-item, .client-logo, .portfolio-case, .archive-row, .hero-visual-card, .portfolio-filter-btn, .portfolio-pagination-btn');
hoverEls.forEach(el => {
  el.addEventListener('mouseenter', () => {
    cursor.classList.add('expand');
    cursorRing.classList.add('expand');
  });
  el.addEventListener('mouseleave', () => {
    cursor.classList.remove('expand');
    cursorRing.classList.remove('expand');
  });
});

// ── SCROLL REVEAL ──
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
    }
  });
}, { threshold: 0.12 });
reveals.forEach(el => observer.observe(el));

// Portfolio index page
const portfolioIndexRoot = document.querySelector('[data-portfolio-index]');
if (portfolioIndexRoot) {
  const portfolioGrid = portfolioIndexRoot.querySelector('[data-portfolio-grid]');
  const portfolioPagination = portfolioIndexRoot.querySelector('[data-portfolio-pagination]');
  const filterBar = document.querySelector('[data-portfolio-filters]');
  const filterButtons = filterBar ? Array.from(filterBar.querySelectorAll('[data-filter]')) : [];
  const resultCount = document.querySelector('[data-portfolio-result-count]');
  const activeFilterLabel = document.querySelector('[data-portfolio-active-filter]');
  const pageSize = Number(portfolioIndexRoot.dataset.pageSize || 6);
  const categoryLabels = {
    all: 'All categories',
    brand: 'Brand',
    web: 'Web',
    commerce: 'Commerce',
    campaign: 'Campaign',
    product: 'Product'
  };
  const portfolioItems = [
    {
      title: 'Northline House',
      categories: ['brand', 'web'],
      year: '2025',
      scope: 'Luxury hospitality',
      result: '42% more direct inquiries',
      summary: 'A quieter editorial system and clearer booking flow for a boutique hospitality brand.',
      surface: 'case-surface-1',
      link: 'portfolio-single.html'
    },
    {
      title: 'Helio Fund',
      categories: ['web', 'product'],
      year: '2024',
      scope: 'Investor experience',
      result: '3x average time on page',
      summary: 'A complex content structure turned into a calm, legible product story.',
      surface: 'case-surface-2',
      link: 'portfolio-single.html'
    },
    {
      title: 'Sable Atelier',
      categories: ['commerce', 'campaign'],
      year: '2024',
      scope: 'Premium commerce',
      result: '19% conversion lift',
      summary: 'Editorial pacing and direct product paths for a beauty label relaunch.',
      surface: 'case-surface-3',
      link: 'portfolio-single.html'
    },
    {
      title: 'Aurele',
      categories: ['brand', 'commerce'],
      year: '2023',
      scope: 'Luxury retail',
      result: 'Launch sold out in 9 days',
      summary: 'A premium visual system with tighter product storytelling and clearer hierarchy.',
      surface: 'case-surface-4',
      link: 'portfolio-single.html'
    },
    {
      title: 'Morrow',
      categories: ['web', 'product'],
      year: '2023',
      scope: 'SaaS platform',
      result: 'Homepage bounce down 21%',
      summary: 'Sharper messaging, better structure, and a faster path to product understanding.',
      surface: 'case-surface-5',
      link: 'portfolio-single.html'
    },
    {
      title: 'Noir + Pine',
      categories: ['web', 'campaign'],
      year: '2022',
      scope: 'Hospitality booking',
      result: 'Direct bookings up 31%',
      summary: 'A reservation journey redesigned for speed, confidence, and atmosphere.',
      surface: 'case-surface-6',
      link: 'portfolio-single.html'
    },
    {
      title: 'Clare',
      categories: ['brand', 'campaign'],
      year: '2022',
      scope: 'Wellness launch',
      result: 'Email signups doubled',
      summary: 'A gentler identity system paired with more decisive landing pages.',
      surface: 'case-surface-2',
      link: 'portfolio-single.html'
    },
    {
      title: 'Forma',
      categories: ['brand', 'web'],
      year: '2021',
      scope: 'Architecture portfolio',
      result: 'Press inquiries increased',
      summary: 'A restrained archive that lets the projects carry the authority.',
      surface: 'case-surface-3',
      link: 'portfolio-single.html'
    },
    {
      title: 'Lune',
      categories: ['product', 'campaign'],
      year: '2021',
      scope: 'Consumer tech launch',
      result: 'Waitlist filled before launch',
      summary: 'A launch page built to convert while still feeling editorial.',
      surface: 'case-surface-1',
      link: 'portfolio-single.html'
    },
    {
      title: 'Velour Labs',
      categories: ['product', 'web'],
      year: '2020',
      scope: 'Beauty tech',
      result: 'Sales page conversion +27%',
      summary: 'A product story with stronger pacing and more explicit calls to action.',
      surface: 'case-surface-4',
      link: 'portfolio-single.html'
    },
    {
      title: 'Maison Orbis',
      categories: ['brand', 'campaign'],
      year: '2020',
      scope: 'Luxury house',
      result: 'Brand recall improved',
      summary: 'A visual refresh that made the brand feel sharper without overexposing it.',
      surface: 'case-surface-5',
      link: 'portfolio-single.html'
    },
    {
      title: 'Stratum Capital',
      categories: ['web', 'product'],
      year: '2019',
      scope: 'Financial services',
      result: 'Qualified leads up 24%',
      summary: 'A measured content system that made a complex offer easier to trust.',
      surface: 'case-surface-6',
      link: 'portfolio-single.html'
    }
  ];
  const state = {
    filter: 'all',
    page: 1
  };

  function getFilteredItems() {
    return state.filter === 'all'
      ? portfolioItems
      : portfolioItems.filter(item => item.categories.includes(state.filter));
  }

  function updateFilterMeta(filteredItems) {
    if (resultCount) {
      const start = Math.min((state.page - 1) * pageSize + 1, filteredItems.length);
      const end = Math.min(state.page * pageSize, filteredItems.length);
      resultCount.textContent = filteredItems.length
        ? `Showing ${start}-${end} of ${filteredItems.length} projects`
        : 'No projects match this filter';
    }

    if (activeFilterLabel) {
      activeFilterLabel.textContent = categoryLabels[state.filter] || categoryLabels.all;
    }
  }

  function updateFilterButtons() {
    filterButtons.forEach(button => {
      const isActive = button.dataset.filter === state.filter;
      button.classList.toggle('is-active', isActive);
      button.setAttribute('aria-pressed', String(isActive));
    });
  }

  function renderPagination(totalPages) {
    const controls = [];

    controls.push(`<button type="button" class="portfolio-pagination-btn" data-page="prev" ${state.page === 1 ? 'disabled' : ''}>Prev</button>`);

    for (let page = 1; page <= totalPages; page += 1) {
      const activeClass = page === state.page ? ' is-active' : '';
      controls.push(`<button type="button" class="portfolio-pagination-btn${activeClass}" data-page="${page}" aria-current="${page === state.page ? 'page' : 'false'}">${page}</button>`);
    }

    controls.push(`<button type="button" class="portfolio-pagination-btn" data-page="next" ${state.page === totalPages ? 'disabled' : ''}>Next</button>`);

    portfolioPagination.innerHTML = controls.join('');
  }

  function observeNewCards() {
    portfolioGrid.querySelectorAll('.reveal').forEach(card => observer.observe(card));
  }

  function renderPortfolioItems() {
    const filteredItems = getFilteredItems();
    const totalPages = Math.max(1, Math.ceil(filteredItems.length / pageSize));

    if (state.page > totalPages) {
      state.page = totalPages;
    }

    const startIndex = (state.page - 1) * pageSize;
    const visibleItems = filteredItems.slice(startIndex, startIndex + pageSize);

    portfolioGrid.innerHTML = visibleItems.map((item, index) => {
      const categoryText = item.categories.map(category => categoryLabels[category] || category).join(' / ');
      const delayClass = `reveal-delay-${(index % 4) + 1}`;

      return `
          <article class="portfolio-list-card reveal ${delayClass}">
            <div class="portfolio-list-card-media">
              <div class="portfolio-list-card-media-inner ${item.surface}"></div>
            </div>
            <div class="portfolio-list-card-copy">
              <div class="portfolio-list-card-topline">
                <span class="portfolio-list-card-category">${categoryText}</span>
                <span class="portfolio-list-card-year">${item.year}</span>
              </div>
              <h2 class="portfolio-list-card-title">${item.title}</h2>
              <p class="portfolio-list-card-text">${item.summary}</p>
              <div class="portfolio-list-card-meta">
                <div class="portfolio-list-card-meta-group">
                  <div class="portfolio-list-card-meta-label">Scope</div>
                  <div class="portfolio-list-card-meta-value">${item.scope}</div>
                </div>
                <div class="portfolio-list-card-meta-group">
                  <div class="portfolio-list-card-meta-label">Result</div>
                  <div class="portfolio-list-card-meta-value">${item.result}</div>
                </div>
              </div>
              <a class="portfolio-list-card-link" href="${item.link}">View case study</a>
            </div>
          </article>
        `;
    }).join('');

    renderPagination(totalPages);
    updateFilterMeta(filteredItems);
    updateFilterButtons();
    observeNewCards();
  }

  if (filterBar) {
    filterBar.addEventListener('click', event => {
      const button = event.target.closest('[data-filter]');
      if (!button) {
        return;
      }

      state.filter = button.dataset.filter;
      state.page = 1;
      renderPortfolioItems();
    });
  }

  portfolioPagination.addEventListener('click', event => {
    const button = event.target.closest('[data-page]');
    if (!button || button.disabled) {
      return;
    }

    const action = button.dataset.page;
    const totalPages = Math.max(1, Math.ceil(getFilteredItems().length / pageSize));

    if (action === 'prev') {
      state.page = Math.max(1, state.page - 1);
    } else if (action === 'next') {
      state.page = Math.min(totalPages, state.page + 1);
    } else {
      state.page = Number(action);
    }

    renderPortfolioItems();
  });

  renderPortfolioItems();
}

// ── SMOOTH NAV ON SCROLL ──
const nav = document.querySelector('nav');
window.addEventListener('scroll', () => {
  if (window.scrollY > 60) {
    nav.style.background = 'rgba(10,10,10,0.92)';
    nav.style.backdropFilter = 'blur(12px)';
    nav.style.borderBottom = '1px solid rgba(201,185,154,0.08)';
    nav.style.padding = '20px 48px';
  } else {
    nav.style.background = 'transparent';
    nav.style.backdropFilter = 'none';
    nav.style.borderBottom = 'none';
    nav.style.padding = '32px 48px';
  }
});

// ── HERO SLIDER ──
const heroSlider = document.querySelector('.hero-slider');
if (heroSlider) {
  const slides = heroSlider.querySelectorAll('.hero-slide');
  const dots = heroSlider.querySelectorAll('.hero-dot');
  const prevBtn = heroSlider.querySelector('.prev-btn');
  const nextBtn = heroSlider.querySelector('.next-btn');
  let currentSlide = 0;
  let slideInterval;
  const duration = 7000;

  function goToSlide(index) {
    slides[currentSlide].classList.remove('active');
    dots[currentSlide].classList.remove('active');
    currentSlide = (index + slides.length) % slides.length;
    slides[currentSlide].classList.add('active');
    dots[currentSlide].classList.add('active');
  }

  if (nextBtn) nextBtn.addEventListener('click', () => { goToSlide(currentSlide + 1); resetInterval(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { goToSlide(currentSlide - 1); resetInterval(); });
  dots.forEach((dot, index) => {
    dot.addEventListener('click', () => { goToSlide(index); resetInterval(); });
  });

  function startInterval() {
    slideInterval = setInterval(() => goToSlide(currentSlide + 1), duration);
  }
  function resetInterval() {
    clearInterval(slideInterval);
    startInterval();
  }
  startInterval();
}

// ── HERO PARALLAX ──
window.addEventListener('scroll', () => {
  const y = window.scrollY;
  document.querySelectorAll('.hero-slide.active .hero-content').forEach(content => {
    content.style.transform = `translateY(${y * 0.15}px)`;
  });
});

// ── PROJECT CARD TILT ──
document.querySelectorAll('.project-card').forEach(card => {
  card.addEventListener('mousemove', e => {
    const rect = card.getBoundingClientRect();
    const x = (e.clientX - rect.left) / rect.width - 0.5;
    const y = (e.clientY - rect.top) / rect.height - 0.5;
    card.style.transform = `perspective(1000px) rotateY(${x * 4}deg) rotateX(${-y * 4}deg)`;
  });
  card.addEventListener('mouseleave', () => {
    card.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
    card.style.transition = 'transform 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
  });
  card.addEventListener('mouseenter', () => {
    card.style.transition = 'transform 0.1s';
  });
});

// ── MOBILE: hide cursor ──
if ('ontouchstart' in window) {
  cursor.style.display = 'none';
  cursorRing.style.display = 'none';
  document.body.style.cursor = 'auto';
}

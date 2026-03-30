(function () {
  'use strict';

  /* ---- NAV SCROLL BEHAVIOUR ---- */
  const nav = document.getElementById('mainNav');
  const onScroll = () => {
    nav.classList.toggle('scrolled', window.scrollY > 40);
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  /* ---- HAMBURGER / MOBILE MENU ---- */
  const hamburger = document.getElementById('hamburgerBtn');
  const mobileMenu = document.getElementById('mobileMenu');
  let menuOpen = false;

  const toggleMenu = (open) => {
    menuOpen = open;
    mobileMenu.classList.toggle('open', open);
    hamburger.setAttribute('aria-expanded', String(open));
    mobileMenu.setAttribute('aria-hidden', String(!open));
    document.body.style.overflow = open ? 'hidden' : '';
    const spans = hamburger.querySelectorAll('span');
    if (open) {
      spans[0].style.transform = 'rotate(45deg) translate(4px, 4px)';
      spans[1].style.opacity = '0';
      spans[2].style.transform = 'rotate(-45deg) translate(4px, -4px)';
    } else {
      spans.forEach(s => { s.style.transform = ''; s.style.opacity = ''; });
    }
  };

  hamburger.addEventListener('click', () => toggleMenu(!menuOpen));
  mobileMenu.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => toggleMenu(false));
  });

  /* ---- SCROLL REVEAL ---- */
  const revealEls = document.querySelectorAll('.reveal');
  const revealObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObs.unobserve(entry.target);
      }
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
  revealEls.forEach(el => revealObs.observe(el));

  /* ---- TEST BAR ANIMATIONS ---- */
  const barObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const fill = entry.target;
        const width = fill.dataset.width;
        fill.style.width = width + '%';
        barObs.unobserve(fill);
      }
    });
  }, { threshold: 0.5 });
  document.querySelectorAll('.test-bar__fill').forEach(fill => barObs.observe(fill));

  /* ---- PRODUCT FILTER ---- */
  const filterBtns = document.querySelectorAll('.filter-btn');
  const productCards = document.querySelectorAll('.product-card');

  if (filterBtns.length && productCards.length) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        productCards.forEach(card => {
          const match = filter === 'all' || card.dataset.category === filter;
          card.style.display = match ? '' : 'none';
        });
      });
    });
  }

  /* ---- IMAGE SHOWCASE GALLERY ---- */
  const showcase = document.querySelector('[data-gallery="showcase"]');
  if (showcase) {
    const perPage = Number(showcase.dataset.galleryPerPage || 6);
    const filterButtons = Array.from(showcase.querySelectorAll('[data-gallery-filter]'));
    const items = Array.from(showcase.querySelectorAll('[data-gallery-item]'));
    const countValue = showcase.querySelector('[data-gallery-count]');
    const pageValue = showcase.querySelector('[data-gallery-page]');
    const emptyState = showcase.querySelector('[data-gallery-empty]');
    const pagination = showcase.querySelector('[data-gallery-pagination]');
    const paginationInfo = showcase.querySelector('[data-gallery-pagination-info]');
    const previousBtn = showcase.querySelector('[data-gallery-prev]');
    const nextBtn = showcase.querySelector('[data-gallery-next]');
    const lightbox = document.querySelector('[data-lightbox]');
    const lightboxImage = lightbox?.querySelector('[data-lightbox-image]');
    const lightboxTitle = lightbox?.querySelector('[data-lightbox-title]');
    const lightboxMeta = lightbox?.querySelector('[data-lightbox-meta]');
    const lightboxText = lightbox?.querySelector('[data-lightbox-text]');
    const lightboxCounter = lightbox?.querySelector('[data-lightbox-counter]');
    const lightboxClose = lightbox?.querySelector('[data-lightbox-close]');
    const lightboxPrev = lightbox?.querySelector('[data-lightbox-prev]');
    const lightboxNext = lightbox?.querySelector('[data-lightbox-next]');
    const lightboxBackdrop = lightbox?.querySelector('[data-lightbox-backdrop]');

    let activeFilter = 'all';
    let currentPage = 1;
    let filteredItems = items.slice();
    let lightboxIndex = -1;

    const updatePaginationButtons = () => {
      if (!pagination) return;
      const totalPages = Math.max(1, Math.ceil(filteredItems.length / perPage));
      const pageButtons = Array.from(pagination.querySelectorAll('[data-gallery-page-btn]'));
      pageButtons.forEach(button => button.remove());

      for (let page = 1; page <= totalPages; page += 1) {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'pagination__btn';
        button.dataset.galleryPageBtn = String(page);
        button.textContent = String(page);
        if (page === currentPage) {
          button.classList.add('active');
          button.setAttribute('aria-current', 'page');
        }
        button.addEventListener('click', () => {
          currentPage = page;
          renderGallery();
        });
        nextBtn?.before(button);
      }

      if (paginationInfo) {
        paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
      }
      if (previousBtn) previousBtn.disabled = currentPage === 1;
      if (nextBtn) nextBtn.disabled = currentPage === totalPages;
    };

    const updateLightbox = () => {
      if (!lightbox || lightboxIndex < 0 || !filteredItems[lightboxIndex]) return;
      const item = filteredItems[lightboxIndex];
      const image = item.querySelector('.showcase-card__image');
      const title = item.dataset.title || '';
      const meta = item.dataset.meta || '';
      const text = item.dataset.description || '';

      if (lightboxImage && image) {
        lightboxImage.src = image.src;
        lightboxImage.alt = image.alt;
      }
      if (lightboxTitle) lightboxTitle.textContent = title;
      if (lightboxMeta) lightboxMeta.textContent = meta;
      if (lightboxText) lightboxText.textContent = text;
      if (lightboxCounter) {
        lightboxCounter.textContent = `${lightboxIndex + 1} / ${filteredItems.length}`;
      }
      if (lightboxPrev) lightboxPrev.disabled = lightboxIndex === 0;
      if (lightboxNext) lightboxNext.disabled = lightboxIndex === filteredItems.length - 1;
    };

    const closeLightbox = () => {
      if (!lightbox) return;
      lightbox.classList.remove('open');
      lightbox.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      lightboxIndex = -1;
    };

    const openLightbox = (item) => {
      if (!lightbox) return;
      const index = filteredItems.indexOf(item);
      if (index === -1) return;
      lightboxIndex = index;
      updateLightbox();
      lightbox.classList.add('open');
      lightbox.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    };

    const renderGallery = () => {
      const openItemId = lightboxIndex >= 0 ? filteredItems[lightboxIndex]?.dataset.id : '';
      filteredItems = items.filter(item => activeFilter === 'all' || item.dataset.category === activeFilter);

      const totalPages = Math.max(1, Math.ceil(filteredItems.length / perPage));
      currentPage = Math.min(currentPage, totalPages);
      const start = (currentPage - 1) * perPage;
      const end = start + perPage;
      const pageItems = filteredItems.slice(start, end);

      items.forEach(item => {
        const visible = pageItems.includes(item);
        item.hidden = !visible;
      });

      if (countValue) countValue.textContent = String(filteredItems.length);
      if (pageValue) pageValue.textContent = String(currentPage);
      if (emptyState) emptyState.hidden = filteredItems.length !== 0;

      updatePaginationButtons();

      if (lightbox?.classList.contains('open')) {
        const activeItem = filteredItems.find(item => item.dataset.id === openItemId);
        if (!activeItem) {
          closeLightbox();
        } else {
          lightboxIndex = filteredItems.indexOf(activeItem);
          updateLightbox();
        }
      }
    };

    filterButtons.forEach(button => {
      button.addEventListener('click', () => {
        activeFilter = button.dataset.galleryFilter || 'all';
        currentPage = 1;
        filterButtons.forEach(btn => {
          const active = btn === button;
          btn.classList.toggle('active', active);
          btn.setAttribute('aria-pressed', String(active));
        });
        renderGallery();
      });
    });

    items.forEach(item => {
      item.querySelectorAll('[data-gallery-open]').forEach(trigger => {
        trigger.addEventListener('click', () => openLightbox(item));
      });
    });

    previousBtn?.addEventListener('click', () => {
      if (currentPage === 1) return;
      currentPage -= 1;
      renderGallery();
    });

    nextBtn?.addEventListener('click', () => {
      const totalPages = Math.max(1, Math.ceil(filteredItems.length / perPage));
      if (currentPage === totalPages) return;
      currentPage += 1;
      renderGallery();
    });

    lightboxClose?.addEventListener('click', closeLightbox);
    lightboxBackdrop?.addEventListener('click', closeLightbox);
    lightboxPrev?.addEventListener('click', () => {
      if (lightboxIndex <= 0) return;
      lightboxIndex -= 1;
      updateLightbox();
    });
    lightboxNext?.addEventListener('click', () => {
      if (lightboxIndex >= filteredItems.length - 1) return;
      lightboxIndex += 1;
      updateLightbox();
    });

    document.addEventListener('keydown', (event) => {
      if (!lightbox?.classList.contains('open')) return;
      if (event.key === 'Escape') closeLightbox();
      if (event.key === 'ArrowLeft' && lightboxIndex > 0) {
        lightboxIndex -= 1;
        updateLightbox();
      }
      if (event.key === 'ArrowRight' && lightboxIndex < filteredItems.length - 1) {
        lightboxIndex += 1;
        updateLightbox();
      }
    });

    renderGallery();
  }

  /* ---- ADVANCED PRODUCT SEARCH ---- */
  const advancedSearch = document.querySelector('[data-advanced-search]');
  if (advancedSearch) {
    const form = advancedSearch.querySelector('[data-advanced-search-form]');
    const keywordInput = advancedSearch.querySelector('[data-search-keyword]');
    const yearSelect = advancedSearch.querySelector('[data-search-year]');
    const makeSelect = advancedSearch.querySelector('[data-search-make]');
    const modelSelect = advancedSearch.querySelector('[data-search-model]');
    const categorySelect = advancedSearch.querySelector('[data-search-category]');
    const resetButton = advancedSearch.querySelector('[data-search-reset]');
    const cards = Array.from(advancedSearch.querySelectorAll('[data-search-card]'));
    const countValue = advancedSearch.querySelector('[data-search-count]');
    const summaryValue = advancedSearch.querySelector('[data-search-summary]');
    const emptyState = advancedSearch.querySelector('[data-search-empty]');

    const uniqueValues = (items, key, filters = {}) => {
      const values = items
        .filter(item => Object.entries(filters).every(([filterKey, filterValue]) => !filterValue || item.dataset[filterKey] === filterValue))
        .map(item => item.dataset[key])
        .filter(Boolean);
      return [...new Set(values)].sort((a, b) => a.localeCompare(b, undefined, { numeric: true }));
    };

    const populateSelect = (select, values, placeholder, preserveValue = '') => {
      if (!select) return;
      const nextValue = values.includes(preserveValue) ? preserveValue : '';
      select.innerHTML = '';
      const defaultOption = document.createElement('option');
      defaultOption.value = '';
      defaultOption.textContent = placeholder;
      select.append(defaultOption);
      values.forEach(value => {
        const option = document.createElement('option');
        option.value = value;
        option.textContent = value;
        select.append(option);
      });
      select.value = nextValue;
    };

    const syncDependentFilters = () => {
      const selectedYear = yearSelect?.value || '';
      const selectedMake = makeSelect?.value || '';
      const selectedModel = modelSelect?.value || '';

      const makeValues = uniqueValues(cards, 'make', { year: selectedYear });
      populateSelect(makeSelect, makeValues, 'All Makes', selectedMake);

      const modelValues = uniqueValues(cards, 'model', {
        year: selectedYear,
        make: makeSelect?.value || ''
      });
      populateSelect(modelSelect, modelValues, 'All Models', selectedModel);
    };

    const renderAdvancedSearch = () => {
      syncDependentFilters();

      const keyword = (keywordInput?.value || '').trim().toLowerCase();
      const year = yearSelect?.value || '';
      const make = makeSelect?.value || '';
      const model = modelSelect?.value || '';
      const category = categorySelect?.value || '';

      const visibleCards = cards.filter(card => {
        const matchesKeyword = !keyword || [
          card.dataset.partnumber,
          card.dataset.oenumber,
          card.dataset.vin
        ].join(' ').toLowerCase().includes(keyword);
        const matchesYear = !year || card.dataset.year === year;
        const matchesMake = !make || card.dataset.make === make;
        const matchesModel = !model || card.dataset.model === model;
        const matchesCategory = !category || card.dataset.category === category;
        return matchesKeyword && matchesYear && matchesMake && matchesModel && matchesCategory;
      });

      cards.forEach(card => {
        card.hidden = !visibleCards.includes(card);
      });

      if (countValue) countValue.textContent = String(visibleCards.length);

      const summaryParts = [];
      if (keyword) summaryParts.push(`Keyword: ${keywordInput.value.trim()}`);
      if (year) summaryParts.push(`Year: ${year}`);
      if (make) summaryParts.push(`Make: ${make}`);
      if (model) summaryParts.push(`Model: ${model}`);
      if (category) summaryParts.push(`Category: ${category}`);
      if (summaryValue) {
        summaryValue.textContent = summaryParts.length ? summaryParts.join(' | ') : 'Showing all indexed hub assemblies';
      }

      if (emptyState) emptyState.hidden = visibleCards.length !== 0;
    };

    yearSelect?.addEventListener('change', () => {
      populateSelect(modelSelect, [], 'All Models');
      renderAdvancedSearch();
    });
    makeSelect?.addEventListener('change', renderAdvancedSearch);
    modelSelect?.addEventListener('change', renderAdvancedSearch);
    categorySelect?.addEventListener('change', renderAdvancedSearch);
    keywordInput?.addEventListener('input', renderAdvancedSearch);
    form?.addEventListener('submit', event => {
      event.preventDefault();
      renderAdvancedSearch();
    });
    resetButton?.addEventListener('click', () => {
      form?.reset();
      syncDependentFilters();
      renderAdvancedSearch();
    });

    populateSelect(yearSelect, uniqueValues(cards, 'year'), 'All Years');
    populateSelect(categorySelect, uniqueValues(cards, 'category'), 'All Categories');
    syncDependentFilters();
    renderAdvancedSearch();
  }

  /* ---- SMOOTH ANCHOR NAVIGATION ---- */
  document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      const id = a.getAttribute('href');
      if (id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  /* ---- PARALLAX HERO ---- */
  const heroBg = document.querySelector('.hero__bg');
  const heroHub = document.querySelector('.hero__hub-visual');
  if (heroBg) {
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      heroBg.style.transform = `translateY(${y * 0.25}px)`;
      if (heroHub) heroHub.style.transform = `translateY(calc(-50% + ${y * 0.12}px))`;
    }, { passive: true });
  }

  /* ---- RFQ FORM ---- */
  const rfqForm = document.getElementById('rfqForm');
  if (rfqForm) {
    rfqForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const btn = rfqForm.querySelector('.form-submit');
      btn.textContent = '✓ Request Submitted — We\'ll contact you within 24 hours';
      btn.style.background = 'var(--c-accent-amber-dim)';
      btn.disabled = true;
    });
  }

  /* ---- COUNTER ANIMATION ---- */
  const counters = document.querySelectorAll('.trust-stat__value');
  const counterObs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        const text = el.textContent;
        const num = parseFloat(text.replace(/[^0-9.]/g, ''));
        if (isNaN(num) || num === 0) return;
        const suffix = text.replace(/[0-9.]/g, '').trim();
        const duration = 1200;
        const steps = 60;
        const step = num / steps;
        let current = 0;
        const interval = setInterval(() => {
          current = Math.min(current + step, num);
          const display = Number.isInteger(num) ? Math.round(current) : current.toFixed(1);
          el.innerHTML = display + suffix;
          if (current >= num) clearInterval(interval);
        }, duration / steps);
        counterObs.unobserve(el);
      }
    });
  }, { threshold: 0.5 });
  counters.forEach(c => counterObs.observe(c));

})();

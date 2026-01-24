// assets/js/landing.js

(function() {
    // Utility functions
    const fmt = (s) => String(s || '')
        .replace(/[<>&]/g, '')
        .replace(/`/g, '')
        .replace(/\$\{/g, '')
        .trim();

    function sanitizeUrl(raw) {
        if (!raw) return '';
        let u = String(raw).trim();
        if (!/^https?:\/\//i.test(u)) u = 'https://' + u;
        return u;
    }

    // Get configuration from global window object
    const fallbackStats = window.fallbackStats || {
        openJobs: 0
    };
    const pesoFeedUrl = window.pesoFeedUrl || '/peso/feed';

    // Format date for display
    const formatDate = (value) => {
        if (!value) return '';
        const str = String(value).trim();
        const normalized = str.includes('T') ? str : str.replace(' ', 'T');
        const d = new Date(normalized);
        if (!isNaN(d.getTime())) {
            return d.toLocaleDateString('en-PH', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
        }
        return str;
    };

    // Format currency for display
    const formatPeso = (value) => {
        const num = Number(value);
        if (!isFinite(num)) return null;
        const hasCents = Math.abs(num % 1) > 0;
        return '\u20B1 ' + num.toLocaleString('en-PH', {
            minimumFractionDigits: hasCents ? 2 : 0,
            maximumFractionDigits: hasCents ? 2 : 0
        });
    };

    // Update insights section with job data
    function updateInsights(list) {
        const safeList = Array.isArray(list) ? list : [];
        const total = safeList.length;
        const fallbackTotal = Number.isFinite(fallbackStats.openJobs) ? fallbackStats.openJobs : 0;

        const totalEl = document.getElementById('insight-total-jobs');
        const totalNoteEl = document.getElementById('insight-total-jobs-note');
        if (totalEl) {
            if (total > 0) {
                totalEl.textContent = String(total);
                if (totalNoteEl) totalNoteEl.textContent = 'From the latest PESO feed';
            } else if (fallbackTotal > 0) {
                totalEl.textContent = String(fallbackTotal);
                if (totalNoteEl) totalNoteEl.textContent = 'Based on dashboard totals';
            } else {
                totalEl.textContent = '0';
                if (totalNoteEl) totalNoteEl.textContent = 'Awaiting new job orders';
            }
        } else if (totalNoteEl) {
            totalNoteEl.textContent = '';
        }

        const locationCounts = {};
        safeList.forEach(item => {
            const loc = fmt(item && item.location_text);
            if (!loc) return;
            locationCounts[loc] = (locationCounts[loc] || 0) + 1;
        });

        const locationEl = document.getElementById('insight-total-locations');
        const locationNoteEl = document.getElementById('insight-location-top');
        const uniqueLocations = Object.keys(locationCounts).length;

        if (locationEl) {
            if (uniqueLocations > 0) {
                locationEl.textContent = String(uniqueLocations);
            } else if (total > 0) {
                locationEl.textContent = '1';
            } else {
                locationEl.textContent = '--';
            }
        }
        if (locationNoteEl) {
            if (uniqueLocations > 0) {
                const [topLoc, topCount] = Object.entries(locationCounts).sort((a, b) => b[1] - a[1])[0];
                locationNoteEl.textContent = `${topLoc} - ${topCount} post${topCount > 1 ? 's' : ''}`;
            } else if (total > 0) {
                locationNoteEl.textContent = 'Listings span multiple barangays';
            } else {
                locationNoteEl.textContent = 'No public locations yet';
            }
        }

        const payCountEl = document.getElementById('insight-pay-count');
        const payPercentEl = document.getElementById('insight-pay-percent');
        const withPay = safeList.filter(item => {
            if (!item) return false;
            const hasMin = item.price_min !== null && item.price_min !== undefined && item.price_min !== '';
            const hasMax = item.price_max !== null && item.price_max !== undefined && item.price_max !== '';
            return hasMin || hasMax;
        }).length;

        if (payCountEl) {
            if (total > 0) {
                payCountEl.textContent = `${withPay}/${total}`;
            } else {
                payCountEl.textContent = fallbackTotal > 0 ? '--' : '0';
            }
        }
        if (payPercentEl) {
            if (total > 0) {
                const percent = Math.round((withPay / total) * 100);
                payPercentEl.textContent = `${percent}% include salary info`;
            } else {
                payPercentEl.textContent = 'Awaiting salary details';
            }
        }

        const lastUpdatedEl = document.getElementById('insight-last-updated');
        const lastUpdatedNoteEl = document.getElementById('insight-last-updated-note');
        if (lastUpdatedEl && lastUpdatedNoteEl) {
            if (total > 0) {
                const recent = safeList[0];
                const stampRaw = recent && recent.created_at ? String(recent.created_at) : '';
                const stamp = stampRaw ? new Date(stampRaw.replace(' ', 'T')) : null;
                if (stamp && !Number.isNaN(stamp.getTime())) {
                    const diffMs = Date.now() - stamp.getTime();
                    const diffHours = diffMs / (1000 * 60 * 60);
                    if (diffHours < 1) {
                        lastUpdatedEl.textContent = 'Just now';
                        lastUpdatedNoteEl.textContent = 'Posted within the last hour';
                    } else if (diffHours < 24) {
                        lastUpdatedEl.textContent = Math.round(diffHours) + 'h';
                        lastUpdatedNoteEl.textContent = 'Hours since the newest job order';
                    } else {
                        lastUpdatedEl.textContent = Math.round(diffHours / 24) + 'd';
                        lastUpdatedNoteEl.textContent = 'Days since the newest job order';
                    }
                } else {
                    lastUpdatedEl.textContent = 'Today';
                    lastUpdatedNoteEl.textContent = 'Latest timestamp unavailable';
                }
            } else {
                lastUpdatedEl.textContent = '--';
                lastUpdatedNoteEl.textContent = 'No public job feed yet';
            }
        }
    }

    // Create job slide DOM element
    function makeSlideNode(j, isLatest) {
        const slide = document.createElement('div');
        slide.className = 'job-slide';

        const card = document.createElement('div');
        card.className = 'job-card';
        slide.appendChild(card);

        const mediaRaw = j && j.media && typeof j.media === 'object' ? j.media : null;
        const viewerUrl = mediaRaw && mediaRaw.viewer_url ? sanitizeUrl(mediaRaw.viewer_url) : '';
        const wmUrl = mediaRaw && mediaRaw.wm_url ? sanitizeUrl(mediaRaw.wm_url) : '';
        const publicUrl = mediaRaw && mediaRaw.public_url ? sanitizeUrl(mediaRaw.public_url) : '';
        const mediaType = mediaRaw && mediaRaw.type ? String(mediaRaw.type) : '';
        const mediaLabel = mediaRaw && mediaRaw.original ? String(mediaRaw.original) : 'Job attachment';
        const hasMedia = Boolean(mediaRaw && (publicUrl || viewerUrl || wmUrl));

        if (hasMedia) {
            const inlineUrl = publicUrl || viewerUrl || wmUrl;
            if (inlineUrl) {
                if (mediaType === 'image') {
                    const link = document.createElement('a');
                    link.className = 'job-card__media job-card__media--image';
                    link.href = viewerUrl || inlineUrl;
                    link.target = '_blank';
                    link.rel = 'noopener';
                    const img = document.createElement('img');
                    img.src = inlineUrl;
                    img.alt = mediaLabel;
                    img.loading = 'lazy';
                    img.decoding = 'async';
                    link.appendChild(img);
                    card.appendChild(link);
                } else if (mediaType === 'pdf') {
                    const wrap = document.createElement('div');
                    wrap.className = 'job-card__media job-card__media--pdf';
                    const frame = document.createElement('iframe');
                    frame.src = inlineUrl;
                    frame.loading = 'lazy';
                    frame.title = mediaLabel;
                    frame.setAttribute('aria-label', mediaLabel);
                    wrap.appendChild(frame);
                    const viewLink = document.createElement('a');
                    viewLink.href = viewerUrl || inlineUrl;
                    viewLink.target = '_blank';
                    viewLink.rel = 'noopener';
                    viewLink.className = 'job-card__media-link';
                    viewLink.innerHTML = '<i class="mdi mdi-open-in-new"></i> View document';
                    wrap.appendChild(viewLink);
                    card.appendChild(wrap);
                }
            }
        }

        const body = document.createElement('div');
        body.className = 'job-card__content';
        card.appendChild(body);

        if (isLatest) {
            const badge = document.createElement('span');
            badge.className = 'job-badge';
            badge.textContent = 'Newest Listing';
            body.appendChild(badge);
        }

        const title = document.createElement('div');
        title.className = 'title';
        title.textContent = fmt(j && j.title);
        body.appendChild(title);

        const small = document.createElement('div');
        small.className = 'muted small';
        small.textContent = formatDate(j && j.created_at);
        body.appendChild(small);

        const meta = document.createElement('div');
        meta.className = 'meta';
        body.appendChild(meta);

        if (j && j.location_text) {
            const spanLoc = document.createElement('span');
            spanLoc.innerHTML = '<i class="mdi mdi-map-marker"></i> ' + fmt(j.location_text);
            meta.appendChild(spanLoc);
        }

        const minText = formatPeso(j && j.price_min);
        const maxText = formatPeso(j && j.price_max);
        if (minText || maxText) {
            const spanPrice = document.createElement('span');
            const range = (minText && maxText) ? `${minText} - ${maxText}` : (minText || maxText);
            spanPrice.innerHTML = '<i class="mdi mdi-cash"></i> ' + range;
            meta.appendChild(spanPrice);
        }

        const desc = document.createElement('div');
        desc.className = 'job-card__description';
        const descriptionText = fmt(j && j.description);
        desc.textContent = descriptionText || 'No job summary provided yet.';
        body.appendChild(desc);

        const links = [];

        if (hasMedia) {
            const att = document.createElement('a');
            att.className = 'ext-link';
            att.href = viewerUrl || publicUrl || wmUrl;
            att.target = '_blank';
            att.rel = 'noopener';
            const icon = mediaType === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-image-outline';
            att.innerHTML = `<i class="mdi ${icon}"></i> View attachment`;
            links.push(att);
        }

        const rawUrl = (j && (j.website_url || j.url || j.link)) ? (j.website_url || j.url || j.link) : '';
        const safe = sanitizeUrl(rawUrl);
        if (safe) {
            const a = document.createElement('a');
            a.className = 'ext-link';
            a.target = '_blank';
            a.rel = 'noopener';
            a.href = safe;
            a.innerHTML = '<i class="mdi mdi-open-in-new"></i> Apply / Website';
            links.push(a);
        }

        if (links.length) {
            const wrap = document.createElement('div');
            wrap.className = 'job-card__links';
            links.forEach(node => wrap.appendChild(node));
            body.appendChild(wrap);
        }

        return slide;
    }

    // Build pagination dots for carousel
    function buildDots(cnt, dotsEl) {
        if (!dotsEl) return;
        dotsEl.innerHTML = '';
        for (let i = 0; i < cnt; i++) {
            const b = document.createElement('button');
            if (i === 0) b.classList.add('active');
            b.setAttribute('aria-label', `Go to slide ${i + 1}`);
            dotsEl.appendChild(b);
        }
    }

    // Initialize carousel functionality
    function startCarousel(innerEl, dotsEl, prevBtn, nextBtn, intervalMs) {
        if (!innerEl) return;
        const slides = innerEl.children.length;
        if (!slides) return;
        let idx = 0,
            timer = null;

        function go(i) {
            idx = (i + slides) % slides;
            innerEl.style.transform = `translateX(-${idx * 100}%)`;
            if (dotsEl) {
                [...dotsEl.children].forEach((d, k) => d.classList.toggle('active', k === idx));
            }
        }

        function start() {
            timer = setInterval(() => go(idx + 1), intervalMs);
        }

        function stop() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        innerEl.addEventListener('mouseenter', stop);
        innerEl.addEventListener('mouseleave', start);

        if (prevBtn) prevBtn.addEventListener('click', () => {
            stop();
            go(idx - 1);
            start();
        });

        if (nextBtn) nextBtn.addEventListener('click', () => {
            stop();
            go(idx + 1);
            start();
        });

        if (dotsEl) {
            [...dotsEl.children].forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    stop();
                    go(i);
                    start();
                });
            });
        }

        let x0 = null;
        innerEl.addEventListener('touchstart', e => {
            x0 = e.touches[0].clientX;
            stop();
        }, {
            passive: true
        });

        innerEl.addEventListener('touchend', e => {
            if (x0 === null) return;
            const dx = (e.changedTouches[0].clientX - x0);
            if (Math.abs(dx) > 40) {
                go(idx + (dx < 0 ? 1 : -1));
            }
            x0 = null;
            start();
        });

        start();
    }

    // Initialize toolkit tabs functionality
    function initToolkitTabs() {
        const shell = document.querySelector('.toolkit-shell');
        if (!shell) return;
        const tabs = Array.from(shell.querySelectorAll('.toolkit-tab'));
        const panels = Array.from(shell.querySelectorAll('.toolkit-panel'));
        if (!tabs.length || !panels.length) return;

        const activate = (slug) => {
            tabs.forEach(btn => btn.classList.toggle('active', btn.dataset.role === slug));
            panels.forEach(panel => panel.classList.toggle('active', panel.dataset.role === slug));
            shell.setAttribute('data-active-role', slug);
        };

        tabs.forEach(btn => {
            btn.addEventListener('click', () => activate(btn.dataset.role));
        });

        let defaultRole = shell.getAttribute('data-default-role') || '';
        if (!tabs.some(btn => btn.dataset.role === defaultRole)) {
            defaultRole = tabs[0].dataset.role;
        }
        activate(defaultRole);
    }

    // Main initialization function
    function initializeLandingPage() {
        // Initialize toolkit tabs
        initToolkitTabs();
        
        // Initial empty insights
        updateInsights([]);

        // Fetch job data and initialize carousel
        fetch(pesoFeedUrl, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(r => r.ok ? r.text() : Promise.reject())
            .then(t => {
                try {
                    return JSON.parse(t);
                } catch (e) {
                    return {
                        ok: false
                    };
                }
            })
            .then(json => {
                if (!json || !json.ok) {
                    updateInsights([]);
                    return;
                }
                const list = Array.isArray(json.data) ? json.data : [];
                updateInsights(list);

                const wrap = document.getElementById('jobs-carousel');
                const inner = document.getElementById('jobs-inner');
                const dots = document.getElementById('jobs-dots');
                const prevD = document.getElementById('prevD');
                const nextD = document.getElementById('nextD');

                if (!wrap || !inner || !list.length) {
                    return;
                }

                // Clear existing slides
                while (inner.firstChild) inner.removeChild(inner.firstChild);
                
                // Add new slides
                list.forEach((item, idx) => inner.appendChild(makeSlideNode(item, idx === 0)));

                // Show carousel and initialize
                wrap.style.display = 'block';
                buildDots(list.length, dots);
                startCarousel(inner, dots, prevD, nextD, 4800);
            })
            .catch(() => {
                updateInsights([]);
            });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeLandingPage);
    } else {
        initializeLandingPage();
    }
})();
  (function() {
      const body = document.body;

      function revealContent() {
        body.classList.add('reveal-content');
      }

      function hideContent() {
        body.classList.remove('reveal-content');
        window.scrollTo({
          top: 0,
          left: 0,
          behavior: 'smooth'
        });
      }

      function revealAndScrollTo(hash) {
        const id = (hash || '').replace('#', '');
        if (!id) return;

        revealContent();

        requestAnimationFrame(() => {
          const el = document.getElementById(id);
          if (el) {
            el.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      }

      // NAV links: reveal sections only when clicked (CAPTURE PHASE)
      document.addEventListener('click', function(e) {
        const a = e.target.closest('.nav-links a[href^="#"]');
        if (!a) return;

        const href = a.getAttribute('href') || '';
        if (!href || href === '#') return;

        e.preventDefault();
        revealAndScrollTo(href);

        // update URL hash without jumping
        history.pushState(null, '', href);
      }, true);

      // Brand/logo returns to hero-only mode
      document.addEventListener('click', function(e) {
        const brand = e.target.closest('.brand');
        if (!brand) return;

        e.preventDefault();
        history.pushState(null, '', '#home');
        hideContent();
      }, true);

      // If user loads page with #section in URL, reveal immediately
      window.addEventListener('load', function() {
        if (location.hash && location.hash !== '#home') {
          revealAndScrollTo(location.hash);
        } else {
          body.classList.remove('reveal-content');
          window.scrollTo(0, 0);
        }
      });

      // Back/forward navigation support
      window.addEventListener('popstate', function() {
        if (location.hash && location.hash !== '#home') {
          revealAndScrollTo(location.hash);
        } else {
          hideContent();
        }
      });
    })();
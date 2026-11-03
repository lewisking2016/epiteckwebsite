/* EPiTEK Solutions — progressive enhancement only. The site works without JS. */
(function () {
  'use strict';

  var $ = function (sel, ctx) { return (ctx || document).querySelector(sel); };
  var $$ = function (sel, ctx) { return Array.prototype.slice.call((ctx || document).querySelectorAll(sel)); };

  /* ------------------------------------------------------------ mobile drawer */
  var drawer = $('#Drawer');
  var scrim = $('[data-scrim]');
  var openBtn = $('[data-menu-open]');
  var closeBtn = $('[data-menu-close]');
  var lastFocus = null;

  function openMenu() {
    if (!drawer) return;
    lastFocus = document.activeElement;
    drawer.hidden = false;
    if (scrim) scrim.hidden = false;
    document.body.classList.add('is-locked');
    if (openBtn) openBtn.setAttribute('aria-expanded', 'true');
    var first = drawer.querySelector('a, button');
    if (first) first.focus();
    document.addEventListener('keydown', onMenuKey);
  }

  function closeMenu() {
    if (!drawer || drawer.hidden) return;
    drawer.hidden = true;
    if (scrim) scrim.hidden = true;
    document.body.classList.remove('is-locked');
    if (openBtn) openBtn.setAttribute('aria-expanded', 'false');
    document.removeEventListener('keydown', onMenuKey);
    if (lastFocus && lastFocus.focus) lastFocus.focus();
  }

  function onMenuKey(e) {
    if (e.key === 'Escape') { closeMenu(); return; }
    if (e.key !== 'Tab' || !drawer) return;
    var f = $$('a[href], button:not([disabled]), input, [tabindex]:not([tabindex="-1"])', drawer)
      .filter(function (el) { return el.offsetParent !== null; });
    if (!f.length) return;
    var first = f[0], last = f[f.length - 1];
    if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
    else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
  }

  if (openBtn) openBtn.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (scrim) scrim.addEventListener('click', closeMenu);
  $$('#Drawer a').forEach(function (a) { a.addEventListener('click', closeMenu); });
  window.addEventListener('resize', function () { if (window.innerWidth > 980) closeMenu(); });

  /* ------------------------------------------------------------ nav dropdown */
  var navBtns = $$('.Nav_Btn');
  navBtns.forEach(function (btn) {
    var panel = document.getElementById(btn.getAttribute('aria-controls'));
    if (!panel) return;

    function setOpen(open) {
      btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      panel.hidden = !open;
    }

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      setOpen(btn.getAttribute('aria-expanded') !== 'true');
    });

    btn.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowDown') {
        e.preventDefault();
        setOpen(true);
        var first = panel.querySelector('a');
        if (first) first.focus();
      }
    });

    panel.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { setOpen(false); btn.focus(); }
    });

    document.addEventListener('click', function (e) {
      if (!panel.contains(e.target) && e.target !== btn) setOpen(false);
    });
    document.addEventListener('focusin', function (e) {
      if (!panel.contains(e.target) && e.target !== btn) setOpen(false);
    });
  });

  /* ------------------------------------------------------------ domain filter */
  var controls = $('[data-domain-controls]');
  if (controls) {
    var chips = $$('[data-domain]', controls);
    var items = $$('[data-domain-item]');
    var count = $('[data-domain-count]');
    var empty = $('[data-filter-empty]');
    var labels = { all: 'All domains', air: 'Autonomous systems', aviation: 'Aviation software', cyber: 'C4ISR & cyber', support: 'Sustainment' };

    function apply(domain) {
      var shown = 0;
      chips.forEach(function (c) {
        var on = c.getAttribute('data-domain') === domain;
        c.classList.toggle('is-on', on);
        c.setAttribute('aria-pressed', on ? 'true' : 'false');
      });
      items.forEach(function (item) {
        var list = (item.getAttribute('data-domains') || '').split(/\s+/).filter(Boolean);
        var match = domain === 'all' || list.indexOf(domain) !== -1;
        item.hidden = !match;
        if (match) shown++;
      });
      if (count) count.textContent = shown + (shown === 1 ? ' capability' : ' capabilities');
      if (empty) empty.hidden = shown !== 0;
      // Keep card URLs meaningful for filtered views without reloading.
      var active = chips.filter(function (c) { return c.getAttribute('data-domain') === domain; })[0];
      if (active) active.setAttribute('aria-label', 'Show ' + (labels[domain] || domain));
    }

    chips.forEach(function (chip) {
      chip.addEventListener('click', function () {
        apply(chip.getAttribute('data-domain'));
      });
    });

    apply('all', false);

    // Deep-link support: /#domain-air scrolls to the card and selects its domain.
    var hash = window.location.hash.replace('#domain-', '');
    if (hash && chips.some(function (c) { return c.getAttribute('data-domain') === hash; })) {
      apply(hash);
    }
  }

  /* ------------------------------------------------------------ reveal on scroll */
  var reveals = $$('.Reveal');
  if (reveals.length && 'IntersectionObserver' in window) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-in');
          io.unobserve(entry.target);
        }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ------------------------------------------------------------ form guards */
  var forms = $$('form[data-validate]');
  forms.forEach(function (form) {
    form.addEventListener('submit', function (e) {
      var hp = form.querySelector('input[name="company_website"]');
      if (hp && hp.value !== '') { e.preventDefault(); return; }
      if (!form.checkValidity()) {
        // Let the browser surface the first invalid field.
        var invalid = form.querySelector(':invalid');
        if (invalid && invalid.focus) invalid.focus();
        e.preventDefault();
        return;
      }
      var submit = form.querySelector('button[type="submit"]');
      if (submit) {
        submit.disabled = true;
        submit.setAttribute('data-label', submit.innerHTML);
        submit.textContent = 'Sending request…';
      }
    });

    $$('input, select, textarea', form).forEach(function (field) {
      field.addEventListener('input', function () {
        var wrap = field.closest('.Field');
        if (wrap) wrap.classList.remove('is-invalid');
      });
    });
  });

  /* ------------------------------------------------------------ 3D Hero Globe */
  var heroGlobe = $('#hero-globe');
  if (heroGlobe) {
    import('./globe.js')
      .then(function (mod) {
        if (mod && mod.initGlobe) {
          mod.initGlobe(heroGlobe);
        }
      })
      .catch(function (err) {
        console.warn('Hero globe initialization error:', err);
      });
  }
})();

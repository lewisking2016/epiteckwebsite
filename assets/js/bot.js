/* EPI — fixed-answer site assistant. No network calls, no free text. */
(function () {
  'use strict';

  var root = document.querySelector('[data-epi]');
  if (!root) return;

  var panel = root.querySelector('#Epi_Panel');
  var openBtn = root.querySelector('[data-epi-open]');
  var closeBtn = root.querySelector('[data-epi-close]');
  var body = root.querySelector('[data-epi-body]');
  var status = root.querySelector('[data-epi-status]');
  var reset = root.querySelector('[data-epi-reset]');
  var chips = Array.prototype.slice.call(root.querySelectorAll('.Epi_Chip'));
  var lastFocus = null;

  function setOpen(open) {
    if (open) {
      lastFocus = document.activeElement;
      panel.hidden = false;
      root.classList.add('is-open');
      openBtn.setAttribute('aria-expanded', 'true');
      var first = panel.querySelector('.Epi_Chip, .Epi_Close');
      if (first) first.focus();
      document.addEventListener('keydown', onKey);
    } else {
      panel.hidden = true;
      root.classList.remove('is-open');
      openBtn.setAttribute('aria-expanded', 'false');
      document.removeEventListener('keydown', onKey);
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }
  }

  function onKey(e) {
    if (e.key === 'Escape') {
      setOpen(false);
      return;
    }
    if (e.key !== 'Tab') return;
    var focusable = Array.prototype.slice.call(
      panel.querySelectorAll('button:not([disabled]), a[href]')
    ).filter(function (el) { return el.offsetParent !== null; });
    if (!focusable.length) return;
    var first = focusable[0];
    var last = focusable[focusable.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  function bubble(text, who) {
    var wrap = document.createElement('div');
    wrap.className = 'Epi_Msg Epi_Msg--' + who;
    var p = document.createElement('p');
    p.textContent = text;
    wrap.appendChild(p);
    return wrap;
  }

  function answer(chip) {
    var question = chip.textContent.trim();
    var text = chip.getAttribute('data-epi-a') || '';
    var href = chip.getAttribute('data-epi-link') || '';
    var label = chip.getAttribute('data-epi-link-label') || '';

    body.appendChild(bubble(question, 'user'));
    var reply = bubble(text, 'bot');

    if (href && label) {
      var a = document.createElement('a');
      a.className = 'Epi_Link';
      a.href = href;
      a.textContent = label;
      reply.appendChild(a);
    }

    body.appendChild(reply);
    if (status) status.textContent = 'EPI replied to: ' + question;
    body.scrollTop = body.scrollHeight;
  }

  chips.forEach(function (chip) {
    chip.addEventListener('click', function () { answer(chip); });
  });

  openBtn.addEventListener('click', function () { setOpen(panel.hidden); });
  closeBtn.addEventListener('click', function () { setOpen(false); });

  if (reset) {
    reset.addEventListener('click', function () {
      Array.prototype.slice.call(body.querySelectorAll('.Epi_Msg')).forEach(function (node, i) {
        if (i > 0) node.remove();
      });
      body.scrollTop = 0;
      if (status) status.textContent = 'Conversation cleared.';
      var first = panel.querySelector('.Epi_Chip');
      if (first) first.focus();
    });
  }

  /* Cookie notice: dismissal is stored locally, never sent anywhere. */
  var note = document.querySelector('[data-cookie]');
  if (note) {
    var KEY = 'epx-cookie-notice';
    var stored = null;
    try { stored = window.localStorage.getItem(KEY); } catch (err) { stored = null; }
    if (!stored) {
      note.hidden = false;
      var dismiss = note.querySelector('[data-cookie-ok]');
      if (dismiss) {
        dismiss.addEventListener('click', function () {
          note.hidden = true;
          try { window.localStorage.setItem(KEY, '1'); } catch (err) { /* private mode */ }
        });
      }
    }
  }
}());

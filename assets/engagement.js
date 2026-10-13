/* Engagement Kit — vanilla ES module.
 * init({ googleClientId }) — finds .eng-zone divs and renders:
 * spark count + toggle, login island, comment form / list.
 * Also renders a header account island (.eng-account) if present.
 * Non-intrusive: logged-out users see a small "login to spark" hint only
 * inside the zone; nothing ever blocks reading.
 */

const api = '/engagement/api';

async function j(action, opts = {}) {
  const url = new URL(api, location.href);
  url.searchParams.set('action', action);
  if (opts.post) url.searchParams.set('post', opts.post);
  const res = await fetch(url, Object.assign({ credentials: 'same-origin' }, opts.fetch));
  return res.json();
}

async function post(action, body, postId) {
  return j(action, {
    post: postId,
    fetch: { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body || {}) },
  });
}

function el(tag, cls, text) {
  const n = document.createElement(tag);
  if (cls) n.className = cls;
  if (text != null) n.textContent = text;
  return n;
}

function esc(s) {
  const d = document.createElement('div');
  d.textContent = s ?? '';
  return d.innerHTML;
}

async function renderZone(zone, cfg, state) {
  zone.textContent = '';
  const postId = zone.dataset.post;

  // --- spark section
  const sparkWrap = el('div', 'eng-spark');
  const btn = el('button', 'eng-spark-btn' + (state.sparked ? ' on' : ''),
    (state.sparked ? '⚡ Sparked' : '⚡ Spark'));
  const count = el('span', 'eng-count', String(state.count));
  sparkWrap.append(btn, count);
  zone.appendChild(sparkWrap);

  btn.addEventListener('click', async () => {
    if (!state.user) { renderLogin(zone, cfg, state); return; }
    const r = await post('spark', {}, postId);
    if (r.error) return;
    state.sparked = r.sparked; state.count = r.count;
    btn.textContent = r.sparked ? '⚡ Sparked' : '⚡ Spark';
    btn.classList.toggle('on', r.sparked);
    count.textContent = String(r.count);
  });

  // --- comments
  const cHead = el('h3', 'eng-h', 'Comments');
  zone.appendChild(cHead);

  const list = el('div', 'eng-comments');
  (state.comments || []).forEach((c) => {
    const item = el('div', 'eng-comment');
    item.innerHTML = '<div class="eng-c-meta">' + esc(c.author) + ' · ' + esc((c.created_at || '').slice(0, 10)) + '</div>'
      + '<div class="eng-c-body">' + esc(c.body) + '</div>';
    list.appendChild(item);
  });
  if (!state.comments || state.comments.length === 0) {
    list.appendChild(el('div', 'eng-empty', 'No comments yet.'));
  }
  zone.appendChild(list);

  if (state.user) {
    const form = el('form', 'eng-form');
    const ta = el('textarea', 'eng-ta');
    ta.placeholder = 'Add a comment (moderated — links and emails are masked)';
    ta.maxLength = 2000;
    const hint = el('div', 'eng-hint');
    const sub = el('button', 'eng-submit', 'Comment');
    sub.type = 'submit';
    form.append(ta, hint, sub);
    zone.appendChild(form);

    form.addEventListener('submit', async (ev) => {
      ev.preventDefault();
      hint.textContent = '';
      const r = await post('comment', { body: ta.value }, postId);
      if (r.error) { hint.textContent = r.error; return; }
      ta.value = '';
      hint.textContent = r.masked
        ? 'Sent for moderation (links/emails masked).'
        : 'Sent for moderation.';
    });
  } else {
    renderLogin(zone, cfg, state);
  }
}

function renderLogin(zone, cfg, state) {
  const existing = zone.querySelector('.eng-login-row');
  if (existing) existing.remove();

  const loginRow = el('div', 'eng-login-row');
  loginRow.appendChild(el('span', 'eng-login-hint', 'Sign in with Google to spark or comment'));
  const gbtn = el('div', 'eng-gbtn');
  loginRow.appendChild(gbtn);
  zone.appendChild(loginRow);
  mountGoogle(zone, cfg, gbtn);
}

function mountGoogle(zone, cfg, gbtn) {
  if (!window.google || !google.accounts || !google.accounts.id) {
    window.addEventListener('google-loaded', () => mountGoogle(zone, cfg, gbtn), { once: true });
    return;
  }
  google.accounts.id.initialize({
    client_id: cfg.googleClientId,
    callback: async (resp) => {
      const r = await post('google-login', { credential: resp.credential });
      if (r.error) { alert('Sign-in failed'); return; }
      const postId = zone.dataset.post;
      const state = await j('state', { post: postId });
      state.user = r.user;
      renderZone(zone, cfg, state);
      renderAccount(cfg);
    },
  });
  google.accounts.id.renderButton(gbtn, { theme: 'outline', size: 'small', text: 'signin' });
  // non-intrusive: NEVER call google.accounts.id.prompt() — no popups.
}

/** Header account island: signed-in name + logout, or nothing. */
async function renderAccount(cfg) {
  const holder = document.querySelector('.eng-account');
  if (!holder) return;

  // reuse any zone's state if present; otherwise ask whoami-less state via first zone
  let user = null;
  const zone = document.querySelector('.eng-zone');
  if (zone && zone.dataset.post) {
    const s = await j('state', { post: zone.dataset.post });
    user = s.user;
  }

  holder.textContent = '';
  if (user) {
    const name = el('span', 'eng-user-name', user.name || 'Signed in');
    const out = el('button', 'eng-logout', 'Sign out');
    out.addEventListener('click', async () => {
      await post('logout', {});
      location.reload();
    });
    holder.append(name, out);
  }
}

export async function init(cfg) {
  // load GIS once
  if (!document.getElementById('eng-gis')) {
    const s = document.createElement('script');
    s.src = 'https://accounts.google.com/gsi/client';
    s.async = true; s.defer = true;
    s.id = 'eng-gis';
    s.onload = () => window.dispatchEvent(new Event('google-loaded'));
    document.head.appendChild(s);
  }

  const zones = document.querySelectorAll('.eng-zone');
  for (const zone of zones) {
    const postId = zone.dataset.post;
    if (!postId) continue;
    const state = await j('state', { post: postId });
    renderZone(zone, cfg, state);
  }

  renderAccount(cfg);
}
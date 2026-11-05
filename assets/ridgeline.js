/* Ridgeline animated background — adapted from the operator's
 * ridgeline-background.html (canvas at z-index:-1, layered-sine
 * ridges, pointer parallax, reduced-motion static frame).
 * Config lives at top; keep the file dependency-free.
 */
(function () {
  'use strict';
  var cfg = {
    lines: 30,
    detail: 7,
    amp: 0.16,
    speed: 0.12,
    horizon: 0.30,
    floor: 0.96,
    parallax: 0.35,
    bg: '#060a12',
    line: [120, 200, 230],
    glow: [90, 140, 255],
    opacity: 1
  };

  var cvs = document.getElementById('ridges');
  if (!cvs || !cvs.getContext) return;
  var ctx = cvs.getContext('2d');
  var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var W, H, dpr, N, step, raf = 0, hGrad;
  var mx = 0, tx = 0;

  function height(x, z, t, m) {
    return Math.sin(x * 1.3 + z * 0.55 + t * 0.9 + m * 1.5) * 0.50
         + Math.sin(x * 2.7 - z * 0.90 - t * 0.6) * 0.30
         + Math.sin(x * 5.3 + z * 1.70 + t * 0.4 - m) * 0.15
         + Math.sin(x * 9.1 - z * 2.30 - t * 0.7) * 0.05;
  }

  function resize() {
    dpr = Math.min(devicePixelRatio || 1, 2);
    W = innerWidth; H = innerHeight;
    cvs.width = W * dpr; cvs.height = H * dpr;
    N = W < 700 ? Math.round(cfg.lines * 0.6) : cfg.lines;
    step = W < 700 ? cfg.detail * 1.5 : cfg.detail;
    var r = cfg.line[0], g = cfg.line[1], b = cfg.line[2];
    hGrad = ctx.createLinearGradient(0, 0, W, 0);
    hGrad.addColorStop(0, 'rgba(' + r + ',' + g + ',' + b + ',0)');
    hGrad.addColorStop(0.25, 'rgba(' + r + ',' + g + ',' + b + ',1)');
    hGrad.addColorStop(0.75, 'rgba(' + r + ',' + g + ',' + b + ',1)');
    hGrad.addColorStop(1, 'rgba(' + r + ',' + g + ',' + b + ',0)');
  }

  function draw(t) {
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    ctx.fillStyle = cfg.bg;
    ctx.fillRect(0, 0, W, H);

    mx += (tx - mx) * 0.04;
    var lean = mx * cfg.parallax;
    var time = t * cfg.speed;
    var gr = cfg.glow[0], gg = cfg.glow[1], gb = cfg.glow[2];

    for (var i = 0; i < N; i++) {
      var p = i / (N - 1);
      var baseY = H * (cfg.horizon + (cfg.floor - cfg.horizon) * Math.pow(p, 1.5));
      var amp = H * cfg.amp * (0.25 + p * 0.85);
      var z = i * 0.9 - time * 6;

      ctx.beginPath();
      ctx.moveTo(-step, H + 2);
      for (var x = -step; x <= W + step; x += step) {
        var xn = x / W - 0.5 - lean * 0.3;
        var env = Math.exp(-xn * xn * 5);
        var y = baseY - height(x / W * 4.2, z, time, lean) * amp * (0.25 + env * 0.95);
        ctx.lineTo(x, y);
      }
      ctx.lineTo(W + step, H + 2);
      ctx.closePath();

      ctx.fillStyle = cfg.bg;
      ctx.fill();

      ctx.globalAlpha = cfg.opacity * (0.10 + 0.65 * Math.pow(p, 1.2));
      ctx.lineWidth = 0.6 + p * 1.1;
      ctx.strokeStyle = hGrad;
      ctx.stroke();
    }

    ctx.globalAlpha = 1;
    var haze = ctx.createLinearGradient(0, H * cfg.horizon - 120, 0, H * cfg.horizon + 40);
    haze.addColorStop(0, 'rgba(' + gr + ',' + gg + ',' + gb + ',0)');
    haze.addColorStop(1, 'rgba(' + gr + ',' + gg + ',' + gb + ',0.05)');
    ctx.globalCompositeOperation = 'lighter';
    ctx.fillStyle = haze;
    ctx.fillRect(0, H * cfg.horizon - 120, W, 160);
    ctx.globalCompositeOperation = 'source-over';
  }

  function loop(ms) { draw(ms / 1000); raf = requestAnimationFrame(loop); }
  function start() { if (!raf && !reduce) raf = requestAnimationFrame(loop); }
  function stop() { cancelAnimationFrame(raf); raf = 0; }

  addEventListener('pointermove', function (e) { tx = (e.clientX / innerWidth - 0.5) * 2; }, { passive: true });
  addEventListener('resize', function () { resize(); if (reduce) draw(0); });
  document.addEventListener('visibilitychange', function () { document.hidden ? stop() : start(); });

  resize();
  reduce ? draw(0) : start();
})();

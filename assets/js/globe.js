/* ==========================================================================
   EPiTEK Solutions — 3D Interactive Defence & Aviation Globe
   East African sovereign capital mesh: luminous electric blue markers,
   pulsing radar beacon waves, and high-altitude telemetry arcs over a
   precision dot-matrix Earth.

   Engineered with Three.js & self-hosted Natural Earth land data.
   Zero external API dependencies, fully CSP-compliant.
   ========================================================================== */

import * as THREE from './vendor/three.module.min.js';

/* --- Regional hubs shown for orientation --------------------------------- */
const CAPITALS = [
  {
    id: 'nairobi',
    name: 'Nairobi',
    country: 'Kenya',
    role: 'Regional coordination',
    lat: -1.286389,
    lng: 36.817223,
    hub: true
  },
  {
    id: 'kampala',
    name: 'Kampala',
    country: 'Uganda',
    role: 'Engineering support',
    lat: 0.347596,
    lng: 32.582520,
    hub: false
  },
  {
    id: 'dodoma',
    name: 'Dodoma',
    country: 'Tanzania',
    role: 'Field operations',
    lat: -6.162959,
    lng: 35.751607,
    hub: false
  },
  {
    id: 'daressalaam',
    name: 'Dar es Salaam',
    country: 'Tanzania',
    role: 'Regional context',
    lat: -6.7924,
    lng: 39.2083,
    hub: false
  },
  {
    id: 'kigali',
    name: 'Kigali',
    country: 'Rwanda',
    role: 'Training delivery',
    lat: -1.944073,
    lng: 30.061885,
    hub: false
  },
  {
    id: 'addis',
    name: 'Addis Ababa',
    country: 'Ethiopia',
    role: 'Programme liaison',
    lat: 9.02497,
    lng: 38.74689,
    hub: false
  },
  {
    id: 'bujumbura',
    name: 'Bujumbura',
    country: 'Burundi',
    role: 'Regional context',
    lat: -3.361,
    lng: 29.360,
    hub: false
  },
  {
    id: 'juba',
    name: 'Juba',
    country: 'South Sudan',
    role: 'Regional context',
    lat: 4.8594,
    lng: 31.5713,
    hub: false
  },
  {
    id: 'djibouti',
    name: 'Djibouti City',
    country: 'Djibouti',
    role: 'Regional context',
    lat: 11.588,
    lng: 43.145,
    hub: false
  },
  {
    id: 'mogadishu',
    name: 'Mogadishu',
    country: 'Somalia',
    role: 'Regional context',
    lat: 2.0469,
    lng: 45.3182,
    hub: false
  },
  {
    id: 'asmara',
    name: 'Asmara',
    country: 'Eritrea',
    role: 'Regional context',
    lat: 15.322,
    lng: 38.925,
    hub: false
  }
];

/* Regional links drawn for orientation, not a network claim */
const LINKS = [
  // Nairobi regional hub spokes
  [0, 1], // Nairobi <-> Kampala
  [0, 2], // Nairobi <-> Dodoma
  [0, 3], // Nairobi <-> Dar es Salaam
  [0, 4], // Nairobi <-> Kigali
  [0, 5], // Nairobi <-> Addis Ababa
  [0, 7], // Nairobi <-> Juba
  [0, 9], // Nairobi <-> Mogadishu

  // Cross-border corridors
  [1, 4], // Kampala <-> Kigali
  [1, 7], // Kampala <-> Juba
  [4, 6], // Kigali <-> Bujumbura
  [2, 3], // Dodoma <-> Dar es Salaam
  [2, 6], // Dodoma <-> Bujumbura
  [5, 8], // Addis Ababa <-> Djibouti
  [5, 7], // Addis Ababa <-> Juba
  [5, 10], // Addis Ababa <-> Asmara
  [8, 9]  // Djibouti <-> Mogadishu
];

/* Sophisticated aerospace blue palette — rich twilight cobalt ocean, vibrant electric blue network (no dark black) */
const C = {
  ocean: 0x142b4d,     // Rich twilight aerospace cobalt (vibrant, deep, never black)
  grid: 0x224673,      // Tactical graticule with distinct presence
  coast: 0x3d70a8,     // Defined aerospace continent coastline
  dot: 0xa4c6ed,       // Luminous titanium-ice continental dots
  markCore: 0x00f0ff,  // Luminous electric cyan beacon
  markHub: 0x38bdf8,   // Nairobi command hub
  markHalo: 0x0284c7,  // Beacon halo
  arcCore: 0x38bdf8,   // Glowing electric blue arc
  arcGlow: 0x0284c7,   // Ambient line glow
  pulse: 0xffffff,     // Travelling telemetry photon
  radarRing: 0x38bdf8  // Expanding tactical radar ring
};

const R = 1.0;               // Globe radius
const DOT_R = 0.0092;        // Land dot radius
const MARK_R = 0.014;        // Capital beacon radius
const HUB_MARK_R = 0.020;    // Nairobi HQ beacon radius
const HOME = { lat: 2.2, lng: 37.2 }; // Initial view centered on East Africa

function toVec(lat, lng, r) {
  const phi = (lat * Math.PI) / 180;
  const lam = (lng * Math.PI) / 180;
  return new THREE.Vector3(
    Math.cos(phi) * Math.sin(lam) * r,
    Math.sin(phi) * r,
    Math.cos(phi) * Math.cos(lam) * r
  );
}

/* Rotation that brings (lat, lng) to face the camera at +z */
function facing(lat, lng) {
  const phi = (lat * Math.PI) / 180;
  const lam = (lng * Math.PI) / 180;
  const alpha = Math.atan2(Math.sin(phi), Math.cos(phi) * Math.cos(lam));
  const zp = Math.sin(phi) * Math.sin(alpha) +
    Math.cos(phi) * Math.cos(lam) * Math.cos(alpha);
  const xp = Math.cos(phi) * Math.sin(lam);
  return { alpha, theta: Math.atan2(-xp, zp) };
}

/* Equirectangular land mask rasterizer (no third-party dependencies) */
function landSampler(features) {
  const W = 1440;
  const H = 720;
  const cv = document.createElement('canvas');
  cv.width = W;
  cv.height = H;
  const ctx = cv.getContext('2d', { willReadFrequently: true });
  if (!ctx) throw new Error('canvas unavailable');
  ctx.fillStyle = '#000';
  ctx.fillRect(0, 0, W, H);
  ctx.fillStyle = '#fff';
  ctx.beginPath();
  for (const f of features) {
    const g = f.geometry;
    if (!g) continue;
    const polys = g.type === 'Polygon' ? [g.coordinates]
      : g.type === 'MultiPolygon' ? g.coordinates : [];
    for (const poly of polys) {
      for (const ring of poly) {
        if (!ring || ring.length < 2) continue;
        for (let i = 0; i < ring.length; i++) {
          const x = ((ring[i][0] + 180) / 360) * W;
          const y = ((90 - ring[i][1]) / 180) * H;
          if (i === 0) ctx.moveTo(x, y);
          else ctx.lineTo(x, y);
        }
        ctx.closePath();
      }
    }
  }
  ctx.fill('evenodd');
  const px = ctx.getImageData(0, 0, W, H).data;
  return (lng, lat) => {
    const y = Math.floor(((90 - lat) / 180) * H);
    if (y < 0 || y >= H) return false;
    const x = ((Math.floor(((lng + 180) / 360) * W) % W) + W) % W;
    return px[(y * W + x) * 4] > 128;
  };
}

/* Circular particle texture with smooth radial alpha falloff */
function dotTexture() {
  const size = 64;
  const cv = document.createElement('canvas');
  cv.width = size;
  cv.height = size;
  const ctx = cv.getContext('2d');
  if (!ctx) return null;
  const g = ctx.createRadialGradient(size / 2, size / 2, 0, size / 2, size / 2, size / 2);
  g.addColorStop(0, 'rgba(255,255,255,1)');
  g.addColorStop(0.45, 'rgba(255,255,255,0.92)');
  g.addColorStop(0.85, 'rgba(255,255,255,0.25)');
  g.addColorStop(1, 'rgba(255,255,255,0)');
  ctx.fillStyle = g;
  ctx.beginPath();
  ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
  ctx.fill();
  const tex = new THREE.CanvasTexture(cv);
  tex.needsUpdate = true;
  return tex;
}

/* Outer atmospheric rim glow texture */
function atmosphereTexture() {
  const size = 256;
  const cv = document.createElement('canvas');
  cv.width = size;
  cv.height = size;
  const ctx = cv.getContext('2d');
  if (!ctx) return null;
  const g = ctx.createRadialGradient(size / 2, size / 2, size * 0.35, size / 2, size / 2, size / 2);
  g.addColorStop(0, 'rgba(0, 240, 255, 0)');
  g.addColorStop(0.65, 'rgba(2, 132, 199, 0.12)');
  g.addColorStop(0.85, 'rgba(56, 189, 248, 0.28)');
  g.addColorStop(0.96, 'rgba(0, 240, 255, 0.45)');
  g.addColorStop(1, 'rgba(0, 240, 255, 0)');
  ctx.fillStyle = g;
  ctx.fillRect(0, 0, size, size);
  const tex = new THREE.CanvasTexture(cv);
  tex.needsUpdate = true;
  return tex;
}

/* Graticule segments (tactical coordinates grid) */
function graticuleSegments(radius) {
  const pts = [];
  const step = 15;
  for (let lat = -75; lat <= 75; lat += step) {
    for (let lng = -180; lng < 180; lng += 4) {
      pts.push(toVec(lat, lng, radius), toVec(lat, lng + 4, radius));
    }
  }
  for (let lng = -180; lng < 180; lng += step) {
    for (let lat = -84; lat < 84; lat += 4) {
      pts.push(toVec(lat, lng, radius), toVec(lat + 4, lng, radius));
    }
  }
  return pts;
}

/* Coastline boundary lines */
function coastSegments(features, radius) {
  const pts = [];
  for (const f of features) {
    const g = f.geometry;
    if (!g) continue;
    const polys = g.type === 'Polygon' ? [g.coordinates]
      : g.type === 'MultiPolygon' ? g.coordinates : [];
    for (const poly of polys) {
      for (const ring of poly) {
        if (!ring || ring.length < 3) continue;
        for (let i = 0; i < ring.length - 1; i++) {
          pts.push(toVec(ring[i][1], ring[i][0], radius), toVec(ring[i + 1][1], ring[i + 1][0], radius));
        }
        pts.push(toVec(ring[ring.length - 1][1], ring[ring.length - 1][0], radius), toVec(ring[0][1], ring[0][0], radius));
      }
    }
  }
  return pts;
}

/* Lifted great-circle arc points linking two geographical positions */
function arcPoints(from, to, radius) {
  const a = toVec(from.lat, from.lng, 1);
  const b = toVec(to.lat, to.lng, 1);
  const angle = a.angleTo(b);
  const segs = Math.max(24, Math.round((angle / Math.PI) * 110));
  const out = [];
  for (let i = 0; i <= segs; i++) {
    const t = i / segs;
    const p = a.clone().lerp(b, t);
    if (p.lengthSq() === 0) p.copy(a);
    p.normalize();
    // Parabolic lift above the surface
    const lift = radius + 0.014 + Math.sin(Math.PI * t) * (0.055 + angle * 0.04);
    out.push(p.multiplyScalar(lift));
  }
  return out;
}

function lineSegments(pts, material) {
  const pos = new Float32Array(pts.length * 3);
  for (let i = 0; i < pts.length; i++) {
    pos[i * 3] = pts[i].x;
    pos[i * 3 + 1] = pts[i].y;
    pos[i * 3 + 2] = pts[i].z;
  }
  const geo = new THREE.BufferGeometry();
  geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
  return new THREE.LineSegments(geo, material);
}

/* ==========================================================================
   initGlobe: Main entrypoint
   ========================================================================== */
export async function initGlobe(host) {
  const frame = host.querySelector('.Globe_Frame');
  const tip = host.querySelector('.Globe_Tip');
  const capButtons = host.querySelectorAll('.Globe_CapBtn');

  if (!frame) throw new Error('globe frame missing');

  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Load self-hosted Natural Earth GeoJSON land data
  const res = await fetch(new URL('../data/land-110m.json', import.meta.url));
  if (!res.ok) throw new Error('land data unavailable');
  const land = await res.json();
  const onLand = landSampler(land.features);

  const w = frame.clientWidth || 580;
  const h = frame.clientHeight || 540;

  const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true, powerPreference: 'high-performance' });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.setSize(w, h, false);
  renderer.outputColorSpace = THREE.SRGBColorSpace;
  const canvas = renderer.domElement;
  canvas.className = 'Globe_Canvas';
  canvas.setAttribute('tabindex', '0');
  canvas.setAttribute('role', 'region');
  canvas.setAttribute('aria-label', 'Illustrative regional map. Drag to rotate, or use the hub buttons below.');
  frame.appendChild(canvas);

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(34, w / h, 0.1, 20);
  camera.position.set(0, 0, 2.82);

  const globe = new THREE.Group();
  scene.add(globe);

  /* --- Ocean Sphere (Deep Stealth Black/Carbon) -------------------------- */
  const oceanMesh = new THREE.Mesh(
    new THREE.SphereGeometry(R, 64, 48),
    new THREE.MeshBasicMaterial({ color: C.ocean })
  );
  globe.add(oceanMesh);

  /* --- Tactical Graticule & Coastlines ---------------------------------- */
  globe.add(lineSegments(
    graticuleSegments(R * 1.001),
    new THREE.LineBasicMaterial({ color: C.grid, transparent: true, opacity: 0.28 })
  ));
  globe.add(lineSegments(
    coastSegments(land.features, R * 1.003),
    new THREE.LineBasicMaterial({ color: C.coast, transparent: true, opacity: 0.48 })
  ));

  /* --- Continental Dot Matrix (Sampled from GeoJSON) --------------------- */
  const dotPos = [];
  const step = 1.35;
  for (let lat = -89; lat <= 89; lat += step) {
    const cos = Math.cos((lat * Math.PI) / 180);
    const lngStep = cos > 0.02 ? step / Math.max(0.34, cos) : 360;
    for (let lng = -180; lng < 180; lng += lngStep) {
      if (onLand(lng, lat)) {
        const p = toVec(lat, lng, R * 1.005);
        dotPos.push(p.x, p.y, p.z);
      }
    }
  }
  const dotGeo = new THREE.BufferGeometry();
  dotGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(dotPos), 3));
  const tex = dotTexture();
  globe.add(new THREE.Points(dotGeo, new THREE.PointsMaterial({
    color: C.dot,
    size: DOT_R * 2.3,
    map: tex,
    transparent: true,
    opacity: 0.94,
    depthWrite: false,
    sizeAttenuation: true
  })));

  /* --- Atmospheric Glow Rim (Billboard Plane in Scene) ------------------- */
  const atmoTex = atmosphereTexture();
  if (atmoTex) {
    const atmoMat = new THREE.SpriteMaterial({
      map: atmoTex,
      color: 0x00f0ff,
      transparent: true,
      opacity: 0.75,
      blending: THREE.AdditiveBlending
    });
    const atmoSprite = new THREE.Sprite(atmoMat);
    atmoSprite.scale.set(2.45, 2.45, 1);
    scene.add(atmoSprite);
  }

  /* --- Blue Joining Lines (Luminous 3D Catmull-Rom Arcs) ----------------- */
  const arcs = [];
  const arcPos = [];
  const arcGlowPos = [];
  for (const [i, j] of LINKS) {
    const pts = arcPoints(CAPITALS[i], CAPITALS[j], R);
    arcs.push(pts);
    for (let k = 0; k < pts.length - 1; k++) {
      arcPos.push(pts[k], pts[k + 1]);
      arcGlowPos.push(pts[k], pts[k + 1]);
    }
  }

  // Dual-pass rendering for luminous bloom appearance
  globe.add(lineSegments(
    arcGlowPos,
    new THREE.LineBasicMaterial({ color: C.arcGlow, transparent: true, opacity: 0.45, depthWrite: false })
  ));
  globe.add(lineSegments(
    arcPos,
    new THREE.LineBasicMaterial({ color: C.arcCore, transparent: true, opacity: 0.92, depthWrite: false })
  ));

  /* --- Animated Travelling Signal Pulses (Telemetry Photons) ------------ */
  const pulseGeo = new THREE.SphereGeometry(0.009, 8, 8);
  const pulseMat = new THREE.MeshBasicMaterial({ color: C.pulse });
  const pulses = arcs.map((pts, i) => {
    const m = new THREE.Mesh(pulseGeo, pulseMat);
    m.userData = { pts, phase: i / arcs.length, speed: 0.85 + (i % 3) * 0.15 };
    globe.add(m);
    return m;
  });

  /* --- East African Capital Markers (Blue Beacons & Radar Waves) --------- */
  const markers = [];
  const radarRings = [];

  const coreGeo = new THREE.SphereGeometry(MARK_R, 16, 16);
  const hubGeo = new THREE.SphereGeometry(HUB_MARK_R, 20, 20);
  const coreMat = new THREE.MeshBasicMaterial({ color: C.markCore });
  const hubMat = new THREE.MeshBasicMaterial({ color: C.markHub });
  const haloGeo = new THREE.SphereGeometry(0.052, 12, 12);
  const haloMat = new THREE.MeshBasicMaterial({
    color: C.markHalo,
    transparent: true,
    opacity: 0.22,
    depthWrite: false
  });

  CAPITALS.forEach((cap, idx) => {
    const at = toVec(cap.lat, cap.lng, R * 1.008);

    // Click/Hover target (invisible enlarged collider)
    const halo = new THREE.Mesh(haloGeo, haloMat.clone());
    halo.position.copy(at);
    halo.userData = { ...cap, index: idx };
    globe.add(halo);
    markers.push(halo);

    // Glowing core beacon
    const core = new THREE.Mesh(cap.hub ? hubGeo : coreGeo, cap.hub ? hubMat : coreMat);
    core.position.copy(at);
    globe.add(core);

    // Concentric expanding radar rings (2 rings per city, offset in phase)
    const ringRadius = cap.hub ? 0.035 : 0.024;
    for (let r = 0; r < 2; r++) {
      const ringGeo = new THREE.RingGeometry(ringRadius * 0.7, ringRadius, 28);
      const ringMat = new THREE.MeshBasicMaterial({
        color: C.radarRing,
        transparent: true,
        opacity: 0.8,
        side: THREE.DoubleSide,
        depthWrite: false
      });
      const ringMesh = new THREE.Mesh(ringGeo, ringMat);
      ringMesh.position.copy(at);
      ringMesh.lookAt(at.clone().multiplyScalar(2));
      ringMesh.userData = {
        baseRadius: ringRadius,
        phase: (idx * 0.15 + r * 0.5) % 1.0,
        isHub: cap.hub
      };
      globe.add(ringMesh);
      radarRings.push(ringMesh);
    }
  });

  /* --- Dynamic Orientation, Auto-Spin & Inertia Drag --------------------- */
  const base = facing(HOME.lat, HOME.lng);
  const spinAxis = new THREE.Vector3(0, 1, 0);
  const qBase = new THREE.Quaternion();
  const qSpin = new THREE.Quaternion();

  let spin = 0;
  let tilt = base.alpha;
  let spinVel = 0;
  let tiltVel = 0;
  let dragging = false;
  let lastX = 0;
  let lastY = 0;
  let spinRate = reduced ? 0 : 0.048; // Controlled rotation rate

  // Target transition for clicking capital buttons
  let targetTilt = tilt;
  let targetSpin = spin;
  let isNavigating = false;

  const applyOrientation = () => {
    qBase.setFromAxisAngle(new THREE.Vector3(1, 0, 0), tilt);
    qBase.premultiply(new THREE.Quaternion().setFromAxisAngle(spinAxis, base.theta));
    qSpin.setFromAxisAngle(spinAxis, spin);
    globe.quaternion.copy(qBase).multiply(qSpin);

  };
  applyOrientation();

  /* --- Hover & Focus Tooltip HUD ---------------------------------------- */
  const ray = new THREE.Raycaster();
  const ptr = new THREE.Vector2();
  let hoveredMarker = null;

  function showTooltip(capData, clientX, clientY) {
    if (!tip) return;
    const cityEl = tip.querySelector('.Globe_Tip_City');
    const countryEl = tip.querySelector('.Globe_Tip_Country');
    const roleEl = tip.querySelector('.Globe_Tip_Role');

    if (cityEl) cityEl.textContent = capData.name;
    if (countryEl) countryEl.textContent = capData.country;
    if (roleEl) roleEl.textContent = capData.role;

    const r = frame.getBoundingClientRect();
    const posX = clientX ? clientX - r.left : r.width * 0.5;
    const posY = clientY ? clientY - r.top : r.height * 0.4;
    tip.style.left = `${posX}px`;
    tip.style.top = `${posY}px`;
    tip.hidden = false;
    host.classList.add('is-tip');
  }

  function hideTooltip() {
    if (tip) tip.hidden = true;
    host.classList.remove('is-tip');
    hoveredMarker = null;
  }

  const onMove = (e) => {
    const r = canvas.getBoundingClientRect();
    ptr.x = ((e.clientX - r.left) / r.width) * 2 - 1;
    ptr.y = -((e.clientY - r.top) / r.height) * 2 + 1;
    ray.setFromCamera(ptr, camera);
    const hit = ray.intersectObjects(markers, false)[0];

    if (hit) {
      canvas.style.cursor = 'pointer';
      hoveredMarker = hit.object;
      showTooltip(hit.object.userData, e.clientX, e.clientY);
    } else {
      canvas.style.cursor = dragging ? 'grabbing' : 'grab';
      if (!isNavigating) hideTooltip();
    }
  };

  const onLeave = () => {
    if (!isNavigating) hideTooltip();
  };

  /* --- Inertia Drag Pointer Events --------------------------------------- */
  const onDown = (e) => {
    dragging = true;
    isNavigating = false;
    spinVel = 0;
    tiltVel = 0;
    lastX = e.clientX;
    lastY = e.clientY;
    canvas.setPointerCapture && canvas.setPointerCapture(e.pointerId);
    host.classList.add('is-drag');
    canvas.style.cursor = 'grabbing';
  };

  const onDrag = (e) => {
    if (!dragging) return;
    const dx = e.clientX - lastX;
    const dy = e.clientY - lastY;
    lastX = e.clientX;
    lastY = e.clientY;
    spin += dx * 0.005;
    tilt = Math.max(-1.15, Math.min(1.15, tilt - dy * 0.004));
    spinVel = dx * 0.0018;
    tiltVel = -dy * 0.0015;
    applyOrientation();
    render();
  };

  const onUp = (e) => {
    if (!dragging) return;
    dragging = false;
    host.classList.remove('is-drag');
    canvas.style.cursor = 'grab';
    canvas.releasePointerCapture && canvas.releasePointerCapture(e.pointerId);
  };

  canvas.addEventListener('pointerdown', onDown);
  canvas.addEventListener('pointermove', onMove);
  canvas.addEventListener('pointerup', onUp);
  canvas.addEventListener('pointercancel', onUp);
  canvas.addEventListener('pointerleave', onLeave);

  /* --- Capital Focus Navigator ------------------------------------------ */
  function focusCapital(capId) {
    const cap = CAPITALS.find(c => c.id === capId);
    if (!cap) return;

    // Highlight active button
    capButtons.forEach(btn => {
      btn.classList.toggle('is-active', btn.getAttribute('data-cap') === capId);
    });

    const target = facing(cap.lat, cap.lng);
    targetTilt = target.alpha;
    // Compute shortest angular distance for spin
    let deltaTheta = (target.theta - base.theta) - spin;
    while (deltaTheta > Math.PI) deltaTheta -= Math.PI * 2;
    while (deltaTheta < -Math.PI) deltaTheta += Math.PI * 2;
    targetSpin = spin + deltaTheta;

    isNavigating = true;
    spinVel = 0;
    tiltVel = 0;

    // Display tooltip for selected capital
    showTooltip(cap);
  }

  capButtons.forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.preventDefault();
      const capId = btn.getAttribute('data-cap');
      if (capId) focusCapital(capId);
    });
  });

  // Clicking directly on canvas capital marker
  canvas.addEventListener('click', (e) => {
    if (hoveredMarker) {
      focusCapital(hoveredMarker.userData.id);
    }
  });

  /* --- Animation Loop ---------------------------------------------------- */
  let raf = 0;
  let last = 0;
  let visible = true;

  function sampleArc(pts, t) {
    const f = t * (pts.length - 1);
    const i = Math.min(pts.length - 2, Math.floor(f));
    return pts[i].clone().lerp(pts[i + 1], f - i);
  }

  function render() {
    renderer.render(scene, camera);
  }

  function frameLoop(now) {
    raf = requestAnimationFrame(frameLoop);
    const dt = Math.min(0.05, (now - (last || now)) / 1000);
    last = now;

    // Navigation interpolation towards focused capital
    if (isNavigating) {
      const dTilt = targetTilt - tilt;
      const dSpin = targetSpin - spin;
      tilt += dTilt * 0.08;
      spin += dSpin * 0.08;
      if (Math.abs(dTilt) < 0.002 && Math.abs(dSpin) < 0.002) {
        tilt = targetTilt;
        spin = targetSpin;
        isNavigating = false;
      }
      applyOrientation();
    } else if (!dragging) {
      // Natural auto-rotation and inertia decay
      spin += spinRate * dt + spinVel;
      spinVel *= 0.94;
      tilt = Math.max(-1.15, Math.min(1.15, tilt + tiltVel));
      tiltVel *= 0.90;
      applyOrientation();
    }

    if (!reduced) {
      // Animate travelling telemetry pulses
      const t = (now / 3800) % 1;
      for (const p of pulses) {
        const u = (t * p.userData.speed + p.userData.phase) % 1;
        p.position.copy(sampleArc(p.userData.pts, u));
        const s = 0.8 + 0.6 * Math.sin(Math.PI * u);
        p.scale.setScalar(s);
      }

      // Animate tactical radar beacon rings pulsing outward
      for (const ring of radarRings) {
        ring.userData.phase = (ring.userData.phase + dt * 0.65) % 1.0;
        const p = ring.userData.phase;
        const scale = 1.0 + p * (ring.userData.isHub ? 2.6 : 2.2);
        ring.scale.set(scale, scale, 1);
        ring.material.opacity = Math.max(0, (1.0 - p) * 0.85);
      }
    }

    render();
  }

  function start() {
    if (!raf) { last = 0; raf = requestAnimationFrame(frameLoop); }
  }
  function stop() {
    if (raf) { cancelAnimationFrame(raf); raf = 0; }
  }

  if (reduced) {
    for (const p of pulses) p.position.copy(sampleArc(p.userData.pts, 0.5));
    render();
  } else {
    start();
  }

  /* Visibility and Intersection Observer */
  if ('IntersectionObserver' in window) {
    new IntersectionObserver((entries) => {
      visible = entries[0].isIntersecting;
      if (reduced) return;
      if (visible) start(); else stop();
    }, { rootMargin: '80px' }).observe(host);
  }
  document.addEventListener('visibilitychange', () => {
    if (reduced) return;
    if (document.hidden) stop();
    else if (visible) start();
  });

  /* Window & Container Resize */
  const ro = new ResizeObserver(() => {
    const nw = frame.clientWidth;
    const nh = frame.clientHeight;
    if (!nw || !nh) return;
    camera.aspect = nw / nh;
    camera.updateProjectionMatrix();
    renderer.setSize(nw, nh, false);
    render();
  });
  ro.observe(frame);

  render();
  host.classList.add('is-live');

  return {
    destroy() {
      stop();
      ro.disconnect();
      canvas.removeEventListener('pointerdown', onDown);
      canvas.removeEventListener('pointermove', onMove);
      canvas.removeEventListener('pointerup', onUp);
      canvas.removeEventListener('pointercancel', onUp);
      canvas.removeEventListener('pointerleave', onLeave);
      renderer.dispose();
      canvas.remove();
    },
    focusCapital
  };
}
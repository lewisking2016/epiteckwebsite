/* EPiTEK anatomical isometric figures — built with the Anatomy kit.
   Usage: node tools/build_figures.mjs [name ...] */
import { writeFileSync } from "node:fs";
import { dirname, join } from "node:path";
import { fileURLToPath } from "node:url";
import * as k from "./anatomy/kit/iso-kit.mjs";

const HERE = dirname(fileURLToPath(import.meta.url));
const OUT = join(HERE, "..", "assets", "img");

const THEME = `
.iso-svg{
  --anatomy-page:#0a1c31;--anatomy-paper:#10273e;--anatomy-face:#1c405e;--anatomy-top:#2b567a;
  --anatomy-shade-0:#0b1e31;--anatomy-shade-1:#0f2438;--anatomy-shade-2:#132a42;--anatomy-shade-3:#193650;
  --anatomy-lit-top:#3a719c;--anatomy-lit-shade:#24506f;
  --anatomy-hi:#eaf6ff;--anatomy-mid:#87a9c1;--anatomy-lo:#4e708a;--anatomy-faint:#2c4b64;
  --anatomy-lit:#8fdcff;--anatomy-halo:#04101c;--anatomy-halo-opacity:.9;--anatomy-dot:#d9edfa;
  --anatomy-green:#8fe3c0;--anatomy-red:#ff9a8a;--anatomy-blue:#8fdcff;
  --anatomy-weight:.85px;
}
.iso-svg .led{fill:var(--anatomy-green);opacity:.9}
.iso-svg .led--warm{fill:#ffc46b}
.iso-svg .led--red{fill:#ff9a8a}
.iso-svg .screen-glass{fill:#071a2b;stroke:var(--anatomy-lo);stroke-width:.7}
.iso-svg .screen-grid{stroke:var(--anatomy-faint);stroke-width:.5;fill:none}
.iso-svg .screen-route{stroke:var(--anatomy-lit);stroke-width:1.1;fill:none;stroke-linecap:round;stroke-linejoin:round}
.iso-svg .screen-soft{stroke:var(--anatomy-mid);stroke-width:.6;fill:none}
.iso-svg .lens-glass{fill:#04121f;stroke:var(--anatomy-lit);stroke-width:.8}
.iso-svg .trace{stroke:var(--anatomy-lo);stroke-width:.55;fill:none}
.iso-svg .base>.iso-fill{fill:#0a1d30}
.iso-svg .base>.iso-top{fill:#0f253a}
.iso-svg .base>.iso-shade{fill:#0b2033}
.iso-svg .base>.iso-edge{stroke:#3f6684}
.iso-svg .subject>.iso-top{fill:var(--anatomy-lit-top)}
.iso-svg .subject>.iso-shade{fill:var(--anatomy-lit-shade)}
.iso-svg .iso-dots[data-tone="lit"] circle{fill:var(--anatomy-lit);opacity:.9}
@media (prefers-reduced-motion:reduce){.iso-svg .led,.iso-svg .iso-dots circle[data-pulse]{animation:none}}
`;

const wrote = [];
function write(name, w, h, label, body) {
  const svg = k
    .figureSvg({ width: w, height: h, label, body, standalone: true })
    .replace("<svg ", `<svg width="${w}" height="${h}" `)
    .replace("</svg>", `<style>${THEME}</style></svg>`);
  writeFileSync(join(OUT, `${name}.svg`), svg);
  wrote.push(`${name}.svg  ${(Buffer.byteLength(svg) / 1024).toFixed(1)} KB`);
}

/* ---------------------------------------------------------------- helpers */
function context(width, height, base, extra = [], opts = {}) {
  const P = k.fitProjection(
    [...k.boxCorners(base, -16, 0), ...extra],
    width,
    height,
    { pad: opts.pad ?? 34, azimuth: opts.azimuth ?? 45, elevation: opts.elevation ?? 32 },
  );
  return { P, at: (p) => k.iso(p, P), S: (paths, style) => k.solidSvg(paths, style), L: (d, style) => k.lineSvg(d, style), D: (pts, style) => k.dotsSvg(pts, style) };
}

const barPlan = (a, b, w) => {
  const dx = b[0] - a[0];
  const dy = b[1] - a[1];
  const len = Math.hypot(dx, dy) || 1;
  const nx = (-dy / len) * (w / 2);
  const ny = (dx / len) * (w / 2);
  return [[a[0] + nx, a[1] + ny], [b[0] + nx, b[1] + ny], [b[0] - nx, b[1] - ny], [a[0] - nx, a[1] - ny]];
};

const quad = (pts, z, h, P, options = {}) =>
  k.extrude(pts.map(([x, y]) => [x, y]), z, h, P, { convex: true, bevel: options.bevel ?? 0 });

const bladeRing = (cx, cy, angle, r0, r1, half, z, P) => {
  const c = Math.cos(angle), s = Math.sin(angle);
  return quad([[cx + c * r0 - s * half, cy + s * r0 + c * half], [cx + c * r1 - s * half, cy + s * r1 + c * half], [cx + c * r1 + s * half, cy + s * r1 - c * half], [cx + c * r0 + s * half, cy + s * r0 - c * half]], z, 1.4, P, { bevel: 0.5 });
};

function plate(ctx, plan, { thickness = 14, ticks = true, label = true } = {}) {
  const { P, at, S, L, D } = ctx;
  const out = [`<path class="iso-halo" d="${k.haloOf(plan, -thickness - 4, 1, P)}"/>`];
  const feet = k.corners(plan, 30).sort((a, b) => k.depthOf([a[0], a[1], 0], P) - k.depthOf([b[0], b[1], 0], P));
  for (const [x, y] of feet) {
    out.push(S(k.cylinder(x, y, 9, -thickness - 4, 2, P, 12), { tone: "lo" }));
    out.push(S(k.cylinder(x, y, 7, -thickness - 2, 4, P, 12), { tone: "mid" }));
  }
  out.push(S(k.slabOf(plan, -thickness, thickness, P, 6, 2), { tone: "mid", className: "base" }));
  out.push(L(k.planOutline(k.insetPlan(plan, 9), 0, P), { tone: "faint" }));
  const screws = k.corners(plan, 18);
  out.push(L(screws.map(([x, y]) => k.ring(x, y, 2.6, 0, P, 9)).join(""), { tone: "lo" }));
  out.push(D(screws.map(([x, y]) => at([x, y, 0])), { size: 0.55, tone: "mid" }));

  if (ticks) {
    const strip = { x: plan.x + 42, y: plan.y + plan.d - 15, w: plan.w - 84, d: 7, r: 1 };
    out.push(S(k.slabOf(strip, 0, 0.7, P, 3), { tone: "lo", crease: "none" }));
    const t = k.topTicks(strip.x + 4, strip.x + strip.w - 4, 6, 5, strip.y + 0.8, 0.7, [2.4, 4.6], P);
    out.push(L(t.minor, { tone: "lo" }), L(t.major, { tone: "mid" }));
    out.push(D([at([strip.x + 2, strip.y + 3.5, 0.7]), at([strip.x + strip.w - 2, strip.y + 3.5, 0.7])], { size: 0.45 }));
  }
  if (label) {
    const lp = { x: plan.x + 20, y: plan.y + 22, w: 132, d: 44, r: 3 };
    out.push(S(k.slabOf(lp, 0, 1.4, P, 3), { tone: "lo", crease: "lo" }));
    out.push(L(k.planOutline(k.insetPlan(lp, 3), 1.4, P), { tone: "faint" }));
    const rule = (row, w) => L(k.lineOnTop([lp.x + 10, lp.y + lp.d - row], [lp.x + 10 + w, lp.y + lp.d - row], 1.4, P), { tone: "lo", free: true });
    out.push(rule(11, 92), rule(18, 64), rule(25, 43));
    out.push(D([at([lp.x + 7, lp.y + 7, 1.4]), at([lp.x + lp.w - 7, lp.y + 7, 1.4])], { size: 0.5 }));
  }
  return out.join("");
}

function screw(ctx, x, y, z) {
  return [ctx.L(k.ring(x, y, 1.8, z, ctx.P, 12), { tone: "lo" }), ctx.D([ctx.at([x, y, z])], { size: 0.42 })].join("");
}

function vent(ctx, plan, z, rows = 4, cols = 7) {
  const { P, at, L, S } = ctx;
  const out = [S(k.slabOf(plan, z, 0.6, P, 3), { tone: "lo", crease: "none" })];
  const gapX = plan.w / (cols + 1);
  const gapY = plan.d / (rows + 1);
  const bars = [];
  for (let row = 1; row <= rows; row++) {
    for (let col = 1; col <= cols; col++) {
      const x = plan.x + col * gapX;
      const y = plan.y + row * gapY;
      bars.push(k.lineOnTop([x - 2.2, y], [x + 2.2, y], z + 0.6, P));
    }
  }
  out.push(L(bars.join(""), { tone: "lo" }));
  return out.join("");
}

function bolts(ctx, ring, z, count, size = 0.5) {
  const pts = [];
  for (let i = 0; i < count; i++) {
    const a = (i / count) * Math.PI * 2;
    pts.push(ctx.at([ring[0] + Math.cos(a) * ring[2], ring[1] + Math.sin(a) * ring[2], z]));
  }
  return ctx.D(pts, { size, tone: "mid" });
}

/* ------------------------------------------------------------------- UAS */
function rotor(ctx, cx, cy, z, angle, radius = 70) {
  const { P, S, L, D, at } = ctx;
  const out = [];
  const hub = [cx, cy];
  out.push(S(k.cylinder(cx, cy, 7.4, z, 3, P, 14, 0.6), { tone: "mid" }));
  out.push(L(k.knurl(cx, cy, 7.4, z, z + 3, 24, P), { tone: "lo" }));
  out.push(S(k.cylinder(cx, cy, 3.4, z + 3, 4, P, 9), { tone: "hi" }));
  for (const side of [0, Math.PI]) {
    const a = angle + side;
    out.push(S(bladeRing(cx, cy, a, 6, radius, 4.6, z + 1.2, P), { tone: "hi", crease: "lo" }));
    const tip = [cx + Math.cos(a) * (radius - 5), cy + Math.sin(a) * (radius - 5)];
    const base = [cx + Math.cos(a) * 8, cy + Math.sin(a) * 8];
    out.push(L(k.lineOnTop(base, tip, z + 2.6, P), { tone: "faint" }));
  }
  out.push(D([at([cx, cy, z + 3])], { size: 0.5, tone: "lit" }));
  return out.join("");
}

function motor(ctx, cx, cy, z) {
  const { P, S, L, D, at } = ctx;
  return [
    S(k.slabOf({ x: cx - 16, y: cy - 16, w: 32, d: 32, r: 5 }, z, 4, P, 4, 0.8), { tone: "mid" }),
    S(k.cylinder(cx, cy, 11, z + 4, 19, P, 20, 0.8), { tone: "mid" }),
    L(k.knurl(cx, cy, 11, z + 6, z + 19, 40, P), { tone: "lo" }),
    L(k.sideArc(cx, cy, 11, z + 19, P, 17), { tone: "faint" }),
    S(k.cylinder(cx, cy, 7, z + 23, 3.6, P, 16), { tone: "lo" }),
    L(k.ring(cx, cy, 6.2, z + 26.6, P, 15), { tone: "lo" }),
    D([at([cx - 11.5, cy + 6, z + 12]), at([cx + 11.5, cy - 6, z + 12])], { size: 0.5 }),
    bolts(ctx, [cx, cy, 13], z + 22.6, 6, 0.45),
  ].join("");
}

function landingGear(ctx, x, y) {
  const { P, S, L } = ctx;
  return [
    S(k.cylinder(x, y, 3.4, 6, 18, P, 8), { tone: "mid" }),
    S(k.cylinder(x, y, 6, 5, 5, P, 10, 1), { tone: "mid" }),
    L(k.sideArc(x, y, 6, 9.5, P, 10), { tone: "lo" }),
    S(k.cylinder(x, y, 3.8, 24, 4, P, 8), { tone: "mid" }),
  ].join("");
}

function uasScene(width, height) {
  const BASE = { x: -280, y: -200, w: 560, d: 400, r: 22 };
  const ctx = context(width, height, BASE, [[0, 0, 74], [220, 150, 40]], { pad: 26, azimuth: 45, elevation: 34 });
  const { P, S, L, D, at } = ctx;

  const ARM = [];
  const armPts = [[-58, 0, -165, 0], [58, 0, 165, 0], [0, -66, 0, -165], [0, 66, 0, 165]];
  const armEnds = [[-165, 0], [165, 0], [0, -165], [0, 165]];

  for (let i = 0; i < 4; i++) {
    const [ax, ay, bx, by] = armPts[i];
    const [mx, my] = armEnds[i];
    ARM.push({ mx, my, a: [ax, ay], b: [mx, my], near: k.depthOf([mx, my, 0], P) });
  }
  ARM.sort((a, b) => a.near - b.near);

  const chassis = { x: -62, y: -104, w: 124, d: 196, r: 20 };

  const parts = [plate(ctx, BASE)];
  parts.push(`<path class="iso-halo" d="${k.haloOf({ x: -175, y: -155, w: 350, d: 300, r: 60 }, 0, 1, P)}" style="filter:blur(12px)"/>`);
  // skid landing gear
  for (const x of [-52, 44]) parts.push(S(k.slabOf({ x, y: -102, w: 9, d: 204, r: 4 }, 4, 5, P, 4, 0.8), { tone: "mid" }));
  for (const x of [-47.5, 48.5]) {
    for (const y of [-64, 64]) {
      parts.push(S(k.cylinder(x, y, 3, 9, 12, P, 7), { tone: "mid" }));
      parts.push(S(k.cylinder(x, y, 4.6, 8, 2, P, 7), { tone: "lo" }));
    }
  }

  // arms far to near, with motor + rotor
  ARM.forEach((arm, index) => {
    parts.push(S(quad(barPlan(arm.a, arm.b, 17), 27, 8, P, { bevel: 0.7 }), { tone: "mid" }));
    parts.push(S(k.slabOf({ x: arm.a[0] - 15, y: arm.a[1] - 17, w: 30, d: 34, r: 5 }, 22, 11, P, 4, 0.6), { tone: "mid" }));
    parts.push(
      L(
        k.lineOnTop(
          [arm.a[0] + 4, arm.a[1]],
          [arm.b[0] - 12, arm.b[1]],
          35,
          P,
        ),
        { tone: "lo" },
      ),
    );
    parts.push(screw(ctx, arm.a[0] - 10, arm.a[1] + 10, 33));
    parts.push(screw(ctx, arm.a[0] + 10, arm.a[1] - 10, 33));
    parts.push(motor(ctx, arm.mx, arm.my, 35));
    parts.push(rotor(ctx, arm.mx, arm.my, 61, [0.55, 1.4, 2.55, 3.75][index]));
  });

  // chassis tray
  parts.push(S(k.slabOf(chassis, 18, 9, P, 5, 1.2), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(chassis, 6), 27, P), { tone: "faint" }));
  // battery pack (front)
  const BAT = { x: -46, y: 18, w: 92, d: 68, r: 7 };
  parts.push(S(k.slabOf(BAT, 27, 26, P, 4, 1.4), { tone: "hi", crease: "lo", className: "subject" }));
  parts.push(L(k.planOutline(k.insetPlan(BAT, 5), 53, P), { tone: "faint" }));
  for (const row of [9, 18, 27, 36, 45, 54]) {
    parts.push(L(k.lineOnTop([BAT.x + 8, BAT.y + row], [BAT.x + BAT.w - 8, BAT.y + row], 53, P), { tone: "faint" }));
  }
  parts.push(S(k.slabOf({ x: -16, y: 30, w: 32, d: 12, r: 3 }, 53, 4, P, 3, 0.5), { tone: "mid" }));
  parts.push(L(k.lineOnTop([-10, 36], [10, 36], 57, P), { tone: "lo" }));
  parts.push(L(k.lineOnTop([-16, 60], [16, 60], 57, P), { tone: "lo" }));
  parts.push(L(k.lineOnTop([-16, 72], [16, 72], 57, P), { tone: "lo" }));
  parts.push(S(k.slabOf({ x: -34, y: 62, w: 68, d: 16, r: 3 }, 53, 3, P, 3, 0.5), { tone: "mid" }));
  parts.push(D([at([-28, 70, 56]), at([-16, 70, 56])], { size: 0.6, tone: "lit" }));
  parts.push(D([at([28, 70, 56]), at([16, 70, 56])], { size: 0.55, tone: "mid" }));
  parts.push(D([at([-42, 24, 53]), at([42, 24, 53])], { size: 0.5 }));
  parts.push(S(k.cylinder(38, 24, 4, 53, 2, P, 9), { tone: "lo" }));
  parts.push(S(k.cylinder(-38, 24, 4, 53, 2, P, 9), { tone: "lo" }));

  // avionics stack (rear)
  const AV = { x: -50, y: -92, w: 100, d: 76, r: 6 };
  [0, 9, 18].forEach((dz, index) => {
    const z = 27 + dz;
    const plan = { x: AV.x + (index % 2) * 4, y: AV.y + (index % 2) * 4, w: AV.w - (index % 2) * 8, d: AV.d - (index % 2) * 8, r: 4 };
    parts.push(S(k.slabOf(plan, z, 6, P, 4, 0.8), { tone: index === 2 ? "hi" : "mid" }));
    parts.push(D(k.dotGrid(plan.x + 10, plan.y + 10, 4, 2, 9, z + 6, P), { size: 0.5, tone: "mid" }));
    parts.push(L(k.lineOnTop([plan.x + 6, plan.y + plan.d - 8], [plan.x + plan.w - 30, plan.y + plan.d - 8], z + 6, P), { tone: "faint" }));
    parts.push(S(k.slabOf({ x: plan.x + plan.w - 26, y: plan.y + 6, w: 20, d: 10, r: 2 }, z + 6, 3, P, 3), { tone: "lo" }));
    parts.push(screw(ctx, plan.x + 8, plan.y + plan.d - 6, z + 6));
    parts.push(screw(ctx, plan.x + plan.w - 8, plan.y + 6, z + 6));
  });
  parts.push(L(k.lineOnTop([AV.x + 8, AV.y + AV.d + 6], [AV.x + AV.w - 8, AV.y + AV.d + 6], 47, P), { tone: "faint" }));

  // GPS patch + antennas
  parts.push(S(k.cylinder(-30, -96, 12, 47, 2.4, P, 16), { tone: "mid" }));
  parts.push(L(k.ring(-30, -96, 9, 49.4, P, 14), { tone: "lo" }));
  parts.push(L(k.lineOnTop([-38, -96], [-22, -96], 49.4, P), { tone: "faint" }));
  parts.push(L(k.lineOnTop([-30, -104], [-30, -88], 49.4, P), { tone: "faint" }));
  parts.push(S(k.slabOf({ x: 24, y: -100, w: 7, d: 26, r: 2 }, 27, 22, P, 3, 0.6), { tone: "mid" }));
  parts.push(S(k.slabOf({ x: -40, y: -104, w: 6, d: 20, r: 2 }, 27, 14, P, 3, 0.5), { tone: "mid" }));
  parts.push(S(k.cylinder(-38, -72, 1.6, 47, 34, P, 6), { tone: "lo" }));
  parts.push(D([at([-38, -72, 81])], { size: 0.7, tone: "lit" }));

  // wiring harness: battery to avionics, avionics to arm
  const wire = (a, b, via, tone = "mid") => k.wireSvg(at(a), at(b), { via: via.map((p) => at(p)), tone, ends: "both", size: 1.3 });
  parts.push(wire([-30, 16, 34], [-32, -16, 34], [[-26, 6, 39], [-26, -6, 39]]));
  parts.push(wire([30, 16, 34], [24, -8, 41], [[38, 10, 44], [40, -2, 44]]));
  parts.push(wire([44, -60, 33], [158, -132, 39], [[74, -76, 42], [116, -104, 42]], "faint"));

  // underside EO/IR turret at the nose
  const GX = 0, GY = 98, GZ = 18;
  parts.push(S(k.slabOf({ x: GX - 12, y: 80, w: 24, d: 22, r: 4 }, 9, 12, P, 3, 0.7), { tone: "mid" }));
  parts.push(S(k.cylinder(GX, GY, 15, GZ - 2, 3, P, 20, 0.8), { tone: "mid" }));
  parts.push(S(k.slabOf({ x: GX - 19, y: GY - 8, w: 38, d: 22, r: 5 }, GZ - 14, 12, P, 4, 0.8), { tone: "mid" }));
  parts.push(S(k.cylinder(GX, GY, 13, GZ - 30, 16, P, 22, 1.2), { tone: "hi", crease: "lo", lit: true }));
  parts.push(L(k.sideArc(GX, GY, 13, GZ - 14, P, 18), { tone: "lo" }));
  parts.push(L(k.knurl(GX, GY, 13, GZ - 28, GZ - 19, 44, P), { tone: "faint" }));
  // lens face on the front plane
  const lensM = k.sideMatrix([GX, GY + 13, GZ - 23], GZ - 23, P, "left");
  parts.push(`<g transform="${lensM}"><circle class="lens-glass" r="7.6"/><circle class="screen-grid" r="5.2"/><circle class="screen-grid" r="2.2"/></g>`);
  parts.push(S(k.cylinder(GX + 16, GY - 6, 4, GZ - 28, 9, P, 10), { tone: "lo" }));
  parts.push(D([at([GX - 15, GY, GZ - 21])], { size: 0.55, tone: "lit" }));

  // removed access panel resting on the plate
  const PANEL = { x: 152, y: -186, w: 116, d: 74, r: 7 };
  parts.push(S(k.slabOf(PANEL, 0, 3.4, P, 4, 0.9), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(PANEL, 7), 3.4, P), { tone: "faint" }));
  parts.push(S(k.slabOf(k.insetPlan(PANEL, 14), 3.4, 10, P, 5, 1.6), { tone: "hi", crease: "lo" }));
  parts.push(L(k.planOutline(k.insetPlan(PANEL, 22), 13.4, P), { tone: "faint" }));
  parts.push(D([at([PANEL.x + PANEL.w / 2, PANEL.y + PANEL.d / 2, 13.4])], { size: 1.2, tone: "mid" }));
  parts.push(vent(ctx, { x: PANEL.x + 16, y: PANEL.y + 18, w: 62, d: 42, r: 3 }, 3.4, 3, 5));
  parts.push(screw(ctx, PANEL.x + 10, PANEL.y + 10, 3.4));
  parts.push(screw(ctx, PANEL.x + PANEL.w - 10, PANEL.y + 10, 3.4));
  parts.push(screw(ctx, PANEL.x + 10, PANEL.y + PANEL.d - 10, 3.4));
  parts.push(screw(ctx, PANEL.x + PANEL.w - 10, PANEL.y + PANEL.d - 10, 3.4));
  parts.push(L(k.lineOnTop([PANEL.x + 12, PANEL.y + PANEL.d - 16], [PANEL.x + 92, PANEL.y + PANEL.d - 16], 3.4, P), { tone: "lo", free: true }));

  // datalink relay module on the plate
  const MOD = { x: 168, y: 108, w: 100, d: 68, r: 9 };
  parts.push(S(k.slabOf(MOD, 0, 15, P, 4, 1.2), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(MOD, 5), 15, P), { tone: "faint" }));
  parts.push(S(k.cylinder(MOD.x + 28, MOD.y + 32, 12, 15, 8, P, 20, 0.8), { tone: "hi", className: "subject" }));
  parts.push(L(k.ring(MOD.x + 28, MOD.y + 32, 9, 23, P, 16), { tone: "lo" }));
  parts.push(S(k.cylinder(MOD.x + 70, MOD.y + 22, 2, 15, 26, P, 7), { tone: "lo" }));
  parts.push(S(k.cylinder(MOD.x + 70, MOD.y + 48, 2, 15, 18, P, 7), { tone: "lo" }));
  parts.push(D([at([MOD.x + 12, MOD.y + 54, 15]), at([MOD.x + 22, MOD.y + 54, 15])], { size: 0.55, tone: "lit" }));
  parts.push(L(k.lineOnTop([MOD.x + 44, MOD.y + 52], [MOD.x + 84, MOD.y + 52], 15, P), { tone: "faint" }));
  for (let i = 0; i < 4; i++) parts.push(L(k.lineOnTop([MOD.x + 44 + i * 10, MOD.y + 52], [MOD.x + 44 + i * 10, MOD.y + 60], 15, P), { tone: "faint" }));

  return { P, body: parts.join(""), label: "Isometric cutaway of a tactical quad-rotor UAS on a service plate. Carbon arms carry four motors and two-blade rotors; the open fuselage shows the battery pack, three avionics boards, GNSS patch, datalink antennas and the underslung EO/IR turret. A removed access panel and a datalink relay module rest on the plate." };
}


/* -------------------------------------------------------------- avionics */
function avionicsScene(width, height) {
  const BASE = { x: -290, y: -210, w: 580, d: 420, r: 22 };
  const ctx = context(width, height, BASE, [[-190, -150, 120], [240, 120, 90]], { pad: 34, azimuth: 45, elevation: 36 });
  const { P, S, L, D, at } = ctx;

  const BODY = { x: -196, y: -150, w: 352, d: 292, r: 18 };
  const GLASS = { x: -168, y: -128, w: 296, d: 176, r: 8 };
  const TOP = 54;

  const parts = [plate(ctx, BASE, { label: false })];
  parts.push(`<path class="iso-halo" d="${k.haloOf({ x: -200, y: -156, w: 364, d: 300, r: 40 }, 0, 1, P)}" style="filter:blur(12px)"/>`);

  // console chassis
  parts.push(S(k.slabOf(BODY, 0, 40, P, 5, 2), { tone: "mid" }));
  parts.push(S(k.slabOf(k.insetPlan(BODY, 10), 40, 10, P, 4, 1.2), { tone: "hi" }));
  parts.push(L(k.planOutline(k.insetPlan(BODY, 4), 40, P), { tone: "faint" }));
  parts.push(L(k.sideSeam(BODY, 20, P, 5), { tone: "faint" }));

  // corner bumpers
  for (const [x, y] of [[BODY.x + 4, BODY.y + 4], [BODY.x + BODY.w - 28, BODY.y + 4], [BODY.x + 4, BODY.y + BODY.d - 28], [BODY.x + BODY.w - 28, BODY.y + BODY.d - 28]]) {
    parts.push(S(k.slabOf({ x, y, w: 24, d: 24, r: 7 }, 24, 18, P, 3, 1), { tone: "lo" }));
  }
  // feet
  for (const [x, y] of k.corners(BODY, 34)) {
    parts.push(S(k.cylinder(x, y, 7, -6, 6, P, 9), { tone: "lo" }));
  }

  // screen bezel and glass
  parts.push(S(k.slabOf({ x: GLASS.x - 12, y: GLASS.y - 12, w: GLASS.w + 24, d: GLASS.d + 24, r: 12 }, TOP, 2.4, P, 4, 0.8), { tone: "mid" }));
  parts.push(`<path class="screen-glass" d="${k.planOutline(GLASS, TOP + 2.4, P, 5)}"/>`);
  parts.push(L(k.planOutline(k.insetPlan(GLASS, 2), TOP + 2.4, P), { tone: "lo" }));

  // screen content, on the glass plane
  const inside = (u, v) => [GLASS.x + GLASS.w * u, GLASS.y + GLASS.d * v];
  const scrLine = (pts, cls) => `<path class="${cls}" d="${k.pointsAt(pts.map(([u, v]) => [...inside(u, v), TOP + 2.5]), P).map(([x, y], i) => `${i ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join("")}"/>`;
  const grid = [];
  for (let i = 1; i < 8; i++) grid.push(scrLine([[i / 8, 0.04], [i / 8, 0.96]], "screen-grid"));
  for (let i = 1; i < 5; i++) grid.push(scrLine([[0.04, i / 5], [0.96, i / 5]], "screen-grid"));
  parts.push(grid.join(""));

  // coastline and corridor
  const coast = [[0.02, 0.72], [0.12, 0.66], [0.18, 0.55], [0.27, 0.5], [0.32, 0.4], [0.45, 0.36], [0.52, 0.28], [0.63, 0.24], [0.7, 0.14], [0.84, 0.1], [0.98, 0.08]];
  parts.push(scrLine(coast, "screen-soft"));
  const route = [[0.16, 0.78], [0.3, 0.66], [0.42, 0.58], [0.55, 0.46], [0.68, 0.38], [0.82, 0.26]];
  parts.push(scrLine(route, "screen-route"));
  route.slice(1, -1).forEach(([u, v]) => {
    const [x, y] = at([...inside(u, v), TOP + 2.5]);
    parts.push(`<circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="2.6" fill="none" stroke="#8fdcff" stroke-width="1"/>`);
    parts.push(`<circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="0.8" fill="#8fdcff"/>`);
  });
  const [tx, ty] = at([...inside(0.82, 0.26), TOP + 2.5]);
  parts.push(`<rect x="${(tx - 7).toFixed(1)}" y="${(ty - 7).toFixed(1)}" width="14" height="14" fill="none" stroke="#8fdcff" stroke-width="1"/>`);
  // scale bar and status chips
  parts.push(scrLine([[0.06, 0.9], [0.2, 0.9]], "screen-soft"));
  parts.push(scrLine([[0.06, 0.88], [0.06, 0.92]], "screen-soft"));
  parts.push(scrLine([[0.2, 0.88], [0.2, 0.92]], "screen-soft"));
  for (let i = 0; i < 3; i++) {
    const [x, y] = at([...inside(0.74 + i * 0.06, 0.9), TOP + 2.5]);
    parts.push(`<circle class="led${i === 2 ? " led--warm" : ""}" cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="1.8"/>`);
  }

  // keyboard well
  const KEY = { x: -120, y: 62, w: 210, d: 74, r: 5 };
  parts.push(S(k.slabOf(KEY, TOP, 1.2, P, 3), { tone: "lo", crease: "none" }));
  const keys = k.dotGrid(KEY.x + 8, KEY.y + 8, 17, 4, 11.6, TOP + 1.3, P);
  parts.push(D(keys, { size: 2.4, tone: "mid" }));
  parts.push(S(k.slabOf({ x: KEY.x + 60, y: KEY.y + 58, w: 80, d: 8, r: 2 }, TOP, 2, P, 3), { tone: "mid" }));

  // trackball
  parts.push(S(k.cylinder(150, 106, 13, TOP, 5, P, 16, 1), { tone: "mid" }));
  parts.push(S(k.cylinder(150, 106, 7.4, TOP + 5, 4, P, 14), { tone: "hi", lit: true }));
  parts.push(L(k.ring(150, 106, 10, TOP + 5, P, 14), { tone: "lo" }));

  // two rotary controls
  for (const [x, y] of [[-170, 96], [-170, 126]]) {
    parts.push(S(k.cylinder(x, y, 11, TOP, 7, P, 14, 1), { tone: "mid" }));
    parts.push(L(k.knurl(x, y, 11, TOP, TOP + 7, 28, P), { tone: "lo" }));
    parts.push(L(k.lineOnTop([x, y], [x + 7, y - 1], TOP + 7, P), { tone: "hi" }));
  }
  // power switch and guarded cover
  parts.push(S(k.slabOf({ x: 150, y: 62, w: 26, d: 20, r: 4 }, TOP, 3, P, 3), { tone: "mid" }));
  parts.push(S(k.cylinder(158, 72, 4.2, TOP + 3, 5, P, 9), { tone: "lo" }));
  parts.push(D([at([168, 72, TOP + 3])], { size: 0.75, tone: "lit" }));

  // side handles
  for (const x of [BODY.x - 16, BODY.x + BODY.w + 2]) {
    parts.push(S(k.slabOf({ x, y: -32, w: 14, d: 64, r: 5 }, 24, 6, P, 3), { tone: "mid" }));
    parts.push(S(k.slabOf({ x: x + 2, y: -42, w: 10, d: 12, r: 3 }, 24, 6, P, 3), { tone: "mid" }));
    parts.push(S(k.slabOf({ x: x + 2, y: 30, w: 10, d: 12, r: 3 }, 24, 6, P, 3), { tone: "mid" }));
  }

  // processing unit at the rear right
  const PU = { x: 190, y: -186, w: 96, d: 96, r: 8 };
  parts.push(S(k.slabOf(PU, 0, 46, P, 4, 1.4), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(PU, 6), 46, P), { tone: "faint" }));
  parts.push(vent(ctx, { x: PU.x + 10, y: PU.y + 10, w: 50, d: 46, r: 3 }, 46, 4, 5));
  parts.push(S(k.cylinder(PU.x + 74, PU.y + 26, 9, 46, 4, P, 14), { tone: "lo" }));
  parts.push(L(k.ring(PU.x + 74, PU.y + 26, 6.4, 50, P, 12), { tone: "faint" }));
  parts.push(D([at([PU.x + 12, PU.y + 82, 46])], { size: 0.8, tone: "lit" }));
  parts.push(screw(ctx, PU.x + 8, PU.y + 86, 46));
  parts.push(screw(ctx, PU.x + 88, PU.y + 8, 46));

  // cable from console to processing unit
  parts.push(
    k.wireSvg(at([150, -20, 44]), at([190, -92, 30]), { via: [at([172, -40, 30]), at([196, -70, 26])], tone: "mid", ends: "both", size: 1.4 }),
  );
  // antenna on the processing unit
  parts.push(S(k.slabOf({ x: PU.x + 30, y: PU.y + 66, w: 16, d: 16, r: 4 }, 46, 5, P, 3), { tone: "mid" }));
  parts.push(S(k.cylinder(PU.x + 38, PU.y + 74, 1.8, 51, 62, P, 6), { tone: "lo" }));
  parts.push(D([at([PU.x + 38, PU.y + 74, 113])], { size: 1, tone: "lit" }));

  return { body: parts.join(""), label: "Isometric rugged mission console on a technical plate. A planning table shows a lit route across a coastline, with waypoints and a target box; a keyboard, trackball, rotary controls and guarded power switch sit on the console top. A processing unit, cooling vents and antenna stand behind, linked by a cable." };
}

/* ----------------------------------------------------------------- c4isr */
function c4isrScene(width, height) {
  const BASE = { x: -290, y: -210, w: 580, d: 420, r: 22 };
  const ctx = context(width, height, BASE, [[-200, -150, 360], [150, 150, 90]], { pad: 30, azimuth: 45, elevation: 32 });
  const { P, S, L, D, at } = ctx;

  const RACK = { x: -206, y: -156, w: 212, d: 158, r: 5 };
  const parts = [plate(ctx, BASE, { label: false })];
  parts.push(`<path class="iso-halo" d="${k.haloOf({ x: -210, y: -160, w: 220, d: 166, r: 20 }, 0, 1, P)}" style="filter:blur(12px)"/>`);

  // rack shell
  parts.push(S(k.slabOf(RACK, 0, 10, P, 3, 0.8), { tone: "lo" }));
  parts.push(S(k.slabOf({ x: RACK.x, y: RACK.y, w: RACK.w, d: 8, r: 2 }, 10, 222, P, 3, 0.6), { tone: "mid" }));
  for (const x of [RACK.x, RACK.x + RACK.w - 9]) {
    parts.push(S(k.slabOf({ x, y: RACK.y, w: 9, d: RACK.d, r: 2 }, 10, 222, P, 3, 0.6), { tone: "mid" }));
    parts.push(L(k.sideSeam({ x, y: RACK.y, w: 9, d: RACK.d, r: 2 }, 60, P, 3), { tone: "faint" }));
  }
  parts.push(S(k.slabOf({ x: RACK.x - 4, y: RACK.y - 4, w: RACK.w + 8, d: RACK.d + 8, r: 4 }, 232, 8, P, 3, 0.8), { tone: "hi" }));
  parts.push(L(k.planOutline(k.insetPlan({ x: RACK.x - 4, y: RACK.y - 4, w: RACK.w + 8, d: RACK.d + 8, r: 4 }, 3), 240, P), { tone: "faint" }));
  parts.push(screw(ctx, RACK.x + 10, RACK.y + RACK.d - 10, 240));
  parts.push(screw(ctx, RACK.x + RACK.w - 10, RACK.y + RACK.d - 10, 240));

  // four rack modules
  const levels = [26, 78, 130, 182];
  levels.forEach((z, index) => {
    const mod = { x: RACK.x + 12, y: RACK.y + 10, w: RACK.w - 24, d: RACK.d - 20, r: 2 };
    parts.push(S(k.slabOf(mod, z, 42, P, 4, 0.8), { tone: index === 0 ? "lo" : "mid" }));
    parts.push(L(k.planOutline(k.insetPlan(mod, 3), z + 42, P), { tone: "faint" }));
    parts.push(S(k.slabOf({ x: mod.x + 2, y: mod.y + mod.d - 3, w: mod.w - 4, d: 3, r: 1 }, z + 4, 34, P, 3), { tone: index === 2 ? "hi" : "lo" }));
    for (const side of [0, 1]) {
      const x = mod.x + (side ? mod.w - 26 : 10);
      parts.push(L(k.segment([x, mod.y + mod.d - 1.2, z + 32], [x + 16, mod.y + mod.d - 1.2, z + 32], P), { tone: "lo" }));
      parts.push(L(k.segment([x, mod.y + mod.d - 1.2, z + 32], [x, mod.y + mod.d - 1.2, z + 26], P), { tone: "lo" }));
      parts.push(L(k.segment([x + 16, mod.y + mod.d - 1.2, z + 32], [x + 16, mod.y + mod.d - 1.2, z + 26], P), { tone: "lo" }));
    }
    for (let i = 0; i < 3; i++) {
      parts.push(D([at([mod.x + mod.w - 42 + i * 8, mod.y + mod.d - 1.4, z + 34])], { size: 0.8, tone: i === 0 ? "lit" : "mid" }));
    }
    parts.push(D(k.dotGrid(mod.x + 10, mod.y + 8, 10, 2, 12, z + 42, P), { size: 0.5, tone: "faint" }));
    parts.push(L(k.lineOnTop([mod.x + 8, mod.y + mod.d - 10], [mod.x + mod.w - 8, mod.y + mod.d - 10], z + 42, P), { tone: "faint" }));
  });

  // flat planning screen docked on top of the rack
  const SCRW = 196, SCRD = 104, SCRZ = 240;
  const SF = { x: -156, y: -118, w: SCRW, d: SCRD, r: 6 };
  parts.push(S(k.slabOf(SF, SCRZ, 6, P, 4, 0.9), { tone: "hi" }));
  parts.push(`<path class="screen-glass" d="${k.planOutline(k.insetPlan(SF, 8), SCRZ + 6, P, 4)}"/>`);
  parts.push(L(k.planOutline(k.insetPlan(SF, 4), SCRZ + 6, P), { tone: "lo" }));
  const G = k.insetPlan(SF, 12);
  const sp = (u, v) => [G.x + G.w * u, G.y + G.d * v, SCRZ + 6.2];
  const sline = (pts, cls) => `<path class="${cls}" d="${k.pointsAt(pts, P).map(([x, y], i) => `${i ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join("")}"/>`;
  const nodes = [[0.14, 0.3], [0.34, 0.2], [0.58, 0.32], [0.78, 0.22], [0.5, 0.68], [0.82, 0.62]];
  const links = [[0, 1], [1, 2], [2, 3], [0, 4], [4, 5], [3, 5], [2, 4]];
  links.forEach(([a, b]) => parts.push(sline([sp(...nodes[a]), sp(...nodes[b])], "screen-soft")));
  nodes.forEach(([u, v], i) => {
    const [x, y] = at(sp(u, v));
    parts.push(`<circle cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="${i === 3 ? 3.4 : 2.6}" fill="${i === 3 ? "#8fdcff" : "none"}" stroke="#8fdcff" stroke-width="1"/>`);
  });
  parts.push(sline([sp(0.06, 0.88), sp(0.3, 0.88)], "screen-route"));
  for (let i = 0; i < 3; i++) {
    const [x, y] = at(sp(0.62 + i * 0.08, 0.88));
    parts.push(`<circle class="led${i === 2 ? " led--warm" : ""}" cx="${x.toFixed(1)}" cy="${y.toFixed(1)}" r="1.8"/>`);
  }
  for (const x of [-130, 0, 40]) {
    parts.push(S(k.cylinder(x, SF.y + SF.d - 2, 4.4, SCRZ - 3, 5, P, 8), { tone: "mid" }));
  }

  // telescopic mast with planar array
  const MX = 120, MY = -118;
  parts.push(S(k.cylinder(MX, MY, 26, 0, 10, P, 18, 1.2), { tone: "mid" }));
  parts.push(S(k.cylinder(MX, MY, 14, 10, 18, P, 14), { tone: "lo" }));
  parts.push(D(k.dotGrid(MX - 18, MY - 8, 4, 2, 12, 10, P), { size: 0.5 }));
  parts.push(S(k.cylinder(MX, MY, 8.4, 28, 96, P, 12), { tone: "mid" }));
  parts.push(S(k.cylinder(MX, MY, 6, 124, 82, P, 12), { tone: "mid" }));
  parts.push(S(k.cylinder(MX, MY, 4, 206, 66, P, 9), { tone: "mid" }));
  parts.push(L(k.sideArc(MX, MY, 8.4, 40, P, 10), { tone: "faint" }));
  parts.push(L(k.sideArc(MX, MY, 6, 140, P, 10), { tone: "faint" }));
  const ARR = { x: MX - 42, y: MY - 6, w: 84, d: 12, r: 2 };
  parts.push(S(k.slabOf(ARR, 272, 34, P, 3, 0.8), { tone: "hi" }));
  parts.push(L(k.planOutline(k.insetPlan(ARR, 3), 306, P), { tone: "lo" }));
  for (let i = 1; i < 6; i++) {
    parts.push(L(k.lineOnTop([ARR.x + 4 + i * 12, ARR.y + 2], [ARR.x + 4 + i * 12, ARR.y + ARR.d - 2], 306, P), { tone: "faint" }));
  }
  parts.push(D([at([MX, MY, 308])], { size: 0.9, tone: "lit" }));

  // whip antennas on the rack
  for (const [x, y, h] of [[-40, -140, 90], [-6, -140, 66]]) {
    parts.push(S(k.cylinder(x, y, 2, 240, h, P, 6), { tone: "lo" }));
    parts.push(D([at([x, y, 240 + h])], { size: 0.9, tone: "lit" }));
  }

  // satcom dome
  const DX = -128, DY = 124;
  parts.push(S(k.cylinder(DX, DY, 38, 0, 8, P, 22, 1.2), { tone: "mid" }));
  parts.push(S(k.cylinder(DX, DY, 35, 8, 9, P, 22, 1.2), { tone: "hi" }));
  parts.push(S(k.cylinder(DX, DY, 27, 17, 10, P, 20, 1.4), { tone: "hi" }));
  parts.push(S(k.cylinder(DX, DY, 18, 27, 10, P, 17, 1.4), { tone: "hi" }));
  parts.push(S(k.cylinder(DX, DY, 10, 37, 8, P, 14, 1.2), { tone: "hi", lit: true }));
  parts.push(L(k.knurl(DX, DY, 38, 2, 8, 44, P), { tone: "faint" }));

  // generator
  const GEN = { x: 160, y: 96, w: 98, d: 66, r: 7 };
  parts.push(S(k.slabOf(GEN, 0, 44, P, 4, 1.2), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(GEN, 5), 44, P), { tone: "faint" }));
  parts.push(vent(ctx, { x: GEN.x + 8, y: GEN.y + 8, w: 48, d: 30, r: 3 }, 44, 3, 4));
  parts.push(S(k.cylinder(GEN.x + 78, GEN.y + 16, 6, 44, 3, P, 10), { tone: "lo" }));
  parts.push(S(k.slabOf({ x: GEN.x + 30, y: GEN.y + GEN.d - 4, w: 36, d: 4, r: 1 }, 32, 3, P, 3), { tone: "lo" }));
  parts.push(D([at([GEN.x + 86, GEN.y + 48, 44])], { size: 0.8, tone: "lit" }));

  // cable spool
  const CX = -246, CY = -30;
  parts.push(S(k.cylinder(CX, CY, 34, 0, 6, P, 20, 1), { tone: "lo" }));
  parts.push(S(k.cylinder(CX, CY, 10, 6, 16, P, 12), { tone: "mid" }));
  parts.push(L(k.ring(CX, CY, 28, 8, P, 18), { tone: "faint" }));
  parts.push(L(k.ring(CX, CY, 22, 14, P, 18), { tone: "faint" }));
  parts.push(L(k.ring(CX, CY, 28, 22, P, 18), { tone: "faint" }));
  parts.push(k.wireSvg(at([CX + 30, CY, 6]), at([RACK.x + 30, RACK.y + RACK.d, 24]), { via: [at([CX + 60, CY + 30, 4]), at([-180, 60, 4]), at([-170, 20, 18])], tone: "mid", ends: "both", size: 1.4 }));

  return { body: parts.join(""), label: "Isometric deployable C4ISR node on a technical plate: an open equipment rack with four powered modules and a network status screen, a telescopic mast carrying a planar array, two whip antennas, a satellite dome, a generator and a cable spool connected to the rack." };
}

/* ------------------------------------------------------------------ naval */
function navalScene(width, height) {
  const BASE = { x: -270, y: -196, w: 540, d: 392, r: 22 };
  const ctx = context(width, height, BASE, [[0, 0, 160], [0, 200, 60]], { pad: 26, azimuth: 42, elevation: 32 });
  const { P, S, L, D, at } = ctx;

  const HULL = [[-66, -176], [66, -176], [92, -60], [88, 30], [62, 110], [26, 164], [0, 178], [-26, 164], [-62, 110], [-88, 30], [-92, -60]];
  const parts = [plate(ctx, BASE, { ticks: false, label: false })];
  parts.push(`<path class="iso-halo" d="${k.haloOf({ x: -100, y: -180, w: 200, d: 360, r: 60 }, 0, 1, P)}" style="filter:blur(12px)"/>`);

  // wake grooves on the plate
  for (let i = 0; i < 5; i++) {
    parts.push(L(k.lineOnTop([-150 - i * 14, 60 + i * 30], [-110 - i * 12, 92 + i * 26], 0.6, P), { tone: "faint" }));
    parts.push(L(k.lineOnTop([150 + i * 14, 60 + i * 30], [112 + i * 12, 94 + i * 26], 0.6, P), { tone: "faint" }));
  }

  // keel blocks and cradle
  for (const [x, y, w] of [[-34, -60, 68], [-34, 40, 68], [-24, 120, 48]]) {
    parts.push(S(k.slabOf({ x, y, w, d: 32, r: 4 }, 0, 14, P, 3, 0.8), { tone: "lo" }));
    parts.push(S(k.slabOf({ x: x + 6, y: y + 4, w: w - 12, d: 24, r: 6 }, 14, 10, P, 3, 1.4), { tone: "mid" }));
  }

  // hull
  parts.push(S(k.extrude(HULL, 24, 46, P, { convex: true, bevel: 2 }), { tone: "mid" }));
  parts.push(L(k.ringSeam(HULL, 56, P), { tone: "faint" }));
  parts.push(L(k.ringSeam(HULL, 36, P), { tone: "lo" }));
  const DECK = HULL.map(([x, y]) => [x * 0.86, -6 + (y + 6) * 0.9]);
  parts.push(S(k.extrude(DECK, 70, 5, P, { convex: true, bevel: 1.2 }), { tone: "hi" }));
  parts.push(L(k.ringSeam(DECK, 75, P), { tone: "lo" }));
  for (const x of [-36, 0, 36]) parts.push(L(k.lineOnTop([x, -150], [x, 96], 75, P), { tone: "faint" }));
  parts.push(S(k.slabOf({ x: -20, y: -46, w: 40, d: 34, r: 3 }, 75, 1.2, P, 3), { tone: "lo", crease: "none" }));
  parts.push(L(k.planOutline({ x: -20, y: -46, w: 40, d: 34, r: 3 }, 76.2, P), { tone: "faint" }));
  // foredeck windlass and chain
  parts.push(S(k.cylinder(0, 148, 8, 75, 5, P, 12, 0.8), { tone: "mid" }));
  parts.push(L(k.lineOnTop([0, 156], [0, 176], 80, P), { tone: "lo" }));
  parts.push(S(k.cylinder(0, 176, 4.2, 75, 4, P, 8), { tone: "lo" }));

  // deckhouse
  const HOUSE = { x: -52, y: -116, w: 104, d: 128, r: 8 };
  parts.push(S(k.slabOf(HOUSE, 74, 46, P, 4, 1.2), { tone: "hi" }));
  parts.push(S(k.slabOf(k.insetPlan(HOUSE, 8), 120, 22, P, 4, 1), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(HOUSE, 4), 120, P), { tone: "faint" }));
  parts.push(L(k.sideSeam(HOUSE, 100, P, 4), { tone: "faint" }));
  const winM = k.sideMatrix([HOUSE.x + 10, HOUSE.y + HOUSE.d, 108], 108, P, "left");
  parts.push(`<g transform="${winM}">${[0, 26, 52, 78].map((x) => `<rect x="${x}" y="6" width="18" height="10" fill="#071a2b" stroke="#4e708a" stroke-width="0.7"/>`).join("")}</g>`);
  const doorM = k.sideMatrix([HOUSE.x + 44, HOUSE.y + HOUSE.d, 82], 82, P, "left");
  parts.push(`<g transform="${doorM}"><rect x="0" y="0" width="18" height="34" fill="#0b2338" stroke="#4e708a" stroke-width="0.7"/><circle cx="14" cy="17" r="1.1" fill="#8fdcff"/></g>`);

  // radar mast and array
  parts.push(S(k.cylinder(0, -20, 5, 142, 62, P, 9), { tone: "mid" }));
  parts.push(S(k.cylinder(0, -20, 9, 168, 8, P, 12), { tone: "mid" }));
  const RAD = { x: -42, y: -26, w: 84, d: 10, r: 2 };
  parts.push(S(k.slabOf(RAD, 194, 22, P, 3, 0.8), { tone: "hi" }));
  for (let i = 1; i < 7; i++) parts.push(L(k.lineOnTop([RAD.x + 3 + i * 11, RAD.y + 2], [RAD.x + 3 + i * 11, RAD.y + RAD.d - 2], 216, P), { tone: "faint" }));
  parts.push(D([at([0, -20, 218])], { size: 0.9, tone: "lit" }));

  // yardarm and antennas
  parts.push(S(k.slabOf({ x: -58, y: -23, w: 116, d: 4, r: 1 }, 176, 3, P, 3), { tone: "mid" }));
  for (const x of [-44, -16, 20, 48]) {
    parts.push(S(k.cylinder(x, -21, 1.2, 179, 18 + ((x % 3) + 3) * 4, P, 5), { tone: "lo" }));
    parts.push(D([at([x, -21, 199])], { size: 0.7, tone: "lit" }));
  }
  // EO turret on the mast
  parts.push(S(k.cylinder(0, -6, 8, 164, 12, P, 13, 1), { tone: "hi", lit: true }));
  parts.push(S(k.cylinder(0, -6, 4.6, 176, 4, P, 10), { tone: "lo" }));
  parts.push(L(k.ring(0, -6, 6.4, 180, P, 10), { tone: "lo" }));

  // bow gun
  const GUN = [0, 130];
  parts.push(S(k.cylinder(GUN[0], GUN[1], 17, 75, 8, P, 16, 1.2), { tone: "mid" }));
  parts.push(S(k.cylinder(GUN[0], GUN[1], 11, 83, 12, P, 13, 1.2), { tone: "hi" }));
  parts.push(S(k.slabOf({ x: GUN[0] - 2.6, y: GUN[1] + 8, w: 5.2, d: 54, r: 2 }, 89, 5, P, 3, 0.6), { tone: "mid" }));
  parts.push(S(k.cylinder(GUN[0], GUN[1] + 60, 2.4, 89, 4, P, 7), { tone: "lo" }));
  parts.push(D([at([GUN[0], GUN[1] + 8, 95])], { size: 0.7, tone: "lit" }));

  // life raft canisters and cleats
  for (const [x, y] of [[-60, 44], [60, 44]]) {
    parts.push(S(k.cylinder(x, y, 7, 77, 26, P, 12), { tone: "mid" }));
    parts.push(L(k.sideArc(x, y, 7, 90, P, 12), { tone: "lo" }));
    parts.push(L(k.knurl(x, y, 7, 83, 97, 18, P), { tone: "faint" }));
  }
  for (const [x, y] of [[-66, -160], [66, -160], [-84, -20], [84, -20], [-54, 108], [54, 108]]) {
    parts.push(S(k.cylinder(x, y, 3.4, 75, 5, P, 7), { tone: "lo" }));
  }
  // railings
  const railPoints = [[-62, 42], [-50, 92], [-28, 130], [0, 146], [28, 130], [50, 92], [62, 42]];
  const posts = railPoints.map(([x, y]) => k.segment([x, y, 75], [x, y, 88], P));
  parts.push(L(posts.join(""), { tone: "lo" }));
  const rail = (z) => k.pointsAt(railPoints.map(([x, y]) => [x, y, z]), P).map(([x, y], i) => `${i ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join("");
  parts.push(L(rail(88), { tone: "lo" }));
  parts.push(L(rail(82), { tone: "lo" }));

  return { body: parts.join(""), label: "Isometric fast patrol boat on a slipway cradle: a pointed hull with waterline band and non-slip deck, a bridge with four windows and a door, a radar mast with planar array, yardarm antennas and EO turret, a bow gun, life-raft canisters, cleats and railings." };
}

/* ------------------------------------------------------------------ depot */
function depotScene(width, height) {
  const BASE = { x: -280, y: -205, w: 560, d: 410, r: 22 };
  const ctx = context(width, height, BASE, [[-200, -130, 230], [180, 120, 190]], { pad: 28, azimuth: 45, elevation: 34 });
  const { P, S, L, D, at } = ctx;

  const BENCH = { x: -200, y: -132, w: 396, d: 264, r: 12 };
  const TOP = 66;
  const parts = [plate(ctx, BASE, { label: false })];
  parts.push(`<path class="iso-halo" d="${k.haloOf({ x: -242, y: -162, w: 444, d: 308, r: 30 }, 0, 1, P)}" style="filter:blur(12px)"/>`);

  // bench legs, braces, under-shelf, top
  for (const [x, y] of k.corners(BENCH, 26)) {
    parts.push(S(k.slabOf({ x: x - 7, y: y - 7, w: 14, d: 14, r: 2 }, 0, TOP - 12, P, 3, 0.6), { tone: "mid" }));
    parts.push(S(k.slabOf({ x: x - 11, y: y - 11, w: 22, d: 22, r: 3 }, 0, 5, P, 3, 0.6), { tone: "lo" }));
  }
  parts.push(S(k.slabOf({ x: BENCH.x + 20, y: BENCH.y + 20, w: BENCH.w - 40, d: BENCH.d - 40, r: 4 }, 22, 4, P, 3), { tone: "lo" }));
  parts.push(S(k.slabOf({ x: BENCH.x, y: BENCH.y, w: BENCH.w, d: BENCH.d, r: 10 }, TOP - 12, 12, P, 4, 1.4), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(BENCH, 7), TOP, P), { tone: "faint" }));
  parts.push(L(k.sideSeam(BENCH, TOP - 6, P, 4), { tone: "lo" }));
  for (const [x, y] of k.corners(BENCH, 16)) parts.push(screw(ctx, x, y, TOP));
  // bench label plate on the front edge
  const LP = { x: -60, y: BENCH.y + BENCH.d - 28, w: 150, d: 20, r: 2 };
  parts.push(S(k.slabOf(LP, TOP, 0.8, P, 3), { tone: "lo", crease: "none" }));
  parts.push(L(k.lineOnTop([LP.x + 12, LP.y + 10], [LP.x + 96, LP.y + 10], TOP + 0.8, P), { tone: "lo", free: true }));
  parts.push(L(k.lineOnTop([LP.x + 12, LP.y + 15], [LP.x + 64, LP.y + 15], TOP + 0.8, P), { tone: "lo", free: true }));

  // tool chest with four drawers
  const CHEST = { x: 74, y: -118, w: 116, d: 88, r: 6 };
  parts.push(S(k.slabOf(CHEST, TOP, 84, P, 4, 1.2), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(CHEST, 5), TOP + 84, P), { tone: "faint" }));
  [0, 18, 36, 54].forEach((dz, i) => {
    parts.push(S(k.slabOf({ x: CHEST.x + 4, y: CHEST.y + CHEST.d - 4, w: CHEST.w - 8, d: 3, r: 1 }, TOP + 6 + dz, 14, P, 3), { tone: "lo" }));
    parts.push(L(k.segment([CHEST.x + 34, CHEST.y + CHEST.d + 0.4, TOP + 19 + dz], [CHEST.x + 98, CHEST.y + CHEST.d + 0.4, TOP + 19 + dz], P), { tone: "lo" }));
    parts.push(D([at([CHEST.x + 22, CHEST.y + CHEST.d + 0.6, TOP + 13 + dz])], { size: 0.6, tone: i === 0 ? "lit" : "mid" }));
  });
  parts.push(S(k.slabOf({ x: CHEST.x + 8, y: CHEST.y + 8, w: 40, d: 26, r: 3 }, TOP + 84, 5, P, 3), { tone: "lo" }));
  parts.push(S(k.slabOf({ x: CHEST.x + 52, y: CHEST.y + 8, w: 30, d: 26, r: 3 }, TOP + 84, 3, P, 3), { tone: "mid" }));

  // test rack with diagnostic display
  const RACK = { x: -186, y: -122, w: 108, d: 92, r: 6 };
  parts.push(S(k.slabOf(RACK, TOP, 130, P, 4, 1.4), { tone: "mid" }));
  parts.push(L(k.planOutline(k.insetPlan(RACK, 6), TOP + 130, P), { tone: "faint" }));
  parts.push(vent(ctx, { x: RACK.x + 10, y: RACK.y + 12, w: 44, d: 50, r: 3 }, TOP + 130, 5, 4));
  const RM = k.sideMatrix([RACK.x + 12, RACK.y + RACK.d, TOP + 106], TOP + 106, P, "left");
  parts.push(`<g transform="${RM}"><rect x="0" y="0" width="92" height="54" fill="#071a2b" stroke="#4e708a" stroke-width="0.8"/></g>`);
  const wave = [];
  for (let i = 0; i <= 40; i++) {
    const x = 6 + i * 2.1;
    const y = 28 + Math.sin(i * 0.55) * 11 * Math.exp(-Math.pow((i - 22) / 18, 2));
    wave.push(`<line x1="${x.toFixed(1)}" y1="${(28 + 0.01).toFixed(1)}" x2="${x.toFixed(1)}" y2="${y.toFixed(1)}" stroke="#8fdcff" stroke-width="0.8"/>`);
  }
  parts.push(`<g transform="${RM}">${wave.join("")}<line x1="4" y1="46" x2="88" y2="46" stroke="#4e708a" stroke-width="0.6"/></g>`);
  parts.push(`<g transform="${RM}"><circle cx="82" cy="46" r="2" class="led"/><circle cx="74" cy="46" r="2" class="led led--warm"/></g>`);
  for (const [x, z] of [[RACK.x + 92, TOP + 106], [RACK.x + 92, TOP + 88]]) {
    parts.push(S(k.cylinder(x, RACK.y + RACK.d - 1, 5, z, 3, P, 10), { tone: "lo" }));
  }
  parts.push(S(k.slabOf({ x: RACK.x + 12, y: RACK.y + RACK.d - 2, w: 70, d: 2.4, r: 1 }, TOP + 6, 3, P, 3), { tone: "lo" }));
  parts.push(D([at([RACK.x + 18, RACK.y + RACK.d + 0.4, TOP + 18])], { size: 0.7, tone: "lit" }));

  // ESD mat under the airframe
  parts.push(S(k.slabOf({ x: -96, y: -104, w: 192, d: 212, r: 8 }, TOP, 1.4, P, 4), { tone: "lo", crease: "none" }));
  parts.push(L(k.planOutline({ x: -96, y: -104, w: 192, d: 212, r: 8 }, TOP + 1.4, P), { tone: "faint" }));
  parts.push(D([at([-88, -96, TOP + 1.4]), at([88, 96, TOP + 1.4])], { size: 0.7, tone: "mid" }));

  // maintenance jig: two V blocks holding the airframe
  for (const y of [-56, 62]) {
    parts.push(S(k.slabOf({ x: -44, y: y - 16, w: 88, d: 32, r: 4 }, TOP, 7, P, 3, 0.7), { tone: "lo" }));
    parts.push(S(k.slabOf({ x: -34, y, w: 22, d: 8, r: 2 }, TOP + 7, 22, P, 3), { tone: "mid" }));
    parts.push(S(k.slabOf({ x: 12, y, w: 22, d: 8, r: 2 }, TOP + 7, 22, P, 3), { tone: "mid" }));
    parts.push(L(k.segment([-34, y + 8, TOP + 29], [12, y + 8, TOP + 29], P), { tone: "lo" }));
  }
  // airframe, upper shell removed
  const FUSE = { x: -34, y: -84, w: 68, d: 176, r: 16 };
  parts.push(S(k.slabOf(FUSE, TOP + 26, 12, P, 4, 1.4), { tone: "mid" }));
  parts.push(`<path d="${k.planOutline(k.insetPlan(FUSE, 4), TOP + 38, P)}" fill="#071a2b" stroke="#4e708a" stroke-width="0.7"/>`);
  // battery and boards inside
  parts.push(S(k.slabOf({ x: -24, y: 4, w: 48, d: 52, r: 5 }, TOP + 38, 14, P, 4, 1), { tone: "hi", className: "subject" }));
  parts.push(L(k.lineOnTop([-20, 18], [20, 18], TOP + 52, P), { tone: "lo" }));
  parts.push(L(k.lineOnTop([-20, 30], [20, 30], TOP + 52, P), { tone: "lo" }));
  parts.push(D([at([-14, 44, TOP + 52]), at([14, 44, TOP + 52])], { size: 0.7, tone: "lit" }));
  parts.push(S(k.slabOf({ x: -26, y: -74, w: 52, d: 64, r: 3 }, TOP + 38, 3, P, 3), { tone: "mid" }));
  parts.push(D(k.dotGrid(-20, -68, 5, 3, 10, TOP + 41, P), { size: 0.6, tone: "faint" }));
  parts.push(S(k.slabOf({ x: -20, y: -64, w: 24, d: 18, r: 2 }, TOP + 41, 4, P, 3), { tone: "lo" }));
  // arm sockets and one attached arm
  parts.push(S(k.slabOf({ x: 30, y: -10, w: 22, d: 34, r: 4 }, TOP + 38, 8, P, 3, 0.6), { tone: "mid" }));
  parts.push(S(quad(barPlan([48, 8], [142, 8], 14), TOP + 40, 7, P, { bevel: 0.6 }), { tone: "mid" }));
  parts.push(S(k.cylinder(150, 8, 10, TOP + 40, 16, P, 14, 0.8), { tone: "mid" }));
  parts.push(L(k.knurl(150, 8, 10, TOP + 42, TOP + 54, 24, P), { tone: "lo" }));
  parts.push(D([at([150, 8, TOP + 58])], { size: 0.8, tone: "lit" }));
  // detached arm, motor and blade on the bench
  parts.push(S(quad(barPlan([86, 12], [168, 36], 14), TOP, 7, P, { bevel: 0.6 }), { tone: "lo" }));
  parts.push(S(k.cylinder(176, 40, 10, TOP, 14, P, 14, 0.8), { tone: "mid" }));
  parts.push(S(k.cylinder(152, 74, 3, TOP, 2.4, P, 10), { tone: "lo" }));
  parts.push(S(quad(barPlan([152, 74], [202, 90], 7), TOP + 2, 2.4, P, { bevel: 0.5 }), { tone: "hi" }));
  parts.push(screw(ctx, 100, 20, TOP));
  // EO/IR ball on the nose of the airframe
  parts.push(S(k.cylinder(0, 92, 12, TOP + 6, 14, P, 17, 1.2), { tone: "hi", lit: true }));
  const lensM = k.sideMatrix([0, 98, TOP + 9], TOP + 9, P, "left");
  parts.push(`<g transform="${lensM}"><circle class="lens-glass" r="6.4"/><circle class="screen-grid" r="4"/></g>`);

  // battery charging station with two packs
  const CH = { x: -184, y: 62, w: 94, d: 54, r: 6 };
  parts.push(S(k.slabOf(CH, TOP, 12, P, 3, 0.8), { tone: "mid" }));
  for (const [x, y] of [[CH.x + 14, CH.y + 8], [CH.x + 58, CH.y + 8]]) {
    parts.push(S(k.slabOf({ x, y, w: 26, d: 42, r: 4 }, TOP + 12, 14, P, 3, 0.8), { tone: "hi" }));
    parts.push(S(k.slabOf({ x: x + 6, y: y + 6, w: 14, d: 4, r: 1 }, TOP + 26, 2, P, 3), { tone: "lo" }));
    parts.push(D([at([x + 20, y + 36, TOP + 26])], { size: 0.65, tone: "lit" }));
  }
  parts.push(L(k.lineOnTop([CH.x + 8, CH.y + CH.d - 8], [CH.x + 30, CH.y + CH.d - 8], TOP + 12, P), { tone: "faint" }));

  // parts tray and fasteners
  const TRAY = { x: 26, y: 76, w: 60, d: 40, r: 4 };
  parts.push(S(k.slabOf(TRAY, TOP, 7, P, 3, 0.7), { tone: "lo" }));
  parts.push(L(k.planOutline(k.insetPlan(TRAY, 6), TOP + 7, P), { tone: "faint" }));
  parts.push(D(k.dotGrid(TRAY.x + 12, TRAY.y + 10, 4, 3, 10, TOP + 7.4, P), { size: 0.9, tone: "mid" }));

  // diagnostic cable from rack to airframe
  parts.push(k.wireSvg(at([-150, -40, TOP + 90]), at([-18, 20, TOP + 40]), { via: [at([-140, 20, TOP + 80]), at([-100, 40, TOP + 40]), at([-50, 30, TOP + 40])], tone: "mid", ends: "both", size: 1.4 }));

  return { body: parts.join(""), label: "Isometric maintenance bench on a technical plate: a stripped tactical UAS airframe in a V-block jig with battery and avionics exposed, its EO/IR ball at the nose, one arm attached and a removed arm, motor and blade laid out. A diagnostic rack shows a live waveform, a tool chest, battery charging station and fastener tray complete the bench." };
}

/* --------------------------------------------------------------- terrain */
function terrainScene(width, height, coverage) {
  const sea = coverage ? { x: -310, y: -236, w: 620, d: 472, r: 26 } : { x: -312, y: -232, w: 624, d: 464, r: 26 };
  const ctx = context(width, height, sea, [[-260, -200, 150], [260, 190, 90]], { pad: coverage ? 34 : 46, azimuth: 44, elevation: 34 });
  const { P, S, L, D, at } = ctx;

  const L1 = [[-200, -200], [250, -200], [292, -80], [286, 40], [210, 150], [90, 200], [-80, 192], [-206, 110], [-232, 20], [-226, -110]];
  const shrink = (poly, cx, cy, k) => poly.map(([x, y]) => [cx + (x - cx) * k, cy + (y - cy) * k]);
  const L2 = shrink(L1, 20, 0, 0.78);
  const L3 = shrink(L1, 40, -6, 0.52);

  const parts = [plate(ctx, sea, { ticks: false, label: true })];
  // sea texture: long swell lines and a compass arc
  for (let i = 0; i < 7; i++) {
    const y0 = -226 + i * 60;
    parts.push(L(k.pointsAt([[-316, y0, 0.4], [-286, y0 + 8, 0.4], [-256, y0 + 2, 0.4]], P).map(([x, y], j) => `${j ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join(""), { tone: "faint" }));
    parts.push(L(k.pointsAt([[286, y0 + 24, 0.4], [306, y0 + 30, 0.4]], P).map(([x, y], j) => `${j ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join(""), { tone: "faint" }));
  }
  parts.push(L(k.ring(0, 0, 236, 0.4, P, 30), { tone: "faint" }));
  parts.push(L(k.ring(0, 0, 250, 0.4, P, 30), { tone: "faint" }));
  for (let i = 0; i < 12; i++) {
    const a = (i / 12) * Math.PI * 2;
    parts.push(L(k.lineOnTop([Math.cos(a) * 228, Math.sin(a) * 228], [Math.cos(a) * 244, Math.sin(a) * 244], 0.4, P), { tone: "faint" }));
  }

  // landmass, three contour terraces
  const terrace = (poly, z0, h, tone) => {
    const out = [S(k.extrude(poly, z0, h, P, { convex: true, bevel: 1.6 }), { tone })];
    out.push(L(k.ringSeam(poly, z0 + h, P), { tone: "lo" }));
    return out.join("");
  };
  parts.push(terrace(L1, 6, 16, "lo"));
  parts.push(terrace(L2, 22, 14, "mid"));
  parts.push(terrace(L3, 36, 12, "hi"));
  parts.push(L(k.ringSeam(L1, 30, P), { tone: "faint" }));
  parts.push(L(k.ringSeam(L2, 40, P), { tone: "faint" }));
  for (const [poly, z, kk] of [[L1, 22, 0.94], [L2, 36, 0.9], [L3, 48, 0.94]]) {
    parts.push(L(k.ringSeam(shrink(poly, 20, 0, kk), z, P), { tone: "faint" }));
  }

  // inland drainage lines
  for (const line of [
    [[-150, -120], [-70, -60], [-30, 10], [-10, 80], [30, 130]],
    [[170, -130], [120, -50], [150, 30], [190, 90]],
    [[-180, 40], [-110, 70], [-60, 120]],
  ]) {
    const pts = line.map(([x, y]) => [x, y, 48.4]);
    parts.push(L(k.pointsAt(pts, P).map(([x, y], i) => `${i ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join(""), { tone: "faint" }));
  }

  // runway on the middle terrace
  const RW = { x: -30, y: -18, w: 210, d: 36, r: 3 };
  parts.push(S(k.slabOf(RW, 48, 2.4, P, 3, 0.6), { tone: "lo", crease: "lo" }));
  for (let i = 0; i < 9; i++) {
    parts.push(L(k.lineOnTop([RW.x + 14 + i * 22, 0], [RW.x + 24 + i * 22, 0], 50.4, P), { tone: "mid" }));
  }
  for (const side of [-1, 1]) {
    for (let i = 0; i < 4; i++) {
      parts.push(L(k.lineOnTop([RW.x + 4, side * 12 + i * 3], [RW.x + 4, side * 12 + i * 3 - side * 4], 50.4, P), { tone: "lo" }));
      parts.push(L(k.lineOnTop([RW.x + RW.w - 4, side * 12 + i * 3], [RW.x + RW.w - 4, side * 12 + i * 3 - side * 4], 50.4, P), { tone: "lo" }));
    }
  }

  // hangar, control tower and radar on the plateau
  const HANGAR = { x: -150, y: -110, w: 92, d: 68, r: 5 };
  parts.push(S(k.slabOf(HANGAR, 48, 22, P, 4, 1), { tone: "mid" }));
  parts.push(S(k.slabOf(k.insetPlan(HANGAR, 5), 70, 10, P, 4, 2.4), { tone: "hi" }));
  parts.push(L(k.planOutline(k.insetPlan(HANGAR, 9), 80, P), { tone: "faint" }));
  const hm = k.sideMatrix([HANGAR.x + 12, HANGAR.y + HANGAR.d, 60], 60, P, "left");
  parts.push(`<g transform="${hm}"><rect x="0" y="0" width="30" height="14" fill="#0b2338" stroke="#4e708a" stroke-width="0.7"/><line x1="4" y1="3" x2="26" y2="3" stroke="#2c4b64" stroke-width="0.6"/><line x1="4" y1="7" x2="26" y2="7" stroke="#2c4b64" stroke-width="0.6"/><line x1="4" y1="11" x2="26" y2="11" stroke="#2c4b64" stroke-width="0.6"/></g>`);
  const TOWER = { x: -170, y: 20, w: 30, d: 30, r: 3 };
  parts.push(S(k.slabOf(TOWER, 48, 40, P, 3, 0.7), { tone: "mid" }));
  parts.push(S(k.slabOf({ x: TOWER.x - 6, y: TOWER.y - 6, w: 42, d: 42, r: 4 }, 88, 12, P, 3, 0.8), { tone: "hi" }));
  parts.push(L(k.planOutline(k.insetPlan({ x: TOWER.x - 6, y: TOWER.y - 6, w: 42, d: 42, r: 4 }, 4), 100, P), { tone: "faint" }));
  parts.push(S(k.cylinder(TOWER.x + 15, TOWER.y + 15, 1.6, 100, 22, P, 6), { tone: "lo" }));
  parts.push(D([at([TOWER.x + 15, TOWER.y + 15, 122])], { size: 0.8, tone: "lit" }));
  const RAD = { x: 96, y: 96, w: 44, d: 10, r: 2 };
  parts.push(S(k.cylinder(118, 108, 10, 48, 14, P, 13, 0.8), { tone: "mid" }));
  parts.push(S(k.slabOf(RAD, 62, 12, P, 3, 0.6), { tone: "hi" }));
  parts.push(D([at([118, 108, 75])], { size: 0.7, tone: "lit" }));

  // beacons: plinth, mast, cap, pulse
  const BEACONS = coverage
    ? [[-140, -120, 48], [40, 120, 48], [180, -110, 48], [200, 60, 48], [-70, -10, 48]]
    : [[-120, -96, 48], [120, 110, 48], [196, -64, 48]];
  BEACONS.forEach(([x, y, z], index) => {
    const h = 62 + (index % 3) * 14;
    parts.push(S(k.cylinder(x, y, 17, z, 4, P, 13, 0.8), { tone: "lo" }));
    parts.push(S(k.cylinder(x, y, 5, z + 4, h, P, 8, 0.6), { tone: "mid" }));
    parts.push(S(k.cylinder(x, y, 9.4, z + h, 7, P, 12, 0.8), { tone: "hi" }));
    parts.push(L(k.ring(x, y, 6.4, z + h + 7, P, 9), { tone: "lo" }));
    parts.push(D([at([x, y, z + h + 9])], { size: 1.3, tone: "lit", pulse: true }));
    parts.push(L(k.ring(x, y, 26 + index * 4, z + 0.5, P, 22), { tone: "faint" }));
    parts.push(L(k.ring(x, y, 30 + index * 4, z + 0.5, P, 22), { tone: "faint" }));
    // site huts
    for (const [dx, dy] of [[26, 10], [34, -14]]) {
      parts.push(S(k.slabOf({ x: x + dx - 11, y: y + dy - 8, w: 22, d: 16, r: 3 }, z, 9, P, 3, 0.6), { tone: "mid" }));
      parts.push(D([at([x + dx, y + dy, z + 9])], { size: 0.55, tone: "lit" }));
    }
  });
  // coverage corridors between beacons
  const routePairs = coverage ? [[0, 1], [1, 3], [3, 2], [2, 0], [0, 4], [4, 2]] : [[0, 1], [1, 2]];
  routePairs.forEach(([a, b], index) => {
    const A = BEACONS[a], B = BEACONS[b];
    const mid = [(A[0] + B[0]) / 2 + (index % 2 ? 24 : -18), (A[1] + B[1]) / 2 + (index % 2 ? -18 : 22), 48.8];
    parts.push(
      k.wireSvg(at([A[0], A[1], 48.8]), at([B[0], B[1], 48.8]), { via: [at(mid)], tone: "lit", ends: "none", size: 1.2 }),
    );
  });

  // two aircraft on the runway
  for (const [ax, ay, ang] of [[44, 0, 0.9], [92, 6, 2.2]]) {
    parts.push(S(k.slabOf({ x: ax - 10, y: ay - 4, w: 20, d: 8, r: 3 }, 50.4, 2.6, P, 3, 0.5), { tone: "hi" }));
    parts.push(S(quad(barPlan([ax, ay], [ax + Math.cos(ang) * 16, ay + Math.sin(ang) * 16], 3.4), 50.4, 1.2, P, { bevel: 0.3 }), { tone: "mid" }));
    parts.push(S(quad(barPlan([ax, ay], [ax + Math.cos(ang + 2.5) * 16, ay + Math.sin(ang + 2.5) * 16], 3.4), 50.4, 1.2, P, { bevel: 0.3 }), { tone: "mid" }));
  }

  // a UAS on the apron
  const UX = 52, UY = -74;
  parts.push(S(k.cylinder(UX, UY, 9, 50, 3, P, 12), { tone: "lo" }));
  parts.push(S(k.slabOf({ x: UX - 16, y: UY - 12, w: 32, d: 24, r: 6 }, 53, 6, P, 3, 0.8), { tone: "hi" }));
  for (const [dx, dy] of [[-24, -18], [24, -18], [-24, 18], [24, 18]]) {
    parts.push(S(quad(barPlan([UX + dx * 0.4, UY + dy * 0.4], [UX + dx, UY + dy], 4), 54, 3, P, { bevel: 0.5 }), { tone: "mid" }));
    parts.push(S(k.cylinder(UX + dx, UY + dy, 5, 54, 4, P, 7), { tone: "mid" }));
    parts.push(S(bladeRing(UX + dx, UY + dy, 0.6, 3, 16, 1.6, 58, P), { tone: "hi" }));
  }

  if (!coverage) {
    // hero: add a second apron and route line so the composition reads at wide crop
    const PX = -60, PY = 140;
    parts.push(S(k.cylinder(PX, PY, 11, 48, 3, P, 12), { tone: "lo" }));
    parts.push(S(k.slabOf({ x: PX - 18, y: PY - 14, w: 36, d: 28, r: 7 }, 51, 6, P, 3, 0.8), { tone: "hi" }));
    parts.push(S(bladeRing(PX, PY, 2.1, 3, 18, 1.8, 57, P), { tone: "mid" }));
    parts.push(L(k.pointsAt([[PX, PY, 49], [10, 60, 49], [UX - 20, UY + 20, 49]], P).map(([x, y], i) => `${i ? "L" : "M"}${x.toFixed(1)} ${y.toFixed(1)}`).join(""), { tone: "faint", dashed: true }));
  }

  return {
    body: parts.join(""),
    label: "Isometric relief model of the East African operating area on an engraved plate: a stepped landmass above a dark sea, a runway with centreline markings, a hangar, control tower and radar, and lit beacon masts joined by dashed coverage corridors.",
  };
}

/* ------------------------------------------------------------------ main */
const only = process.argv.slice(2);
const jobs = {
  "uas-kargu": () => { const s = uasScene(860, 640); write("uas-kargu", 860, 640, s.label, s.body); },
  avionics: () => { const s = avionicsScene(860, 640); write("avionics", 860, 640, s.label, s.body); },
  c4isr: () => { const s = c4isrScene(860, 640); write("c4isr", 860, 640, s.label, s.body); },
  naval: () => { const s = navalScene(860, 640); write("naval", 860, 640, s.label, s.body); },
  depot: () => { const s = depotScene(860, 640); write("depot", 860, 640, s.label, s.body); },
  "hero-terrain": () => { const s = terrainScene(1600, 1100, false); write("hero-terrain", 1600, 1100, s.label, s.body); },
  "coverage-map": () => { const s = terrainScene(900, 760, true); write("coverage-map", 900, 760, s.label, s.body); },
};

for (const [name, job] of Object.entries(jobs)) {
  if (only.length && !only.includes(name)) continue;
  job();
}
console.log(wrote.join("\n") || "nothing built");

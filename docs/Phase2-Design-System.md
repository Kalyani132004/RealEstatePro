# RealEstatePro — Phase 2: UI Design System

This is the single source of visual truth for Phases 3–8. Every Blade page will only ever use the tokens, classes, and components defined here — no page-specific one-off colors or fonts.

---

## 1. Brand Personality

Modern, trustworthy, premium PropTech — inspired by high-end real estate marketplaces. Airy layouts, soft glass surfaces floating over subtle gradient backgrounds, confident typography, generous white space, micro-interactions that feel expensive but never gimmicky.

---

## 2. Color Palette

### Light Theme
| Token | Hex | Usage |
|---|---|---|
| `--rep-primary` | `#1E3A5F` | Deep navy — headings, primary buttons |
| `--rep-primary-light` | `#2C5282` | Hover states |
| `--rep-accent` | `#D4A853` | Gold — CTAs, badges, price highlights |
| `--rep-accent-light` | `#E8C77E` | Accent hover |
| `--rep-secondary` | `#0EA5A0` | Teal — links, secondary actions, icons |
| `--rep-bg` | `#F4F6F9` | Page background |
| `--rep-surface` | `#FFFFFF` | Card/base surface |
| `--rep-glass` | `rgba(255,255,255,0.65)` | Glassmorphism surfaces |
| `--rep-text` | `#1A202C` | Primary text |
| `--rep-text-muted` | `#64748B` | Secondary text |
| `--rep-border` | `rgba(15,23,42,0.08)` | Card borders |
| `--rep-success` | `#22A65A` | Available / success toast |
| `--rep-warning` | `#F0A93B` | Pending toast |
| `--rep-danger` | `#E5484D` | Errors / sold-out badge |

### Dark Theme
| Token | Hex | Usage |
|---|---|---|
| `--rep-primary` | `#6FA8DC` | Headings / buttons on dark |
| `--rep-accent` | `#E8C77E` | CTAs |
| `--rep-secondary` | `#2DD4CF` | Links / icons |
| `--rep-bg` | `#0F1720` | Page background |
| `--rep-surface` | `#182233` | Card base |
| `--rep-glass` | `rgba(24,34,51,0.55)` | Glass surfaces |
| `--rep-text` | `#E7ECF3` | Primary text |
| `--rep-text-muted` | `#94A3B8` | Secondary text |
| `--rep-border` | `rgba(255,255,255,0.08)` | Card borders |

Theme is toggled by adding `data-theme="dark"` on `<html>`, persisted in `localStorage` via `theme-toggle.js` (Phase 3+). All tokens are CSS custom properties, so no component ever hardcodes a color.

---

## 3. Typography

| Role | Font | Notes |
|---|---|---|
| Headings | **"Poppins"**, sans-serif | 600/700 weight, tight letter-spacing (-0.02em) |
| Body | **"Inter"**, sans-serif | 400/500 weight, 1.6 line-height |
| Numeric / Price | **"Poppins"** 600 | tabular-nums for prices |

Both loaded via Google Fonts `<link>` in `layouts/app.blade.php` (Phase 3).

### Type Scale (rem, 1rem = 16px)
| Class | Size | Line-height | Usage |
|---|---|---|---|
| `.rep-h1` | 3rem (48px) | 1.15 | Hero headline |
| `.rep-h2` | 2.25rem (36px) | 1.2 | Section titles |
| `.rep-h3` | 1.5rem (24px) | 1.3 | Card titles |
| `.rep-h4` | 1.25rem (20px) | 1.35 | Sub-headings |
| `.rep-body` | 1rem (16px) | 1.6 | Paragraphs |
| `.rep-small` | 0.875rem (14px) | 1.5 | Meta text / captions |

Mobile-first: h1 scales to `2rem` and h2 to `1.5rem` below 576px via media query (defined in `theme.css`).

---

## 4. Spacing, Radius & Shadow Scale

| Token | Value |
|---|---|
| `--rep-radius-sm` | 8px |
| `--rep-radius-md` | 16px |
| `--rep-radius-lg` | 24px |
| `--rep-radius-pill` | 999px |
| `--rep-shadow-sm` | `0 2px 8px rgba(15,23,42,0.06)` |
| `--rep-shadow-md` | `0 8px 24px rgba(15,23,42,0.10)` |
| `--rep-shadow-lg` | `0 20px 48px rgba(15,23,42,0.16)` |
| `--rep-space-1..8` | 4px, 8px, 12px, 16px, 24px, 32px, 48px, 64px |

---

## 5. Icons

**Bootstrap Icons** (`bi bi-*`) via CDN — free, matches Bootstrap 5, no build step needed. Used for: navigation, amenities, property stats (bed/bath/area), category icons, dashboard sidebar icons, toast icons, theme toggle (sun/moon).

---

## 6. Core Reusable Components (defined in `theme.css`, consumed everywhere)

1. **`.rep-navbar`** — sticky, glassmorphic, blurred backdrop, collapses to offcanvas on mobile.
2. **`.rep-card`** — rounded-lg, soft shadow, hover lift + shadow grow, used for property cards, dashboard stat cards, category cards.
3. **`.rep-card-glass`** — translucent glass variant for hero overlays / floating filter bars.
4. **`.rep-btn-primary` / `.rep-btn-accent` / `.rep-btn-outline`** — pill-shaped, gradient primary, subtle press animation.
5. **`.rep-badge`** — status pills (Available/Sold/Rented/Featured), color-coded via modifier classes.
6. **`.rep-toast`** — bottom-right stacked toast notifications with slide-in + auto-dismiss (Bootstrap 5 Toast component re-skinned).
7. **`.rep-loader`** — full-page loading overlay with animated logo pulse, and `.rep-skeleton` shimmer cards for lazy content.
8. **`.rep-input` / `.rep-select`** — floating-label form controls, focus glow ring in accent color.
9. **`.rep-hero`** — full-bleed gradient/image hero with glass search bar overlapping the bottom edge.
10. **`.rep-theme-toggle`** — circular icon button, sun/moon swap animation.

All components are **mobile-first**: base styles target phones, then progressively enhanced with `min-width` media queries at 576px / 768px / 992px / 1200px (Bootstrap 5 breakpoints), matching Bootstrap's grid so no custom breakpoint system is introduced.

---

## 7. Animation & Motion Guidelines

- Standard transition: `all 0.25s cubic-bezier(0.4, 0, 0.2, 1)`
- Card hover: `translateY(-6px)` + shadow upgrade from `sm → lg`
- Buttons: `translateY(-2px)` on hover, `translateY(0)` + scale 0.98 on active/press
- Page loader: pulsing logo, fades out with `opacity` + `visibility` after `window.load`
- Toasts: slide-in from right (`translateX(120%) → 0`), auto-dismiss fade-out after 4s
- Respect `prefers-reduced-motion: reduce` — all animations disabled for users who request it (accessibility, included directly in `theme.css`)

---

## 8. File Delivered This Phase

**`public/assets/css/theme.css`** — the complete, production-ready design-token + component stylesheet. This single file is loaded on every layout (`layouts/app.blade.php`, `auth.blade.php`, `*-dashboard.blade.php`) starting Phase 3, right after the Bootstrap 5 CDN link, so Bootstrap provides the grid/utilities and `theme.css` provides the RealEstatePro brand layer on top.

Next phase (Phase 3) will consume every class listed above to build the actual Home Page markup — nothing new will be invented ad hoc.

Type **Continue** for **Phase 3: Home Page Frontend**.

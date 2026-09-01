## CoPiStarter — Implementation Guide and Reuse Reference

### Purpose
Copy/paste reference for reusing CoPiStarter’s architecture (hooks, blocks, SCSS, JS, utilities) in other WordPress projects.

## Architecture Overview
- **Bootstrap**: `functions.php` loads feature modules in `inc/…`, enqueues `assets/js/global.js` (as a module) and `assets/css/main.css`, and sets theme supports.
- **THA Hooks**: `inc/tha-theme-hooks.php` exposes `tha_*` hook wrappers used across templates.
- **Templates**:
  - `header.php`/`footer.php` wire THA hooks and page shell.
  - `index.php` composes content via THA content hooks.
  - `archive.php`/`single.php` add context-specific filters then include `index.php`.
- **Design Tokens**: `theme.json` defines color palette, typography, spacing tokens, and global styles.

## Key Features (Files)
- **Blocks Loader**: `inc/blocks.php` auto-registers blocks from `blocks/*/block.json`, registers `style.css`, and includes optional `init.php`.
- **Block Classes**: `inc/block-classes.php` → `cs_block_class($block, $classes)` maps Gutenberg supports (align, color, text/link/heading/button colors) into class names.
- **Block Areas (CPT)**: `inc/block-areas.php` registers `block_area` CPT with ACF assignment to areas like `sidebar`, `after-post`, `before-footer`, `404`. Render with `CoPiStarter\Block_Areas\show('area')`.
- **Loop**: `inc/loop.php` implements `be_default_loop()` and H1-in-content detection for headings.
- **Archive Header**: `inc/archive-header.php` builds title/description and moves breadcrumbs into the header.
- **Pagination**: `inc/archive-navigation.php` creates accessible paginated navigation with SVG icons.
- **Template Tags/Utils**: `inc/helper-functions.php` includes `be_icon()`, `be_button()`, `be_first_term()`, `be_render_search()`, and content filter wrapper `be_the_content`.
- **Header/Nav**: `inc/site-header.php` registers `primary` menu, renders logo/toggle/nav, and injects submenu expand buttons.
- **Cleanup/SEO**: `inc/wordpress-cleanup.php` reduces CSS classes, image `srcset` tweaks, removes core block patterns. `inc/wordpress-seo.php` wires Yoast breadcrumbs.
- **Comments**: `inc/comments.php` provides accessible comment form/list and hook wiring.
- **Gravity Forms**: `inc/gravity-forms.php` disables default GF notification.
- **Login Logo**: `inc/login-logo.php` helper for custom login logo (off by default).

## Frontend JS
- **Global module**: `assets/js/global.js`
  - Mobile menu toggle and submenu expand.
  - Imports `MenuPositioning` from `assets/js/nav-position.js` and calls `MenuPositioning.init()` on `DOMContentLoaded`.
- Enqueued as a module in `functions.php` by filtering the script tag to include `type="module"` for handle `theme-global`.

## Styles and Build
- **SCSS sources**: `assets/scss/**/*.scss` (partials under `assets/scss/partials/`).
- **Output CSS**: `assets/css/*.css` compiled via Node Sass scripts.
- **Watch/compile commands** (from `package.json`):
  - Development (expanded): `npm run sass-dev`
  - Production (compressed): `npm run sass-prod`
  - One-off compile all: `npm run sass-compile`
- **Compiler**: `compile-scss.js` compiles changed `.scss` to parallel `.css` paths (also supports `blocks/**/*.scss`).

## Icons System
- SVGs live under `assets/icons/{group}/*.svg` (e.g., `utility/chevron-large-left.svg`).
- Use `be_icon([ 'icon' => 'chevron-large-left', 'group' => 'utility', 'size' => 24 ])` to reference icons; definitions are inlined once in the footer via `be_icon_definitions()`.

## Reuse Playbook
1. **Copy core modules**
   - `inc/tha-theme-hooks.php`
   - `inc/blocks.php`, `inc/block-classes.php`
   - `inc/block-areas.php`
   - `inc/loop.php`, `inc/archive-header.php`, `inc/archive-navigation.php`
   - `inc/helper-functions.php`, `inc/site-header.php`, `inc/site-footer.php`
   - `inc/wordpress-cleanup.php`, `inc/wordpress-seo.php`, `inc/comments.php`
   - Optional: `inc/gravity-forms.php`, `inc/login-logo.php`
2. **Wire includes in your `functions.php`** (adjust paths as needed):
```php
require_once get_template_directory() . '/inc/tha-theme-hooks.php';
require_once get_template_directory() . '/inc/layouts.php';
require_once get_template_directory() . '/inc/helper-functions.php';
require_once get_template_directory() . '/inc/wordpress-cleanup.php';
require_once get_template_directory() . '/inc/comments.php';
include_once get_template_directory() . '/inc/site-header.php';
include_once get_template_directory() . '/inc/site-footer.php';
include_once get_template_directory() . '/inc/archive-header.php';
include_once get_template_directory() . '/inc/archive-navigation.php';
include_once get_template_directory() . '/inc/template-tags.php';
require_once get_template_directory() . '/inc/blocks.php';
require_once get_template_directory() . '/inc/block-areas.php';
require_once get_template_directory() . '/inc/loop.php';
include_once get_template_directory() . '/inc/login-logo.php';
include_once get_template_directory() . '/inc/acf-theme-colors.php';
include_once get_template_directory() . '/inc/block-classes.php';
require_once get_template_directory() . '/inc/acf.php';
require_once get_template_directory() . '/inc/wordpress-seo.php';
include_once get_template_directory() . '/inc/gravity-forms.php';
```
3. **Enqueue assets** (pattern from CoPiStarter):
```php
// Register and enqueue global.js as an ES module
wp_register_script(
  'theme-global',
  get_theme_file_uri('/assets/js/global.js'),
  [],
  filemtime(get_theme_file_path('/assets/js/global.js')),
  true
);
add_filter('script_loader_tag', function($tag, $handle){
  if ($handle === 'theme-global') return str_replace(' src', ' type=\"module\" src', $tag);
  return $tag;
}, 10, 2);
wp_enqueue_script('theme-global');
wp_enqueue_style('theme-style', get_theme_file_uri('/assets/css/main.css'), [], filemtime(get_theme_file_path('/assets/css/main.css')));
```
4. **Blocks**
   - Place blocks in `blocks/{my-block}/` with `block.json`, `render.php`, optional `init.php`, `style.css`.
   - `inc/blocks.php` will auto-register and load ACF JSON for each block folder.
   - Use `cs_block_class($block, $classes)` to add support-driven classes.
5. **Block Areas**
   - After copying `inc/block-areas.php`, create posts of type `Block Area` and assign to `sidebar`, `after-post`, `before-footer`, or `404`.
   - Render via `CoPiStarter\Block_Areas\show('before-footer')` where needed (already used in `footer.php`).
6. **Header/Nav**
   - Reuse `inc/site-header.php` and `assets/js/global.js` (with `assets/js/nav-position.js`).
   - Add `assets/icons/utility/*.svg` for menu icons.
7. **Archive Header & Pagination**
   - Include `inc/archive-header.php` and `inc/archive-navigation.php` for consistent archive pages.
8. **Comments**
   - Include `inc/comments.php` for accessible comment form/list and hook-based placement.
9. **Cleanup & SEO**
   - Include `inc/wordpress-cleanup.php` and `inc/wordpress-seo.php` (Yoast breadcrumbs).
10. **Design Tokens**
    - Start from `theme.json` to establish palette, font sizes, spacing, and element styles.

## Build Commands
- Development watch: `npm run sass-dev`
- Production watch: `npm run sass-prod`
- Compile all once: `npm run sass-compile`

## Quick Usage Snippets
- **Icon**:
```php
echo be_icon([ 'icon' => 'chevron-large-right', 'group' => 'utility', 'size' => 24 ]);
```
- **Block Area**:
```php
use CoPiStarter\Block_Areas;
Block_Areas\show('before-footer');
```
- **Heading in content detection** (already wired): `be_has_h1_block()` ensures single H1 placement.
- **Search block render**:
```php
echo be_render_search();
```

## Porting Checklist
- Copy `inc/` modules listed above and wire includes.
- Copy `assets/js/global.js` (+ `nav-position.js`) and enqueue as module.
- Copy `assets/scss/**/*` and `compile-scss.js`; run build scripts.
- Copy `assets/icons/utility/*` and any used icons.
- Bring over `theme.json` and adjust tokens to your brand.
- Verify THA hooks exist via `inc/tha-theme-hooks.php` in your theme.
- Test: menu toggle, block areas rendering, archive header, pagination, comments.

## Notes
- ACF: Theme disables ACF CPT/Taxonomies UI (`acf/settings/enable_post_types`), adds a Site Options page, and uses local JSON for block fields.
- Gravity Forms: default notification disabled; adjust as needed.
- Login logo helper is optional; enable `add_action('login_head', 'be_login_logo')` when logo assets exist.

## Alignment model (alignwide/alignfull)

### How classes are applied
- **Core**: When a block supports alignment, WordPress adds classes like `alignwide` or `alignfull` to the block wrapper.
- **Theme helper**: `inc/block-classes.php` adds `align{value}` via `cs_block_class($block, $classes)` when `supports.align` is enabled. It also adds `has-align-content-*` and `has-text-align-*` for `alignContent`/`alignText` supports.

### Container and variables
- Side padding is applied on `.site-inner` using `var(--wp--custom--layout--padding)`.
- Content widths are tokenized in `theme.json` and referenced by CSS variables:
  - `--wp--custom--layout--page` (default content max-width)
  - `--wp--custom--layout--wide` (wide content max-width)

### Practical takeaways for reuse
- Default blocks are centered to `--wp--custom--layout--page`; `alignwide` expands to `--wp--custom--layout--wide`; `alignfull` bleeds edge-to-edge by negating `.site-inner` padding and using `100vw`.
- On small screens, `alignwide` collapses to full-bleed for a consistent mobile rhythm.
- Vertical rhythm: base gap uses `var(--wp--style--block-gap)`; `alignfull` and separators get larger spacing using `--wp--custom--layout--block-gap-large`, with special collapsing rules for adjacent full-background sections and for first/last full-bleed sections.

### Porting checklist
- Ensure `theme.json` defines and maps:
  - `settings.layout.contentSize` → `var(--wp--custom--layout--content)` and `styles.spacing.blockGap` → `var(--wp--custom--layout--block-gap)`
  - `--wp--custom--layout--page`, `--wp--custom--layout--wide`, `--wp--custom--layout--padding`, and `--wp--custom--layout--block-gap-large` custom tokens
- Add the SCSS rules above in your theme’s content wrapper (`.entry-content`/`.block-area`).
- Include `cs_block_class()` or rely on core’s `align*` classes if your blocks are core-only.

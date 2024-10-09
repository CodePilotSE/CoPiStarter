# Things to do when starting a new Copistarter theme:

- Update the font sizes in _font-sizes.scss
- Run `npm run build`
- Search and replace "copistarter" with the new theme name
- Check the allowed blocks list in `assets/js/editor.js` so that it only includes the blocks that should be allowed in the theme
- Delete or rebuild the social links block

# Copistarter Theme

A theme created by CodePilot AB for its customers.

## Features

- Full site editing (FSE) support
- Responsive design
- Customizable color schemes
- Typography options with system fonts
- Block editor styles
- Custom block patterns
- Sidebar and full-width layout options

## Getting Started

1. Upload the theme folder to your `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Customize your site using the WordPress Customizer or Site Editor

## Customization

### Colors and Typography

You can easily change colors and typography settings in the `theme.json` file or through the Site Editor.

### Styles

The theme uses Sass for styles. Main stylesheets are located in the `assets/scss/` directory.

To compile Sass files:

1. Make sure you have Node.js installed
2. Run `npm install` to install dependencies
3. Use `npm run sass-dev` to compile styles


### Block Patterns

Custom block patterns are located in the `patterns/` directory. You can modify existing patterns or add new ones to suit your needs.

## License

Copistarter is licensed under the GPL-2.0+ license.


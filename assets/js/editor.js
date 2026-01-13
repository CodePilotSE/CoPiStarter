wp.domReady(() => {
  // Get all registered block types
  const allBlocks = wp.blocks.getBlockTypes();
  
  // ------------------------------------------------------------
  // Unregister blocks
  // ------------------------------------------------------------

  const disallowedBlocks = [
    'core/code',
    'core/html',
  ];
  
  const disallowedBlockCategories = [
    'theme',
    'widgets',
  ];

  // Blocks in disallowed block categories that should still be allowed
  const allowedBlocks = [
    'core/separator',
    'core/featured-image',
    'core/post-featured-image',
    'core/post-terms',
    'core/post-date',
    'core/post-title'
  ];
  

  allBlocks.forEach((block) => {
    if (((!allowedBlocks.includes(block.name) && disallowedBlockCategories.includes(block.category)) || disallowedBlocks.includes(block.name)) && block.name.substring(0, 5) === 'core/') {
      wp.blocks.unregisterBlockType(block.name);
    }
  });


  // ------------------------------------------------------------
  // Limit embed block variations
  // ------------------------------------------------------------

  const allowedEmbedBlocks = [
    'vimeo',
    'youtube',
  ];
  wp.blocks.getBlockVariations('core/embed').forEach(function (blockVariation) {
    if (-1 === allowedEmbedBlocks.indexOf(blockVariation.name)) {
      wp.blocks.unregisterBlockVariation('core/embed', blockVariation.name);
    }
  });

  // ------------------------------------------------------------
  // Unregister block styles
  // ------------------------------------------------------------

  const blockStylesToUnregister = {
    // 'core/button': ['squared', 'fill'],
    // 'core/separator': ['wide', 'dots'],
    // 'core/quote': ['large', 'plain']
  };

  Object.keys(blockStylesToUnregister).forEach((block) => {
      blockStylesToUnregister[block].forEach((style) => {
          wp.blocks.unregisterBlockStyle(block, style);
      });
  });
  // Trigger change event for ACF fields
  setTimeout(() => {
    document.querySelectorAll('.acf-block-has-validation-error [data-name]').forEach(field => {
      field.dispatchEvent(new Event('change'));
    });
  }, 1000); // Adjust timeout as needed to ensure fields are loaded
  
  
  // ------------------------------------------------------------
  // Set align to none if it is empty to fix issue with align setting reverting to default
  // ------------------------------------------------------------

  const { select, subscribe, dispatch } = wp.data;
  const store = 'core/block-editor';

  subscribe(() => {
    const blocks = select(store).getBlocks();
    blocks.forEach(block => {
      if (block.attributes && block.attributes.align === '') {
        dispatch(store).updateBlockAttributes(block.clientId, {
          align: 'none'
        });
      }
    });
  });
});

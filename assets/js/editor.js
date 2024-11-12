wp.domReady(() => {
  // Get all registered block types
  const allBlocks = wp.blocks.getBlockTypes();

  // Define the allowed blocks
  const allowedBlocks = [
      'core/paragraph',
      'core/image',
      'core/heading',
      'core/list',
      'core/list-item',
      'core/button',
      'core/buttons',
      'core/separator',
      'core/quote',
      'core/image',
      'core/gallery',
      'core/spacer',
      'core/featured-image',
      'core/post-featured-image',
      'core/post-terms',
      'core/post-date',
      'core/post-title'

  ];

  // Define the blocks that you manually want to disallow
  const disallowedBlocks = [
    
  ];

  // Unregister blocks
  allBlocks.forEach((block) => {
    var blockName = block.name;
    if(!allowedBlocks.includes(blockName) && blockName.substring(0, 5) === 'core/'){
      wp.blocks.unregisterBlockType(blockName);
    }
    if(disallowedBlocks.includes(blockName)){
      wp.blocks.unregisterBlockType(blockName);
    }
  });


  const blockStylesToUnregister = {
    'core/button': ['squared', 'fill'],
    'core/separator': ['default', 'wide', 'dots'],
    'core/quote': ['default', 'large', 'plain']
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

} );

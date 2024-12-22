   const { __ } = wp.i18n;
   const { registerBlockType } = wp.blocks;
   const { TextControl } = wp.components;
   const { useBlockProps } = wp.blockEditor;

   registerBlockType('chess-tempo/viewer', {
       title: __('Chess Tempo PGN Viewer'),
       icon: 'chess',
       category: 'widgets',
       attributes: {
           id: { type: 'string', default: 'demo' },
           pieceset: { type: 'string', default: 'leipzig' },
           pgn: { type: 'string', default: '' },
       },
       edit: ({ attributes, setAttributes }) => {
           const blockProps = useBlockProps();

           return (
               <div {...blockProps}>
                   <TextControl
                       label={__('PGN Content')}
                       value={attributes.pgn}
                       onChange={(val) => setAttributes({ pgn: val })}
                   />
                   <TextControl
                       label={__('Piece Set')}
                       value={attributes.pieceset}
                       onChange={(val) => setAttributes({ pieceset: val })}
                   />
                   <TextControl
                       label={__('Viewer ID')}
                       value={attributes.id}
                       onChange={(val) => setAttributes({ id: val })}
                   />
               </div>
           );
       },
       save: () => null, // Server-side rendered block
   });
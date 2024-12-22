   const { __ } = wp.i18n;
   const { registerBlockType } = wp.blocks;
   const { TextControl, TextareaControl, SelectControl } = wp.components;
   const { useBlockProps } = wp.blockEditor;

   registerBlockType('chess-tempo/viewer', {
       title: __('Chess Tempo PGN Viewer'),
       icon: 'chess',
       category: 'widgets',
       attributes: {
           id: { type: 'string', default: '' },
           pieceset: { type: 'string', default: 'alpha' },
           pgn: { type: 'string', default: '' },
       },
       edit: ({ attributes, setAttributes }) => {
           const blockProps = useBlockProps();
           // Generate random ID if it hasn't been set
           if (!attributes.id) {
               setAttributes({ id: generateRandomID() });
           }

           return (
               <div {...blockProps}>
                   <TextareaControl
                       label="PGN Content"
                       help="Enter the PGN data here."
                       value={attributes.pgn}
                       onChange={(content) => setAttributes({ pgn: content })}
                       __nextHasNoMarginBottom={true}
                   />
                   <SelectControl
                       label="Piece Set"
                       value={attributes.pieceset} // The current selected value
                       options={[
                           { label: 'Merida (Default)', value: 'merida-gradient' },
                           { label: 'Goodcomp', value: 'goodcomp-gradient' },
                           { label: 'Alpha', value: 'alpha' },
                           { label: 'Case', value: 'case' },
                           { label: 'Eyes', value: 'eyes' },
                           { label: 'Leipzig', value: 'leipzig' },
                           { label: 'Maya', value: 'maya' },
                           { label: 'Skulls', value: 'skulls' },
                       ]} // Drop-down options
                       onChange={(newValue) => setAttributes({ pieceset: newValue })} // Update the attribute when user selects a new value
                       __nextHasNoMarginBottom={true} // Avoid margin-bottom deprecation warning
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

   function generateRandomID() {
       return Math.random().toString(36).substr(2, 10); // Generates 10 random characters
   }
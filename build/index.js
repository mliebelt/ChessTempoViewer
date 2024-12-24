(()=>{"use strict";const e=window.ReactJSXRuntime,{__}=wp.i18n,{registerBlockType:t}=wp.blocks,{CheckboxControl:l,TextControl:a,TextareaControl:o,SelectControl:s}=wp.components,{useBlockProps:i}=wp.blockEditor;t("chess-tempo/viewer",{title:__("Chess Tempo PGN Viewer"),icon:"chess",category:"widgets",attributes:{id:{type:"string",default:""},pieceset:{type:"string",default:"alpha"},boardsize:{type:"string",default:"500px"},movelistposition:{type:"string",default:"right"},moveliststyle:{type:"string",default:"indented"},pgn:{type:"string",default:""},pgnfile:{type:"boolean",default:!1},fen:{type:"string",default:""}},edit:({attributes:t,setAttributes:n})=>{const r=i({className:"chess-tempo-viewer-wrapper"});return t.id||n({id:Math.random().toString(36).substr(2,10)}),(0,e.jsxs)("div",{...r,children:[(0,e.jsxs)("div",{className:"row group-2",children:[(0,e.jsx)(l,{label:__("Use PGN File"),help:__("Enable this if you want to import PGN content via a file."),checked:t.pgnfile,onChange:e=>n({pgnfile:!!e}),__nextHasNoMarginBottom:!0}),(0,e.jsx)(a,{label:__("FEN"),value:t.fen,onChange:e=>n({fen:e}),__nextHasNoMarginBottom:!0})]}),(0,e.jsx)("div",{className:"row group-1",children:(0,e.jsx)(o,{label:"PGN Content",help:"Enter the PGN data here.",value:t.pgn,onChange:e=>n({pgn:e}),__nextHasNoMarginBottom:!0})}),(0,e.jsxs)("div",{className:"row group-3",children:[(0,e.jsx)(s,{label:"Piece Set",value:t.pieceset,options:[{label:"Merida (Default)",value:"merida-gradient"},{label:"Goodcomp",value:"goodcomp-gradient"},{label:"Alpha",value:"alpha"},{label:"Case",value:"case"},{label:"Eyes",value:"eyes"},{label:"Leipzig",value:"leipzig"},{label:"Maya",value:"maya"},{label:"Skulls",value:"skulls"}],onChange:e=>n({pieceset:e}),__nextHasNoMarginBottom:!0}),(0,e.jsx)(a,{label:__("Board Size"),value:t.boardsize,onChange:e=>n({boardsize:e}),__nextHasNoMarginBottom:!0}),(0,e.jsx)(s,{label:__("Position of Move List"),value:t.movelistposition,options:[{label:"Right (Default)",value:"right"},{label:"Under",value:"under"}],onChange:e=>n({movelistposition:e}),__nextHasNoMarginBottom:!0})]}),(0,e.jsxs)("div",{className:"row group-2",children:[(0,e.jsx)(s,{label:__("Move List Style"),value:t.moveliststyle,options:[{label:"Indented (Default)",value:"indented"},{label:"Two Column",value:"twocolumn"}],onChange:e=>n({moveliststyle:e}),__nextHasNoMarginBottom:!0}),(0,e.jsx)(a,{label:__("Viewer ID"),value:t.id,onChange:e=>n({id:e}),__nextHasNoMarginBottom:!0})]})]})},save:()=>null})})();
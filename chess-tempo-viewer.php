<?php

/*
Plugin Name: Chess Tempo Viewer
Plugin URI: http://wordpress.org/plugins/chesstempoviewer/
Description: Integrates the Chess Tempo Viewer into WordPress
Version: 1.0.0
Author: Markus Liebelt
Author URI: http://profiles.wordpress.org/mliebelt
License: GPLv2 or later
*/

// Enqueue scripts and styles for the frontend.
function pgnviewer_enqueue_assets() {
    $loc = get_locale();

    // Enqueue scripts
    wp_enqueue_script('pgnlocale', "https://chesstempo.com/locale/$loc/LC_MESSAGES/ct.json", [], null, true);
    wp_enqueue_script('pgnviewer', 'https://c1a.chesstempo.com/pgnviewer/v2.5/pgnviewerext.bundle.vers1.js', [], null, true);

    // Enqueue styles
    wp_enqueue_style('pgnviewer-css', 'https://c2a.chesstempo.com/pgnviewer/v2.5/pgnviewerext.vers1.css', [], null);
    wp_enqueue_style('pgnviewer-fonts', 'https://c1a.chesstempo.com/fonts/MaterialIcons-Regular.woff2', [], null);
    wp_enqueue_style('pgnviewer-figurine', 'https://c1a.chesstempo.com/fonts/chessalphanew-webfont.woff', [], null);
}
add_action('wp_enqueue_scripts', 'pgnviewer_enqueue_assets');

// Main shortcode handler for [ctpgn]
function chess_tempo_viewer_shortcode($attributes, $content = null) {
    $attributes = shortcode_atts(
        [
            'id'          => 'demo',
            'pieceset'    => 'leipzig',
            'boardsize'   => '500px',
            'movesformat' => 'default',
            'layout'      => 'top',
            'movelistposition' => 'right',
            'moveliststyle' => 'indented', // possible: indented/twocolumn
        ],
        $attributes,
        'ctpgn'
    );

    // Clean and prepare PGN content
    $cleaned_content = cleanup_pgn($content); // Uses the improved `cleanup_pgn` from before.

    // Escape the content properly for safe output (no WordPress transformations)
    $cleaned_content = htmlspecialchars($cleaned_content, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');

    // Construct the viewer element
    return sprintf(
        '<ct-pgn-viewer id="%s" board-pieceStyle="%s" board-size="%s" layout="%s" move-list-position="%s">%s</ct-pgn-viewer>',
        esc_attr($attributes['id']),
        esc_attr($attributes['pieceset']),
        esc_attr($attributes['boardsize']),
        esc_attr($attributes['layout']),
        esc_attr($attributes['movelistposition']),
        esc_attr($attributes['moveliststyle']),
        $cleaned_content // Insert PGN unaltered (HTML-escaped manually)
    );
}
add_shortcode('ctpgn', 'chess_tempo_viewer_shortcode');

// Clean up PGN content to avoid format issues
function cleanup_pgn($content) {
    // Remove any unwanted HTML tags accidentally injected (e.g., by wpautop)
    $content = wp_strip_all_tags($content);
    // Replace smart quotes (left, right) with standard ASCII quotes
    $smart_quotes = [
        '“', '”', '‘', '’', // Smart quotes
        '&#8220;', '&#8221;', '&#8216;', '&#8217;', // HTML-encoded smart quotes
    ];
    $standard_quotes = [
        '"',   '"',   "'",   "'", // Replace all with standard single/double quotes
    ];
    $content = str_replace($smart_quotes, $standard_quotes, $content);
    // Normalize ellipsis
    $content = str_replace(["…", '&#8230;'], '..', $content);

    // Normalize line breaks (convert to spaces)
    $content = preg_replace('/[\r\n]+/', ' ', $content);

    // Reduce multiple spaces to a single space
    $content = preg_replace('/\s+/', ' ', $content);

    // Trim the final cleaned content
    return trim($content);
}

add_action('enqueue_block_editor_assets', function () {
    wp_enqueue_script('wp-blocks');
    wp_enqueue_script('wp-element');
    wp_enqueue_script('wp-editor');
});

function chess_tempo_register_block() {
    wp_register_script(
        'chess-tempo-block',
        plugins_url('build/index.js', __FILE__), // Path to your custom block script
        ['wp-blocks', 'wp-element', 'wp-editor'], // Block dependencies
        null,
        true
    );

    register_block_type('chess-tempo/viewer', [
        'editor_script' => 'chess-tempo-block', // Enqueue the JavaScript
        'render_callback' => 'chess_tempo_render_block', // Server-rendered PGN
    ]);
}
add_action('init', 'chess_tempo_register_block');

// Callback to server-render (PHP side rendering)
function chess_tempo_render_block($attributes) {
    $pgn_content = isset($attributes['pgn']) ? cleanup_pgn($attributes['pgn']) : '';
    $pieceset = isset($attributes['pieceset']) ? $attributes['pieceset'] : 'merida-gradient';
    $boardsize = isset($attributes['boardsize']) ? $attributes['boardsize'] : '500px';
    $movelistposition = isset($attributes['movelistposition']) ? $attributes['movelistposition'] : 'right';
    $moveliststyle = isset($attributes['moveliststyle']) ? $attributes['moveliststyle'] : 'indented';
    $id = isset($attributes['id']) ? $attributes['id'] : 'demo';

    return sprintf(
        '<ct-pgn-viewer id="%s" board-pieceStyle="%s" board-size="%s" move-list-position="%s" move-list-moveListStyle="%s" move-list-resizable="true">%s</ct-pgn-viewer>',
        esc_attr($id),
        esc_attr($pieceset),
        esc_attr($boardsize),
        esc_attr($movelistposition),
        esc_attr($moveliststyle),
        esc_html($pgn_content)
    );
}
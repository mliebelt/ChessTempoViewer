<?php

/*
Plugin Name: Chess Tempo Viewer
Plugin URI: http://wordpress.org/plugins/chesstempoviewer/
Description: Integrates the Chess Tempo Viewer into WordPress
Version: 0.9.3
Author: Markus Liebelt
Author URI: http://profiles.wordpress.org/mliebelt
License: GPLv2 or later
*/

function pgnviewer_js_and_css(){
    $loc = get_locale();
    wp_enqueue_script("jquery");
    wp_enqueue_script('pgnyui', 'http://chesstempo.com/js/pgnyui.js');
    wp_enqueue_script('pgnlocale', "http://chesstempo.com/locale/$loc/LC_MESSAGES/ct.json");
    wp_enqueue_script('pgnviewer', 'http://chesstempo.com/js/pgnviewer.js');
    wp_enqueue_style('pgnviewer-css', 'http://chesstempo.com/css/board-min.css');
    wp_enqueue_style('ctpgn', plugins_url('ctpgn.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'pgnviewer_js_and_css');

// [ctpgn pieceSet=leipzig pieceSize=40 movesformat=main_on_own_line] 1. e4 e5 2. Nf3 Nc6 3. Bb5 [/ctpgn]
function chessTempoViewer($attributes, $content = NULL) {
    extract( shortcode_atts( array(
        'id' => 'demo',
        'fen' => NULL,
        'pieceset' => 'merida',
        'piecesize' => '46',
        'movesformat' => 'default',
        'layout' => 'top'
    ), $attributes ) );
    $cleaned = cleanup_pgn($content);
    if (is_null($cleaned)) {
        $type = "pgnfile";
        $pgn = $attributes['pgnfile'];
        $pgnpart = "pgnFile: $pgn";
    } else {
        $type = "pgnstring";
        if (is_null($fen)) {
            $pgnpart = "pgnString: '$cleaned'";
        } else {
            $pgn = '[FEN "' . $fen . ']" ' . $cleaned;
            $pgnpart = "pgnString: '$pgn'";
        }
    }
    if ($layout == 'bottom') {
        $float = <<<EOD
<div id="$id-moves">
</div><div id="$id-container" class="cont-float-$layout"></div>
EOD;
    } else {
        $float = <<<EOD
<div id="$id-container" class="cont-float-$layout"></div><div id="$id-moves"></div>
EOD;
    }
    $text = "";
    //$text .= var_dump($attributes);
/*    $text .= "Type: $type ";
    $text .= "Set:  $pieceSet";
    $text .= "Size: $pieceSize ";
    $text .= "Format: $movesFormat";*/
    $template = <<<EOD
<script>
    new PgnViewer(
            { boardName: '$id',
                $pgnpart,
                pieceSet: '$pieceset',
                pieceSize: $piecesize,
                showCoordinates: true,
                movesFormat: '$movesformat'
            }
    );
</script>

$float
<div class="cont-float-clear"></div>
EOD;
    return $text . $template;
}

add_shortcode( 'ctpgn', 'chessTempoViewer');

// Cleanup the content, so it will not have any errors. Known are
// * line breaks ==> Spaces
// * Pattern: ... ==> ..
function cleanup_pgn( $content ) {
    $search = array("...", "&#8230;");
    $replace = array("..", "..");
    $tmp = str_replace($search, $replace, $content);
    return str_replace (array("\r\n", "\n", "\r", "<br />"), ' ', $tmp);
}

/** Step 2 (from text above). */
add_action( 'admin_menu', 'ctpgn_menu' );

/** Step 1. */
function ctpgn_menu() {
    add_submenu_page('options-general.php', 'Chess Tempo Viewer Settings', 'Chess Tempo Viewer', 'administrator', __FILE__, 'ctpgn_options' );
}

/** Step 3. */
function ctpgn_options() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Greetings from Chess Tempo Viewer.</p>';
    echo '</div>';
}
?>
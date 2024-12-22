# Development Environment

I use the following development environment to develop this (and other) plugins for Wordpress:

* PHPStorm (current version)
* wp-env for spinning Wordpress with the one plugin I am developing
* npm for all building
* nvm for having a defined npm version

## Doing the setup

* Get the git repository: `git clone https://github.com/mliebelt/ChessTempoViewer.git`.
* cd into it: `cd ChessTempoViewer`.
* Ensure that a current version of node is available: `node -v`. Result should be "v20.x.y".
* Run `npm install`.
* Ensure that `wp-env` is installed and available by running `wp-env help`. This should display information to the currently available commands. We are using start and stop

## Building the dev version

* Run `npm run build`. This results in creation of `build/index.js` which is needed later then.

## Starting the dev wordpress

* Run `npm run start`. This uses the files
  * `chess-tempo-viewer.php` and
  * `build/index.js` (for creating the block element).
* Open then `http://localhost:8888/wp-admin` and login as user `root` with password `admin`.

You are now able to create a new page, and have in that page the shortcode and the block element available.

### Using shortcode

* Go to a page, edit the page.
* Include the element `/shortcode`.
* Insert into the element `[ctpgn] e4 e5 [/ctpgn]` and save it.
* Visit the resulting page. You should see the default board, with the 2 moves available.

### Using block element

* Go to a page, edit the page.
* Include the element `/Chess Tempo PGN Viewer`.
* This gives you a form to fill in:
  * The pgn string to use. This should be a text area, with enough space. Copy any game from any source here.
  * The piece set to select as drop-down. Select any one you like.
  * The ID with a generated code. You don't have to change that.

## Stopping the dev wordpress

* Run `npm run stop` to stop the server.
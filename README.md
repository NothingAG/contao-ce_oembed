# [UNMAINTAINED]
This project is not maintained anymore.

# CONTAO CONTENT ELEMENT: OEMBED
A content element allowing to embed content of third-party websites (YouTube, Vimdeo, Flickr, Hulu etc.) by using the oEmbed format.

## SETUP AND USAGE
### Prerequisites
 * Contao 3.1.x
 * jquery
 * FitVids (http://fitvidsjs.com/)

### Installation
1. Copy the files into the modules folder from Contao
2. Download the fitvids package and place it in TL_ROOT/assets/jquery/fitvids
3. Update the database (e.g. with the _Extension manager_)
4. Use the content element inside your articles
5. Give the oEmbed URL for the content to be embedded (e.g. http://www.youtube.com/oembed?url=http://www.youtube.com/watch?v=yhlo4dT0iq4)
6. Enjoy!

### Known Bugs
 * Backend inputs are basically not validated.

## VERSION HISTORY

### 3.1.0 (2013-07-15)
 * Fixed a problem where a language fallback page could not be loaded after cache generation

### 3.0.0 (2013-06-20)
 * Added FItVid jQuery package
 * Videos are now responsive to the page
 * Removed height and width options in the backend.
 * Updated installation guide
 * Added support for Youtube direct-links in the Backend

### 2.0.0 (2013-06-05)
 * Added autoload files to config to preload php files.
 * Deleted sql file and added content to respective dca file!
 * Edited folder structure to match contao 3 standards.
 * Removed htaccess files that deny acces and added ones to allow where needed.
 * Removed "if (!defined('TL_ROOT')) die('You can not access this file directly!');" from classes as it is outdated.
 * Added "namespace Contao;" to class files.
 * "extends Module" changed to "extends \Module" in class files.
 * Added this new hack file for documentation of changes made.

### 1.0.0 (2012-10-03)
 * Initial release

## LICENSE
* Author:		Nothing Interactive, Switzerland
* Website: 		[https://www.nothing.ch/](https://www.nothing.ch/)
* Version: 		2.0.0
* Date: 		2013-06-05
* License: 		[GNU Lesser General Public License (LGPL)](http://www.gnu.org/licenses/lgpl.html)

<?php

/**
 * spieFeed is a SimplePie's bridge to MODx Revolution
 *
 * This file is part of spieFeed, a feed syndication aggregator component for
 * MODx Revolution, which runs on top of SimplePie's script.
 *
 * spieFeed is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 3 of the License, or (at your option) any later
 * version.
 *
 * spieFeed is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * SimplePie itself is bonded to its license which can be found on their website
 * here http://simplepie.org/wiki/faq/can_i_include_simplepie_with_software_i_m_selling
 * or the included file LICENSE.txt.
 *
 * You should have received a copy of the GNU General Public License along with
 * spieFeed; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @copyright Copyright 2010 by goldsky <goldsky@modx-id.com>
 * @version 1.0.0-beta-1
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @package spieFeed
 * @subpackage snippet
 * @link http://simplepie.org/
 */
/**
 * This sets the URL (or an array of URLs) that you want to parse.
 * If there is not a feed at this location, auto-discovery is used unless it is disabled.
 * Note that if you've already loaded the raw RSS data, you should use set_raw_data().
 * @link http://simplepie.org/wiki/reference/simplepie/set_feed_url
 */
$defaultFeedUrl = 'http://feeds.feedburner.com/modx-announce'
        // non latin-1 testing
        . '| http://www.voanews.com/templates/Articles.rss?sectionPath=/russian/news'
;
$spie['feedUrl'] = isset($setFeedUrl) ? $setFeedUrl : $defaultFeedUrl;
if (trim($spie['feedUrl']) == '') {
    return FALSE;
}

$spie['setFeedUrl'] = @explode('|', $spie['feedUrl']);
foreach ($spie['setFeedUrl'] as $k => $v) {
    $spie['setFeedUrl'][$k] = trim($v);
}

/**
 * This option allows you to disable caching all-together in SimplePie.
 * However, disabling the cache can lead to longer load times.
 * @var 0 | 1
 * @link http://simplepie.org/wiki/reference/simplepie/enable_cache
 */
$spie['enableCache'] = isset($enableCache) ? (int) $enableCache : null;

/**
 * Sometimes feeds don't have their items in chronological order.
 * By default, SimplePie will re-order them to be in such an order.
 * With this option, you can enable/disable the reordering of items into reverse
 * chronological order if you don't want it.
 * @link http://simplepie.org/wiki/reference/simplepie/enable_order_by_date;
 */
$spie['enableOrderByDate'] = isset($enableOrderByDate) && (int) $enableOrderByDate == 0 ? 'false' : null;

/**
 * Set the minimum time (in seconds) for which a feed will be cached.
 * @link http://simplepie.org/wiki/reference/simplepie/set_cache_duration
 */
$spie['setCacheDuration'] = isset($setCacheDuration) ? (int) $setCacheDuration : null;

/**
 * Set the file system location (not WWW location) where the cache files should be written.
 * The cache folder should be make or error will returned
 * @link http://simplepie.org/wiki/reference/simplepie/set_cache_location
 */
$defaultCacheLocation = $modx->getOption('assets_path') . 'components/spiefeed/cache';
$spie['setCacheLocation'] = isset($setCacheLocation) ? $setCacheLocation : $defaultCacheLocation;

/**
 * Set the handler to enable the display of cached favicons.
 * @link http://simplepie.org/wiki/reference/simplepie/set_favicon_handler
 */
$spie['setFaviconHandler'] = array();
if (!empty($setFaviconHandler)) {
    $spie['setFaviconHandler'] = @explode(',', $setFaviconHandler);
    foreach ($spie['setFaviconHandler'] as $k => $v) {
        $spie['setFaviconHandler'][$k] = trim($v);
    }
}

/**
 * Set the handler to enable the display of cached images.
 * Setting set_image_handler() tells SimplePie :<br/>
 * (a) to cache them in the first place, and <br />
 * (b) the file that will be used to read them back from the cache and display them.
 * @link http://simplepie.org/wiki/reference/simplepie/set_image_handler
 */
$spie['setImageHandler'] = array();
if (!empty($setImageHandler)) {
    $spie['setImageHandler'] = @explode(',', $setImageHandler);
    foreach ($spie['setImageHandler'] as $k => $v) {
        $spie['setImageHandler'][$k] = trim($v);
    }
}

/**
 * Set the maximum number of items to return per feed with Multifeeds.
 * This is NOT for limiting the number of items to loop through in a single feed.
 * For that, you want to pass $start and $length parameters to get_items()
 * @link http://simplepie.org/wiki/reference/simplepie/set_item_limit
 */
$spie['setItemLimit'] = isset($setItemLimit) ? (int) $setItemLimit : null;

/**
 * Set the query string that triggers SimplePie to generate the JavaScript code
 * for embedding media files.
 * @link http://simplepie.org/wiki/reference/simplepie/set_javascript
 */
$spie['setJavascript'] = isset($setJavascript) ? $setJavascript : null;

/**
 * Set which attributes get stripped from an entry's content. <br />
 * The default set of attributes is stored in the property SimplePie→strip_attributes,
 * not to be confused with the method SimplePie→strip_attributes().
 * This way, you can modify the existing list without having to create a whole new one.
 * @link http://simplepie.org/wiki/reference/simplepie/strip_attributes
 */
$spie['stripAttributes'] = array();
if (!empty($stripAttributes)) {
    $spie['stripAttributes'] = @explode(',', $stripAttributes);
    foreach ($spie['stripAttributes'] as $k => $v) {
        $spie['stripAttributes'][$k] = trim($v);
    }
}

/**
 * Set whether to strip out HTML comments from an entry's content.
 * @link http://simplepie.org/wiki/reference/simplepie/strip_comments
 */
$spie['stripComments'] = isset($stripComments) && $stripComments == '1' ? 'true' : null;

/**
 * Set which HTML tags get stripped from an entry's content. <br />
 * The default set of tags is stored in the property SimplePie→strip_htmltags,
 * not to be confused with the method SimplePie→strip_htmltags().
 * This way, you can modify the existing list without having to create a whole new one.
 * @link http://simplepie.org/wiki/reference/simplepie/strip_htmltags
 */
$spie['stripHtmlTags'] = array();
if (!empty($stripHtmlTags)) {
    $spie['stripHtmlTags'] = @explode(',', $stripHtmlTags);
    foreach ($spie['stripHtmlTags'] as $k => $v) {
        $spie['stripHtmlTags'][$k] = trim($v);
    }
}

/**
 * Date format supports anything that works with PHP's date() function.
 * Only supports the English language
 * @link http://simplepie.org/wiki/reference/simplepie_item/get_date
 */
$spie['dateFormat'] = isset($dateFormat) ? $dateFormat : null;

/**
 * Returns the date/timestamp of the posting in the localized language.
 * Date format supports anything that works with PHP's strftime() function.
 * To display in other languages, you need to change the locale with PHP's setlocale() function.
 * The available localizations depend on which ones are installed on your web server.
 * @link http://simplepie.org/wiki/reference/simplepie_item/get_local_date
 */
$spie['localDateFormat'] = isset($localDateFormat) ? $localDateFormat : null;

/**
 * Returns an array of SimplePie_Item references for each item in the feed, which can be looped through. 
 * @link http://simplepie.org/wiki/reference/simplepie/get_items
 */
$spie['getItemStart'] = isset($getItemStart) ? $getItemStart : null;
$spie['getItemEnd'] = isset($getItemEnd) ? $getItemEnd : null;

/**
 * If cURL is available, SimplePie will use it instead of the built-in fsockopen functions for fetching remote feeds.
 * This config option will force SimplePie to use fsockopen even if cURL is installed.
 * @link http://simplepie.org/wiki/reference/simplepie/force_fsockopen
 */
$spie['forceFSockopen'] = isset($forceFSockopen) && $forceFSockopen == '0' ? 0 : 1;

/**
 * Allows you to override the character encoding of the feed.
 * This is only useful for times when the feed is reporting an incorrect character encoding
 * (as per RFC 3023 and Determining the character encoding of a feed).
 * This setting is similar to set_output_encoding().
 *
 * The number of supported character encodings depends on whether your web host supports mbstring, iconv, or both.
 * See Supported Character Encodings for more information.
 * @link http://simplepie.org/wiki/reference/simplepie/set_input_encoding
 * @link http://simplepie.org/wiki/faq/supported_character_encodings
 */
$spie['setInputEncoding'] = isset($setInputEncoding) ? $setInputEncoding : null;

/**
 * Allows you to override SimplePie's output to match that of your webpage.
 * This is useful for times when your webpages are not being served as UTF-8.
 * This setting will be obeyed by handle_content_type(), and is similar to set_input_encoding().
 *
 * It should be noted, however, that not all character encodings can support all characters.
 * If your page is being served as ISO-8859-1 and you try to display a Japanese feed,
 * you'll likely see garbled characters.
 * Because of this, it is highly recommended to ensure that your webpages are served as UTF-8.
 *
 * The number of supported character encodings depends on whether your web host supports mbstring, iconv, or both.
 * See Supported Character Encodings for more information.
 * @link http://simplepie.org/wiki/reference/simplepie/set_output_encoding
 * @link http://simplepie.org/wiki/faq/supported_character_encodings
 */
$spie['setOutputEncoding'] = isset($setOutputEncoding) ? $setOutputEncoding : null;

$spie['sortBy'] = isset($sortBy) ? $sortBy : 'date';
$spie['sortOrder'] = isset($sortOrder) && strtoupper($sortOrder) == 'ASC' ? 'ASC' : 'DESC';


$spie['tpl'] = isset($tpl) ? $tpl : 'defaultSpieFeedTpl';
$spie['tplFile'] = 'assets/components/spiefeed/templates/tpl/default-spiefeed.tpl';
$spie['firstRowCls'] = isset($firstRowCls) ? $firstRowCls : 'spie-first-row';
$spie['lastRowCls'] = isset($lastRowCls) ? $lastRowCls : 'spie-last-row';
$spie['rowCls'] = isset($rowCls) ? $rowCls : 'spie-row';
$spie['oddRowCls'] = isset($oddRowCls) ? $oddRowCls : 'spie-odd-row';

// clean up all empty params
foreach ($spie as $k => $v) {
    if (($spie[$k]) == '' || empty($spie[$k]))
        unset($spie[$k]);
}

################################################################################

$spieModxClassFile = MODX_CORE_PATH . 'components/spiefeed/model/spiefeed.class.php';
$spie['simplePieClassFile'] = MODX_CORE_PATH . 'components/spiefeed/includes/simplepie/simplepie.inc';

$output = '';
$attachHeaders = FALSE;

if (!class_exists('SimplePieModx')) {
    if (!file_exists($spieModxClassFile)) {
        $output = 'File ' . $spieModxClassFile . ' does not exist.';
    } else {
        include_once $spieModxClassFile;
    }
}

ob_start();
$simplePieModx = new SimplePieModx($modx, $spie);
echo $simplePieModx->spieModx($spie);
$output = ob_get_contents();
ob_end_clean();

if ($output) {
    $attachHeaders = TRUE;
}

if ($attachHeaders) {
    $defaultCssFile = MODX_ASSETS_URL . 'components/spiefeed/templates/css/spiefeed.css';
    $spie['css'] = isset($css) ? $css : $defaultCssFile;
    if ($spie['css'] != 'disabled') {
        $modx->regClientCSS($defaultCssFile, 'screen');
    }
}

echo $output;
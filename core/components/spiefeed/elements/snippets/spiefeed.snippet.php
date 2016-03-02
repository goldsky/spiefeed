<?php

/**
 * ******* spieFeed's license. *******
 * GPLv3
 * http://www.gnu.org/licenses/gpl-3.0.html
 *
 * ******* SimplePie's license. *******
 * BSD
 * Copyright (c) 2004-2007, Ryan Parman and Geoffrey Sneddon.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * * Neither the name of the SimplePie Team nor the names of its contributors
 *   may be used to endorse or promote products derived from this software
 *   without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @author goldsky <goldsky@virtudraft.com>
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
$defaultFeedUrl =
        'http://feeds.feedburner.com/modx-announce'
        // non latin-1 testing
        . '| http://www.voanews.com/templates/Articles.rss?sectionPath=/russian/news'
;
$scriptProperties['setFeedUrl'] = $modx->getOption('setFeedUrl', $scriptProperties, $defaultFeedUrl);
if (trim($scriptProperties['setFeedUrl']) == '') {
    return FALSE;
}

$urls = @explode('|', $scriptProperties['setFeedUrl']);
$scriptProperties['setFeedUrl'] = array();
foreach ($urls as $k => $v) {
    $scriptProperties['setFeedUrl'][$k] = trim($v);
}

/**
 * This option allows you to disable caching all-together in SimplePie.
 * However, disabling the cache can lead to longer load times.
 * @var 0 | 1
 * @link http://simplepie.org/wiki/reference/simplepie/enable_cache
 */
$scriptProperties['enableCache'] = $modx->getOption('enableCache', $scriptProperties);

/**
 * Sometimes feeds don't have their items in chronological order.
 * By default, SimplePie will re-order them to be in such an order.
 * With this option, you can enable/disable the reordering of items into reverse
 * chronological order if you don't want it.
 * @link http://simplepie.org/wiki/reference/simplepie/enable_order_by_date;
 */
$scriptProperties['enableOrderByDate'] = !empty($scriptProperties['enableOrderByDate']) && $scriptProperties['enableOrderByDate'] == '0' ? 'false' : null;
$scriptProperties['enableOrderByDate'] = $modx->getOption('enableOrderByDate', $scriptProperties);

/**
 * Set the minimum time (in seconds) for which a feed will be cached.
 * @link http://simplepie.org/wiki/reference/simplepie/set_cache_duration
 */
$scriptProperties['setCacheDuration'] = $modx->getOption('setCacheDuration', $scriptProperties);

/**
 * Set the file system location (not WWW location) where the cache files should be written.
 * The cache folder should be make or error will returned
 * @link http://simplepie.org/wiki/reference/simplepie/set_cache_location
 */
$defaultCacheLocation = $modx->getOption('core_path') . 'components/spiefeed/cache';
$scriptProperties['setCacheLocation'] = $modx->getOption('setCacheLocation', $scriptProperties, $defaultCacheLocation);
$cachePath = realpath($scriptProperties['setCacheLocation']);
if (!is_dir($cachePath)) {
    @mkdir($cachePath, 0755);
}

/**
 * Set the handler to enable the display of cached images.
 * Setting set_image_handler() tells SimplePie :<br/>
 * (a) to cache them in the first place, and <br />
 * (b) the file that will be used to read them back from the cache and display them.
 * @link http://simplepie.org/wiki/reference/simplepie/set_image_handler
 */
$scriptProperties['setImageHandler'] = array();
$setImageHandler = $modx->getOption('setImageHandler', $scriptProperties);
if (!empty($setImageHandler)) {
    $scriptProperties['setImageHandler'] = @explode(',', $setImageHandler);
    foreach ($scriptProperties['setImageHandler'] as $k => $v) {
        $scriptProperties['setImageHandler'][$k] = trim($v);
    }
}

/**
 * Set the maximum number of items to return per feed with Multifeeds.
 * This is NOT for limiting the number of items to loop through in a single feed.
 * For that, you want to pass $start and $length parameters to get_items()
 * @link http://simplepie.org/wiki/reference/simplepie/set_item_limit
 */
$scriptProperties['setItemLimit'] = (int) $modx->getOption('setItemLimit', $scriptProperties);

/**
 * Set the query string that triggers SimplePie to generate the JavaScript code
 * for embedding media files.
 * @link http://simplepie.org/wiki/reference/simplepie/set_javascript
 */
$scriptProperties['setJavascript'] = $modx->getOption('setJavascript', $scriptProperties);

/**
 * Set which attributes get stripped from an entry's content. <br />
 * The default set of attributes is stored in the property SimplePie→strip_attributes,
 * not to be confused with the method SimplePie→strip_attributes().
 * This way, you can modify the existing list without having to create a whole new one.
 * @link http://simplepie.org/wiki/reference/simplepie/strip_attributes
 */
$scriptProperties['stripAttributes'] = array();
$stripAttributes = $modx->getOption('stripAttributes', $scriptProperties);
if (!empty($stripAttributes)) {
    $scriptProperties['stripAttributes'] = @explode(',', $stripAttributes);
    foreach ($scriptProperties['stripAttributes'] as $k => $v) {
        $scriptProperties['stripAttributes'][$k] = trim($v);
    }
}

/**
 * Set whether to strip out HTML comments from an entry's content.
 * @link http://simplepie.org/wiki/reference/simplepie/strip_comments
 */
$scriptProperties['stripComments'] = !empty($scriptProperties['stripComments']) && $scriptProperties['stripComments'] == '1' ? 'true' : null;
$scriptProperties['stripComments'] = $modx->getOption('stripComments', $scriptProperties);

/**
 * Set which HTML tags get stripped from an entry's content. <br />
 * The default set of tags is stored in the property SimplePie→strip_htmltags,
 * not to be confused with the method SimplePie→strip_htmltags().
 * This way, you can modify the existing list without having to create a whole new one.
 * @link http://simplepie.org/wiki/reference/simplepie/strip_htmltags
 */
$stripHtmlTags = $modx->getOption('stripHtmlTags', $scriptProperties);
$scriptProperties['stripHtmlTags'] = array();
if (!empty($stripHtmlTags)) {
    $scriptProperties['stripHtmlTags'] = @explode(',', $stripHtmlTags);
    foreach ($scriptProperties['stripHtmlTags'] as $k => $v) {
        $scriptProperties['stripHtmlTags'][$k] = trim($v);
    }
}

/**
 * Date format supports anything that works with PHP's date() function.
 * Only supports the English language
 * @link http://simplepie.org/wiki/reference/simplepie_item/get_date
 */
$scriptProperties['dateFormat'] = $modx->getOption('dateFormat', $scriptProperties);

/**
 * Returns the date/timestamp of the posting in the localized language.
 * Date format supports anything that works with PHP's strftime() function.
 * To display in other languages, you need to change the locale with PHP's setlocale() function.
 * The available localizations depend on which ones are installed on your web server.
 * @link http://simplepie.org/wiki/reference/simplepie_item/get_local_date
 */
$scriptProperties['localDateFormat'] = $modx->getOption('localDateFormat', $scriptProperties);

/**
 * Returns an array of SimplePie_Item references for each item in the feed, which can be looped through.
 * @link http://simplepie.org/wiki/reference/simplepie/get_items
 */
$scriptProperties['getItemStart'] = $modx->getOption('getItemStart', $scriptProperties);
$scriptProperties['getItemLength'] = $modx->getOption('getItemLength', $scriptProperties);

/**
 * If cURL is available, SimplePie will use it instead of the built-in fsockopen functions for fetching remote feeds.
 * This config option will force SimplePie to use fsockopen even if cURL is installed.
 * @link http://simplepie.org/wiki/reference/simplepie/force_fsockopen
 */
$scriptProperties['forceFSockopen'] = !empty($scriptProperties['forceFSockopen']) && $scriptProperties['forceFSockopen'] == '0' ? 0 : 1;
$scriptProperties['forceFSockopen'] = $modx->getOption('forceFSockopen', $scriptProperties);

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
$scriptProperties['setInputEncoding'] = $modx->getOption('setInputEncoding', $scriptProperties);

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
$scriptProperties['setOutputEncoding'] = $modx->getOption('setOutputEncoding', $scriptProperties);

$scriptProperties['sortBy'] = $modx->getOption('sortBy', $scriptProperties, 'date');
$scriptProperties['sortOrder'] = !empty($scriptProperties['sortOrder']) && strtoupper($scriptProperties['sortOrder']) == 'ASC' ? 'ASC' : 'DESC';
$scriptProperties['sortOrder'] = $modx->getOption('sortOrder', $scriptProperties);

/**
 * Templates
 */
$scriptProperties['tpl'] = $modx->getOption('tpl', $scriptProperties);
$scriptProperties['tplPath'] = MODX_BASE_PATH . $modx->getOption('tplPath', $scriptProperties);
$scriptProperties['tplFile'] = $modx->getOption('tplFile', $scriptProperties);
$scriptProperties['tplFilePath'] = $scriptProperties['tplPath'] . $scriptProperties['tplFile'];
$scriptProperties['firstRowCls'] = $modx->getOption('firstRowCls', $scriptProperties);
$scriptProperties['lastRowCls'] = $modx->getOption('lastRowCls', $scriptProperties);
$scriptProperties['rowCls'] = $modx->getOption('rowCls', $scriptProperties);
$scriptProperties['oddRowCls'] = $modx->getOption('oddRowCls', $scriptProperties);

################################################################################

$scriptPropertiesModxClassFile = MODX_CORE_PATH . 'components/spiefeed/model/spiefeed.class.php';
$scriptProperties['simplePieClassFile'] = MODX_CORE_PATH . 'components/spiefeed/includes/simplepie/simplepie_1.3.mini.php';

$output = '';

if (!class_exists('SimplePieModx')) {
    if (!file_exists($scriptPropertiesModxClassFile)) {
        return 'File ' . $scriptPropertiesModxClassFile . ' does not exist.';
    } else {
        include_once $scriptPropertiesModxClassFile;
    }
}

ob_start();
$simplePieModx = new SimplePieModx($modx);
$simplePieModx->setConfigs($scriptProperties);
$phs = $simplePieModx->getPlaceholders();

$error = $simplePieModx->getError();
if (!empty($error)) {
    $modx->log(modX::LOG_LEVEL_ERROR, '[spieFeed] ' . $error);
}

if (!empty($phs['err'])) {
    echo $phs['err'];
} else {
    $feeds = $simplePieModx->sortPlaceholders($phs['suc']);
    if ($feeds === false) {
        return '';
    }
    if (!empty($toArray)) {
        echo '<pre>' . print_r($feeds, 1) . '</pre>';
    } else {
        echo $simplePieModx->fetchTpl($feeds);
    }
}

$output = ob_get_contents();
ob_end_clean();

if (!empty($output)) {
    $scriptProperties['css'] = $modx->getOption('css', $scriptProperties);
    if ($scriptProperties['css'] != 'disabled') {
        $modx->regClientCSS($scriptProperties['css'], 'screen');
    }
    if (!empty($toPlaceholder)) {
        $output = $modx->setPlaceholder($toPlaceholder, $output);
    }
} else {
    if (!empty($emptyMessage)) {
        $output = $emptyMessage;
    }
}


return $output;

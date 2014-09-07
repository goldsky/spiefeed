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
 * @link http://simplepie.org/
 */

/**
 * @author goldsky <goldsky@modx-id.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @package spieFeed
 * @subpackage class
 */
class SimplePieModx {

    /**
     * @var MODx's object reference
     */
    public $modx;

    /**
     * @var mixed   snippet call's parameters
     */
    public $spie = null;

    private $_error;

    /**
     * Initiating MODx's object inside the class
     * @param mixed $modx  MODx's object
     */
    public function __construct(&$modx) {
        $this->modx = & $modx;
    }

    /**
     * Sets the parameters
     * @param   array   $spie   $scriptProperties
     */
    public function setConfigs(&$spie) {
        $this->spie = & $spie;
    }

    /**
     * Processing the parameters into placeholders
     * @return array    placeholders
     */
    public function getPlaceholders() {
        $res = array();
        $res['err'] = '';
        $res['suc'] = '';
        /**
         * @link http://github.com/simplepie/simplepie/tree/one-dot-two
         */
        if (!file_exists($this->spie['simplePieClassFile'])) {
            $res['err'] = 'File ' . $this->spie['simplePieClassFile'] . ' does not exist.';
            return $res;
        }

        include_once $this->spie['simplePieClassFile'];
        $feed = new SimplePie();
        $joinKey = 0;
        $phArray = array();

        foreach ($this->spie['setFeedUrl'] as $setFeedUrl) {
            $feed->set_cache_location($this->spie['setCacheLocation']);
            $feed->set_feed_url($setFeedUrl);

            if (isset($this->spie['setInputEncoding'])) {
                $feed->set_input_encoding($this->spie['setInputEncoding']);
            }
            if (isset($this->spie['setOutputEncoding'])) {
                $feed->set_output_encoding($this->spie['setOutputEncoding']);
            }
            // if no cURL, try fsockopen
            if ($this->spie['forceFSockopen']) {
                $feed->force_fsockopen(true);
            }
            if (isset($this->spie['enableCache']))
                $feed->enable_cache($this->spie['enableCache']);
            if (isset($this->spie['enableOrderByDate']))
                $feed->enable_order_by_date($this->spie['enableOrderByDate']);
            if (isset($this->spie['setCacheDuration']))
                $feed->set_cache_duration($this->spie['setCacheDuration']);
            if (!empty($this->spie['setImageHandler'])) {
                // handler_image.php?image=67d5fa9a87bad230fb03ea68b9f71090
                $feed->set_image_handler($this->spie['setImageHandler'][0], $this->spie['setImageHandler'][1]);
            }

            // disabled since these are all splitted into a single fetching
            // it's  been used with different way, see below looping
            if (isset($this->spie['setItemLimit']))
                $feed->set_item_limit((int) $this->spie['setItemLimit']);

            if (isset($this->spie['setJavascript']))
                $feed->set_javascript($this->spie['setJavascript']);
            if (isset($this->spie['stripAttributes']))
                $feed->strip_attributes(array_merge($feed->strip_attributes, $this->spie['stripAttributes']));
            if (isset($this->spie['stripComments']))
                $feed->strip_comments($this->spie['stripComments']);
            if (isset($this->spie['stripHtmlTags']))
                $feed->strip_htmltags(array_merge($feed->strip_htmltags, $this->spie['stripHtmlTags']));

            /**
             * Initiating the Feeding.
             * This always be placed AFTER all the settings above.
             */
            if (!$feed->init()) {
                if (!empty($this->spie['debug'])) {
                    $res['err'] = $feed->error();
                }
                return $res;
            }

            $feed->handle_content_type();

            $feedItems = $feed->get_items($this->spie['getItemStart'], $this->spie['getItemLength']);
            if (empty($feedItems)) {
                continue;
            }
            foreach ($feedItems as $item) {
                $phArray[$joinKey]['link'] = $item->get_link();
                $phArray[$joinKey]['title'] = $item->get_title();
                $phArray[$joinKey]['description'] = $item->get_description();
                $phArray[$joinKey]['content'] = $item->get_content();

                $phArray[$joinKey]['permalink'] = $item->get_permalink();
                $parsedUrl = parse_url($phArray[$joinKey]['permalink']);
                $implodedParsedUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                $imageLink = $feed->get_image_link() != '' ? $feed->get_image_link() : $implodedParsedUrl;
                $phArray[$joinKey]['imageLink'] = $imageLink;

                $phArray[$joinKey]['imageTitle'] = $feed->get_image_title();
                $phArray[$joinKey]['imageUrl'] = $feed->get_image_url();
                $phArray[$joinKey]['imageWidth'] = $feed->get_image_width();
                $phArray[$joinKey]['imageHeight'] = $feed->get_image_height();

                $phArray[$joinKey]['date'] = $item->get_date($this->spie['dateFormat']);
                $phArray[$joinKey]['localDate'] = $item->get_local_date($this->spie['localDateFormat']);
                $phArray[$joinKey]['copyright'] = $item->get_copyright();

                $phArray[$joinKey]['latitude'] = $feed->get_latitude();
                $phArray[$joinKey]['longitude'] = $feed->get_longitude();

                $phArray[$joinKey]['language'] = $feed->get_language();
                $phArray[$joinKey]['encoding'] = $feed->get_encoding();

                if ($item->get_authors()) {
                    foreach ($item->get_authors() as $authorObject) {
                        $authorName = $authorObject->get_name();
                        $authorLink = $authorObject->get_link();
                        $authorEmail = $authorObject->get_email();
                    }
                    $phArray[$joinKey]['authorName'] = $authorName;
                    $phArray[$joinKey]['authorLink'] = $authorLink;
                    $phArray[$joinKey]['authorEmail'] = $authorEmail;
                }

                $category = $item->get_category();
                if ($category) {
                    $phArray[$joinKey]['category'] = htmlspecialchars_decode($category->get_label(), ENT_QUOTES);
                }

                $contributor = $item->get_contributor();
                $phArray[$joinKey]['contributor'] = '';
                if ($contributor) {
                    $phArray[$joinKey]['contributor'] = $contributor->get_name();
                }

                if ($feed->get_type() && SIMPLEPIE_TYPE_NONE) {
                    $phArray[$joinKey]['getType'] = 'Unknown';
                } elseif ($feed->get_type() && SIMPLEPIE_TYPE_RSS_ALL) {
                    $phArray[$joinKey]['getType'] = 'RSS';
                } elseif ($feed->get_type() && SIMPLEPIE_TYPE_ATOM_ALL) {
                    $phArray[$joinKey]['getType'] = 'Atom';
                } elseif ($feed->get_type() && SIMPLEPIE_TYPE_ALL) {
                    $phArray[$joinKey]['getType'] = 'Supported';
                }

                // Media from Flickr RSS stream
                if ($enclosure = $item->get_enclosure()) {
                    $phArray[$joinKey]['itemImageThumbnailUrl'] = $enclosure->get_link();
                    $phArray[$joinKey]['itemImageWidth'] = $enclosure->get_width();
                    $phArray[$joinKey]['itemImageHeight'] = $enclosure->get_height();
                }

                $joinKey++;
            } // foreach ($feed->get_items($getItemStart, $getItemEnd) as $item)
        } // foreach ($this->spie['setFeedUrl'] as $setFeedUrl)

        if (0 === $joinKey && isset($this->spie['emptyMessage'])) {
            $res['err'] = $this->spie['emptyMessage'];
        } else {
            $res['suc'] = $this->_filterModxTags($phArray);
        }
        if ($feed->error()) {
            $this->_error = $feed->error();
        }
        return $res;
    }

    /**
     * Sorting by key/placeholder.
     * This ignores the simplepie's enable_order_by_date(),
     * to adjust multiple feeds.
     * @link http://simplepie.org/wiki/reference/simplepie/enable_order_by_date
     */
    public function sortPlaceholders($phs) {
        if (empty($phs)) {
            return FALSE;
        }
        $sortByArray = array();
        foreach ($phs as $k => $v) {
            if ('date' == strtolower($this->spie['sortBy'])) {
                $sortByArray[strtotime($v['date'])][] = $v;
            } elseif ('localdate' == strtolower($this->spie['sortBy'])) {
                $sortByArray[strtotime($v['localDate'])][] = $v;
            } else {
                $sortByArray[$v[$this->spie['sortBy']]][] = $v;
            }
        }
        $phs = array();
        unset($phs);

        if ('asc' == strtolower($this->spie['sortOrder'])) {
            ksort($sortByArray);
        } else {
            krsort($sortByArray);
        }

        $pushedArray = array();
        foreach ($sortByArray as $k => $v) {
            foreach ($v as $vv) {
                $pushedArray[] = $vv;
            }
        }

        // if the result number is limited
        $countLimit = 0;
        if ($this->spie['setItemLimit'] > 0) {
            foreach ($pushedArray as $k => $v) {
                if ($this->spie['setItemLimit'] == $countLimit)
                    break;
                $limitedItems[$k] = $v;
                $countLimit++;
            }
            // overide the previous value;
            $pushedArray = $limitedItems;
        }

        return $pushedArray;
    }

    /**
     * Initiating the templates.
     * @param string    $phs    placeholders
     * @return string   parsed result;
     */
    public function fetchTpl($phs) {
        $countPlacehoders = count($phs);
        $i = 0;
        $output = '';
        foreach ($phs as $v) {
            $i++;

            if (intval(0) === $i % 2) {
                $v['feedClass'] = $this->spie['rowCls'];
            } else {
                $v['feedClass'] = $this->spie['oddRowCls'];
            }
            if (intval(1) === $i) {
                $v['feedClass'] .= ' ' . $this->spie['firstRowCls'];
            } elseif ($i == $countPlacehoders) {
                $v['feedClass'] .= ' ' . $this->spie['lastRowCls'];
            }

            $getChunk = $this->modx->getChunk($this->spie['tpl']);
            if (!empty($getChunk)) {
                $output .= $this->modx->getChunk($this->spie['tpl'], $v);
            } else {
                $output .= $this->_fetchTplFile('tpl', $this->spie['tplFilePath'], $v);
            }
        }
        return $output;
    }

    private function _fetchTplFile($name, $filePath, $params) {
        $chunk = false;
        if (!file_exists($filePath)) {
            echo __LINE__ . ': Missing tempate file: ' . $filePath . '<br />';
            return FALSE;
        } else {
            $o = file_get_contents($filePath);
            $chunk = $this->modx->newObject('modChunk');
            $chunk->set('name', $name);
            $chunk->setContent($o);
            return $chunk->process($params);
        }
        return FALSE;
    }

    /**
     * Replace tags
     * @param array     $sources    parsed template
     * @param array     $filters    custom filters
     * @return array    parsed tags
     */
    private function _filterModxTags($sources = array(), array $filters = array()) {
        if (empty($filters)) {
            $filters = array(
                '[[' => '&#91;&#91;',
                ']]' => '&#93;&#93;'
            );
        }

        $countSources = count($sources);
        for ($i = 0; $i < $countSources; $i++) {
            foreach ($filters as $search => $replace) {
                $sources[$i] = str_replace($search, $replace, $sources[$i]);
            }
        }

        return $sources;
    }

    public function getError() {
        return $this->_error;
    }
}

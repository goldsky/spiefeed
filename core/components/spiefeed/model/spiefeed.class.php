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

    /**
     * Initiating MODx's object inside the class
     * @param mixed $modx  MODx's object
     */
    public function __construct(&$modx) {
        $this->modx = & $modx;
    }

    /**
     * Essambles the parameters to be sorted into the placeholders and
     * returns them inside a template.
     * @param string    $spie   snippet parameters
     * @return string   final output
     */
    public function spieModx($spie) {
        $placeholders = $this->_setSimplePieModxPlaceholders($spie);
        if (FALSE === $placeholders)
            return FALSE;

        $sortedPlaceholders = $this->_sortFeeds($placeholders, $spie['sortBy'], $spie['sortOrder']);
        return $this->fetchTpl($sortedPlaceholders, $spie);
    }

    /**
     * Processing the parameters into placeholders
     * @param string    $spie   snippet parameters
     * @return array    placeholders
     */
    private function _setSimplePieModxPlaceholders($spie) {
        /**
         * @link http://github.com/simplepie/simplepie/tree/one-dot-two
         */
        if (!file_exists($spie['simplePieClassFile'])) {
            return 'File ' . $spie['simplePieClassFile'] . ' does not exist.';
        }
        include_once $spie['simplePieClassFile'];
        $feed = new SimplePie();
        $joinKey = 0;
        foreach ($spie['setFeedUrl'] as $setFeedUrl) {
            $feed->set_cache_location($spie['setCacheLocation']);
            $feed->set_feed_url($setFeedUrl);

            if (isset($spie['setInputEncoding'])) {
                $feed->set_input_encoding($spie['setInputEncoding']);
            }
            if (isset($spie['setOutputEncoding'])) {
                $feed->set_output_encoding($spie['setOutputEncoding']);
            }
            // if no cURL, try fsockopen
            if (isset($spie['forceFSockopen'])) {
                $feed->force_fsockopen(true);
            }
            if (isset($spie['enableCache']))
                $feed->enable_cache($spie['enableCache']);
            if (isset($spie['enableOrderByDate']))
                $feed->enable_order_by_date($spie['enableOrderByDate']);
            if (isset($spie['setCacheDuration']))
                $feed->set_cache_duration($spie['setCacheDuration']);
            if (!empty($spie['setFaviconHandler']))
                $feed->set_favicon_handler($spie['setFaviconHandler'][0], $spie['setFaviconHandler'][1]);
            if (!empty($spie['setImageHandler'])) {
                // handler_image.php?image=67d5fa9a87bad230fb03ea68b9f71090
                $feed->set_image_handler($spie['setImageHandler'][0], $spie['setImageHandler'][1]);
            }

            // disabled since these are all splitted into a single fetching
            // it's  been used with different way, see below looping
//            if (isset($spie['setItemLimit']))
//                $feed->set_item_limit((int) $spie['setItemLimit']);

            if (isset($spie['setJavascript']))
                $feed->set_javascript($spie['setJavascript']);
            if (isset($spie['stripAttributes']))
                $feed->strip_attributes(array_merge($feed->strip_attributes, $spie['stripAttributes']));
            if (isset($spie['stripComments']))
                $feed->strip_comments($spie['stripComments']);
            if (isset($spie['stripHtmlTags']))
                $feed->strip_htmltags(array_merge($feed->strip_htmltags, $spie['stripHtmlTags']));

            /**
             * Initiating the Feeding.
             * This always be placed AFTER all the settings above.
             */
            if (!$feed->init()) {
                echo $feed->error();
                return FALSE;
            }

            $countItems = count($feed->get_items());
            if (1 > $countItems) {
                continue;
            }

            $feed->handle_content_type();

            $countLimit = 0;
            foreach ($feed->get_items($getItemStart, $getItemEnd) as $item) {

                if (isset($spie['setItemLimit']) && $spie['setItemLimit'] == $countLimit)
                    continue;

                $phArray[$joinKey]['favicon'] = $feed->get_favicon();
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

                $phArray[$joinKey]['date'] = $item->get_date($spie['dateFormat']);
                $phArray[$joinKey]['localDate'] = $item->get_local_date($spie['localDateFormat']);
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

                if ($feed->get_type() & SIMPLEPIE_TYPE_NONE) {
                    $phArray[$joinKey]['getType'] = 'Unknown';
                } elseif ($feed->get_type() & SIMPLEPIE_TYPE_RSS_ALL) {
                    $phArray[$joinKey]['getType'] = 'RSS';
                } elseif ($feed->get_type() & SIMPLEPIE_TYPE_ATOM_ALL) {
                    $phArray[$joinKey]['getType'] = 'Atom';
                } elseif ($feed->get_type() & SIMPLEPIE_TYPE_ALL) {
                    $phArray[$joinKey]['getType'] = 'Supported';
                }
				
				// Media from Flickr RSS stream
				if ($enclosure = $item->get_enclosure()) {
						$phArray[$joinKey]['itemImageThumbnailUrl'] = $enclosure->get_thumbnail();
						$phArray[$joinKey]['itemImageWidth'] = $enclosure->get_width();
						$phArray[$joinKey]['itemImageHeight'] = $enclosure->get_height();
				}
				

                $countLimit++;
                $joinKey++;
            } // foreach ($feed->get_items($getItemStart, $getItemEnd) as $item)
        } // foreach ($spie['setFeedUrl'] as $setFeedUrl)
        return $this->_filterModxTags($phArray);
    }

    /**
     * Sorting by keys.
     * This ignores the simplepie's enable_order_by_date(),
     * to adjust multiple feeds.
     * @link http://simplepie.org/wiki/reference/simplepie/enable_order_by_date
     */
    private function _sortFeeds($feeds, $sortBy, $sortOrder) {
        foreach ($feeds as $k => $v) {
            if ('date' == strtolower($sortBy)) {
                $sortByArray[strtotime($v['date'])] = $v;
            } elseif ('localdate' == strtolower($sortBy)) {
                $sortByArray[strtotime($v['localDate'])] = $v;
            } else {
                $sortByArray[$v[$sortBy]] = $v;
            }
        }
        $feeds = array();
        unset($feeds);

        if ('ASC' == $sortOrder) {
            ksort($sortByArray);
        } else {
            krsort($sortByArray);
        }

        return $sortByArray;
    }

    /**
     * Initiating the templates.
     * @param string    $placehoders    placeholders
     * @return string   templated result;
     */
    public function fetchTpl($placehoders, $tpls) {
        $countPlacehoders = count($placehoders);
        $i = 0;
        $output = '';
        $chunk = null;

        foreach ($placehoders as $v) {
            $i++;

            if (intval(0) === $i % 2) {
                $v['feedClass'] = $tpls['rowCls'];
            } else {
                $v['feedClass'] = $tpls['oddRowCls'];
            }
            if (intval(1) === $i) {
                $v['feedClass'] .= ' ' . $tpls['firstRowCls'];
            } elseif ($i == $countPlacehoders) {
                $v['feedClass'] .= ' ' . $tpls['lastRowCls'];
            }

            $getChunk = $this->modx->getChunk($tpls['tpl']);
            if (!empty($getChunk)) {
                $output .= $this->modx->getChunk($tpls['tpl'], $v);
            } else {
                $output .= $this->_fetchTplFile('tpl', $tpls['tplFilePath'], $v);
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

    private function _filterModxTags($sources=array(), array $filters= array()) {
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

}
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
 * @subpackage class
 * @link http://simplepie.org/
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
    public function __construct(&$modx, $spie) {
        $this->modx = & $modx;
        $this->spie = $spie;
    }

    /**
     * Essambles the parameters to be sorted into the placeholders and
     * returns them inside a template.
     * @param string    $spie   snippet parameters
     * @return string   final output
     */
    public function spieModx($spie) {
        $placeholders = $this->setSimplePieModxPlaceholders($spie);
        if (!$placeholders)
            return FALSE;

        $sortedPlaceholders = $this->sortFeeds($placeholders, $spie['sortBy'], $spie['sortOrder']);
        return $this->fetchTpl($sortedPlaceholders, $spie);
    }

    /**
     * Processing the parameters into placeholders
     * @param string    $spie   snippet parameters
     * @return array    placeholders
     */
    public function setSimplePieModxPlaceholders($spie) {
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
                $this->modx->log(
                        modX::LOG_LEVEL_ERROR
                        , "Error parsing RSS feed at {$setFeedUrl}"
                        , ''
                        , 'simplepie'
                        , __FILE__
                        , __LINE__
                );
                $phArray[$joinKey] = $feed->error();
                continue;
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

                $phArray[$joinKey]['permalink'] = $item->get_permalink();
                $parsedUrl = parse_url($phArray[$joinKey]['permalink']);
                $implodedParsedUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'];
                $imageLink = $feed->get_image_link() != '' ? $feed->get_image_link() : $implodedParsedUrl;
                $phArray[$joinKey]['imageLink'] = $imageLink;

                $phArray[$joinKey]['imageTitle'] = $feed->get_image_title();
                $phArray[$joinKey]['imageUrl'] = $feed->get_image_url();
                $phArray[$joinKey]['imageWidth'] = $feed->get_image_width();
                $phArray[$joinKey]['imageHeight'] = $feed->get_image_height();

                $phArray[$joinKey]['date'] = $item->get_date($dateFormat);
                $phArray[$joinKey]['localDate'] = $item->get_local_date($localDateFormat);
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
                    $phArray[$joinKey]['category'] = $category->get_label();
                }

                $contributor = $item->get_contributor();
                if ($contributor) {
                    $phArray[$joinKey]['contributor'] = $contributor->get_name();
                } else {
                    $phArray[$joinKey]['contributor'] = '';
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

                $countLimit++;
                $joinKey++;
            } // foreach ($feed->get_items($getItemStart, $getItemEnd) as $item)
        } // foreach ($spie['setFeedUrl'] as $setFeedUrl)
        return $phArray;
    }

    /**
     * Sorting by keys.
     * This ignores the simplepie's enable_order_by_date(),
     * to adjust multiple feeds.
     * @link http://simplepie.org/wiki/reference/simplepie/enable_order_by_date
     */
    public function sortFeeds($feeds, $sortBy, $sortOrder) {
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

            if (0 === $i % 2) {
                $v['feedClass'] = $tpls['rowCls'];
            } else {
                $v['feedClass'] = $tpls['oddRowCls'];
            }
            if (1 === $i) {
                $v['feedClass'] .= ' ' . $tpls['firstRowCls'];
            } elseif ($i == $countPlacehoders) {
                $v['feedClass'] .= ' ' . $tpls['lastRowCls'];
            }

            $getChunk = $this->modx->getChunk($tpls['tpl']);
            if ($getChunk != '') {
                $output .= $this->modx->getChunk($tpls['tpl'], $v);
            } else {
                $output .= $this->_fetchTplFile('defaultSpieTpl', $tpls['tplFile'], $v);
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

}
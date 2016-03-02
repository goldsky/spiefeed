<?php

/**
 * @package     spiefeed
 * @subpackage  build
 */

$snippets = array();

$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'spieFeed',
    'description' => 'Feeds aggregator based on SimplePie.',
    'snippet' => 'return include MODX_CORE_PATH . \'components/spiefeed/elements/snippets/spiefeed.snippet.php\';',
        ), '', true, true);
$properties = include $sources['data'] . 'properties/properties.spiefeed.php';
$snippets[0]->setProperties($properties);
unset($properties);

return $snippets;
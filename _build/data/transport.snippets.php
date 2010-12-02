<?php

/**
 * @package login
 * @subpackage build
 */
function getSnippetContent($filename) {
    $o = file_get_contents($filename);
    $o = str_replace('<?php', '', $o);
    $o = str_replace('?>', '', $o);
    $o = trim($o);
    return $o;
}

$snippets = array();

$snippets[0] = $modx->newObject('modSnippet');
$snippets[0]->fromArray(array(
    'id' => 0,
    'name' => 'spieFeed',
    'description' => 'Feeds aggregator based on SimplePie.',
    'snippet' => getSnippetContent($sources['source_core'] . '/elements/snippets/spiefeed.snippet.php'),
        ), '', true, true);
$properties = include $sources['data'] . 'properties/properties.spiefeed.php';
$snippets[0]->setProperties($properties);
unset($properties);

return $snippets;
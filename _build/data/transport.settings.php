<?php
/**
 * @package spiefeed
 * @subpackage build
 */
$settings = array();

$settings['spiefeed.defaultSpieFeedTplPath']= $modx->newObject('modSystemSetting');
$settings['spiefeed.defaultSpieFeedTplPath']->fromArray(array(
    'key' => 'spiefeed.defaultSpieFeedTplPath',
    'value' => '{core_path}components/spiefeed/elements/chunks/',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

$settings['spiefeed.defaultSpieFeedTpl']= $modx->newObject('modSystemSetting');
$settings['spiefeed.defaultSpieFeedTpl']->fromArray(array(
    'key' => 'spiefeed.defaultSpieFeedTpl',
    'value' => 'default-spiefeed.chunk.tpl',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

return $settings;
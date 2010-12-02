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

$settings['spiefeed.css']= $modx->newObject('modSystemSetting');
$settings['spiefeed.css']->fromArray(array(
    'key' => 'spiefeed.css',
    'value' => MODX_ASSETS_URL . 'components/spiefeed/templates/css/spiefeed.css',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

$settings['spiefeed.firstRowCls']= $modx->newObject('modSystemSetting');
$settings['spiefeed.firstRowCls']->fromArray(array(
    'key' => 'spiefeed.firstRowCls',
    'value' => 'spie-first-row',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

$settings['spiefeed.lastRowCls']= $modx->newObject('modSystemSetting');
$settings['spiefeed.lastRowCls']->fromArray(array(
    'key' => 'spiefeed.lastRowCls',
    'value' => 'spie-last-row',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

$settings['spiefeed.rowCls']= $modx->newObject('modSystemSetting');
$settings['spiefeed.rowCls']->fromArray(array(
    'key' => 'spiefeed.rowCls',
    'value' => 'spie-row',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

$settings['spiefeed.rowCls']= $modx->newObject('modSystemSetting');
$settings['spiefeed.rowCls']->fromArray(array(
    'key' => 'spiefeed.oddRowCls',
    'value' => 'spie-odd-row',
    'xtype' => 'textfield',
    'namespace' => 'spiefeed',
    'area' => 'spieFeed',
),'',true,true);

return $settings;
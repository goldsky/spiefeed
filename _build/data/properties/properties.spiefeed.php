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
 * Default spieFeed snippet properties
 *
 * @author goldsky <goldsky@modx-id.com>
 * @license http://www.gnu.org/licenses/gpl-3.0.html
 * @package spieFeed
 * @subpackage build
 */
$properties = array(
    array(
        'name' => 'tpl',
        'desc' => 'spiefeed.tpl',
        'type' => 'textfield',
        'options' => '',
        'value' => 'defaultSpieFeedTpl',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'tplPath',
        'desc' => 'spiefeed.tplPath',
        'type' => 'textfield',
        'options' => '',
        'value' => MODX_CORE_PATH . 'components/spiefeed/elements/chunks/',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'tplFile',
        'desc' => 'spiefeed.tplFile',
        'type' => 'textfield',
        'options' => '',
        'value' => 'default-spiefeed.chunk.tpl',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'css',
        'desc' => 'spiefeed.css',
        'type' => 'textfield',
        'options' => '',
        'value' =>  MODX_ASSETS_URL . 'components/spiefeed/templates/css/spiefeed.css',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'firstRowCls',
        'desc' => 'spiefeed.firstRowCls',
        'type' => 'textfield',
        'options' => '',
        'value' =>  'spie-first-row',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'lastRowCls',
        'desc' => 'spiefeed.lastRowCls',
        'type' => 'textfield',
        'options' => '',
        'value' =>  'spie-last-row',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'rowCls',
        'desc' => 'spiefeed.rowCls',
        'type' => 'textfield',
        'options' => '',
        'value' =>  'spie-row',
        'lexicon' => 'spiefeed:properties',
    ),
    array(
        'name' => 'oddRowCls',
        'desc' => 'spiefeed.oddRowCls',
        'type' => 'textfield',
        'options' => '',
        'value' =>  'spie-odd-row',
        'lexicon' => 'spiefeed:properties',
    ),
);

return $properties;
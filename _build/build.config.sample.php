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
 * @package spieFeed
 */
/* define the MODX path constants necessary for core installation */
if (!defined('MODX_BASE_PATH'))
    define('MODX_BASE_PATH', dirname(dirname(__FILE__)) . '/');
if (!defined('MODX_CORE_PATH'))
    define('MODX_CORE_PATH', MODX_BASE_PATH . 'core/');
if (!defined('MODX_MANAGER_PATH'))
    define('MODX_MANAGER_PATH', MODX_BASE_PATH . 'manager/');
if (!defined('MODX_CONNECTORS_PATH'))
    define('MODX_CONNECTORS_PATH', MODX_BASE_PATH . 'connectors/');
if (!defined('MODX_ASSETS_PATH'))
    define('MODX_ASSETS_PATH', MODX_BASE_PATH . 'assets/');

if (!defined('MODX_BASE_URL'))
    define('MODX_BASE_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/');
if (!defined('MODX_CORE_URL'))
    define('MODX_CORE_URL', MODX_BASE_URL . 'core/');
if (!defined('MODX_MANAGER_URL'))
    define('MODX_MANAGER_URL', MODX_BASE_URL . 'manager/');
if (!defined('MODX_CONNECTORS_URL'))
    define('MODX_CONNECTORS_URL', MODX_BASE_URL . 'connectors/');
if (!defined('MODX_ASSETS_URL'))
    define('MODX_ASSETS_URL', MODX_BASE_URL . 'assets/');
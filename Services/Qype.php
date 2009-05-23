<?php
/**
 * Copyright (c) 2008-2009, Till Klampaeckel
 * 
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * 
 *  * Redistributions of source code must retain the above copyright notice, this
 *    list of conditions and the following disclaimer.
 *  * Redistributions in binary form must reproduce the above copyright notice, this
 *    list of conditions and the following disclaimer in the documentation and/or
 *    other materials provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 * PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF
 * LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP Version 5
 *
 * @category Services
 * @package  Services_Qype
 * @author   Till Klampaeckel <till@php.net>
 * @license  http://www.opensource.org/licenses/bsd-license.php The BSD License
 * @version  GIT: $Id$
 * @link     http://github.com/till/services_qype/tree/master
 */

$libPath = dirname(dirname(__FILE__));
set_include_path($libPath . PATH_SEPARATOR . get_include_path());

function __autoload($className) {
    include str_replace('_', '/', $className) . '.php';
}

/**
 * Services_Qype - A quick test! :-)
 */
class Services_Qype
{
    protected $version;
    protected $stack;
    protected $client;
    protected $consumer;
    
    protected $language = array(
        'content' => 'en',
        'ui'      => 'en_GB',
    );
    
    /**
     * CTR
     * 
     * @param $version Object[optional]
     * 
     * @return Services_Qype
     */
    public function __construct($appKey, $appSecret, OAuthConsumer $consumer = null, $version = 1)
    {
        if ($consumer == null) {
            $this->consumer = new OAuthConsumer($appKey, $appSecret, 1);
        }
        $this->version = $version;
    }
    
    public function setClient(HTTP_Request2 $client)
    {
        $this->client = $client;
        return $this;
    }
    
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Usually, it's plenty to set the UI language.
     *
     * Set this if you want (e.g.) a Germany UI, and reviews in English.
     *
     * @param string $lang The language.
     *
     * @return Services_Qype
     * @throws Services_Qype_Exception In case of an unsupported language.
     */
    public function setContentLanguage($lang)
    {
        static $languages = array('de', 'en', 'fr', 'es', 'pt');

        if (!in_array($lang, $languages)) {
            throw new Services_Qype_Exception("Unsupported language: {$lang}.");
        }
        $this->language['content'] = $lang;
        return $this;
    }

    /**
     * UI language, is the language of the user interface. In differen to UI, on
     * Qype, there's also the content language.
     * (See {@link self::setContentLanguage()}).
     *
     * @param string $locale The language.
     *
     * @return Services_Qype
     * @throws Services_Qype_Exception
     */
    public function setUiLanguage($locale)
    {
        static $locales = array('de_DE', 'en_GB', 'fr_FR', 'es_ES', 'pt_BR');

        if (!in_array($locale, $locales)) {
            throw new Services_Qype_Exception("Unsupported locale: {$locale}");
        }

        $this->language['ui'] = $locale;
        return $this;
    }
    
    /**
     * __call
     * 
     * @param string $method Name of the driver.
     * @param mixed  $params n/a
     * 
     * @return Services_Qype_[Locator, Places]
     */
    public function __call($method, $params)
    {
        static $driver = array('categories', 'locator', 'places', 'reviews', 'tags', 'users');
        if (!in_array($method, $driver)) {
            throw new Services_Qype_Exception("Unknown feature: {$method}");
        }
        if (!isset($this->stack[$method])) {

            $className = "Services_Qype_" . ucwords($method);
            
            $this->stack[$method] = new $className(
                $this->language,
                $this->version
            );
            $this->stack[$method]->setConsumer($this->consumer);
            if (($this->client instanceof HTTP_Request2)) {
                $this->stack[$method]->setClient($this->client);
            }
        }
        return $this->stack[$method];
    }
}

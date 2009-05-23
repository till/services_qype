<?php
/**
 * Copyright (c) 2008, Till Klampaeckel
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

/**
 * Locators are the resources representing physical locations in the world like
 * cities and countries. Almost all resources of the API are in some way associated
 * with a locator. The locators are a hierarchical tree. You can look up locators by
 * name, but be aware that there might more than one result. For example there are
 * more than a hundred cities called 'Neustadt' in Germany but each of them has a
 * unique locator on Qype (identified with a domain_id).
 */
class Services_Qype_Locator extends Services_Qype_Common
{
    protected $fetchPlaces;
    protected $fetchReviews;
    protected $fetchOrder;
    protected $fetchCategory;
    
    public function getLocators($query = null)
    {
        $call = "/v{$this->version}/locators";
        if ($query != null) {
            $call .= '?show=' . urlencode($query);
        }
        $resp = $this->makeRequest($call);
        return $this->parseResponse($resp);
    }
    
    public function getLocator($id)
    {
        $call = "/v{$this->version}/locators/{$id}";
        if ($this->fetchPlaces === true) {
            $call .= "/places?";
            
            if ($this->fetchOrder !== null) {
                $call .= "&order={$this->fetchOrder}";
            }
            if ($this->fetchCategory !== null) {
                $call .= "&in_category={$this->fetchCategory}";
            }
        } elseif ($this->fetchReviews === true) {
            $call .= "/reviews";
        }
        $resp = $this->makeRequest($call);
        return $this->parseResponse($resp);
    }
    
    public function setFetchPlaces($flag = false, $order = null, $category = null)
    {
        static $qypeOrder = array('date_created', 'rating');
        
        $this->fetchPlaces = $flag;
        
        if ($order !== null) {
            if (!in_array($order, $qypeOrder)) {
                throw new Services_Qype_Exception("Unsupported, order: {$order}");
            }
            $this->fetchOrder = $order;
        }
        
        if ($category != null) {
            $this->fetchCategory = $category;
        }
        return $this;
    }
        
    public function setFetchReviews($flag = false)
    {
        $this->fetchReviews = true;
        return $this;
    }
}

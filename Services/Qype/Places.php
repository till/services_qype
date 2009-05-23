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

/**
 * Qype is all about places. A place can be a restaurant, pub, landmark -- pretty
 * much everything.
 */
class Services_Qype_Places extends Services_Qype_Common
{
    protected $fetchLocation;
    protected $fetchCategory;
    protected $fetchQuery;

    protected $latitude, $longitude;
    
    public function getPlaces($nearPosition = false)
    {
        $call = '';
        
        if ($nearPosition === false) {
            $call .= "/v{$this->version}/places?";
        } else {
            if ($this->latitude === null || $this->longitude === false) {
                throw new Services_Qype_Exception("Please use setGeoLocation()");
            }
            $call .= "/v{$this->version}/positions/";
            $call .= "{$this->latitude},{$this->longitude}";
            $call .= "/places?";
        }
        $call = $this->getQueryParams($call);

        $resp = $this->makeRequest($call);
        return $this->parseResponse($resp);
    }
    
    /**
     * Get all info on a place - optional reviews and assets.
     * 
     * @param int  $id      The place' id.
     * @param bool $reviews Yes/no.
     * @param bool $assets  Yes/no.
     * 
     * @return array
     * @todo   Refactor calls to reviews and assets.
     */
    public function getPlace($id, $reviews = false, $assets = false)
    {
        $call  = "/v{$this->version}/places/{$id}";
        $resp  = $this->makeRequest($call);
        $place = $this->parseResponse($resp);
        
        if ($reviews !== false) {
            $resp             = $this->makeRequest($call . '/reviews');
            $place['reviews'] = $this->parseResponse($resp);
        }
        
        if ($assets !== false) {
            $resp            = $this->makeRequest($call . '/assets');
            $place['assets'] = $this->parseResponse($resp);
        }
        
        return $place;
    }
    
    /**
     * Append query parameters to current call.
     * 
     * @param string $call The URI.
     * 
     * @return string
     * @see    self::$fetchCategory
     * @see    self::$fetchLocation
     * @see    self::$fetchQuery
     */
    protected function getQueryParams($call)
    {
        if ($this->fetchCategory !== null) {
            $call .= "&in_category={$this->fetchCategory}";
        }
        if ($this->fetchLocation !== null) {
            $call .= "&in={$location}";
        }
        if ($this->fetchQuery !== null) {
            $call .= "&show={$this->fetchQuery}";
        }
        return $call;
    }
    
    /**
     * Finding places in a category.
     * 
     * @param string $category Qype category.
     * 
     * @return Services_Qype_Places
     */
    public function setCategory($category)
    {
        $this->fetchCategory = $category;
        return $this;
    }
    
    /**
     * setGeoLocation - using latitude and longitude
     * 
     * @param float $latitude  Latitude
     * @param float $longitude Longitude
     * 
     * @return Services_Qype_Places
     * @todo   Validate input?
     * @see    self::setLocation()
     */
    public function setGeoLocation($latitude, $longitude)
    {
        $this->latitude  = $latitude;
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * Finding places by their name or a keyword.
     * 
     * @param $query Object
     * 
     * @return Services_Qype_Places
     */
    public function setQuery($query)
    {
        $this->fetchQuery = $query;
        return $this;
    }
    
    /**
     * Set a location - via string.
     *
     * @param string $location
     * 
     * @return Services_Qype_Places
     */
    public function setLocation($location)
    {
        $this->fetchLocation = $location;
        return $this;
    }
}

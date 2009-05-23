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
 * Locators are the resources representing physical locations in the world like
 * cities and countries. Almost all resources of the API are in some way associated
 * with a locator. The locators are a hierarchical tree. You can look up locators by
 * name, but be aware that there might more than one result. For example there are
 * more than a hundred cities called 'Neustadt' in Germany but each of them has a
 * unique locator on Qype (identified with a domain_id).
 */
class Services_Qype_Users extends Services_Qype_Common
{
    /**
     * Retrieve the details of a user on Qype. If $reviews is true, it will
     * return the reviews of the same user.
     *
     * @param string  $nickname The user's nickname on Qype.
     * @param boolean $reviews  Get the user's reviews.
     *
     * @return mixed
     * @see    self::getReviewsByUser()
     */
    public function getUser($nickname, $reviews = false)
    {
        if (empty($nickname)) {
            throw new Services_Qype_Exception('Please supply a nickname.');
        }
        $call  = "/v{$this->version}/users/";
        $call .= urlencode($nickname);
        if ($reviews === true) {
            $call .= '/reviews';
        }

        $resp = $this->makeRequest($call);
        return $this->parseResponse($resp);
    }

    /**
     * Get the reviews of a user.
     *
     * @param string $nickname The user's nickname on Qype.
     *
     * @return mixed
     * @uses   self::getUser()
     */
    public function getReviewsByUser($nickname)
    {
        return $this->getUser($nickname, true);
    }
}
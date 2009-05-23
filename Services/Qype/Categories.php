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
 * 
 */
class Services_Qype_Categories extends Services_Qype_Common
{
    /**
     * Return the top categories of Qype. If a category ID is supplied, we'll
     * return the designated category instead. With $children  true, you'll see
     * a category's subcategories.
     *
     * @param string $id The categories ID, optional.
     *
     * @return mixed
     * @throws Services_Qype_Exception On invalid argument combination.
     * @see    self::getChildren()
     */
    public function getCategories($id = null, $children = false)
    {
        $call  = "/v{$this->version}/place_categories";
        if ($id !== null) {
            $call .= '/' . $id;
            if ($children === true) {
                $call .= '/children';
            }
        } elseif ($children === true) {
            throw new Services_Qype_Exception('Cannot get children if no ID is supplied.');
        }

        $resp = $this->makeRequest($call);
        return $this->parseResponse($resp);
    }

    /**
     * Convenience method which wraps {@link self::getCategories()}.
     *
     * @param string $id The category's ID.
     *
     * @return mixed
     * @throws Services_Qype_Exception If no ID is supplied.
     * @uses   self::getCategories()
     */
    public function getCategory($id)
    {
        if (empty($id)) {
            throw new Services_Qype_Exception('Please supply a category ID.');
        }
        return $this->getCategories($id);
    }

    /**
     * Convenience method which wraps {@link self::getCategories()}.
     *
     * @param string $id The category's ID.
     *
     * @return mixed
     * @throws Services_Qype_Exception If no ID is supplied.
     * @uses   self::getCategories()
     */
    public function getChildren($id)
    {
        if (empty($id)) {
            throw new Services_Qype_Exception('Please supply a category ID.');
        }
        return $this->getCategories($id, true);
    }
}

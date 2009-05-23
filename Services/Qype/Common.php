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
 * Services_Qype_Common
 */
class Services_Qype_Common
{
    protected $version;
    protected $endpoint = 'api.qype.com';
    protected $client;
    protected $consumer;

    /**
     * CTR
     * 
     * @param int $version 1
     * 
     * @return Services_Qype_Common
     */
    public function __construct($version)
    {
        $this->version = $version;
    }
    
    /**
     * Inject a HTTP_Request2 into the class - for whatever reason.
     * 
     * @param HTTP_Request2 $client The client.
     * 
     * @return Services_Qype_Common
     */
    public function setClient(HTTP_Request2 $client)
    {
        $this->client = $client;
        return $this;
    }

    public function setConsumer(OAuthConsumer $consumer)
    {
        $this->consumer = $consumer;
        return $this;
    }
    
    /**
     * Make the request.
     * 
     * @param string $call A URL resource.
     * 
     * @return mixed
     * @throws Services_Qype_Exception
     * @uses   self::$endpoint
     * @uses   self::$client
     * @uses   HTTP_Request2
     */
    public function makeRequest($call, $body = null)
    {
        $uri = "http://{$this->endpoint}{$call}";
        
        if (!($this->client instanceof HTTP_Request2)) {
            $this->client = new HTTP_Request2();
            $this->client->setAdapter('socket'); // FIXME
            $this->client->setHeader('User-Agent', 'Services_Qype, @package_version@');
        }

        $url = parse_url($uri);

        $api = sprintf('%s://%s%s', $url['scheme'], $url['host'], $url['path']);
        if (isset($url['query'])) {
            $query  = explode('&', $url['query']);
            $params = array();
            foreach ($query as $pair) {
                list($key, $value) = explode('=', $pair);
                $params[$key] = $value;
            }
        } else {
            $params = null;
        }

        $oauth = OAuthRequest::from_consumer_and_token(
            $this->consumer,
            null,
            HTTP_Request2::METHOD_GET,
            $api,
            $params
        );
        $oauth->sign_request(new OAuthSignatureMethod_HMAC_SHA1(), $this->consumer, NULL);

        $this->client->setUrl($oauth->to_url());
        $this->client->setMethod(HTTP_Request2::METHOD_GET);

        if ($body !== null) {
            $this->client->setBody($body);
        }
        
        try {
            $response = $this->client->send();
        } catch (HTTP_Request2_Exception $e) {
            throw new Services_Qype_Exception($e->getMessage(), $e->getCode());
        }

        $status = $response->getStatus();
        $body   = $response->getBody();

        if (in_array($status, array(401, 404, 500, 503))) {
            throw new Services_Qype_Exception($body, $status);
        }
        if ($status == 200) {
            return $body;
        }
        throw new Services_Qype_Exception($body, $status);
    }
    
    /**
     * Parse the response.
     * 
     * @param object $response
     */
    protected function parseResponse($response)
    {
        return simplexml_load_string($response);
    }
}

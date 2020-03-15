<?php

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
/**
 * 
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
class Tenant_Views_SpaRepository extends Pluf_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function find($request, $match)
    {
        // request param
        $backend = Pluf::f('marketplace.backend', 'http://marketplace.viraweb123.ir');
        $path = '/marketplace/spas';
        $param = $request->REQUEST;
        
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $backend . $path, [
            'query' => $param
        ]);
        
        if("200" != $response->getStatusCode()){
            throw new Pluf_Exception($response->getBody()->getContents());
        }
        $contents = $response->getBody()->getContents();
        return json_decode($contents, true);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    {
        
        // request param
        $backend = Pluf::f('marketplace.backend', 'http://marketplace.viraweb123.ir');
        $path = '/marketplace/spas/'.$match['modelId'];
        $param = $request->REQUEST;
        
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $backend . $path, [
            'query' => $param
        ]);
        
        if("200" != $response->getStatusCode()){
            throw new Pluf_Exception($response->getBody()->getContents());
        }
        $contents = $response->getBody()->getContents();
        $spa = new Tenant_SPA();
        $spa->setFromFormData(json_decode($contents, true));
        return $spa;
    }


    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return array nest possible states
     */
    public function findRemoteSpaStates($request, $match)
    {
//         $repo = new Tenant_Views_SpaRepository();
//         $spa = $repo->get($request, $match);
        $spa = $this->get($request, $match);
        $m = new Tenant_SPA_Manager_Remote();
        $items = $m->states($spa);
        return array(
            'items' => $items,
            'counts' => count($items),
            'current_page' => 1,
            'items_per_page' => count($items),
            'page_number' => 1
        );
    }
    
    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function getRemoteSpaState($request, $match)
    {
        $states = $this->findRemoteSpaStates($request, $match);
        foreach ($states as $state){
            if($state['id'] == $match['stateId']){
                return $state;
            }
        }
        throw new Pluf_HTTP_Error404();
    }
    
    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function putToRemoteSpaState($request, $match)
    {
//         $repo = new Tenant_Views_SpaRepository();
//         $spa = $repo->get($request, $match);
        $spa = $this->get($request, $match);
        $m = new Tenant_SPA_Manager_Remote();
        $transitionId = array_key_exists('id', $request->REQUEST) ? $request->REQUEST['id'] : $match['transitionId'];
        return $m->apply($spa, $transitionId);
    }

}

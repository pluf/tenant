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
Pluf::loadFunction('Pluf_Shortcuts_GetObjectOr404');
Pluf::loadFunction('Tenant_Shortcuts_SpaManager');

/**
 * Manages an spa with a state machine.
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant_Views_SpaStates extends Pluf_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return array nest possible states
     */
    public function find($request, $match)
    {
        $spa = Pluf_Shortcuts_GetObjectOr404('Tenant_SPA', $match['modelId']);
        return Tenant_Shortcuts_SpaManager($spa)->states($spa);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    {
        $states = $this->find($request, $match);
        foreach ($states as $state){
            if($state['id'] == $match['transitionId']){
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
    public function put($request, $match)
    {
        $spa = Pluf_Shortcuts_GetObjectOr404('Tenant_SPA', $match['modelId']);
        $transitionId = array_key_exists('id', $request->REQUEST) ? $request->REQUEST['id'] : $match['transitionId'];
        return Tenant_Shortcuts_SpaManager($spa)->apply($spa, $transitionId);
    }
}
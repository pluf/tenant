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
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Tenant_Views_BankBackend
{

    /**
     * Returns bank-backend determined by given id.
     * This method returns backend if and only if backend with given id blongs to main tenant 
     * else it throws not found exception
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     */
    public function get($request, $match, $p)
    {
        $backend = Pluf_Views::getObject($request, $match, $p);
        if ($backend->tenant !== Tenant_Shortcuts_GetMainTenant()->id) {
            throw new Pluf_HTTP_Error404("Object not found (" . $p['model'] . "," . $backend->id . ")");
        }
        return $backend;
    }
    
    public function find($request, $match, $p){
        if(Pluf::f('multitenant', false)){
            $p['sql'] = new Pluf_SQL('tenant = ' . Tenant_Shortcuts_GetMainTenant()->id);
        }
        return Pluf_Views::findObject($request, $match, $p);
    }
}

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
 * Configurations of Tenant
 *
 * @author hadi
 *
 */
class Tenant_Views_Configuration extends Pluf_Views
{

    /**
     * Gets list of configurations
     *
     * @param unknown $request
     * @param unknown $match
     * @param unknown $params
     * @return unknown
     */
    public function findObject($request, $match, $params=array()){
        $params['sql'] = 'tenant='.$request->tenant->id;
        return parent::findObject($request, $match, $params);
    }


    public function getObject($request, $match, $params){
        $cfg = parent::getObject($request, $match, $params);
        if($cfg->tenant === $request->tenant->id){
            return $cfg;
        }
        throw new Pluf_Exception_DoesNotExist('Configuration not found');
    }

    /**
     * Getting system configuration
     *
     * Get configuration properties from the system.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    {
        $model = $this->internalGet($request, $match);
        if (! isset($model)) {
            throw new Pluf_Exception_DoesNotExist('Configuration not found');
        }
        return $model;
    }

    /**
     * Returns configuration with given key in the $match array.
     * Returns null if such configuration does not exist.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_Model|NULL
     */
    private function internalGet($request, $match)
    {
        $model = new Tenant_Configuration();
        $sql = new Pluf_Sql("`tenant`=%s AND `key`=%s", array(
            $request->tenant->id,
            $match['key']
        ));
        return $model->getOne($sql->gen());
    }
}


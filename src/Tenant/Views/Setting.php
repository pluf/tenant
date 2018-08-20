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
 * Settings special views
 *
 * @author maso
 *        
 */
class Tenant_Views_Setting extends Pluf_Views
{

    /**
     * Getting system setting
     *
     * Anonymous are allowed to get Publich properties from the system.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function get($request, $match)
    { // Set the default
        $model = $this->internalGet($request, $match);
        if (! isset($model)) {
            throw new Pluf_Exception_DoesNotExist('Setting not found');
        }
        return $model;
    }

    /**
     * Returns setting with given key in the $match array. Returns null if such setting does not exist.
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_Model|NULL
     */
    private function internalGet($request, $match){
        if ($request->user->hasPerm('Pluf.owner')) {
            $sql = new Pluf_SQL('`key`=%s', array(
                $match['key']
            ));
        } else {
            $sql = new Pluf_SQL('`key`=%s AND `mode`=%s', array(
                $match['key'],
                Tenant_Setting::MOD_PUBLIC
            ));
        }
        $model = new Tenant_Setting();
        $model = $model->getOne(array(
            'filter' => $sql->gen()
        ));
        return $model;
    }
    
    /**
     *
     * {@inheritdoc}
     * @see Pluf_Views::findObject()
     */
    public function findObject($request, $match, $p = array())
    {
        if (! $request->user->hasPerm('Pluf.owner')) {
            $sql = new Pluf_SQL('`mode`=%s', array(
                Tenant_Setting::MOD_PUBLIC
            ));
            $p['sql'] = $sql;
        }
        return parent::findObject($request, $match, $p);
    }
    
    /**
     * Deletes setting with given key
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_Model|NULL
     */
    public function deleteByKey($request, $match){
        $model = $this->internalGet($request, $match);
        if (! isset($model)) {
            throw new Pluf_Exception_DoesNotExist('Setting not found');
        }
        $model->delete();
        return $model;
    }
    
    /**
     * Deletes setting with given key
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_Model|NULL
     */
    public function updateByKey($request, $match){
        $model = $this->internalGet($request, $match);
        if (! isset($model)) {
            throw new Pluf_Exception_DoesNotExist('Setting not found');
        }
        $form = Pluf_Shortcuts_GetFormForUpdateModel($model, $request->REQUEST);
        $model = $form->save();
        return $model;
    }
    
}


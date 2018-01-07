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
        $sql = new Pluf_SQL('`key`=%s', array(
            $match['key']
        ));
        $model = new Tenant_Setting();
        $model = $model->getOne(array(
            'filter' => $sql->gen()
        ));
        if (! isset($model)) {
            throw new Pluf_Exception_DoesNotExist('Setting not found');
        }
        return $model;
    }
}

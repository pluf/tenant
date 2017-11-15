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
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *        
 */
class Tenant_Views extends Pluf_Views
{

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function current ($request, $match)
    {
        return new Pluf_HTTP_Response_Json($request->tenant);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function update ($request, $match)
    {
        $model = $request->tenant;
        $form = Pluf_Shortcuts_GetFormForUpdateModel($model, $request->REQUEST, 
                array());
        return new Pluf_HTTP_Response_Json($form->save());
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public static function get ($request, $match)
    {
        return Tenant_Views::current($request, $match);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request            
     * @param array $match            
     */
    public function delete ($request, $match)
    {
        $model = $request->tenant;
        $model2 = Pluf_Shortcuts_GetObjectOr404('Pluf_Tenant', $request->tenant->id);
        $model2->delete();
        // XXX: maso, 1395: delete permisions
        // XXX: maso, 1395: delete files
        // XXX: maso, 1395: delete Settings, configs
        // XXX: maso, 1395: emite signal
        return new Pluf_HTTP_Response_Json($model);
    }
}

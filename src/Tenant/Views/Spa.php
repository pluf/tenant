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
Pluf::loadFunction('Pluf_Form_Field_File_moveToUploadFolder');
Pluf::loadFunction('Tenant_Shortcuts_SpaManager');

/**
 * لایه نمایش مدیریت spa ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 * @author hadi
 *        
 */
class Tenant_Views_Spa extends Pluf_Views
{

    /**
     * یک نر افزار را نصب می‌کند
     *
     * تنها پارامتری که برای نصب نرم افزار لازم است خود فایل نرم افزار هست. سایر
     * اطلاعات از توی فایل برداشته می‌شه. این فایل باید ساختار نرم افزارهای ما
     * رو داشته باشه.
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function create($request, $match)
    {
        // 1- upload & extract
        $key = 'spa-' . md5(microtime() . rand(0, 123456789));
        Pluf_Form_Field_File_moveToUploadFolder($request->FILES['file'], array(
            'file_name' => $key . '.zip',
            'upload_path' => Pluf::f('temp_folder', '/tmp'),
            'upload_path_create' => true,
            'upload_overwrite' => true
        ));
        $spa = Tenant_SpaService::installFromFile(Pluf::f('temp_folder', '/tmp') . '/' . $key . '.zip', true);
        return Tenant_Shortcuts_SpaManager($spa)->apply($spa, 'create');
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function update($request, $match)
    {
        if(array_key_exists('file', $request->FILES)){
            $key = 'spa-' . md5(microtime() . rand(0, 123456789));
            $spa = Pluf_Shortcuts_GetObjectOr404('Tenant_SPA', $match['modelId']);
            Pluf_FileUtil::removedir($spa->path);
            // 1- upload & extract
            Pluf_Form_Field_File_moveToUploadFolder($request->FILES['file'], array(
                'file_name' => $key . '.zip',
                'upload_path' => Pluf::f('temp_folder', '/tmp'),
                'upload_path_create' => true,
                'upload_overwrite' => true
            ));
            $zipPath = Pluf::f('temp_folder', '/tmp') . '/' . $key . '.zip';
            $spa = Tenant_SpaService::updateFromFile($spa, $zipPath, true);
            return Tenant_Shortcuts_SpaManager($spa)->apply($spa, 'update');
        }else{
            $pw = new Pluf_Views();
            $p = array(
                'model' => 'Tenant_SPA'
            );
            return $pw->updateObject($request, $match, $p);
        }
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function delete($request, $match)
    {
        $spa = Pluf_Shortcuts_GetObjectOr404('Tenant_SPA', $match['spaId']);
        Pluf_FileUtil::removedir($spa->path);
        $spa->delete();
        return new Pluf_HTTP_Response_Json($spa);
    }
}

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
Pluf::loadFunction('CMS_Shortcuts_GetNamedContentOr404');

/**
 * Content model
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class Tenant_Views_Resource extends Pluf_Views
{
    
    /**
     * Download a resource
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File
     */
    public function download($request, $match)
    {
        // GET data
        $resource = Pluf_Shortcuts_GetObjectOr404('Tenant_Resource', $match['modelId']);
        // Do
        $resource->downloads += 1;
        $resource->update();
        return new Pluf_HTTP_Response_File($resource->getAbsloutPath(), $resource->mime_type);
    }

    /**
     * Upload a file as content
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_Json|object
     */
    public function updateFile($request, $match)
    {
        // Get data
        $resource = Pluf_Shortcuts_GetObjectOr404('Tenant_Resource', $match['modelId']);
        // Do action
        if (array_key_exists('file', $request->FILES)) {
            $extra = array(
                'model' => $resource
            );
            $form = new CMS_Form_ContentUpdate(array_merge($request->REQUEST, $request->FILES), $extra);
            $resource = $form->save();
            return $resource;
        } else {
            $myfile = fopen($resource->getAbsloutPath(), "w") or die("Unable to open file!");
            $entityBody = file_get_contents('php://input', 'r');
            fwrite($myfile, $entityBody);
            fclose($myfile);
            $resource->update();
        }
        return $resource;
    }
    
}


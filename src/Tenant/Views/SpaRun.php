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
 * Run SPAs.
 * 
 * @author pluf<info@pluf.ir>
 *
 */
class Tenant_Views_SpaRun
{

    /**
     * Load default spa
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function defaultSpa($request, $match)
    {
        $name = Tenant_Service::setting('spa.default', 'not-found');
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            $spa = Tenant_SpaService::getNotfoundSpa();
        }
        $resPath = $spa->getMainPagePath();
        return new Tenant_HTTP_Response_SpaMain($resPath, Pluf_FileUtil::getMimeType($resPath));
    }

    /**
     * Load robots.txt of default spa
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function defaultSpaRobotsTxt($request, $match){
        $name = Tenant_Service::setting('spa.default', 'not-found');
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            $spa = Tenant_SpaService::getNotfoundSpa();
        }
        $resourcePath = $spa->getResourcePath('robots.txt');
        return new Tenant_HTTP_Response_RobotsTxt($request->SERVER['HTTP_HOST'], $resourcePath);
    }
    
    /**
     * Load a resource from SPA
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public static function loadResource($request, $match)
    {
        // First part of path
        $firstPart = $match['firstPart'];
        // Remain part of path
        $remainPart = '';
        if (array_key_exists('remainPart', $match)) {
            $remainPart = $match['remainPart'];
        }
        $spa = Tenant_SPA::getSpaByName($firstPart);
        if (isset($spa)) { // SPA is valid
            $path = $remainPart;
            $spaName = $firstPart;
        } else { // first part is not an SPA so use default SPA
            $name = Tenant_Service::setting('spa.default', 'not-found');
            $spa = Tenant_SPA::getSpaByName($name);
            if ($spa === null) {
                $spa = Tenant_SpaService::getNotfoundSpa();
                $spaName = 'not-found';
            } else {
                $spaName = null;
            }
            $path = isset($remainPart) && ! empty($remainPart) ? $firstPart . '/' . $remainPart : $firstPart;
        }
        if (preg_match('/.+\.[a-zA-Z0-9]+$/', $path)) {
            // Looking for file in SPA
            $resPath = $spa->getResourcePath($path);
            $isMain = false;
        } else {
            // Request is for main file (path is an internal state)
            $resPath = $spa->getMainPagePath();
            $isMain = true;
        }
        if ($isMain) {
            return new Tenant_HTTP_Response_SpaMain($resPath, Pluf_FileUtil::getMimeType($resPath), $spaName);
        } else {
            return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
        }
    }
}
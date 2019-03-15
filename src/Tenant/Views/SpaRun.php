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
 * Running an SPA is equal to send JS, CSS and other resources to the
 * clients. So this class is nothing more than a resource access view
 * for an SPA.
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
    public function defaultSpa($request, $match)
    {
        $name = Tenant_Service::setting('spa.default', 'not-found');
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            $spa = Tenant_SpaService::getNotfoundSpa();
        }
        // $resPath = $spa->getMainPagePath();
        // return new Tenant_HTTP_Response_SpaMain($resPath, Pluf_FileUtil::getMimeType($resPath));
        $host = ($request->https ? 'https://' : 'http://') . $request->SERVER['HTTP_HOST'];
        $url = $host . '/' . $spa->name . '/';
        return new Pluf_HTTP_Response_Redirect($url);
    }

    /**
     * Load robots.txt of default spa
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public function defaultSpaRobotsTxt($request, $match)
    {
        $name = Tenant_Service::setting('spa.default', 'not-found');
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            $spa = Tenant_SpaService::getNotfoundSpa();
        }
        $resourcePath = $spa->getResourcePath('robots.txt');
        $host = ($request->https ? 'https://' : 'http://') . $request->SERVER['HTTP_HOST'];
        return new Tenant_HTTP_Response_RobotsTxt($host, $resourcePath);
    }

    /**
     * Load a resource from SPA
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @return Pluf_HTTP_Response_File|Pluf_HTTP_Response
     */
    public function loadResource($request, $match)
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
        } else {
            // first part is not an SPA so use default SPA
            $path = isset($remainPart) && ! empty($remainPart) ? $firstPart . '/' . $remainPart : $firstPart;

            // find a resource
            $tenantResource = $this->findTenantResource('/' . $path);
            if ($tenantResource) {
                return new Pluf_HTTP_Response_File($tenantResource->getAbsloutPath(), $tenantResource->mime_type);
            }

            // [OR] check for default spa
            $name = Tenant_Service::setting('spa.default', 'not-found');
            $spa = Tenant_SPA::getSpaByName($name);
            if ($spa === null) {
                $spa = Tenant_SpaService::getNotfoundSpa();
                $spaName = 'not-found';
            } else {
                $spaName = null;
            }
        }

        if (preg_match('/.+\.[a-zA-Z0-9]+$/', $path)) {
            // Looking for file in SPA
            $resPath = $spa->getResourcePath($path);
            $isMain = false;
        } else {
            // [OR]Request is for main file (path is an internal state)
            $resPath = $spa->getMainPagePath();
            $isMain = true;
        }
        if ($isMain) {
            return new Tenant_HTTP_Response_SpaMain($resPath, Pluf_FileUtil::getMimeType($resPath), $spaName);
        } else {
            return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
        }
    }

    /**
     * Finds tenant resource with path
     *
     * @param string $path
     *            of the resource
     * @return Tenant_Resource the resource
     */
    private function findTenantResource($path)
    {
        $q = new Pluf_SQL('path=%s', array(
            $path
        ));
        $item = new Tenant_Resource();
        $item = $item->getList(array(
            'filter' => $q->gen()
        ));
        if (isset($item) && $item->count() == 1) {
            return $item[0];
        }
        return null;
    }
}
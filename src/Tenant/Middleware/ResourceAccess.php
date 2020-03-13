<?php

/**
 * If the request points to a resource with get, then the resource is returned as
 * response.
 *
 * There is no need to check secureity access for a resource, this middleware check
 * the request and return the resource if the request is GET.
 * 
 * There are two types of resources:
 * 
 * - SPA resources
 * - User loaded resources
 * 
 * This middleware support both of them
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class Tenant_Middleware_ResourceAccess
{

    function process_request(Pluf_HTTP_Request &$request)
    {
        if (! $request->isGet()) {
            return false;
        }

        $viewPrefix = Pluf::f('view_prefix', null);

        if (isset($viewPrefix) && strstr($request->query, $viewPrefix)) {
            return false;
        }

        // First part of path
        $match = [];
        preg_match('#^/(?P<firstPart>[^/]+)/(?P<remainPart>.*)$#', $request->query, $match);
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
            $resPath = $spa->getResourcePath($path);
        } else {
            // first part is not an SPA so use default SPA
            $path = isset($remainPart) && ! empty($remainPart) ? $firstPart . '/' . $remainPart : $firstPart;
            // find a resource
            $res = $this->findTenantResource('/' . $path);
            if (isset($res) && !$res->isAnonymous()) {
                $resPath = $res->getAbsloutPath();
            } elseif (! isset($viewPrefix)) {
                return false;
            } else {
                return $this->redirectToDefaultSpa();
            }
        }

        return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
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

    private function redirectToDefaultSpa()
    {
        // maso, 2020: find default spa
        $name = Tenant_Service::setting('spa.default', 'wb');
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            throw new \Pluf\Exception('No SPA found');
        }
        $path = '/' . $name . '/';
        return new Pluf_HTTP_Response_Redirect($path, 302);
    }
}

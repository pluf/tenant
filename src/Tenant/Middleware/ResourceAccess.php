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
class Tenant_Middleware_ResourceAccess implements \Pluf\Middleware
{

    function process_request(Pluf_HTTP_Request &$request)
    {
        if (! $request->isGet()) {
            return false;
        }

        $viewPrefix = Pluf::f('view_api_prefix', null);

        if (! isset($viewPrefix) || strstr($request->query, $viewPrefix)) {
            return false;
        }

        // First part of path
        $match = [];
        preg_match('#^/(?P<firstPart>[^/]+)/(?P<remainPart>.*)$#', $request->query, $match);
        $firstPart = '';
        if (array_key_exists('firstPart', $match)) {
            $firstPart = $match['firstPart'];
        }
        $remainPart = '';
        if (array_key_exists('remainPart', $match)) {
            $remainPart = $match['remainPart'];
        }

        $spa = Tenant_SPA::getSpaByName($firstPart);

        /*
         * SPA resource
         */
        if (isset($spa)) { // If SPA is valid, so resource is blong to SPA
            $path = $remainPart;
            $resPath = $spa->getResourcePath($path);
            if (! file_exists($resPath) || ! isset($remainPart) || strlen($remainPart) == 0) {
                $resPath = $spa->getMainPagePath();
                return new Tenant_HTTP_Response_SpaMain($resPath, Pluf_FileUtil::getMimeType($resPath), $firstPart);
            } else {
                return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
            }
        }

        /*
         * Tenant resource
         */
        // Requested resource is a tenant resource
        $path = isset($remainPart) && ! empty($remainPart) ? $firstPart . '/' . $remainPart : $firstPart;
        // find a resource
        $res = $this->findTenantResource('/' . $path);
        if (isset($res) && ! $res->isAnonymous()) {
            $resPath = $res->getAbsloutPath();
            return new Pluf_HTTP_Response_File($resPath, Pluf_FileUtil::getMimeType($resPath));
        } else {
            throw new Pluf_HTTP_Error404("Resource not found (/" . $path . ")");
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

    /**
     *
     * {@inheritdoc}
     * @see \Pluf\Middleware::process_response()
     */
    public function process_response(Pluf_HTTP_Request $request, Pluf_HTTP_Response $response): Pluf_HTTP_Response
    {
        return $response;
    }
}

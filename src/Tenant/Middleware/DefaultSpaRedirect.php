<?php

/**
 * Returns a redirect response to the default SPA if requested path has not defined spa
 *
 * @author hadi<mohammad.hadi.mansouri@dpq.co.ir>
 */
class Tenant_Middleware_DefaultSpaRedirect implements \Pluf\Middleware
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

        if (strcmp($request->query, "/") == 0) {
            return $this->redirectToDefaultSpa();
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
        if (isset($spa)) { // SPA is valid
            return false;
        }

        /*
         * Tenant resource
         */
        // first part is not an SPA so use default SPA
        $path = isset($remainPart) && ! empty($remainPart) ? $firstPart . '/' . $remainPart : $firstPart;
        // find a resource
        $res = $this->findTenantResource('/' . $path);
        if (isset($res) && ! $res->isAnonymous()) {
            return false;
        } else {
            return $this->redirectToDefaultSpa($path);
        }
        return false;
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

    private function redirectToDefaultSpa($path = '')
    {
        // maso, 2020: find default spa
        $name = Tenant_Service::setting('spa.default', 'wb');
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            throw new \Pluf\Exception('No SPA found');
        }
        $path = '/' . $name . '/' . (isset($path) ? $path : '');
        return new Pluf_HTTP_Response_Redirect($path, 302);
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

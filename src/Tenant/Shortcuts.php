<?php

function Tenant_Shortcuts_GetMainTenant ()
{
    $subdomain = Pluf::f('tenant_default', 'www');
//     if($subdomain === null){
//         throw new Pluf_Exception_DoesNotExist('tenant_default is not set!');
//     }
    $tenant = Pluf_Tenant::bySubDomain($subdomain);
    if ($tenant == null || $tenant->id <= 0) {
        throw new Pluf_Exception_DoesNotExist(
                "Tenant not found (subdomain:" . $subdomain . ")");
    }
    return $tenant;
}

function Tenant_Shortcuts_NormalizeItemPerPage ($request)
{
    $count = array_key_exists('_px_c', $request->REQUEST) ? intval($request->REQUEST['_px_c']) : 30;
    if($count > 30)
        $count = 30;
    return $count;
}

/**
 *
 * @param Tenant_SPA $spa
 * @return Tenant_SPA_Manager
 */
function Tenant_Shortcuts_SpaManager($spa)
{
    // XXX: maso, 2017: read from settings
    $manager = new Tenant_SPA_Manager_Simple();
    return $manager;
}
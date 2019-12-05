<?php

/**
 * Checks the validation of the tenant.
 * 
 * If tenant is not defined it throws a bad-request exception.
 * If tenant is defined but is not verirfied it throws a forbidden exception 
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Tenant_Middleware_Verifier
{

    function process_request (&$request)
    {
        if ($request->tenant->isAnonymous()) {
            throw new Pluf_Exception_BadRequest('Tenant is not defined.');
        }
        
        if(!$request->tenant->validate && $request->method != 'GET'){
            throw new Pluf_Exception_Forbidden('Tenant is not active.');
        }
        return false;
    }
}

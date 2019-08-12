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
use GraphQL\GraphQL;
use GraphQL\Type\Schema;

/**
 * لایه نمایش مدیریت گروه‌ها را به صورت پیش فرض ایجاد می‌کند
 *
 * @author maso
 *
 */
class Tenant_Views extends Pluf_Views
{

    /**
     * Gets current tenant
     *
     * @return Tenant_CurrentTenant current tenant
     */
    public function getCurrent($request, $match, $params)
    {

        if (array_key_exists('graphql', $request->REQUEST)) {
            // clean query
            $query = $request->REQUEST['graphql'];
            unset($request->REQUEST['graphql']);
            // Build result
            Pluf::loadFunction('Tenant_Shortcuts_generateCurrentTenantObjectType');
            try {
                $schema = new Schema([
                    'query' => Tenant_Shortcuts_generateCurrentTenantObjectType()
                ]);
                $result = GraphQL::executeQuery($schema, $query, $request);
                return $result->toArray();
            } catch (Exception $e) {
                throw new Pluf_Exception_BadRequest($e->getMessage());
            }
        }

        $match['modelId'] = $request->tenant->id;
        $params['model'] = 'Tenant_Tenant';
        $tenant = $this->getObject($request, $match, $params);
        return $tenant;
    }

    public function getCurrentConfigurations($request, $match, $params)
    {
        $sql = new Pluf_SQL('tenant=%s', $request->tenant->id);
        if (isset($params['sql'])) {
            $sqlMain = new Pluf_SQL($params['sql']);
            $sql = $sqlMain->SAnd($sql);
        }
        $params['sql'] = $sql;
        return $this->findObject($request, $match, $params);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public function getTenants($request, $match, $params)
    {
        $parentId = $request->tenant->id;
        $sql = new Pluf_SQL('parent_id=%s OR id=%s', array(
            $parentId,
            $parentId
        ));
        $params['model'] = 'Tenant_Tenant';
        if (isset($params['sql'])) {
            $sqlMain = new Pluf_SQL($p['sql']);
            $sql = $sqlMain->SAnd($sql);
        }
        $params['sql'] = $sql;
        return $this->findObject($request, $match, $params);
    }

    /**
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $params
     * @return Tenant_Tenant
     */
    public function putTenant($request, $match, $params)
    {
        $parent = Pluf_Shortcuts_GetObjectOr404('Tenant_Tenant', $request->tenant->id);
        $params = array_merge(array(
            'extra_context' => array(),
            'extra_form' => array()
        ), $params);
        // Set the default
        $tenant = new Tenant_Tenant();
        $form = Pluf_Shortcuts_GetFormForModel($tenant, $request->REQUEST, $params['extra_form']);
        $tenant = $form->save(false);
        $tenant->parent_id = $parent;
        $tenant->create();
        return $tenant;
    }

    /**
     * Gets tenant
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $params
     * @return Tenant_Tenant
     */
    public function getTenant($request, $match, $params)
    {
        $params['model'] = 'Tenant_Tenant';
        $tenant = $this->getObject($request, $match, $params);

        if ($tenant->id == $request->tenant->id || $tenant->parent_id == $request->tenant->id) {
            return $tenant;
        }
        throw new Pluf_Exception_DoesNotExist('Requested tenant not found');
    }

    /**
     * Gets tenant configurations
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $params
     * @return Tenant_Tenant
     */
    public function getTenantConfigurations($request, $match, $params)
    {
        // check tenant
        $tenant = new Tenant_Tenant($match['parentId']);
        if (! isset($tenant) || $tenant->parent_id !== $request->tenant->id) {
            throw new Pluf_Exception_NotExist('Tenant not found');
        }
        return parent::findManyToOne($request, $match, $params);
    }
}

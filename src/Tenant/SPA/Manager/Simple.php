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
 * Simple SPA management
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant_SPA_Manager_Simple implements Tenant_SPA_Manager
{

    /**
     * State machine of spa
     *
     * @var array
     */
    static $STATE_MACHINE = array(
        \Pluf\Workflow\Machine::STATE_UNDEFINED => array(
            'next' => 'Enable',
            'visible' => false,
            'action' => array(
                'Tenant_SPA_Manager_Simple',
                'init'
            )
        ),
        // State
        'Enable' => array(
            'checkUpdate' => array(
                'next' => 'Enable',
                'title' => 'Check Update',
                'visible' => true,
                'action' => array(
                    'Tenant_SPA_Manager_Simple',
                    'checkUpdate'
                ),
                'preconditions' => array(
                    'User_Precondition::isOwner'
                ),
                'properties' => array()
            ),
            'update' => array(
                'next' => 'Enable',
                'title' => 'Update',
                'visible' => true,
                'action' => array(
                    'Tenant_SPA_Manager_Simple',
                    'update'
                ),
                'preconditions' => array(
                    'User_Precondition::isOwner'
                ),
                'properties' => array()
            ),
            'read' => array(
                'next' => 'Enable',
                'visible' => false
            ),
            'setAsDefault' => array(
                'next' => 'Enable',
                'title' => 'Set as Default',
                'visible' => true,
                'action' => array(
                    'Tenant_SPA_Manager_Simple',
                    'setAsDefault'
                ),
                'preconditions' => array(
                    'User_Precondition::isOwner'
                )
            ),
            'delete' => array(
                'next' => 'Deleted',
                'title' => 'Delete',
                'visible' => true,
                'action' => array(
                    'Tenant_SPA_Manager_Simple',
                    'delete'
                ),
                'preconditions' => array(
                    'User_Precondition::isOwner'
                )
            ),
            'disable' => array(
                'next' => 'Disabled',
                'title' => 'Disable',
                'visible' => true,
                'preconditions' => array(
                    'User_Precondition::isOwner'
                )
            )
        ),
        'Disabled' => array(
            'enable' => array(
                'next' => 'Enable',
                'title' => 'Enable',
                'visible' => true,
                'preconditions' => array(
                    'User_Precondition::isOwner'
                )
            )
        ),
        'Deleted' => array()
    );

    /**
     *
     * {@inheritdoc}
     * @see Tenant_SPA_Manager::filter()
     */
    public function filter($request)
    {
        return new Pluf_SQL("true");
    }

    /**
     *
     * {@inheritdoc}
     * @see Tenant_SPA_Manager::apply()
     */
    public function apply($spa, $action)
    {
        $machine = new \Pluf\Workflow\Machine();
        return $machine->setStates(self::$STATE_MACHINE)
            ->setSignals(array(
            'Tenant_SPA::stateChange'
        ))
            ->setProperty('state')
            ->apply($spa, $action);
    }

    /**
     *
     * {@inheritdoc}
     * @see Tenant_SPA_Manager::states()
     */
    public function states($spa)
    {
        $states = array();
        foreach (self::$STATE_MACHINE[$spa->state] as $id => $state) {
            $state['id'] = $id;
            $states[] = $state;
        }
        return $states;
    }

    /**
     * Check update of an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Tenant_SPA $object
     */
    public static function checkUpdate($request, $object)
    {
        $repo = new Tenant_Views_SpaRepository();
        $spa = $repo->get(new Pluf_HTTP_Request('/'), array(
            'modelId' => $object->name
        ));
        $object->last_version = $spa->version;
        $object->update();
        return $object;
    }

    /**
     * Update an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Tenant_SPA $object
     */
    public static function update($request, $object)
    {
        // request param
        $backend = Pluf::f('tenant_spa_marketplace_backend', 'http://marketplace.viraweb123.ir');
        $apiPrefix = Pluf::f('tenant_spa_marketplace_api_prefix', '/api/v2');
        $path = '/marketplace/spas/' . $object->name . '/file';
        $file = Pluf::f('temp_folder', '/tmp') . '/spa-' . rand();
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $backend . $apiPrefix .$path, [
            'sink' => $file
        ]);
        return Tenant_SpaService::updateFromFile($object, $file, true);
    }

    /**
     * Reutn deleted object
     *
     * @param Pluf_HTTP_Request $request
     * @param Tenant_SPA $object
     * @return Tenant_SPA
     */
    public static function delete($request, $object)
    {
        $object->delete();
        return $object;
    }

    /**
     * Reutn init object
     *
     * @param Pluf_HTTP_Request $request
     * @param Tenant_SPA $object
     * @return Tenant_SPA
     */
    public static function init($request, $object)
    {
        $object->state = 'Enable';
        $object->update();
        return $object;
    }
    
    /**
     * Sets a SPA as the default SPA of the tenant
     *
     * @param Pluf_HTTP_Request $request
     * @param Tenant_SPA $object
     * @return Tenant_SPA
     */
    public static function setAsDefault($request, $object)
    {
        Tenant_Service::setSetting('spa.default', $object->name);
        return $object;
    }
}

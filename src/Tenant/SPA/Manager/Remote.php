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
class Tenant_SPA_Manager_Remote implements Tenant_SPA_Manager
{

    /**
     * State machine of spa
     *
     * @var array
     */
    static $STATE_MACHINE = array(
        // State
        'Published' => array(
            'install' => array(
                'next' => 'Published',
                'visible' => true,
                'preconditions' => array(
                    'User_Precondition::isOwner'
                ),
                'action' => array(
                    'Tenant_SPA_Manager_Remote',
                    'install'
                )
            )
        )
    );

    /**
     *
     * {@inheritdoc}
     * @see Tenant_SPA_Manager::filter()
     */
    public function filter($request)
    {
        return new Pluf_SQL("state=%s", array(
            "Published"
        ));
    }

    /**
     *
     * {@inheritdoc}
     * @see Tenant_SPA_Manager::apply()
     */
    public function apply($spa, $action)
    {
        $machine = new \Pluf\Workflow\Machine();
        $machine->setStates(self::$STATE_MACHINE)
            ->setSignals(array(
            'Tenant_SPA::stateChange'
        ))
            ->setProperty('state')
            ->apply($spa, $action);
        return true;
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
     * Install an spa
     *
     * @param Pluf_HTTP_Request $request
     * @param Tenant_SPA $object
     */
    public static function install($request, $object)
    {
        $spa = Tenant_SpaService::installFromRepository($object->id);
        return Tenant_Shortcuts_SpaManager($spa)->apply($spa, 'create');
    }
}

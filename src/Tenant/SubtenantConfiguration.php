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
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 *        
 */
class Tenant_SubtenantConfiguration extends Tenant_Configuration
{

    /**
     * The data model of a configuration
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        parent::init();
        // Set multitenancy as false
        $this->_a['multitenant'] = false;
        $this->_a['cols'] = array_merge($this->_a['cols'], array(
            // relations
            // Tenant properties
            'tenant' => array(
                'type' => 'Foreignkey',
                'model' => 'Tenant_Tenant',
                'is_null' => false,
                'editable' => false,
                'readable' => true,
                'relate_name' => 'configurations',
                'graphql_name' => 'tenant',
                'graphql_feild' => true
            )
        ));
    }
}

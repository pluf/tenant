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
/*
 * Push new settings into the DBMS
 */
Pluf_Signal::connect('Pluf_Dispatcher::postDispatch', array(
    'Tenant_Service',
    'flush'
), 'Pluf_Dispatcher');

return array(
    'Tenant_Invoice' => array(
        'relate_to' => array(
            'Bank_Receipt'
        )
    ),
    'Tenant_Ticket' => array(
        'relate_to' => array(
            'User'
        ),
        'relate_to_many' => array(
            'Tenant_Comment'
        )
    ),
    'Tenant_Comment' => array(
        'relate_to' => array(
            'User_Account'
        )
    ),
    'Tenant_Tenant' => array(
        'relate_to' => array(
            'Tenant_Tenant'
        )
    ),
    'Tenant_Member' => array(
        'relate_to_many' => array(
            'Tenant_Tenant'
        )
    ),
    'Tenant_SubtenantConfiguration' => array(
        'relate_to' => array(
            'Tenant_Tenant'
        )
    ),
);
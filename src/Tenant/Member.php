<?php
Pluf::loadFunction('Pluf_Shortcuts_GetAssociationTableName');
Pluf::loadFunction('Pluf_Shortcuts_GetForeignKeyName');

/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. http://dpq.co.ir
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
 * Account data model
 *
 * Stores information of a member. A member actually is a user.
 */
class Tenant_Member extends Pluf_Model
{
    /**
     * Cache of the Role.
     */
    public $_cache_perms = null;

    function init()
    {
        $this->_a['verbose'] = 'Tenant_Member';
        $this->_a['table'] = 'user_accounts';
        $this->_a['cols'] = array(
            // It is mandatory to have an "id" column.
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                // It is automatically added.
                'is_null' => true,
                'editable' => false,
                'readable' => true
            ),
            'login' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => false,
                'unique' => true,
                'size' => 50,
                'editable' => false,
                'readable' => true
            ),
            'date_joined' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'is_null' => true,
                'editable' => false
            ),
            'last_login' => array(
                'type' => 'Pluf_DB_Field_Datetime',
                'is_null' => true,
                'editable' => false
            ),
            'is_active' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'is_null' => false,
                'default' => false,
                'editable' => false
            ),
            'is_deleted' => array(
                'type' => 'Pluf_DB_Field_Boolean',
                'is_null' => false,
                'default' => false,
                'editable' => false
            ),
            /*
             * Relations
             */
            'tenants' => array(
                'type' => 'Pluf_DB_Field_Manytomany',
                'blank' => true,
                'model' => 'Tenant_Tenant',
                'relate_name' => 'owners',
                'editable' => false,
                'graphql_name' => 'tenants',
                'readable' => true
            )
        );

        // Assoc. table
        $tnt_asso = $this->_con->pfx . Pluf_Shortcuts_GetAssociationTableName('Tenant_Member', 'Tenant_Tenant');
        $t_member = $this->_con->pfx . $this->_a['table'];
        $member_fk = Pluf_Shortcuts_GetForeignKeyName('Tenant_Member');
        $this->_a['views'] = array(
            'join_tenant' => array(
                'join' => 'LEFT JOIN ' . $tnt_asso . ' ON ' . $t_member . '.id=' . $member_fk
            )
        );
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_Model::__toString()
     */
    function __toString()
    {
        return $this->login;
    }

    /**
     * Extract information of user and returns it.
     *
     * @param string $login
     * @return Tenant_Member user information
     */
    public static function getMember($login)
    {
        $model = new Tenant_Member();
        $where = 'login = ' . $model->_toDb($login, 'login');
        $users = $model->getList(array(
            'filter' => $where
        ));
        if ($users === false or count($users) !== 1) {
            return false;
        }
        return $users[0];
    }

    function preSave($create = false)
    {
        throw new Pluf_Exception_NotImplemented('Creating a Tenant_Member is not supported');
    }
    
    function preDelete($create = false)
    {
        throw new Pluf_Exception_NotImplemented('Deleting a Tenant_Member is not supported');
    }

    /**
     * Checks if account is active
     *
     * @return boolean true if account is active else false
     */
    function isActive()
    {
        return $this->is_active;
    }
}

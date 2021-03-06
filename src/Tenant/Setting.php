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
 * Model of setting
 *
 * @property {int}
 *           mode each bit is used for an special protection.
 *           First bit is a protection bit: true means owner can access
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant_Setting extends Pluf_Model
{

    const MOD_PRIVATE = 1;

    // 001
    const MOD_PUBLIC = 0;

    // 000

    /**
     *
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenant_settings';
        $this->_a['verbose'] = 'Tenant Setting';
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Sequence',
                'blank' => true,
                'editable' => false,
                'readable' => true
            ),
            'mode' => array(
                'type' => 'Integer',
                'is_null' => false,
                'editable' => false,
                'default' => self::MOD_PUBLIC
            ),
            'key' => array(
                'type' => 'Varchar',
                'unique' => true,
                'is_null' => false,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            'value' => array(
                'type' => 'Varchar',
                'is_null' => true,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            'description' => array(
                'type' => 'Varchar',
                'is_null' => true,
                'size' => 250,
                'editable' => true,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => 'Datetime',
                'is_null' => true,
                'verbose' => __('creation date'),
                'help_text' => __('Creation date of the configuration.'),
                'editable' => false,
                'readable' => true
            ),
            'modif_dtime' => array(
                'type' => 'Datetime',
                'is_null' => true,
                'verbose' => __('modification date'),
                'help_text' => __('Modification date of the configuration.'),
                'editable' => false,
                'readable' => true
            )
        );
        // $this->_a['idx'] = array(
        // 'mode_key_idx' => array(
        // 'col' => 'mode, key'
        // )
        // );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param $create boolean
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }
}
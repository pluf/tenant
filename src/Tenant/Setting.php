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
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Tenant_Setting extends Pluf_Model
{
    
    const MOD_PRIVATE = 0; // 000
    const MOD_PUBLIC = 1;  // 001
    
    
    /**
     * @brief مدل داده‌ای را بارگذاری می‌کند.
     *
     * @see Pluf_Model::init()
     */
    function init ()
    {
        $this->_a['table'] = 'settings';
        $this->_a['verbose'] = 'System setting';
        $this->_a['cols'] = array(
                'id' => array(
                        'type' => 'Pluf_DB_Field_Sequence',
                        'blank' => true,
                        'editable' => false,
                        'readable' => true
                ),
                'mod' => array(
                        'type' => 'Pluf_DB_Field_Integer',
                        'blank' => false,
                        'editable' => false
                ),
                'key' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => false,
                        'size' => 250,
                        'editable' => true,
                        'readable' => true
                ),
                'value' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'editable' => true,
                        'readable' => true
                ),
                'description' => array(
                        'type' => 'Pluf_DB_Field_Varchar',
                        'blank' => true,
                        'size' => 250,
                        'editable' => true,
                        'readable' => true
                ),
                'creation_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('creation date'),
                        'help_text' => __('Creation date of the configuration.'),
                        'editable' => false,
                        'readable' => true
                ),
                'modif_dtime' => array(
                        'type' => 'Pluf_DB_Field_Datetime',
                        'blank' => true,
                        'verbose' => __('modification date'),
                        'help_text' => __(
                                'Modification date of the configuration.'),
                        'editable' => false,
                        'readable' => true
                )
        );
        $this->_a['idx'] = array(
                'mod_key_idx' => array(
                        'type' => 'unique',
                        'col' => 'mod, key'
                ),
                'key_idx' => array(
                        'type' => 'unique',
                        'col' => 'key'
                )
        );
    }

    /**
     * \brief پیش ذخیره را انجام می‌دهد
     *
     * @param $create boolean
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave ($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }

}
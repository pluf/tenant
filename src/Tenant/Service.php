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
 * Application/Tenant level configuration
 *
 * Setting is a key-value in the application level which is editable by owners. All
 * settings are read only for others.
 *
 * @author maso<mostafa.barmshroy@dpq.co.ir>
 *        
 */
class Tenant_Service
{

    public static $inMemory = array(
        // example entry
        'key' => array(
            'value' => 'value',
            'derty' => false
        )
    );

    /**
     *
     * @param string $key
     * @param object $defValue
     * @return boolean|object|string
     */
    public static function setting($key, $defValue = null)
    {
        if (array_key_exists($key, self::$inMemory)) {
            $entary = self::$inMemory[$key];
        } else {
            $entary = array(
                'value' => $defValue,
                'derty' => false
            );
            // TODO: maso, 2017: load value
            $sql = new Pluf_SQL('`key`=%s', array(
                $key
            ));
            $setting = new Tenant_Setting();
            $setting = $setting->getOne(array(
                'filter' => $sql->gen()
            ));
            if (isset($setting)) {
                $entary['value'] = $setting->value;
            } else {
                $entary['derty'] = true;
            }
        }
        self::$inMemory[$key] = $entary;
        return $entary['value'];
    }

    /**
     *
     * @param string $key
     * @param object $value
     */
    public static function setSetting($key, $value)
    {
        self::$inMemory[$key] = array(
            'value' => $value,
            'derty' => true
        );
    }

    /**
     */
    public static function flush()
    {
        foreach (self::$inMemory as $key => $val) {
            if ($val['derty']) {
                // TODO: maso, 2017: load value
                $sql = new Pluf_SQL('`key`=%s', array(
                    $key
                ));
                $setting = new Tenant_Setting();
                $setting = $setting->getOne(array(
                    'filter' => $sql->gen()
                ));
                if (isset($setting)) {
                    $setting->value = $val['value'];
                    $setting->update();
                } else {
                    $setting = new Tenant_Setting();
                    $setting->value = $val['value'];
                    $setting->key = $key;
                    $setting->type = Tenant_Setting::MOD_PUBLIC;
                    $setting->create();
                }
            }
        }
    }
}
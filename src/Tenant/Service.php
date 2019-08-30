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

    public static function createNewTenant($data){
        // Create a tenant
        $tenant = new Pluf_Tenant();
        // Set domain from subdomain if domain is not set in the request
        if (! isset($data['domain'])) {
            $data['domain'] = $data['subdomain'] . '.' . Pluf::f('general_domain', 'pluf.ir');
        }
        // Set current tenant as the parent tenant
        $tenant->_a['cols']['parent_id']['editable'] = true;
        $currentTenant = Pluf_Tenant::current();
        $data['parent_id'] = $currentTenant->id;
        
        $form = Pluf_Shortcuts_GetFormForModel($tenant, $data);
        $tenant = $form->save();
        
        // Set path to initial data. It should be checked before switching to new tenant.
        if(!$data['initial_default_data']){
            $data['initial_default_data'] = Tenant_Service::setting('initial_default_data'); 
        }
        
        // Init the Tenant
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->init($tenant);
        
        // TODO: hadi, 97-06-18: create account and credential base on given data by user in request
        // For example: login, password, list of modules to install and so on.
        
        // TODO: update user api to get user by login directly
        $user = new User_Account();
        $user = $user->getUser('admin');
        
        $credit = new User_Credential();
        $credit->setFromFormData(array(
            'account_id' => $user->id
        ));
        $credit->setPassword('admin');
        $credit->create();
        
        // Set owner
        $role = User_Role::getFromString('tenant.owner');
        $user->setAssoc($role);
        
        // install SPAcs
        $spas = Pluf::f('spas', array());
        if (sizeof($spas) > 0 && class_exists('Tenant_SpaService')) {
            try {
                Pluf::loadFunction('Tenant_Shortcuts_SpaManager');
                Tenant_Service::setSetting('spa.default', $spas[0]);
                foreach ($spas as $spa) {
                    $myspa = Tenant_SpaService::installFromRepository($spa);
                    Tenant_Shortcuts_SpaManager($myspa)->apply($myspa, 'create');
                }
            } catch (Throwable $e) {
                throw new Pluf_Exception("Impossible to install spas from market.", 5000, $e, 500);
            }
        }
        
        return $tenant;
    }

    private static function provideContent($data){        
        // Load initial default data
        $path = $data['initial_default_data'];
        $file = Pluf::f('temp_folder', '/tmp') . '/content-' . rand() . '.zip';
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $path, [
            'sink' => $file
        ]);
        Backup_Service::loadData($file);
        
        $path = $data['initial_data'];
        $file = Pluf::f('temp_folder', '/tmp') . '/content-' . rand() . '.zip';
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $path, [
            'sink' => $file
        ]);
        Backup_Service::loadData($file);
    }
}
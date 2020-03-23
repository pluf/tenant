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

    public static function createNewTenant($data)
    {
        /*
         * Data validation
         */
        if (! Pluf::f('multitenant', false)) {
            throw new Pluf_Exception_Forbidden('The server does not support multitenancy!');
        }
        if (! Tenant_Service::validateSubdomainFormat($data['subdomain'])) {
            throw new Pluf_Exception_BadRequest('The subdomain is not valid.');
        }
        // Set domain from subdomain if domain is not set in the request
        if (! isset($data['domain'])) {
            $data['domain'] = $data['subdomain'] . '.' . Pluf::f('general_domain', 'pluf.ir');
        }

        /*
         * Create tenant
         */
        $tenant = new Pluf_Tenant();
        $tenant->setFromFormData($data);

        // Set current tenant as the parent tenant
        $currentTenant = Pluf_Tenant::getCurrent();
        if (isset($currentTenant)) {
            $tenant->parent_id = $currentTenant;
        }
        $tenant->create();

        /*
         * Init tenant
         */
        // Set path to initial data. It should be done before switching to the new tenant.
        $data['initial_default_data'] = Tenant_Service::setting('initial_default_data');
        // Initialize the newly created tenant
        $tenant = self::initiateTenant($tenant);
        // Load initial data to newly created tenant
        Tenant_Service::provideContent($data);
        return $tenant;
    }

    /**
     * Initiates some necessary data for given tenant.
     *
     * @param Pluf_Tenant $tenant
     * @throws \Pluf\Exception
     * @return Pluf_Tenant
     */
    public static function initiateTenant($tenant)
    {
        // Init the Tenant
        $m = new Pluf_Migration();
        $m->init($tenant);

        // TODO: hadi, 97-06-18: create account and credential base on given data by user in request
        // For example: login, password, list of modules to install and so on.

        $current = Pluf_Tenant::current();

        // Set password for all users of tenant. Default password is equla to its login.
        try {
            Pluf_Tenant::setCurrent($tenant);
            $user = new User_Account();
            $members = $user->getList();
            foreach ($members as $member) {
                $user = $user->getUser($member->login);
                $credit = new User_Credential();
                $credit->setFromFormData(array(
                    'account_id' => $user->id
                ));
                $credit->setPassword($member->login);
                $credit->create();
            }

            // Set admin as the owner
            $user = $user->getUser('admin');
            $role = User_Role::getFromString('tenant.owner');
            $user->setAssoc($role);

            // install SPAcs
            $spas = Pluf::f('tenant_spa_default', array());
            if (sizeof($spas) > 0 && class_exists('Tenant_SpaService')) {
                try {
                    Pluf::loadFunction('Tenant_Shortcuts_SpaManager');
                    Tenant_Service::setSetting('spa.default', $spas[0]);
                    foreach ($spas as $spa) {
                        $myspa = Tenant_SpaService::installFromRepository($spa);
                        Tenant_Shortcuts_SpaManager($myspa)->apply($myspa, 'create');
                    }
                } catch (Exception $e) {
                    throw new \Pluf\Exception("Impossible to install spas from market.", 5000, $e, 500);
                }
            }
            return $tenant;
        } finally {
            Pluf_Tenant::setCurrent($current);
        }
    }

    public static function validateSubdomainFormat($subdomain)
    {
        $regex = '/^[A-Za-z0-9][A-Za-z0-9_\-]{1,61}[A-Za-z0-9]$/';
        if (preg_match($regex, $subdomain))
            return TRUE;
        return FALSE;
    }

    private static function provideContent($data)
    {
        // Load initial default data
        if (array_key_exists('initial_default_data', $data) && ! empty($data['initial_default_data'])) {
            $path = $data['initial_default_data'];
            $file = Pluf::f('temp_folder', '/tmp') . '/content-' . rand() . '.zip';
            // Do request
            $client = new GuzzleHttp\Client();
            $client->request('GET', $path, [
                'sink' => $file
            ]);
            Pluf\Backup\Service::loadData($file);
        }
        // Load initial data
        if (array_key_exists('initial_data', $data) && ! empty($data['initial_data'])) {
            $path = $data['initial_data'];
            $file = Pluf::f('temp_folder', '/tmp') . '/content-' . rand() . '.zip';
            // Do request
            $client = new GuzzleHttp\Client();
            $response = $client->request('GET', $path, [
                'sink' => $file
            ]);
            Pluf\Backup\Service::loadData($file);
        }
    }
}
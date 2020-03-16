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
Pluf::loadFunction('Tenant_Shortcuts_SpaManager');

/**
 *
 * @author pluf.ir
 * @since 4.0.0
 */
class Tenant_SpaService
{

    public static function getNotfoundSpa()
    {
        $name = 'not-found';
        $spa = Tenant_SPA::getSpaByName($name);
        if (! isset($spa)) {
            $spa = self::installFromFile(__DIR__ . '/resources/not-found-0.1.1.zip');
            return Tenant_Shortcuts_SpaManager($spa)->apply($spa, 'create');
        }
        return $spa;
    }

    /**
     * Install spa from file into the tenant.
     *
     * @param String $path
     * @param string $deleteFile
     * @throws Pluf_Exception
     */
    public static function installFromFile($path, $deleteFile = false)
    {
        // crate spa
        $spa = new Tenant_SPA();
        $spa->path = 'not/set';
        $spa->name = 'spa-' . rand();
        $spa->create();
        try {
            return self::updateFromFile($spa, $path, $deleteFile);
        } catch (Exception $ex) {
            $spa->delete();
            throw $ex;
        }
    }

    public static function updateFromFile($spa, $path, $deleteFile = false)
    {
        // Temp folder
        $key = 'spa-' . md5(microtime() . rand(0, 123456789));
        $dir = Pluf_Tenant::storagePath() . '/spa/' . $key;
        if (! mkdir($dir, 0777, true)) {
            throw new \Pluf\Exception('Failed to create folder in temp: ' . $dir);
        }

        // Unzip to temp folder
        $zip = new ZipArchive();
        if ($zip->open($path) === TRUE) {
            $zip->extractTo($dir);
            $zip->close();
        } else {
            throw new \Pluf\Exception('Unable to unzip SPA.');
        }
        if ($deleteFile) {
            unlink($path);
        }

        // 2- load infor
        $filename = $dir . '/' . Pluf::f('tenant_spa_config', 'spa.json');
        $myfile = fopen($filename, 'r') or die('Unable to open file!');
        $json = fread($myfile, filesize($filename));
        fclose($myfile);
        $package = json_decode($json, true);

        // update spa
        $spa->setFromFormData($package);
        $spa->path = Pluf_Tenant::storagePath() . '/spa/' . $spa->id;
        $spa->update();

        Pluf_FileUtil::removedir($spa->path);
        rename($dir, $spa->path);
        return $spa;
    }

    /**
     * Install tenant with $id from remote repository
     *
     * @param string $id
     * @return Tenant_SPA
     */
    public static function installFromRepository($id)
    {
        // request param
        $backend = Pluf::f('marketplace.backend', 'http://marketplace.viraweb123.ir');
        $path = '/marketplace/spas/' . $id . '/file';
        $file = Pluf::f('temp_folder', '/tmp') . '/spa-' . rand();
        // Do request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $backend . $path, [
            'sink' => $file
        ]);

        // install
        return self::installFromFile($file, true);
    }
}

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
class Tenant_Monitor
{

    /**
     * Find storage size
     */
    public static function storage()
    {
        // maso, 2017: find storage size
        $file_directory = Pluf_Tenant::storagePath();
        return Pluf_Shortcuts_folderSize($file_directory);
    }
    
    /**
     * Retruns permision status
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     */
    public static function permisson ($request, $match)
    {
        
        // Check user
        if ($request->user->isAnonymous()) {
            return false;
        }
        
        // Get permission
        $per = new Role();
        $sql = new Pluf_SQL('code_name=%s',
            array(
                $match['property']
            ));
        $items = $per->getList(
            array(
                'filter' => $sql->gen()
            ));
        if ($items->count() == 0) {
            return false;
        }
        
        // Check permission
        return $request->user->hasPerm($items[0].'');
    }
}


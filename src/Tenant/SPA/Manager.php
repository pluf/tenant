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
 * Manages lifecycle of an SPA
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 *
 */
interface Tenant_SPA_Manager
{
    
    /**
     * Creates a filter
     *
     * @param Pluf_HTTP_Request $request
     * @return Pluf_SQL
     */
    public function filter($request);
    
    /**
     * Apply action on object
     *
     * Each order must follow CRUD actions in life cycle. Here is default action
     * list:
     *
     * <ul>
     * <li>create</li>
     * <li>read</li>
     * <li>update</li>
     * <li>delete</li>
     * </ul>
     *
     * @param Tenant_SPA $order
     * @param String $action
     * @return Tenant_SPA
     */
    public function apply($spa, $action);
    
    /**
     * Returns next possible states
     *
     * @param Tenant_SPA $order
     * @return array of states
     */
    public function states($spa);
}
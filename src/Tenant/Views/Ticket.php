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
Pluf::loadFunction('Pluf_Shortcuts_GetFormForModel');

/**
 * Invoices view
 *
 * @author maso<mostafa.barmshory@dpq.co.ir>
 */
class Tenant_Views_Ticket
{

    /**
     * Create new comment
     *
     * @param Pluf_HTTP_Request $request
     * @param array $match
     * @param array $p
     */
    public function createManyToOne($request, $match)
    {
        $parent = Pluf_Shortcuts_GetObjectOr404('Tenant_Ticket', $match['parentId']);
        $object = new Tenant_Comment();
        $form = Pluf_Shortcuts_GetFormForModel($object, $request->REQUEST);
        $object = $form->save(false);
        $object->ticket_id = $parent;
        $object->author_id = $request->user;
        $object->create();
        return $object;
    }
}
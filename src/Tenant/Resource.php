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
 * Tenant resource date model
 * 
 * A direct resource model for a tenant. Suppose ther is a file which you wish to 
 * put in an specific address. Just create a resource and upload your file.
 *
 * @author hadi <mohammad.hadi.mansouri@dpq.co.ir>
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *        
 */
class Tenant_Resource extends Pluf_Model
{

    function init()
    {
        $this->_a['table'] = 'tenant_resources';
        $this->_a['cols'] = array(
            // Identifier
            'id' => array(
                'type' => 'Pluf_DB_Field_Sequence',
                'is_null' => false,
                'editable' => false
            ),
            // Fields
            'path' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => false,
                'size' => 64,
                'unique' => true,
                'editable' => true
            ),
            'title' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => true,
                'size' => 250,
                'default' => '',
                'editable' => true
            ),
            'description' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => true,
                'size' => 2048,
                'default' => 'auto created content',
                'editable' => true
            ),
            'mime_type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => true,
                'size' => 64,
                'default' => 'application/octet-stream',
                'editable' => true
            ),
            'media_type' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => true,
                'size' => 64,
                'default' => 'application/octet-stream',
                'verbose' => 'Media type',
                'help_text' => 'This types allow you to category contents',
                'editable' => true
            ),
            'file_path' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => false,
                'size' => 250,
                'verbose' => 'File path',
                'help_text' => 'Content file path',
                'editable' => false,
                'readable' => false
            ),
            'file_name' => array(
                'type' => 'Pluf_DB_Field_Varchar',
                'is_null' => false,
                'size' => 250,
                'default' => 'unknown',
                'verbose' => 'file name',
                'help_text' => 'Content file name',
                'editable' => false
            ),
            'file_size' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'is_null' => false,
                'default' => 'no title',
                'verbose' => 'file size',
                'help_text' => 'content file size',
                'editable' => false
            ),
            'downloads' => array(
                'type' => 'Pluf_DB_Field_Integer',
                'is_null' => false,
                'default' => 0,
                'help_text' => 'content downloads number',
                'editable' => false
            ),
        );
    }

    /**
     * پیش ذخیره را انجام می‌دهد
     *
     * @param boolean $create
     *            حالت
     *            ساخت یا به روز رسانی را تعیین می‌کند
     */
    function preSave($create = false)
    {
        // File path
        $path = $this->getAbsloutPath();
        // file size
        if (file_exists($path)) {
            $this->file_size = filesize($path);
        } else {
            $this->file_size = 0;
        }
        // mime type (based on file name)
        $mime_type = $this->mime_type;
        if (! isset($mime_type) || $mime_type === 'application/octet-stream') {
            $fileInfo = Pluf_FileUtil::getMimeType($this->file_name);
            $this->mime_type = $fileInfo[0];
        }
    }

    /**
     * \brief Delete the resource
     *
     * Deletes the resource and files
     */
    function preDelete()
    {
        // remove related file
        $filename = $this->file_path . '/' . $this->id;
        if (is_file($filename)) {
            unlink($filename);
        }
    }

    /**
     * Gets absloutpath
     *
     * @return string
     */
    public function getAbsloutPath()
    {
        return $this->file_path . '/' . $this->id;
    }
}
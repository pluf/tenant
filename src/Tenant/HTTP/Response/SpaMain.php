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
 * @author maso
 *        
 */
class Tenant_HTTP_Response_SpaMain extends Pluf_HTTP_Response
{

    private $spaName;
    public $filePath;

    function __construct($filepath, $mimetype = null, $spaName = null)
    {
        parent::__construct($filepath, $mimetype);
        $this->spaName = $spaName;
        $this->filePath = $filepath;
    }

    /**
     *
     * {@inheritdoc}
     * @see Pluf_HTTP_Response::render()
     */
    function render($output_body = true)
    {
        $content = file_get_contents($this->filePath);
        $basePath = $this->spaName === null ? '/' : '/'. $this->spaName . '/';
        $content = preg_replace('/<!--\s*injector:base-tag\s*-->[\s\S]*<!--\s*endinjector\s*-->/', '<base href="' . $basePath . '">', $content);
        $content = preg_replace('/<!--\s*injector:base-path\s*-->[\s\S]*<!--\s*endinjector\s*-->/', $basePath, $content);
        $this->content = $content;
        parent::render($output_body);
    }
}

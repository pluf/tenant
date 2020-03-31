<?php

/**
 * A model of a tenant
 * @author maso <mostafa.barmshory@dpq.co.ir>
 *
 */
class Tenant_Tenant extends Pluf_Model
{

    /**
     * Initialized the system model
     *
     * @see Pluf_Model::init()
     */
    function init()
    {
        $this->_a['table'] = 'tenants';
        $this->_a['multitenant'] = false;
        $this->_a['mapped'] = true;
        $this->_a['cols'] = array(
            'id' => array(
                'type' => 'Sequence',
                'blank' => true,
                'editable' => false
            ),
            'level' => array(
                'type' => 'Integer',
                'blank' => true,
                'editable' => false
            ),
            'title' => array(
                'type' => 'Varchar',
                'blank' => true,
                'size' => 100
            ),
            'description' => array(
                'type' => 'Varchar',
                'blank' => true,
                'is_null' => true,
                'size' => 1024,
                'editable' => true
            ),
            'domain' => array(
                'type' => 'Varchar',
                'blank' => true,
                'is_null' => true,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'subdomain' => array(
                'type' => 'Varchar',
                'blank' => false,
                'is_null' => false,
                'unique' => true,
                'size' => 63,
                'editable' => true
            ),
            'validate' => array(
                'type' => 'Boolean',
                'default' => false,
                'blank' => true,
                'editable' => true
            ),
            'email' => array(
                'type' => 'Email',
                'blank' => true,
                'verbose' => 'Owner email',
                'editable' => true,
                'readable' => true
            ),
            'phone' => array(
                'type' => 'Varchar',
                'blank' => true,
                'verbose' => 'Owner phone',
                'editable' => true,
                'readable' => true
            ),
            'creation_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'editable' => false
            ),
            'modif_dtime' => array(
                'type' => 'Datetime',
                'blank' => true,
                'editable' => false
            ),
            /*
             * Relations
             */
            'parent_id' => array(
                'type' => 'Foreignkey',
                'model' => 'Tenant_Tenant',
                'blank' => true,
                'name' => 'parent',
                'graphql_name' => 'parent',
                'relate_name' => 'children',
                'editable' => false,
                'readable' => true
            ),
            'owners' => array(
                'type' => 'Manytomany',
                'blank' => true,
                'model' => 'Tenant_Owner',
                'relate_name' => 'tenants',
                'editable' => false,
                'graphql_name' => 'owners',
                'readable' => true
            )
            /*
         * XXX: maso, 2019: add following relations
         *
         * - Configurations
         * - Settings
         * - Members
         * - SPAs
         * - Tickets
         * - Invoice
         */
        );
        $this->_a['views'] = array();
    }

    /**
     * \brief Update date
     *
     * @param boolean $create
     *            create the
     */
    function preSave($create = false)
    {
        if ($this->id == '') {
            $this->creation_dtime = gmdate('Y-m-d H:i:s');
        }
        $this->modif_dtime = gmdate('Y-m-d H:i:s');
    }
}

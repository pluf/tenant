<?php
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

function Tenant_Shortcuts_GetMainTenant()
{
    $subdomain = Pluf::f('tenant_default', 'www');
    // if($subdomain === null){
    // throw new Pluf_Exception_DoesNotExist('tenant_default is not set!');
    // }
    $tenant = Pluf_Tenant::bySubDomain($subdomain);
    if ($tenant == null || $tenant->id <= 0) {
        throw new Pluf_Exception_DoesNotExist("Tenant not found (subdomain:" . $subdomain . ")");
    }
    return $tenant;
}

function Tenant_Shortcuts_NormalizeItemPerPage($request)
{
    $count = array_key_exists('_px_c', $request->REQUEST) ? intval($request->REQUEST['_px_c']) : 30;
    if ($count > 30)
        $count = 30;
    return $count;
}

/**
 *
 * @param Tenant_SPA $spa
 * @return Tenant_SPA_Manager
 */
function Tenant_Shortcuts_SpaManager($spa)
{
    // XXX: maso, 2017: read from settings
    $manager = new Tenant_SPA_Manager_Simple();
    return $manager;
}

function Tenant_Shortcuts_generateCurrentTenantObjectType()
{
    // tenant
    $Tenant_Tenant = null;
    $Tenant_Configuration = null;

    // render object types variables
    $User_Account = null;
    $User_Group = null;
    $User_Role = null;
    $User_Message = null;
    $User_Profile = null;

    $Tenant_Tenant = new ObjectType([
        'name' => 'Tenant_CurrentTenant',
        'fields' => function () use (&$Tenant_Tenant, &$Tenant_Configuration, &$User_Account) {
            return [
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->tenant->id;
                    }
                ],
                'level' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root->tenant->level;
                    }
                ],
                'title' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->title;
                    }
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->description;
                    }
                ],
                'domain' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->domain;
                    }
                ],
                'subdomain' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->subdomain;
                    }
                ],
                'validate' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($root) {
                        return $root->tenant->validate;
                    }
                ],
                'email' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->email;
                    }
                ],
                'phone' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->phone;
                    }
                ],
                'creation_dtime' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->creation_dtime;
                    }
                ],
                'modif_dtime' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->tenant->modif_dtime;
                    }
                ],
                'parent_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root->tenant->parent_id;
                    }
                ],
                'parent' => [
                    'type' => $Tenant_Tenant,
                    'resolve' => function ($root) {
                        return $root->tenant->get_parent();
                    }
                ],
                // relations: forenkey
                'configurations' => [
                    'type' => Type::listOf($Tenant_Configuration),
                    'resolve' => function ($root) {
                        $tenant = new Tenant_Tenant($root->tenant->id);
                        return $tenant->get_configurations_list();
                    }
                ],
                'settings' => [
                    'type' => Type::listOf($Tenant_Configuration),
                    'resolve' => function ($root) {
                        $sql = new Pluf_SQL('`mode`=%s', array(
                            Tenant_Setting::MOD_PUBLIC
                        ));
                        $seting = new Tenant_Setting();
                        $settings = $seting->getList(array(
                            'filter' => $sql->gen()
                        ));
                        if (! isset($settings)) {
                            $settings = array();
                        }
                        return $settings;
                    }
                ],
                'account' => [
                    'type' => $User_Account,
                    'resolve' => function ($root) {
                        return $root->account;
                    }
                ]
            ];
        }
    ]); //

    $Tenant_Configuration = new ObjectType([
        'name' => 'Tenant_Configuration',
        'fields' => function () use (&$Tenant_Tenant) {
            return [
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->id;
                    }
                ],
                'key' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->key;
                    }
                ],
                'value' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->value;
                    }
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->description;
                    }
                ]
            ];
        }
    ]);

    $User_Account = new ObjectType([
        'name' => 'User_Account',
        'fields' => function () use (&$User_Group, &$User_Role, &$User_Message, &$User_Profile) {
            return [
                // List of basic fields

                // id: Array( [type] => Pluf_DB_Field_Sequence [is_null] => 1 [editable] => [readable] => 1)
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->id;
                    }
                ],
                // login: Array( [type] => Pluf_DB_Field_Varchar [is_null] => [unique] => 1 [size] => 50 [editable] => [readable] => 1)
                'login' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->login;
                    }
                ],
                // date_joined: Array( [type] => Pluf_DB_Field_Datetime [is_null] => 1 [editable] => )
                'date_joined' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->date_joined;
                    }
                ],
                // last_login: Array( [type] => Pluf_DB_Field_Datetime [is_null] => 1 [editable] => )
                'last_login' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->last_login;
                    }
                ],
                // is_active: Array( [type] => Pluf_DB_Field_Boolean [is_null] => [default] => [editable] => )
                'is_active' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($root) {
                        return $root->is_active;
                    }
                ],
                // is_deleted: Array( [type] => Pluf_DB_Field_Boolean [is_null] => [default] => [editable] => )
                'is_deleted' => [
                    'type' => Type::boolean(),
                    'resolve' => function ($root) {
                        return $root->is_deleted;
                    }
                ],
                // Foreinkey value-groups: Array( [type] => Pluf_DB_Field_Manytomany [blank] => 1 [model] => User_Group [relate_name] => accounts [editable] => [graphql_name] => groups [readable] => 1)
                'groups' => [
                    'type' => Type::listOf($User_Group),
                    'resolve' => function ($root) {
                        return $root->get_groups_list();
                    }
                ],
                // Foreinkey value-roles: Array( [type] => Pluf_DB_Field_Manytomany [blank] => 1 [relate_name] => accounts [editable] => [model] => User_Role [graphql_name] => roles [readable] => 1)
                'roles' => [
                    'type' => Type::listOf($User_Role),
                    'resolve' => function ($root) {
                        return $root->get_roles_list();
                    }
                ],
                // relations: forenkey

                // Foreinkey list-account_id: Array()
                'messages' => [
                    'type' => Type::listOf($User_Message),
                    'resolve' => function ($root) {
                        return $root->get_messages_list();
                    }
                ],
                // Foreinkey list-account_id: Array()
                'profiles' => [
                    'type' => Type::listOf($User_Profile),
                    'resolve' => function ($root) {
                        return $root->get_profiles_list();
                    }
                ]
            ];
        }
    ]); //
    $User_Group = new ObjectType([
        'name' => 'User_Group',
        'fields' => function () use (&$User_Role, &$User_Account) {
            return [
                // List of basic fields

                // id: Array( [type] => Pluf_DB_Field_Sequence [blank] => 1 [readable] => 1 [editable] => )
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->id;
                    }
                ],
                // name: Array( [type] => Pluf_DB_Field_Varchar [is_null] => [size] => 50 [verbose] => name)
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->name;
                    }
                ],
                // description: Array( [type] => Pluf_DB_Field_Varchar [is_null] => 1 [size] => 250 [verbose] => description)
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->description;
                    }
                ],
                // Foreinkey value-roles: Array( [type] => Pluf_DB_Field_Manytomany [model] => User_Role [is_null] => 1 [editable] => [relate_name] => groups [graphql_name] => roles)
                'roles' => [
                    'type' => Type::listOf($User_Role),
                    'resolve' => function ($root) {
                        return $root->get_roles_list();
                    }
                ],
                // relations: forenkey

                // Foreinkey list-groups: Array()
                'accounts' => [
                    'type' => Type::listOf($User_Account),
                    'resolve' => function ($root) {
                        return $root->get_accounts_list();
                    }
                ]
            ];
        }
    ]); //
    $User_Role = new ObjectType([
        'name' => 'User_Role',
        'fields' => function () use (&$User_Account, &$User_Group) {
            return [
                // List of basic fields

                // id: Array( [type] => Pluf_DB_Field_Sequence [blank] => 1 [editable] => [readable] => 1)
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->id;
                    }
                ],
                // name: Array( [type] => Pluf_DB_Field_Varchar [is_null] => [size] => 50 [verbose] => name)
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->name;
                    }
                ],
                // description: Array( [type] => Pluf_DB_Field_Varchar [is_null] => 1 [size] => 250 [verbose] => description)
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->description;
                    }
                ],
                // application: Array( [type] => Pluf_DB_Field_Varchar [size] => 150 [is_null] => [verbose] => application [help_text] => The application using this permission, for example "YourApp", "CMS" or "SView".)
                'application' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->application;
                    }
                ],
                // code_name: Array( [type] => Pluf_DB_Field_Varchar [is_null] => [size] => 100 [verbose] => code name [help_text] => The code name must be unique for each application. Standard permissions to manage a model in the interface are "Model_Name-create", "Model_Name-update", "Model_Name-list" and "Model_Name-delete".)
                'code_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->code_name;
                    }
                ],
                // relations: forenkey

                // Foreinkey list-roles: Array()
                'accounts' => [
                    'type' => Type::listOf($User_Account),
                    'resolve' => function ($root) {
                        return $root->get_accounts_list();
                    }
                ],
                // Foreinkey list-roles: Array()
                'groups' => [
                    'type' => Type::listOf($User_Group),
                    'resolve' => function ($root) {
                        return $root->get_groups_list();
                    }
                ]
            ];
        }
    ]); //
    $User_Message = new ObjectType([
        'name' => 'User_Message',
        'fields' => function () use (&$User_Account) {
            return [
                // List of basic fields

                // id: Array( [type] => Pluf_DB_Field_Sequence [blank] => 1 [editable] => [readable] => 1)
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->id;
                    }
                ],
                // Foreinkey value-account_id: Array( [type] => Pluf_DB_Field_Foreignkey [model] => User_Account [name] => account [graphql_name] => account [relate_name] => messages [is_null] => [editable] => )
                'account_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root->account_id;
                    }
                ],
                // Foreinkey object-account_id: Array( [type] => Pluf_DB_Field_Foreignkey [model] => User_Account [name] => account [graphql_name] => account [relate_name] => messages [is_null] => [editable] => )
                'account' => [
                    'type' => $User_Account,
                    'resolve' => function ($root) {
                        return $root->get_account();
                    }
                ],
                // message: Array( [type] => Pluf_DB_Field_Text [is_null] => [editable] => [readable] => 1)
                'message' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->message;
                    }
                ],
                // creation_dtime: Array( [type] => Pluf_DB_Field_Datetime [is_null] => 1 [editable] => [readable] => 1)
                'creation_dtime' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->creation_dtime;
                    }
                ]
                // relations: forenkey
            ];
        }
    ]); //
    $User_Profile = new ObjectType([
        'name' => 'User_Profile',
        'fields' => function () use (&$User_Account) {
            return [
                // List of basic fields

                // id: Array( [type] => Pluf_DB_Field_Sequence [is_null] => 1 [editable] => [readable] => 1)
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root->id;
                    }
                ],
                // first_name: Array( [type] => Pluf_DB_Field_Varchar [is_null] => 1 [size] => 100)
                'first_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->first_name;
                    }
                ],
                // last_name: Array( [type] => Pluf_DB_Field_Varchar [is_null] => [size] => 100)
                'last_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->last_name;
                    }
                ],
                // public_email: Array( [type] => Pluf_DB_Field_Email [is_null] => 1 [editable] => [readable] => 1)
                'public_email' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->public_email;
                    }
                ],
                // language: Array( [type] => Pluf_DB_Field_Varchar [is_null] => 1 [default] => en [size] => 5)
                'language' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->language;
                    }
                ],
                // timezone: Array( [type] => Pluf_DB_Field_Varchar [is_null] => 1 [default] => Europe/Berlin [size] => 45 [verbose] => time zone [help_text] => Time zone of the user to display the time in local time.)
                'timezone' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root->timezone;
                    }
                ],
                // Foreinkey value-account_id: Array( [type] => Pluf_DB_Field_Foreignkey [model] => User_Account [name] => account [relate_name] => profiles [graphql_name] => account [is_null] => [editable] => )
                'account_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root->account_id;
                    }
                ],
                // Foreinkey object-account_id: Array( [type] => Pluf_DB_Field_Foreignkey [model] => User_Account [name] => account [relate_name] => profiles [graphql_name] => account [is_null] => [editable] => )
                'account' => [
                    'type' => $User_Account,
                    'resolve' => function ($root) {
                        return $root->get_account();
                    }
                ]
                // relations: forenkey
            ];
        }
    ]); //

    return $Tenant_Tenant;
}

/**
 * Returns the Tenant_Configuration with given key if exist, else returns false.
 * @param string $key
 * @return boolean|Tenant_Configuration
 */
function Tenant_Shortcuts_GetConfiguration($key, $tenantId){
    $model = new Tenant_Configuration();
    $where = '`key` = ' . $model->_toDb($key, 'key') . 'AND `tenant`='.$model->_toDb($tenantId, 'tenant');
    $configs = $model->getList(array(
        'filter' => $where
    ));
    if ($configs === false or count($configs) !== 1) {
        return false;
    }
    return $configs[0];
}
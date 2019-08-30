<?php 
// Import
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
/**
 * Render class of GraphQl
 */
class Pluf_GraphQl_Schema_Pluf_Paginator_Tenant_Tenant { 
    public function render($rootValue, $query) {
        // render object types variables
         $Tenant_Tenant = null;
         $Tenant_Configuration = null;
        // render code
         //
        $Tenant_Tenant = new ObjectType([
            'name' => 'Tenant_Tenant',
            'fields' => function () use (&$Tenant_Tenant, &$Tenant_Configuration){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1    [editable] => )
                    'id' => [
                        'type' => Type::id(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //level: Array(    [type] => Pluf_DB_Field_Integer    [blank] => 1    [editable] => )
                    'level' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->level;
                        },
                    ],
                    //title: Array(    [type] => Pluf_DB_Field_Varchar    [blank] => 1    [size] => 100)
                    'title' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->title;
                        },
                    ],
                    //description: Array(    [type] => Pluf_DB_Field_Varchar    [blank] => 1    [is_null] => 1    [size] => 1024    [editable] => 1)
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->description;
                        },
                    ],
                    //domain: Array(    [type] => Pluf_DB_Field_Varchar    [blank] => 1    [is_null] => 1    [unique] => 1    [size] => 63    [editable] => 1)
                    'domain' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->domain;
                        },
                    ],
                    //subdomain: Array(    [type] => Pluf_DB_Field_Varchar    [blank] =>     [is_null] =>     [unique] => 1    [size] => 63    [editable] => 1)
                    'subdomain' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->subdomain;
                        },
                    ],
                    //validate: Array(    [type] => Pluf_DB_Field_Boolean    [default] =>     [blank] => 1    [editable] => )
                    'validate' => [
                        'type' => Type::boolean(),
                        'resolve' => function ($root) {
                            return $root->validate;
                        },
                    ],
                    //email: Array(    [type] => Pluf_DB_Field_Email    [blank] => 1    [verbose] => Owner email    [editable] => 1    [readable] => 1)
                    'email' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->email;
                        },
                    ],
                    //phone: Array(    [type] => Pluf_DB_Field_Varchar    [blank] => 1    [verbose] => Owner phone    [editable] => 1    [readable] => 1)
                    'phone' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->phone;
                        },
                    ],
                    //creation_dtime: Array(    [type] => Pluf_DB_Field_Datetime    [blank] => 1    [editable] => )
                    'creation_dtime' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->creation_dtime;
                        },
                    ],
                    //modif_dtime: Array(    [type] => Pluf_DB_Field_Datetime    [blank] => 1    [editable] => )
                    'modif_dtime' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->modif_dtime;
                        },
                    ],
                    //Foreinkey value-parent_id: Array(    [type] => Pluf_DB_Field_Foreignkey    [model] => Tenant_Tenant    [blank] => 1    [name] => parent    [graphql_name] => parent    [relate_name] => children    [editable] =>     [readable] => 1)
                    'parent_id' => [
                            'type' => Type::int(),
                            'resolve' => function ($root) {
                                return $root->parent_id;
                            },
                    ],
                    //Foreinkey object-parent_id: Array(    [type] => Pluf_DB_Field_Foreignkey    [model] => Tenant_Tenant    [blank] => 1    [name] => parent    [graphql_name] => parent    [relate_name] => children    [editable] =>     [readable] => 1)
                    'parent' => [
                            'type' => $Tenant_Tenant,
                            'resolve' => function ($root) {
                                return $root->get_parent();
                            },
                    ],
                    // relations: forenkey 
                    
                    //Foreinkey list-parent_id: Array()
                    'children' => [
                            'type' => Type::listOf($Tenant_Tenant),
                            'resolve' => function ($root) {
                                return $root->get_children_list();
                            },
                    ],
                    //Foreinkey list-tenant: Array()
                    'configurations' => [
                            'type' => Type::listOf($Tenant_Configuration),
                            'resolve' => function ($root) {
                                return $root->get_configurations_list();
                            },
                    ],
                    
                ];
            }
        ]); //
        $Tenant_Configuration = new ObjectType([
            'name' => 'Tenant_Configuration',
            'fields' => function () use (&$Tenant_Tenant){
                return [
                    // List of basic fields
                    
                    //id: Array(    [type] => Pluf_DB_Field_Sequence    [blank] => 1    [editable] =>     [readable] => 1)
                    'id' => [
                        'type' => Type::id(),
                        'resolve' => function ($root) {
                            return $root->id;
                        },
                    ],
                    //key: Array(    [type] => Pluf_DB_Field_Varchar    [is_null] =>     [unique] => 1    [size] => 250    [editable] => 1    [readable] => 1)
                    'key' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->key;
                        },
                    ],
                    //value: Array(    [type] => Pluf_DB_Field_Varchar    [is_null] => 1    [size] => 250    [editable] => 1    [readable] => 1)
                    'value' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->value;
                        },
                    ],
                    //description: Array(    [type] => Pluf_DB_Field_Varchar    [is_null] => 1    [size] => 250    [editable] => 1    [readable] => 1)
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->description;
                        },
                    ],
                    //creation_dtime: Array(    [type] => Pluf_DB_Field_Datetime    [is_null] => 1    [editable] =>     [readable] => 1)
                    'creation_dtime' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->creation_dtime;
                        },
                    ],
                    //modif_dtime: Array(    [type] => Pluf_DB_Field_Datetime    [is_null] => 1    [editable] =>     [readable] => 1)
                    'modif_dtime' => [
                        'type' => Type::string(),
                        'resolve' => function ($root) {
                            return $root->modif_dtime;
                        },
                    ],
                    //Foreinkey value-tenant: Array(    [type] => Pluf_DB_Field_Foreignkey    [model] => Tenant_Tenant    [is_null] =>     [editable] =>     [readable] => 1    [relate_name] => configurations    [graphql_name] => tenant    [graphql_feild] => 1)
                    'tenant' => [
                            'type' => Type::int(),
                            'resolve' => function ($root) {
                                return $root->tenant;
                            },
                    ],
                    //Foreinkey object-tenant: Array(    [type] => Pluf_DB_Field_Foreignkey    [model] => Tenant_Tenant    [is_null] =>     [editable] =>     [readable] => 1    [relate_name] => configurations    [graphql_name] => tenant    [graphql_feild] => 1)
                    'tenant' => [
                            'type' => $Tenant_Tenant,
                            'resolve' => function ($root) {
                                return $root->get_tenant();
                            },
                    ],
                    // relations: forenkey 
                    
                    
                ];
            }
        ]);$itemType =$Tenant_Tenant;$rootType =new ObjectType([
            'name' => 'Pluf_paginator',
            'fields' => function () use (&$itemType){
                return [
                    'counts' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->fetchItemsCount();
                        }
                    ],
                    'current_page' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->current_page;
                        }
                    ],
                    'items_per_page' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->items_per_page;
                        }
                    ],
                    'page_number' => [
                        'type' => Type::int(),
                        'resolve' => function ($root) {
                            return $root->getNumberOfPages();
                        }
                    ],
                    'items' => [
                        'type' => Type::listOf($itemType),
                        'resolve' => function ($root) {
                            return $root->fetchItems();
                        }
                    ],
                ];
            }
        ]);
        try {
            $schema = new Schema([
                'query' => $rootType
            ]);
            $result = GraphQL::executeQuery($schema, $query, $rootValue);
            return $result->toArray();
        } catch (Exception $e) {
            throw new Pluf_Exception_BadRequest($e->getMessage());
        }
    }
}

<?php


//  This all function are check for each feature's permission
use App\Helpers\permissionHelpers;


//  permission check function



function checkPermission($feature,$permission){
    return  permissionHelpers::checkPermission($feature,$permission);
};

function appAdmin($id, $name){
if ($id!=1 && $name!=\App\Models\Role::find(1)->name){
    return true;
}
}

function multiHasAll($features)
{
    $permissions = array('create', 'view', 'update', 'delete', 'import', 'export', 'print');

    foreach ($features as $feature){
        foreach ($permissions as $permission) {
            if (checkPermission($feature, $permission)) {
                return true;
            }
        }
    }

    return false;
}
function hasAll($feature)
{
    $permissions = array('create', 'view', 'update', 'delete', 'import', 'export', 'print');

    foreach ($permissions as $permission) {
        if (checkPermission($feature, $permission)) {
            return true;
        }
    }

    return false;
}

function hasCreate($feature)
{
    return  checkPermission($feature, 'create');
}
function hasView($feature){
    return  checkPermission($feature,'view');
}
function hasUpdate($feature)
{
    return  checkPermission($feature, 'update');
}
function hasDelete($feature)
{
    return  checkPermission($feature, 'delete');
}
function hasImport($feature)
{
    return  checkPermission($feature, 'import');
}
function hasExport($feature)
{
    return  checkPermission($feature, 'export');
}
function hasPrint($feature)
{
    return  checkPermission($feature, 'print');
}

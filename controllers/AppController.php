<?php


namespace SBLH\Controllers;

class AppController
{
    public static function init()
    {
        AdminController::addActions();
        MetaFieldsController::addActions();
    }
}

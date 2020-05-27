<?php
    
    //Call database
    $class_get_database = new Get_Database($pdo);
    $function_get_db    = $class_get_database->get_db();

    //Check if database information is exist, redirect to index.php, if not, redirect to setup page
    require __DIR__.'/cr-include/database/connection.php';
    //Call general setting
    $o_general_setting        = new General_Setting($pdo);
    //Set timezone
    $v_set_timezone           = $o_general_setting->set_timezone();
    $get_timezone_city        = substr($v_set_timezone->cr_settingValue, 12);
    if(!empty($v_set_timezone->cr_settingValue)) {
        date_default_timezone_set($get_timezone_city);
    }
    $date_for_now = new DateTime();
    $date_for_now->setTimezone(new DateTimeZone($get_timezone_city));
    $now_date     = $date_for_now->format('Y-m-d H:i:s');
    //Same format as NOW(), use to save datetime value to database, without timezone, that will be diffrent datetime insert in database
    $v_general_setting_url    = $o_general_setting->get_url();
    $v_general_setting_folder = $o_general_setting->get_folder_name();
    $v_get_url                = $v_general_setting_url->cr_settingValue;
    $v_folder_name            = $v_general_setting_folder->cr_settingValue;
    $your_url                 = $v_get_url . $_SERVER['SERVER_NAME'] . "/" . $v_folder_name;
    //Call themes
    $class_appearance  = new Appearance($pdo);
    $v_get_themes      = $class_appearance->get_theme();
    $v_themes          = $v_get_themes->cr_settingValue;
    $v_style           = $your_url. "/cr-content/themes/" . $v_themes . "/";
    //Call all things here
    require_once "cr-include/global-function.php";
    require_once "cr-content/themes/$v_themes/index.php";
    //Save visitor ip
    $visitor_ip        = get_real_ip();
    $visitor_browser   = get_visitor_browser();
    $v_save_visitor_ip = $o_general_setting->save_visitor($visitor_ip, $visitor_browser['name'], $visitor_browser['platform'], $now_date);
    
?>
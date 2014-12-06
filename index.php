<?php

include("lib/Downcast/DowncastCacheController.php");

        DowncastCacheController::setup();
/*
 * If Cache is enabled, use it
 */

if ( DowncastCacheController::isEnabled() ){

    DowncastCacheController::getPageFromCache();

} else {
    /*
     *      
     * If Cache is Disabled, Get a new page
     * 
     */

    DowncastCacheController::getNewPage();

}
?>
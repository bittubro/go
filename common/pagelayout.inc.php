<?php
/**************************************************************************
* This file is part of the WebIssues Server program
* Copyright (C) 2006 Michał Męciński
* Copyright (C) 2007-2017 WebIssues Team
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
**************************************************************************/

if ( !defined( 'WI_VERSION' ) ) die( -1 );

class Common_PageLayout extends System_Web_Component
{
    protected function __construct()
    {
        parent::__construct();
    }

    protected function execute()
    {
        $application = System_Core_Application::getInstance();

        $this->siteName = $this->tr( 'WebIssues' );
        try {
            if ( $application->getConnection()->isOpened() ) {
                $serverManager = new System_Api_ServerManager();
                $server = $serverManager->getServer();
                $this->siteName = $server[ 'server_name' ];
            }
        } catch ( Exception $ex ) {
            $application->handleException( $ex );
        }

        $this->icon = '/common/images/webissues.ico';

        $site = System_Core_Application::getInstance()->getSite();
        $devMode = $site->getConfig( 'dev_mode' );
        $devUrl = $site->getConfig( 'dev_url' );

        if ( !$devMode ) {
            $assetsPath = WI_ROOT_DIR . '/assets/assets.json';

            if ( !file_exists( $assetsPath ) )
                throw new System_Core_Exception( 'Assets were not built' );

            $assets = json_decode( file_get_contents( $assetsPath ), true );

            $this->styleUrl = '/assets/' . $assets[ 'common' ][ 'css' ];
            $this->scriptUrl = '/assets/' . $assets[ 'common' ][ 'js' ];
        } else {
            $this->styleUrl = null;
            $this->scriptUrl = $devUrl . 'js/common.js';
        }

        $this->manualUrl = $application->getManualUrl();
    }
}

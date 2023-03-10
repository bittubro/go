<?php
/**************************************************************************
* This file is part of the WebIssues Server program
* Copyright (C) 2006 Michał Męciński
* Copyright (C) 2007-2020 WebIssues Team
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

require_once( '../../../../system/bootstrap.inc.php' );

class Server_Api_Settings_Email_Load
{
    public $access = 'admin';

    public $params = array();

    public function run()
    {
        $serverManager = new System_Api_ServerManager();

        $engine = $serverManager->getSetting( 'email_engine' );
        $settings[ 'emailEngine' ] = $engine;

        if ( $engine != null )
            $settings[ 'emailFrom' ] = $serverManager->getSetting( 'email_from' );

        if ( $engine == 'smtp' ) {
            $settings[ 'smtpServer' ] = $serverManager->getSetting( 'smtp_server' );
            $settings[ 'smtpPort' ] = (int)$serverManager->getSetting( 'smtp_port' );
            $settings[ 'smtpEncryption' ] = $serverManager->getSetting( 'smtp_encryption' );
            $settings[ 'smtpUser' ] = $serverManager->getSetting( 'smtp_user' );
            $settings[ 'smtpPassword' ] = $serverManager->getSetting( 'smtp_password' );
            $settings[ 'smtpUseOAuth' ] = $serverManager->getSetting( 'smtp_use_oauth' ) == 1;
        }

        $result[ 'settings' ] = $settings;

        $site = System_Core_Application::getInstance()->getSite();
        $oauth = $site->loadNamedConfigFile( 'oauth' );

        $result[ 'hasOAuth' ] = $oauth != null;

        return $result;
    }
}

System_Bootstrap::run( 'Server_Api_Application', 'Server_Api_Settings_Email_Load' );

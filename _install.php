<?php
/**
 * GaiaEHR
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

if(!defined('_UntuEXEC')) die('No direct access allowed.');
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>GaiaEHR :: New Site Setup</title>
    <script type="text/javascript" src="lib/extjs-4.1.1a/ext-all.js"></script>
    <link rel="stylesheet" type="text/css" href="resources/css/ext-all.css">
    <link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
    <link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">

    <link rel="shortcut icon" href="favicon.ico">
    <script src="data/api.php"></script>
    <script type="text/javascript">

        var app, lang = {};
        function say(a){ console.log(a); }
        function i18n(key){ return lang[key] || key; }
        Ext.Loader.setConfig({
            enabled: true,
            disableCaching: false,
            paths: {
                'App': 'app'
            }
        });
    </script>
    <script type="text/javascript">
        function say(a){
            console.log(a);
        }
        for(var x = 0; x < App.data.length; x++){
            Ext.direct.Manager.addProvider(App.data[x]);
        }
        Ext.require('App.view.sitesetup.SiteSetup');
        Ext.onReady(function(){
            app = Ext.create('App.view.sitesetup.SiteSetup').show();
        });
    </script>
</head>
<body id="login">
<div id="bg_logo"></div>
<div id="copyright">
    GaiaEHR | <a href="javascript:void(0)" onClick="app.winCopyright.show();">Copyright Notice</a>
</div>
</body>
</html>

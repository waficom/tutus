<?php
/**
 * GaiaEHR (Electronic Health Records)
 * Copyright (C) 2013 Certun, LLC.
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

if (!defined('_UntuEXEC')) die('No direct access allowed.');
$_SESSION['site']['flops'] = 0;
?>
<html>
	<head>
		<script type="text/javascript">
			var app,
				acl = {},
				user = {},
				settings = {},
				globals = {},
				ext = '<?php print $_SESSION['extjs'] ?>',
				version = '<?php print $_SESSION['version'] ?>',
				requires,
				AppClipboard;
		</script>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Untusoft Application</title>
		<link rel="stylesheet" type="text/css" href="resources/css/dashboard.css">
		<link rel="stylesheet" type="text/css" href="resources/css/ext-all-gray.css">
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar.css"/>
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/calendar-colors.css"/>
		<link rel="stylesheet" type="text/css" href="lib/extensible-1.5.1/resources/css/recurrence.css"/>
		<link rel="stylesheet" type="text/css" href="resources/css/style_newui.css">
		<link rel="stylesheet" type="text/css" href="resources/css/custom_app.css">
		<link rel="shortcut icon" href="favicon.ico">
	</head>
	<body>
		<!-- Loading Mask -->
		<div id="mainapp-loading-mask" class="x-mask mitos-mask"></div>
		<div id="mainapp-x-mask-msg">
			<div id="mainapp-loading" class="x-mask-msg mitos-mask-msg">
				<div>
					Loading...
				</div>
			</div>
		</div>

        <!-- slide down message div -->
        <div id="msg-div"></div>



        <!-- Ext library -->
		<script type="text/javascript" src="lib/extjs-4.1.1a/ext-all-debug.js"></script>


		<!-- JSrouter and Ext.deirect API files -->
		<script src="JSrouter.php"></script>
		<script src="data/api.php"></script>
		<script type="text/javascript" src="lib/ZeroClipboard/ZeroClipboard.js"></script>

        <script type="text/javascript">

            window.i18n = function(key){
                return window.lang[key] || '*'+key+'*';
            };

            window.say = function(a){
                console.log(a);
            };

            window.g = function(global){
	            return globals[global] || false;
            };

            ZeroClipboard.config( { moviePath: 'lib/ZeroClipboard/ZeroClipboard.swf' } );
            AppClipboard = new ZeroClipboard();
            AppClipboard.on("complete", function (client, args) {
	            app.msg(i18n('sweet'), args.text + ' - ' + i18n('copied_to_clipboard'));
            });

			/**
			 * Ext Localization file
			 * Using a anonymous function, in javascript.
			 * Is not intended to be used globally just this once.
			 */
            (function(){
                document.write('<script type="text/javascript" src="lib/extjs-4.1.1a/locale/' + i18n('i18nExtFile') + '?_v' + version + '"><\/script>')
            })();            // Set and enable Ext.loader for dynamic class loading
            Ext.Loader.setConfig({
                enabled: true,
                disableCaching: false,
                paths: {
                    'Ext': 'lib/extjs-4.1.1a/src',
                    'Ext.ux': 'app/ux/ux',
                    'App': 'app',
                    'Modules': 'modules',
                    'Extensible': 'lib/extensible-1.5.1/src'
                }
            });

			for(var x = 0; x < App.data.length; x++){
				Ext.direct.Manager.addProvider(App.data[x]);
			}
			Ext.direct.Manager.on('exception', function(e, o){
				say(e);
				app.alert(
					'<p><span style="font-weight:bold">'+ (e.where != 'undefined' ? e.message : e.message.replace(/\n/g,''))  +'</span></p><hr>' +
						'<p>'+ (typeof e.where != 'undefined' ? e.where.replace(/\n/g,'<br>') : e.data) +'</p>',
					'error'
				);
			});
		</script>
		<script type="text/javascript" src="app/ux/Overrides.js"></script>
		<script type="text/javascript" src="app/ux/VTypes.js"></script>

		<script type="text/javascript">
			requires = [
				'Ext.ux.LiveSearchGridPanel',
				'Ext.ux.SlidingPager',
				'Ext.ux.PreviewPlugin',
				'Ext.ux.form.SearchField',
				'App.ux.RatingField',
				'App.ux.grid.GridToHtml',
				'App.ux.grid.Printer',

				/**
				 * Load the models, the model are the representative of the database
				 * table structure with modifications behind the PHP counterpart.
				 * All table should be declared here, and Sencha's ExtJS models.
				 * This are spread in all the core application.
				 */
//                'App.model.administration.Applications',
                'App.model.administration.Globals',
//                'App.model.administration.ListOptions',
//                'App.model.administration.Lists',
                'App.model.administration.Modules',
                'App.model.administration.User',

//                'App.model.miscellaneous.OfficeNotes',

                'App.model.navigation.Navigation',


				/**
				 * Load all the stores used by GaiaEHR
				 * this includes ComboBoxes, and other stores used by the web application
				 * most of this stores are consumed by the dataStore directory.
				 */
                'App.store.administration.Globals',

                'App.store.administration.User',
//                'App.store.administration.XtypesComboModel',

//                'App.store.miscellaneous.OfficeNotes',

				/*
				 * Load the activity by the user
				 * This will detect the activity of the user, if the user are idle by a
				 * certain time, it will logout.
				 */
				'App.ux.ActivityMonitor',
				/*
				 * Load the classes that the CORE application needs
				 */
				'App.ux.AbstractPanel',
				'App.ux.ManagedIframe',
				'App.ux.NodeDisabled',
//				'App.ux.PhotoIdWindow',
				/*
				 * Load the RenderPanel
				 * This is the main panel when all the forms are rendered.
			     */
				'App.ux.RenderPanel',
				/*
				 * Load the charts related controls
				 */
				'Ext.fx.target.Sprite',
				/*
				 * Load the DropDown related components
				 */
				'Ext.dd.DropZone', 'Ext.dd.DragZone',
				/*
				 * Load the Extensible related controls and panels
				 * This is the Calendar Component that GaiaEHR uses.
				 */
				/*
				 * Load the form specific related fields
				 * Not all the fields are the same.
				 */
				'App.ux.form.fields.Help',
				'App.ux.form.fields.Checkbox',
				'App.ux.form.fields.ColorPicker',
				'App.ux.form.fields.Currency',
				'App.ux.form.fields.CustomTrigger',
				'App.ux.form.fields.DateTime',
				'App.ux.form.fields.Percent',
				'App.ux.form.fields.plugin.BadgeText',
				'App.ux.form.AdvanceForm',
				'App.ux.form.Panel',
				'App.ux.grid.EventHistory',
				'App.ux.grid.RowFormEditing',
				'App.ux.grid.RowFormEditor',
				/*
				 * Load the combo boxes spread on all the web application
				 * remember this are all reusable combo boxes.
				 */
				'App.ux.combo.Combo',
				'App.ux.combo.Providers',
				'App.ux.combo.Race',
				'App.ux.combo.Roles',
				'App.ux.combo.Sex',
				'App.ux.combo.SmokingStatus',
				'App.ux.combo.Surgery',
				'App.ux.combo.TaxId',
				'App.ux.combo.Templates',
				'App.ux.combo.Themes',
				'App.ux.combo.Time',
				'App.ux.combo.Titles',
				'App.ux.combo.TransmitMethod',
				'App.ux.combo.Types',
				'App.ux.combo.Units',
				'App.ux.combo.Users',
				'App.ux.combo.YesNoNa',
				'App.ux.combo.YesNo',
				'App.ux.window.Window',
				'App.ux.NodeDisabled',
//				'App.view.search.PatientSearch',
				/*
				 * Load the patient related panels
				 */
/*
				'App.view.dashboard.panel.PortalColumn',
				'App.view.dashboard.panel.PortalDropZone',
				'App.view.dashboard.panel.PortalPanel',
				'App.view.dashboard.panel.OnotesPortlet',
				'App.view.dashboard.panel.VisitsPortlet',
*/
				'App.view.dashboard.Dashboard',
				/*
				* Load the root related panels
				*/
				//'App.view.calendar.ExtensibleAll',
				'App.view.calendar.Calendar',
//				'App.view.messages.Messages',
				/*
				/*
				 * Load the administration related panels
				 */
//				'App.view.administration.Applications',
//				'App.view.administration.DataManager',
//				'App.view.administration.Documents',
//				'App.view.administration.Facilities',
				'App.view.administration.Globals',
//				'App.view.administration.Layout',
//				'App.view.administration.Lists',
				'App.view.administration.Log',
				'App.view.administration.Modules',
				'App.view.administration.Roles',
//				'App.view.administration.ExternalDataLoads',
				'App.view.administration.Users',
				/*
				 * Load the miscellaneous related panels
				 */
//				'App.view.miscellaneous.AddressBook',
				'App.view.miscellaneous.MyAccount',
				'App.view.miscellaneous.MySettings',
				'App.view.miscellaneous.OfficeNotes',
//				'App.view.miscellaneous.Websearch',
//				'App.view.signature.SignatureWindow',
				/*
				 * Dynamically load the modules
				 */
				'Modules.Module'
			];
            (function(){
                var scripts = document.getElementsByTagName('script'), localhostTests = [/^localhost$/, /\b(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(:\d{1,5})?\b/ // IP v4
                ], host = window.location.hostname, isDevelopment = null, queryString = window.location.search, test, path, i, ln, scriptSrc, match;
                for(i = 0, ln = scripts.length; i < ln; i++){
                    scriptSrc = scripts[i].src;
                    match = scriptSrc.match(/bootstrap\.js$/);
                    if(match){
                        path = scriptSrc.substring(0, scriptSrc.length - match[0].length);
                        break;
                    }
                }
                if(queryString.match('(\\?|&)debug') !== null){
                    isDevelopment = true;
                }else if(queryString.match('(\\?|&)nodebug') !== null){
                    isDevelopment = false;
                }
                if(isDevelopment === null){
                    for(i = 0, ln = localhostTests.length; i < ln; i++){
                        test = localhostTests[i];
                        if(host.search(test) !== -1){
                            isDevelopment = true;
                            break;
                        }
                    }
                }
                if(isDevelopment === null && window.location.protocol === 'file:'){
                    isDevelopment = true;
                }
                if(isDevelopment || !isDevelopment){
                    say('Loading (Development)');
                    //				var jsb3Buffer = '"files": [';
                    document.write('<script type="text/javascript" charset="UTF-8" src="app/view/calendar/ExtensibleAll.js?_v' + version + '"><\/script>');
                    for(var r = 0; r < requires.length; r++){
                        document.write('<script type="text/javascript" charset="UTF-8" src="' + Ext.Loader.getPath(requires[r]) + '?_v' + version + '"><\/script>');
                        //						var arrayBuffer = Ext.Loader.getPath(requires[r]).split('/'),
                        //								fileName = arrayBuffer.pop();
                        //								filePath = arrayBuffer.join('/');
                        //				        jsb3Buffer = jsb3Buffer + '{' +
                        //					        '"path": "'+filePath+'/",' +
                        //						    '"name": "'+fileName+'"' +
                        //				            '},';
                    }
                    //			   jsb3Buffer = jsb3Buffer+' ]';
                }else{
                    say('Loading (Production)');
                    document.write('<script type="text/javascript" charset="UTF-8" src="app/app-all.js' + '?_v' + version + '"><\/script>');
                }
            })();
            /**
			 * Function to Copy to the clip board.
			 * This function is consumable in all the application.
			 */
            function copyToClipBoard(token){
                app.msg('Sweet!', token + ' copied to clipboard, Ctrl-V or Paste where need it.');
                if(window.clipboardData){
                    window.clipboardData.setData('text', token);
                    return null;
                }else{
                    return (token);
                }
            }
            /**
			 * onWebCamComplete
			 * ???
			 */
            function onWebCamComplete(msg){
                app.onWebCamComplete(msg);
            }
            /**
			 * Function to pop-up a Window and enable the user to print the QR Code.
			 */
            function printQRCode(pid){
                var src = settings.site_url + '/patients/' + app.patient.pid + '/patientDataQrCode.png?';
                app.QRCodePrintWin = window.open(src, 'QRCodePrintWin', 'left=20,top=20,width=800,height=600,toolbar=0,resizable=0,location=1,scrollbars=0,menubar=0,directories=0');
                Ext.defer(function(){
                    app.QRCodePrintWin.print();
                }, 1000);
            }
            /**
			 * Sencha ExtJS OnReady Event
			 * When all the JS code is loaded execute the entire code once.
			 */
            Ext.application({
                name: 'App',
                models:[

                ],
                stores:[

                ],
                views:[

                ],
                controllers:[
	                'Cron',
	                'DualScreen',
	                'LogOut',
	                'Navigation',
	                'Support',
	                'Notification'

                ],
                launch: function() {

                    App.Current = this;

                    CronJob.run(function(){
                        say('Loading...');
                        window.app = Ext.create('App.view.Viewport');
                    });
                }
            });
		</script>
	</body>
</html>
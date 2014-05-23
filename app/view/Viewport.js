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

Ext.define('App.view.Viewport', {
    extend: 'Ext.Viewport',
    // app settings
    requires: window.requires, // array is defined on _app.php
    user: window.user, // array defined on _app.php
    version: window.version, // string defined on _app.php
    minWidthToFullMode: 1585, // full mode = nav expanded
    currency: g('gbl_currency_symbol'), // currency used

//	enablePoolAreaFadeInOut: eval(g('enable_poolarea_fade_in_out')),

	// end app settings
    initComponent: function(){
        Ext.tip.QuickTipManager.init();
        var me = this;

	    me.nav = me.getController('Navigation');
	    me.cron = me.getController('Cron');
	    me.log = me.getController('LogOut');
	    me.dual = me.getController('DualScreen');

	    me.logged = true;

        if(eval(g('enable_dual_monitor'))) me.dual.startDual();

	    me.lastCardNode = null;
        me.prevNode = null;
        me.fullMode = window.innerWidth >= me.minWidthToFullMode;

        /**
         * Create the model/store of the AuditLog
         * @type {*}
         */
        //me.User = Ext.ModelManager.getModel('App.model.administration.AuditLog');


        /*
         * TODO: this should be managed by the language files
         * The language file has a definition for this.
         */
        if(me.currency == '$'){
            me.icoMoney = 'icoDollar';
        }else if(me.currency == '€'){
            me.icoMoney = 'icoEuro';
        }else if(me.currency == '£'){
            me.icoMoney = 'icoLibra';
        }else if(me.currency == '¥'){
            me.icoMoney = 'icoYen';
        }

        /**
         * The panel definition for the the TreeMenu & the support button
         */
        me.navColumn = Ext.create('Ext.panel.Panel', {
            title: i18n('navigation'),
            action: 'mainNavPanel',
            layout: 'border',
            region: globals['main_navigation_menu_left'],
            width: parseFloat(globals['gbl_nav_area_width']),
            split: true,
            collapsible: true,
            collapsed: false,
            items: [
                {
                    xtype: 'treepanel',
                    region: 'center',
	                action:'mainNav',
                    cls: 'nav_tree',
                    hideHeaders: true,
                    rootVisible: false,
                    border: false,
                    width: parseFloat(globals['gbl_nav_area_width']),
	                store: Ext.create('App.store.navigation.Navigation', {
		                autoLoad: true
	                })
                }
            ]
        });

        /**
         * MainPanel is where all the pages are displayed
         */
        me.MainPanel = Ext.create('Ext.container.Container', {
            region: 'center',
            layout: 'card',
            border: true,
            itemId: 'MainPanel',
            defaults: {
                layout: 'fit',
                xtype: 'container'
            },
            listeners: {
                scope: me
//                afterrender: me.initializeOpenEncounterDropZone
            }
        });

        /**
         * General Area
         */
        me.nav['App_view_dashboard_Dashboard'] = me.MainPanel.add(Ext.create('App.view.dashboard.Dashboard'));
/*
        me.nav['App_view_areas_FloorPlan'] = me.MainPanel.add(Ext.create('App.view.areas.FloorPlan'));
        me.nav['App_view_areas_PatientPoolDropZone'] = me.MainPanel.add(Ext.create('App.view.areas.PatientPoolDropZone'));
*/
        /**
         * Footer Panel
         */
        me.Footer = Ext.create('Ext.container.Container', {
            height: 30,
            split: false,
            padding: '0 0',
            region: 'south',
	        action:'appFooter',
            items: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [
                        {
                            text: 'Copyright (C) 2014 Untusoft |:|  Open Source Software operating under GPLv3 |:| v' + me.version,
                            iconCls: 'icoGreen',
                            disabled: true
                        },
                        '->'
                        ,
                        {
                            text: '<span style="color: red">'+i18n('logout')+'</span>',
                            iconCls: 'icoLogout',
                            scope : me,
                            action : 'logout'
                        }
                    ]
                }
            ]
        });


        me.layout = {
            type: 'border',
            padding: 3
        };
        me.defaults = {
            split: true
        };
        me.items = [me.navColumn, me.MainPanel, me.Footer];
        me.listeners = {
	        scope: me,
            render: me.appRender
//            beforerender: me.beforeAppRender
        };
        me.callParent(arguments);

    },

	getController:function(controller){
		return App.Current.getController(controller);
	},

	setWindowTitle:function(facility){
		window.document.title = 'UntuSoft :: ' + facility;
	},

    /**
     * Show the medical window dialog.
     */
/*
    onMedicalWin: function(action){
        this.MedicalWindow.show();
        this.MedicalWindow.cardSwitch(action);
    },
*/
    /**
     * Show the Charts window dialog.
     */
/*
    onChartsWin: function(){
        this.ChartsWindow.show();
    },


    onWebCamComplete: function(msg){
        var panel = this.getActivePanel();
        if(panel.id == 'panelSummary'){
            panel.demographics.completePhotoId();
        }
        this.msg('Sweet!', i18n('patient_image_saved'));
    },
*/
    getActivePanel: function(){
        return this.MainPanel.getLayout().getActiveItem();
    },

    msg: function(title, format, error, persistent) {
        var msgBgCls = (error === true) ? 'msg-red' : 'msg-green';
        this.msgCt = Ext.get('msg-div');
	    if(!this.msgCt) this.msgCt = Ext.fly('msg-div');
        this.msgCt.alignTo(document, 't-t');
        var s = Ext.String.format.apply(String, Array.prototype.slice.call(arguments, 1)),
            m = Ext.core.DomHelper.append(this.msgCt, {
                html: '<div class="flyMsg ' + msgBgCls + '"><h3>' + (title || '') + '</h3><p>' + s + '</p></div>'
            }, true);
        if (persistent === true) return m; // if persitent return the message element without the fade animation
        m.addCls('fadeded');
        Ext.create('Ext.fx.Animator', {
            target: m,
            duration: error ? 7000 : 2000,
            keyframes: {
                0: { opacity: 0 },
                20: { opacity: 1 },
                80: { opacity: 1 },
                100: { opacity: 0, height: 0 }
            },
            listeners: {
                afteranimate: function() {
                    m.destroy();
                }
            }
        });
        return true;
    },
    /*
    patientBtnTpl: function(){
        return Ext.create('Ext.XTemplate',
            '<div class="patient_btn  {priority}">',
            '   <div class="patient_btn_img">' +
            '       <img src="{pic}" width="32" height="32">' +
            '   </div>',
            '   <div class="patient_btn_info">',
            '       <div class="patient_btn_name">{name}</div>',
            '       <div class="patient_btn_record">( {pid} )</div>',
            '   </div>',
            '</div>');
    },
    patientBtnRender: function(btn){
        this.patientButtonSet();
        this.initializePatientPoolDragZone(btn)
    },

    getPatientsInPoolArea: function(){
        var me = this,
	        poolArea = me.patientPoolArea,
	        height = 35;

	    this.patientPoolStore.load({
            extraPrams:{ uid:me.user.id },
            callback: function(records){

	            if(records.length >= 1){
                    for(var i = 0; i < records.length; i++){
                        height = height + 45;
                    }
                }else{
                    height = 25;
                }

                if(me.navColumn.collapsed === false && !me.navColumn.isCollapsingOrExpanding){
                    height = (height > 300) ? 300 : height;
                    poolArea.down('dataview').refresh();
                    poolArea.setHeight(height);
                }
            }
        });

	    if(me.nav['App_view_areas_PatientPoolDropZone'].isVisible()) me.nav['App_view_areas_PatientPoolDropZone'].reloadStores();
    },

    initializePatientPoolDragZone: function(panel){
        panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
            ddGroup: 'patientPoolAreas',
            getDragData: function(){

	            if(app.patient.pid){
                    var sourceEl = app.patientBtn.el.dom,
	                    d;

                    if(sourceEl){
                        d = sourceEl.cloneNode(true);
                        d.id = Ext.id();
                        return panel.dragData = {
                            copy: true,
                            sourceEl: sourceEl,
                            repairXY: Ext.fly(sourceEl).getXY(),
                            ddel: d,
                            records: [panel.data],
                            patient: true
                        };
                    }

                    return false;
                }

                return false;
            },

            getRepairXY: function(){
//                app.nav.goBack();
                return this.dragData.repairXY;
            },

	        onBeforeDrag:function(){
		        app.nav.navigateTo('App.view.areas.PatientPoolDropZone');
		        return true;
            }
        });
    },

	onEncounterDragZoneRender:function(panel){
		this.initializeOpenEncounterDragZone(panel);

		if(this.enablePoolAreaFadeInOut){
			panel.el.setStyle({
				opacity: 0.1
			});

			panel.el.on('mouseenter', function(event, el){
				Ext.create('Ext.fx.Animator', {
					target: el,
					duration: 200,
					keyframes: {
						0: { opacity: 0.1 },
						100: { opacity: 1 }
					}
				});
			});

			panel.el.on('mouseleave', function(event, el){
				Ext.create('Ext.fx.Animator', {
					target: el,
					duration: 200,
					keyframes: {
						0: { opacity: 1 },
						100: { opacity: 0.1 }
					}
				});
			});
		}

	},
*/
    /**
     *
     * @param panel
     */
    /*
    initializeOpenEncounterDragZone: function(panel){
        panel.dragZone = Ext.create('Ext.dd.DragZone', panel.getEl(), {
            ddGroup: 'patient',
            newGroupReset: true,
            b4MouseDown: function(e){
                if(this.newGroupReset){
                    var sourceEl = e.getTarget(panel.itemSelector, 10), patientData = panel.getRecord(sourceEl).data;
                    this.removeFromGroup(this.ddGroup);
//                    say('drag record:');
//                    say(patientData);
                    if(patientData.floorPlanId != 0 && patientData.patientZoneId == 0){
                        app.nav.navigateTo('App.view.areas.FloorPlan');
                        this.ddGroup = 'patientPoolAreas';
                    }else{
                        this.ddGroup = 'patient';
                        app.MainPanel.el.mask(i18n('drop_here_to_open') + ' <strong>"' + panel.getRecord(sourceEl).data.name + '"</strong> ' + i18n('current_encounter'));
                    }
                    this.addToGroup(this.ddGroup);
                    this.newGroupReset = false;
                }
                this.autoOffset(e.getPageX(), e.getPageY());
            },
            endDrag: function(e){
                this.newGroupReset = true;
            },
            getDragData: function(e){
                var sourceEl = e.getTarget(panel.itemSelector, 10), d, patientData = panel.getRecord(sourceEl).data;
                if(sourceEl){
                    d = sourceEl.cloneNode(true);
                    d.id = Ext.id();
                    return panel.dragData = {
                        sourceEl: sourceEl,
                        repairXY: Ext.fly(sourceEl).getXY(),
                        ddel: d,
                        patientData: patientData
                    };
                }
                return false;
            },
            getRepairXY: function(){
                app.MainPanel.el.unmask();
                this.newGroupReset = true;
                return this.dragData.repairXY;
            }
        });
    },
    */
    /*
    onDocumentView: function(docId){
        var windows = Ext.ComponentQuery.query('#documentviewerwindow'),
	        window;

	    window = Ext.create('App.view.patient.windows.DocumentViewer',{
		    items:[
			    {
				    xtype:'miframe',
				    autoMask:false,
				    src: 'dataProvider/DocumentViewer.php?doc='+docId
			    }
		    ]
	    });

	    if(windows.length > 0){
		    var last = windows[(windows.length - 1)];
		    for(var i=0; i < windows.length; i++){
			    windows[i].toFront();
		    }
		    window.showAt((last.x + 25), (last.y + 5));

	    }else{
		    window.show();
	    }
    },
*/
    /**
     *
     * @param panel
     */
    /*
    initializeOpenEncounterDropZone: function(panel){
        var me = this;
        panel.dropZone = Ext.create('Ext.dd.DropZone', panel.getEl(), {
            ddGroup: 'patient',

            notifyOver: function(dd, e, data){
                return Ext.dd.DropZone.prototype.dropAllowed;
            },

            notifyDrop: function(dd, e, data){
                say('drop record');
                say(data.patientData);
                app.MainPanel.el.unmask();

	            if(data.patientData.eid && data.patientData.poolArea == 'Check Out'){
//		            me.VisitCheckout.el.mask(i18n('loading...'));
	            }else if(data.patientData.eid && acl['access_encounters']){
//		            me.Encounter.el.mask(i18n('loading...'));
	            }else if(data.patientData.floorPlanId == null || data.patientData.floorPlanId == 0){
//		            me.Summary.el.mask(i18n('loading...'));
	            }

	            me.setPatient(data.patientData.pid, data.patientData.eid, function(){

                    // if encounter id is set and pool area is check out....  go to Patient Checkout panel
                    if(data.patientData.eid && data.patientData.poolArea == 'Checkout'){

	                    say('checkOutPatient');
                        me.checkOutPatient(data.patientData.eid);

                    // if encounter id is set and and user has access to encounter area... go to Encounter panel
                    // and open the encounter
                    }else if(data.patientData.eid && acl['access_encounters']){

	                    say('openEncounter');
                        me.openEncounter(data.patientData.eid);
                    // else go to patient summary
                    }else{

	                    say('openEncounter');
                        me.openPatientSummary();
                    }
                });
            }
        });
    },
*/
	/**
     * When the application finishes loading all the GaiaEHR core.
     * Then it will load all the modules.
     */
    appRender: function(){
        this.loadModules();
    },

    /**
     * Load all the modules on the modules folder.
     * This folder will hold modules created by third-party.
     */
    loadModules: function(){
        say('*** Loading Modules ***');
        Modules.getEnabledModules(function(provider, response){
            var modules = response.result;
            for(var i = 0; i < modules.length; i++){
                var m = App.Current.getController('Modules.' + modules[i].dir + '.Main');
                m.init();
                say('Module ' + modules[i].dir + ' loaded...');
            }
        });
    },

    removeAppMask: function(){
        Ext.get('mainapp-loading').remove();
        Ext.get('mainapp-loading-mask').fadeOut({
            remove: true
        });
    },

    getApp: function(){
        return this;
    },

    /**
     * Access denied massage.
     */
    accessDenied: function(){
        Ext.Msg.show({
            title: 'Oops!',
            msg: i18n('access_denied'),
            buttons: Ext.Msg.OK,
            icon: Ext.Msg.ERROR
        });
    },

    alert: function(msg, icon){
        if(icon == 'error'){
            icon = Ext.Msg.ERROR;
        }else if(icon == 'warning'){
            icon = Ext.Msg.WARNING;
        }else if(icon == 'question'){
            icon = Ext.Msg.QUESTION;
        }else{
            icon = Ext.Msg.INFO;
        }

	    Ext.Msg.width = null;
	    Ext.Msg.height = null;
	    Ext.Msg.maxHeight = 600;
	    Ext.Msg.maxWidth = 1000;
        Ext.Msg.show({
            msg: msg,
            buttons: Ext.Msg.OK,
            icon: icon
        });
	    Ext.Msg.width = 600;
	    Ext.Msg.height = 500;
	    Ext.Msg.maxWidth = 600;
	    Ext.Msg.maxHeight = 500;
    }

});

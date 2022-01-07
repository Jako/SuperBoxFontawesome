/**
 * SuperBoxSelect Input Options Fontawesome
 *
 * @package superboxfontawesome
 * @subpackage inputoptions fontawesome
 */

SuperBoxSelect.panel.InputOptionsFontawesome = function (config) {
    config = config || {};

    this.options = config.options;
    this.params = config.params;

    Ext.applyIf(config, {
        layout: 'fit',
        id: 'type_fontawesome',
        cls: 'alltypes',
        style: {
            height: '0',
            overflow: 'hidden'
        },
        items: [{
            xtype: 'panel',
            layout: 'form',
            labelAlign: 'top',
            items: [{
                layout: 'column',
                items: [{
                    columnWidth: .34,
                    layout: 'form',
                    labelAlign: 'top',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('superboxfontawesome.fontawesomeUrl'),
                        description: MODx.expandHelp ? '' : _('superboxfontawesome.fontawesomeUrl_desc'),
                        name: 'inopt_fontawesomeUrl',
                        id: 'inopt_fontawesomeUrl',
                        value: this.params.fontawesomeUrl || '',
                        anchor: '100%',
                        listeners: {
                            change: {
                                fn: this.markDirty,
                                scope: this
                            }
                        }
                    }, {
                        xtype: MODx.expandHelp ? 'label' : 'hidden',
                        forId: 'inopt_fontawesomeUrl',
                        html: _('superboxfontawesome.fontawesomeUrl_desc'),
                        cls: 'desc-under'
                    }]
                }, {
                    columnWidth: .33,
                    layout: 'form',
                    labelAlign: 'top',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('superboxfontawesome.fontawesomePrefix'),
                        description: MODx.expandHelp ? '' : _('superboxfontawesome.fontawesomePrefix_desc'),
                        name: 'inopt_fontawesomePrefix',
                        id: 'inopt_fontawesomePrefix',
                        value: this.params.fontawesomePrefix || '',
                        anchor: '100%',
                        listeners: {
                            change: {
                                fn: this.markDirty,
                                scope: this
                            }
                        }
                    }, {
                        xtype: MODx.expandHelp ? 'label' : 'hidden',
                        forId: 'inopt_fontawesomePrefix',
                        html: _('superboxfontawesome.fontawesomePrefix_desc'),
                        cls: 'desc-under'
                    }]
                }, {
                    columnWidth: .33,
                    layout: 'form',
                    labelAlign: 'top',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: _('superboxfontawesome.excludeClasses'),
                        description: MODx.expandHelp ? '' : _('superboxfontawesome.excludeClasses_desc'),
                        name: 'inopt_excludeClasses',
                        id: 'inopt_excludeClasses',
                        value: this.params.excludeClasses || '',
                        anchor: '100%',
                        listeners: {
                            change: {
                                fn: this.markDirty,
                                scope: this
                            }
                        }
                    }, {
                        xtype: MODx.expandHelp ? 'label' : 'hidden',
                        forId: 'inopt_excludeClasses',
                        html: _('superboxfontawesome.excludeClasses_desc'),
                        cls: 'desc-under'
                    }]
                }]
            }]
        }]
    });
    SuperBoxSelect.panel.InputOptionsFontawesome.superclass.constructor.call(this, config);
};
Ext.extend(SuperBoxSelect.panel.InputOptionsFontawesome, MODx.Panel, {
    markDirty: function () {
        Ext.getCmp('modx-panel-tv').markDirty();
    }
});
Ext.reg('superboxfontawesome-panel-inputoptions-fontawesome', SuperBoxSelect.panel.InputOptionsFontawesome);

Ext.onReady(
	function()
	{
		Ext.state.Manager.setProvider( new Ext.state.CookieProvider() );
		var viewport = new Ext.Viewport({
			layout:'border',
			items:[
				{
					region:'west',
					contentEl: 'west',
					width: 200,
					split: true,
					title:'Разделы'
				},{
					region:'center',
					contentEl: 'center',
					split:true,
					title:'Администрирование'
				}
			]
		});
	}
);
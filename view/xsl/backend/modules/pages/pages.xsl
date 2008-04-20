<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template match="ADMINPAGES[@mode='pages']">
    <link rel="stylesheet" type="text/css" href="/cms/css/all/form.css" />

    <!-- Ext LIBS -->
   	<script type="text/javascript" src="/lib/j/ext/adapter/yui/yui-utilities.js"></script>
	<script type="text/javascript" src="/lib/j/ext/adapter/yui/ext-yui-adapter.js"></script>
	<!-- Ext ENDLIBS -->
    <script type="text/javascript" src="/lib/j/ext/ext-all.js"></script>

    <script type="text/javascript" src="/cms/j/backend/modules/pages/page_edit_dialog.js"></script>
    <script type="text/javascript" src="/cms/j/backend/modules/pages/page_add_dialog.js"></script>

    <link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/tabs.css" />
    <link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/basic-dialog.css" />
    <link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/button.css" />
    <link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/grid.css" />
    <link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/toolbar.css" />
    <link rel="stylesheet" type="text/css" href="/lib/j/ext/resources/css/tree.css" />


    <link rel="stylesheet" type="text/css" href="/cms/css/all/table.css" />

	<div class="filter">
		<form action="/ajax/admin/pages/list" method="post" id="filter" enctype="multipart/form-data">
			<xsl:attribute name="onsubmit">
				return AjaxLoad( { 'value' : { 'q' : this }, 'script' : this.action, 'method' : 'POST', 'caching' : false, 'callback_function' : [ Pages.Load ]} );
			</xsl:attribute>
			<input type="hidden" name="page" value="1"/>
			<div class="form_container">
				<div class="form_caption">Поиск по фильтру</div>
				<fieldset class="form_fieldset">
					<legend class="form_legend"> Параметры </legend>
					<table border="0" width="100%">
						<tr><td class="form_element_label" valign="top"><label for="filter[page]">Страница:</label></td><td><input class="form_element_text_input" type="text" name="filter[page]" id="filter[page]" value="" style="width: 100%"/><div class="form_note">( введите URL, регулярное выражение или ID страницы)</div></td></tr>
						<tr><td></td><td><input type="submit" value="Применить фильтр" class="form_element_button"/></td></tr>
					</table>
<!--
					View: <input type="text" name="filter[page]" value=""/><br/>
					Шаблон: <input type="text" name="filter[page]" value=""/><br/>
-->
				</fieldset>
			</div>
		</form>
	</div>

    <div id="page_admin_toolbar">
    </div>

    <!-- Список страниц -->
    <div class="admin_page_list">
   		<div id="page_items">
			<table id="page_list" class="default" border="0" cellpadding="0" cellspacing="0">
				<tr class="header">
					<td width="40" class="f">#</td>
					<td>Страница</td>
					<td width="200">Адрес</td>
					<td width="50">View</td>
					<td width="150">Шаблон</td>
					<td width="30">Редактировать</td>
				</tr>
			</table>
		</div>
		<div class="pager" style="padding-left: 2px;">
			Страницы:
			<div id="pager_page_items">
			</div>
		</div>
	</div>
	<br/>
	<br/>

	<!-- Диалог удаления страницы -->
	<div id="delete-page-dlg" style="visibility:hidden; position:absolute; top:0px;">
	    <div class="x-dlg-hd">Удаление страницы</div>
	    <div class="x-dlg-bd" style="padding: 0px; margin: 0px">
            <div id="page_delete_toolbar"></div>
	    	Выберите параметры удаления страницы:<br/>
	    	<hr/>
	    	<form action="/admin/page/delete" enctype="multipart/form-data" id="page_delete_form" method="POST"> 
		    	<input type="checkbox" value="1"/> Удалить файлы, связанные с этой страницей (если они негде больше не используются)<br/>
		    	<input type="checkbox" value="1"/> Удалить из словаря данные, связанные с удаляемыми данными  (если они негде больше не используются)<br/>
		    </form>
	    </div>
	</div>
	<!-- Диалог редактирования страницы -->
	<div id="edit-page-dlg" style="visibility:hidden; position:absolute; top:0px;">
	    <div class="x-dlg-hd">Редактирование страницы</div>
	    <div class="x-dlg-bd">
			<!-- Вкладка для редактирования самой страницы -->
	    	<div id="page_tab" class="x-dlg-tab" title="Страница">
	            <div id="page_tab_container">
		            <div id="page_tab_toolbar"></div>
		            <div style="height: 250px; overflow: auto;">
						<form action="/admin/page/post" enctype="multipart/form-data" id="page_form" method="POST">
							<input id="page_id" type="hidden" name="page_id" value=""/>
							<table width="100%" class="form_default" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="190" align="right">Редактируемый язык:</td>
									<td>
										<select id="language_id" name="language_id" onchange="EditPageDialog.ChangeLanguage( this );">
											<xsl:for-each select="/DOCUMENT/ADMINLOCALIZER[@mode='get_language_list']/DATA/ITEM">
												<option value="{ID}"><xsl:value-of select="ABBR"/></option>
											</xsl:for-each>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right">Адрес:</td><td><input type="text" id="url" name="url" value="" style="width: 300px"/> ( регулярное выражение: <input type="checkbox" id="preg" name="preg" value="1" style="width: 20px;"/>)</td>
								</tr>
								<tr>
									<td align="right">View:</td>
									<td>
										<select id="view_type" name="view_type" onchange="EditPageDialog.PageTab.ChangeViewType( this );">
											<xsl:for-each select="/DOCUMENT/ADMINPAGES[@mode='page_view_type']/DATA/ITEM">
												<option><xsl:value-of select="."/></option>
											</xsl:for-each>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right">Шаблон:</td>
									<td>
										<select id="layout_id" name="layout_id">
											<xsl:for-each select="/DOCUMENT/ADMINPAGES[@mode='layouts']/DATA/ITEM">
												<option value="{ID}"><xsl:value-of select="NAME"/></option>
											</xsl:for-each>
										</select>
									</td>
								</tr>
								<tr>
									<td align="right">Title:</td><td><input type="text" id="title" name="title" value=""/></td>
								</tr>
								<tr>
									<td align="right">Keywords:</td><td><input type="text" id="keywords" name="keywords" value=""/></td>
								</tr>
								<tr>
									<td align="right">Description:</td><td><input type="text" id="description" name="description" value=""/></td>
								</tr>
<!--								<tr>
									<td></td><td><button id="save_action" class="x-btn-text" onclick="Pages.PostPage(); EventDispatcher.cancelEvent( event ); return false;">Сохранить</button></td>
								</tr>
-->							</table>
						</form>
					</div>
				</div>
			</div>
			<!-- Вкладка для редактирования подключенных файлов -->
			<div id="page_files_tab" class="x-dlg-tab" title="Подключенные файлы">
            	<style>
            		div#tree_files ul, div#tree_page_files ul{list-style:none; margin:0; padding:0;}
            		 input.x-tree-node-cb { margin:0; padding:0; margin-left: 4px;}
            	</style>
	            <div id="page_files_tab_container">
		            <div id="page_files_tab_toolbar"></div>
		            <div id="page_files_tab_trees" style="height: 250px; overflow: auto;">
						<div id="tree_page_files" style="width: 49%; float: left; height: 250px; overflow: auto;"></div>
						<div id="tree_files" style="width: 49%; float: right; height: 250px; overflow: auto;"></div>
					</div>
	            </div>
			</div>
			<!-- Вкладка для редактирования подключенных модулей -->
	        <div id="page_modules_tab" class="x-dlg-tab" title="Подключенные модули">
	            <div class="inner-tab">
	            	<!-- HACK //TODO -->
					<form action="/ajax/admin/pages/add_content_module" method="post" id="frm_add_content_modules" enctype="multipart/form-data">
						<xsl:attribute name="onsubmit">
							return AjaxLoad( { 'value' : { 'q' : this }, 'script' : this.action, 'method' : 'POST', 'caching' : false, 'callback_function' : [ Pages.ContentAdded ]} );
						</xsl:attribute>
						<input type="hidden" name="page_id" value=""/>
						<input type="submit" value="Добавить контент к этой странице"/>
					</form>
					<div>Модуль контента</div>
					<div>
						Секция: основной контент<br/>
						Позиция в секции: 1<br/>
						Опубликованы номера: 1, 3, 5<br/>
					</div>
				</div>
			</div>
        </div>
    </div>

	<!-- Диалог добавления страницы -->
	<div id="add-page-dlg" style="visibility:hidden; position:absolute; top:0px;">
	    <div class="x-dlg-hd">Добавление страницы</div>
	    <div class="x-dlg-bd">
            <div id="page_add_dialog_container" style="border: 1px solid #6593CF; background-color: white; height: 200px;">
	            <div id="page_add_tab_toolbar"></div>
	            <div style="padding-top: 10px;">
					<form action="/ajax/admin/page/add" enctype="multipart/form-data" method="POST" id="page_add_form">
						<table width="100%" class="form_default" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td align="right">Адрес:</td><td><input type="text" name="url" value="" style="width: 300px"/> ( регулярное выражение: <input type="checkbox" id="preg" name="preg" value="1" style="width: 20px;"/>)</td>
							</tr>
							<tr>
								<td align="right">View:</td>
								<td>
									<select name="view_type" onchange="AddPageDialog.ChangeViewType( this );">
										<xsl:for-each select="/DOCUMENT/ADMINPAGES[@mode='page_view_type']/DATA/ITEM">
											<option><xsl:value-of select="."/></option>
										</xsl:for-each>
									</select>
								</td>
							</tr>
							<tr>
								<td align="right">Шаблон:</td>
								<td>
									<select name="layout_id" id="layout_add_id">
										<xsl:for-each select="/DOCUMENT/ADMINPAGES[@mode='layouts']/DATA/ITEM">
											<option value="{ID}"><xsl:value-of select="NAME"/></option>
										</xsl:for-each>
									</select>
								</td>
							</tr>
						</table>
					</form>
				</div>
			</div>
        </div>
    </div>
        
    <script>
   		EditPageDialog.FilesTab.ViewFiles = { <xsl:for-each select="/DOCUMENT/ADMINPAGES[@mode='view_files']/DATA/DIRECT_FILES/ITEM"><xsl:value-of select="@key"/> : { 'path': '<xsl:value-of select="PATH"/>'}<xsl:if test="position()!=last()">, </xsl:if></xsl:for-each> };
		EditPageDialog.FilesTab.ViewFilesMap = {
			<xsl:for-each select="/DOCUMENT/ADMINPAGES[@mode='view_files']/DATA/FILE_MAP/ITEM">
				<xsl:value-of select="@key"/> : {
					<xsl:for-each select="ITEM">
						<xsl:value-of select="@key"/> : '<xsl:value-of select="."/>'<xsl:if test="position()!=last()">, </xsl:if>
					</xsl:for-each>
				}<xsl:if test="position()!=last()">, </xsl:if>
			</xsl:for-each>
		};


	    var tb = new Ext.Toolbar('page_admin_toolbar');
	    tb.add({
	        icon: '/cms/i/frontend/design/ico/add.png', // icons can also be specified inline
	        cls: 'x-btn-text-icon',
	        text: 'Новая страница',
	        handler: function(){AddPageDialog.Show();}
	    });

    	<!-- Загружаем список страниц -->
		AjaxLoad(
			{
				'value' : { 'page' : 1, 'filter' : new Array() },
				'script' : '/ajax/admin/pages/list',
				'method' : 'POST',
				'caching' : false,
				'callback_function' : [ 'EditPageDialog.Init', 'AddPageDialog.Init', Pages.Load ]
			}
		);
		
	</script>
</xsl:template>

<xsl:template name="item_map">
</xsl:template>
</xsl:stylesheet>
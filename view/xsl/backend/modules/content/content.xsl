<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" encoding="utf-8"/>

<xsl:template match="ADMINCONTENT[@mode='contents']">
    <link rel="stylesheet" type="text/css" href="/cms/css/all/table.css" />
    <link rel="stylesheet" type="text/css" href="/cms/css/all/form.css" />

	<div class="filter">
		<form action="/ajax/admin/content/list" method="post" id="filter" enctype="multipart/form-data">
			<xsl:attribute name="onsubmit">
				return AjaxLoad( { 'value' : { 'q' : this }, 'script' : this.action,  'method' : 'POST',  'caching' : false,  'callback_function' : [ Contents.Load ] } );
			</xsl:attribute>
			<input type="hidden" name="page" value="1"/>
			<div class="form_container">
				<div class="form_caption">Поиск по фильтру</div>
				<fieldset class="form_fieldset">
					<legend class="form_legend"> Параметры </legend>
					<table border="0" width="100%">
						<tr><td width="180" class="form_element_label" valign="top"><label for="filter[id]">ID:</label></td><td><input class="form_element_text_input" type="text" name="filter[id]" id="filter[id]" value="" style="width: 100%"/></td></tr>
						<tr><td class="form_element_label" valign="top"><label for="filter[page]">Опубликован на странице:</label></td><td><input class="form_element_text_input" type="text" name="filter[page]" id="filter[page]" value="" style="width: 100%"/><div class="form_note">( введите URL, регулярное выражение или ID страницы)</div></td></tr>
						<tr><td></td><td><input type="submit" value="Применить фильтр" class="form_element_button"/></td></tr>
					</table>
				</fieldset>
			</div>
		</form>
	</div>
    <div class="admin_content_list">
		<div class="table_container">
			<table id="content_items" class="default" border="0" cellpadding="0" cellspacing="0">
				<tr class="header">
					<td class="f" width="60">#</td>
					<td>Текст</td>
				</tr>
			</table>
		</div>
		<div class="pager">
			Страницы: 
			<div id="pager_content_items">
			</div>
		</div>
	</div>
	<script>
		AjaxLoad( 
			{ 
				'value' : { 'page' : 1, 'filter' : new Array() }, 
				'script' : '/ajax/admin/content/list', 
				'method' : 'POST', 
				'caching' : false, 
				'callback_function' : [ Contents.Load ] 
			}
		);
	</script>
</xsl:template>

</xsl:stylesheet>
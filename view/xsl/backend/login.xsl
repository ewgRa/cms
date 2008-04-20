<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE xsl:stylesheet>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:output method="html" indent="yes" encoding="utf-8"/>
<xsl:template match="AUTHORIZE[@mode='login_form']">
	<form action="/admin/login/post" method="post">
		Логин: <input type="text" name="login"/>
		Пароль: <input type="password" name="password"/>
		<input type="submit" value="LOGIN"/>
	</form>
</xsl:template>
</xsl:stylesheet>
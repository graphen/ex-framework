{$docType}
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl" lang="pl">
	<head>
	{$meta}
	{$title}
	{$favicon}
	{$css}
	{$javaScript}
	</head>
	<body>	
		<div id="container">
			<div id="header">
				<h1>Moja książka kucharska</h1>
			</div><!--End Header-->
			<div id="wrapper">
				<div id="content">
				{$content}
				</div><!--End Content-->
			</div><!--End Wrapper-->
			<div id="left">
				<div id="sidebar">
					{$PublicMenu}	
					{$CategoryMenu}
					{$SearchBlock}	
					{$UserMenu}	
					{$ProfileBlock}
				</div><!--End Sidebar-->
			</div><!--End Left-->
			<div id="footer">
				&copy; 2011 Książka kucharska. All Rights Reserved. | Designed by <a href="#">Przemysław Szamraj</a>
				<div class="benchmark">Strona wygenerowana w czasie: <!--#timeElapsedTag#--></div>
			</div><!--End Footer-->
		</div><!--End Container-->	
	</body>
</html>

<!DOCTYPE html>
<html>
<head>
	<title>Select Page</title>
	<link rel="stylesheet" type="text/css" href="/twt/public/css/style.css">
</head>
<body>
"yesyesyes";
<?PHP if(true){ ?>
 <h3>Yyyyy </h3>
<?PHP }else{ ?>
 <h3>wwww</h3>
<?PHP } ?>

<?PHP if($id==1){ ?>
<h1>1</h1>
<?PHP }else if($id==2){?>
<h1>2</h1>
<?PHP }else{ ?>
<h1>3</h1>
<?PHP } ?>

<table border="1">
	<?PHP foreach($ret as $key => $value){ ?>
	<tr>
		<?PHP foreach($value as $v){ ?>
		<td><?PHP echo $v ?></td>
		<?PHP } ?>
	</tr>
	<?PHP } ?>
</table>
<img src="/twt/public/img/the_arrow.jpg">

<script type="text/javascript" src="/twt/public/js/main.js"></script>
<script type="text/javascript">
	showName ();	
</script>
</body>
</html>
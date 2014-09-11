<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<title>Vein1992's 随笔</title>
	<link rel="stylesheet" type="text/css" href="/public/css/bootstrap.css">
	<script type="text/javascript" src="/public/js/jquery.js"></script>
	<script type="text/javascript" src="/public/js/bootstrap.js"></script>
</head>
<body>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
  	  <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <a class="navbar-brand" href="/welcome/main">首页</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
	        <li class="active"><a href="#">程序员书籍</a></li>
	        <li><a href="/article/ll">优秀文章</a></li>
	        <li><a href="#">网站介绍</a></li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li><a href="#">Action</a></li>
	            <li><a href="#">Another action</a></li>
	            <li><a href="#">Something else here</a></li>
	            <li class="divider"></li>
	            <li><a href="#">Separated link</a></li>
	            <li class="divider"></li>
	            <li><a href="#">One more separated link</a></li>
	          </ul>
	        </li>
	      </ul>
	      <ul class="nav navbar-nav">
	      	<li><a href="">登录</a></li>	
	      	<li><a href="">注册</a></li>	
	      </ul>
	      <form class="navbar-form navbar-right" role="search">
	        <div class="form-group">
	          <input type="text" class="form-control" placeholder="Search">
	        </div>
	        <button type="submit" class="btn btn-default">查询</button>
	      </form>
	    </div><!-- /.navbar-collapse -->

  </div><!-- /.container-fluid -->
</nav>

<script type="text/javascript" >
	$('li').mouseover(function () {
		$this = $(this);
		$this.addClass('active');
	});
	$('li').mouseout(function () {
		$this = $(this);
		$this.removeClass('active');
	});
</script>
<?PHP
echo "ss";
?>
<div class="col-md-2 btn-group-vertical">
		<button class="btn btn-success">PHP</button>
		<button class="btn btn-success">网络</button>
		<button class="btn btn-success">操作系统</button>
		<button class="btn btn-success">Javascript</button>
		<button class="btn btn-success">Html5</button>
		<button class="btn btn-success">Css3</button>
		<button class="btn btn-success">数据库</button>
</div>
<div class="col-md-10">
	<ul>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>
		<li><a href="">一篇我很喜欢的文章</a></li>

	</ul>
</div>
<nav class="col-md-12">
	<h3>Footer Here</h3>
</nav>

</body>
</html>

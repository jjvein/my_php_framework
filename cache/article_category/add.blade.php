<!DOCTYPE html>
<html>
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
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">管理 <span class="caret"></span></a>
	          <ul class="dropdown-menu" role="menu">
	            <li><a href="/article_category/ll">文章分类列表</a></li>
	            <li><a href="__ROOOT__/article_manage/ll">文章列表</a></li>
	            <li><a href="/book_category/ll">书籍分类列表</a></li>
	            <li><a href="/book_manage/ll">书籍列表</a></li>
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
<div class="col-md-10">
<h3>分类添加</h3>
<form action="add_handler" method="POST">
	<input type="text" id="name" name="name" required/>
	<a id="submit">Submit</a>
</form>
</div>

<script type="text/javascript">
	$('#submit').click(function () {
		var name = $('#name').val ();
		$.ajax({
			url:'add_handler',
			type:'POST',
			data:{name: name},
			dataType:'html',
			
			error: function() {
				console.log("sorry");
			},
			success: function(data) {
				data = eval ('(' + data + ')');
				if (data.code == 0 && data.msg > 0 ) {
					alert("添加成功!!!");
					location.href='add';
				}else {
					alert ("添加失败!!!");
					location.href='add';
				}
			},	
		});
	});

</script>
<nav class="col-md-12">
	<h3>Footer Here</h3>
</nav>

</body>
</html>

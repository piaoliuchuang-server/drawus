<!DOCTYPE html> 
<html> 
<head> 
	<meta http-equiv="content-type"  content="text/html; charset=UTF-8" /> 
	<meta http-equiv="Cache-Control" content="no-cache" />
	<title>数据库连接测试页面</title> 
</head>
<body>
	<h1>数据库连接测试页面</h1>
	<h5><span style="color:#F03">====根据uuid查找用户信息=====</span></h5>
	<form action="../interface/index.php?" method="get">
	<input type="hidden" name="do" value="user_search">
		客户端标识：<input name="uuid" type="text" size="40"/>
		<br/>
		<input type="submit" name="submit" value="Search">
	</form>
	
	
	<h5><span style="color:#F03">====注册用户do=user_add=====</span></h5>
	<form action="../interface/index.php?do=user_add" method="post">
  		注册的用户名：<input name="user_id" type="text" size="40"/>
		<br />
		注册的密码：<input name="user_pwd" type="text" size="40"/>
		<br/>
		客户端标识：<input name="uuid" type="text" size="40"/>
		<br/>
		客户端TOKEN: <input name="token" type="text" size="40">
		<br/>
		<input type="submit" name="submit" value="Add">
	</form>
	
	<h5><span style="color:#F03">====新建游戏do=game_new====</span></h5>
	<form action="../interface/index.php?do=game_new" method="post">
	          客户端玩家ID:<input name="game_founder" type="text" size="40"/>
	    <br/>
	  	寻找玩家：<input name="game_partner" type="text" size="40"/>
		<br/>
		<input type="submit" name="submit" value="search">
	</form>
	
	<h5><span style="color:#F03">====用户选择结束游戏do=game_finish====</span></h5>
	<form action="../interface/index.php?do=game_finish" method="post">
		发起请求人的id：<input name="user_id_finish" type="text" size="40"/>
		<br/>
	          要结束的游戏的id:<input name="game_id_finish" type="text" size="40"/>
	    <br/>
		<input type="submit" name="submit" value="submit">
	</form>
</body>
</html>

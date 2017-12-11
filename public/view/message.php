<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>友情提示</title>
    <link rel="stylesheet" href="./static/bs3/css/bootstrap.min.css">
</head>
<body>
        <!--安装bootstrap提示插件    -->
        <!--文件---设置--plugins--Browse repositories-->
        <!--搜索bootstrap  将bootstrap和bootstrap 3安装，安装完成之后重启编辑器-->
        <!--bs3-form  然后按键盘ctrl+j-->
   <div class="container" style="background: #fff">
       <div class="row">
           <div class="jumbotron" style="background: #fff;margin: 100px auto;">
           	<div class="container">
           		<h1 style="text-align: center"><?php echo $msg;?></h1>
                <p style="text-align: center">
                    <a href="javascript:<?php echo $this->url?>;">
                        <span id="time">3</span>s之后跳转......
                    </a>
                </p>
           		<p style="text-align: center">
           			<a class="btn btn-primary btn-lg" >About Me</a>
           		</p>
           	</div>
           </div>
       </div>
   </div>
        <script>
            //定时器，倒计时
            setInterval(function(){
                var time = document.getElementById('time').innerHTML;
                time--;
                document.getElementById('time').innerHTML=time;
            },1000)
            //点是炸弹，三秒之后跳转
            setTimeout(function(){
                <?php echo $this->url?>
            },3000);
        </script>
</body>
</html>
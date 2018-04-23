<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>相片輪播</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
	$(function(){

		$.ajax({
			url:'image.php',
			dataType:'json',
			async:false,
			success:function(res){
				for(var src of res){
					var img = $("<img>").attr('src',src);
					if(res.indexOf(src)==0){
						$("#background").attr('src',src);
						img.addClass('active');
					}
					$("#imgSet").append(img);
				}
			}
		});
		$("#imgSet img").click(function(){
			if(!$(this).hasClass('active')){
				switchImage(this);
			}
		});
		
		setInterval(function(){
			var index = $("#imgSet img.active").index();
			var count = $("#imgSet img").length;
			index = index+1>=count?0:index+1;
			switchImage($("#imgSet img").eq(index));
		},3000);
		
		function switchImage(img){
			var type = null;
			$.ajax({
				url:'getEffect.php',
				async:false,
				success:function(res){
					type = res;	
				}
			});
			$("#imgSet img").removeClass('active');
			$(img).addClass('active');
			var uri = $(img).attr('src');
			if(type == 1){
				$("#background").css('opacity',0);
				setTimeout(function(){
					$("#background").attr('src',uri);
					$("#background").css('opacity',1);
				},500);
			}else if(type==2){
				$("#background").css('height',0);
				setTimeout(function(){
					$("#background").attr('src',uri);
					$("#background").css('height','100vh');
				},500);	
			}else if(type==3){
				$("#background").css('width',0);
				setTimeout(function(){
					$("#background").attr('src',uri);
					$("#background").css('width','100vw');
				},500);	
			}else if(type==4){
				$("#background").removeClass('transition');
				$("#background").css('transform','rotate(360deg)');
				$("#background").attr('src',uri);
				setTimeout(function(){
					$("#background").addClass('transition');
					$("#background").css('transform','rotate(0deg)');
				},50);

			}else if(type==5){
				$("#background").removeClass('transition');
				$("#background").css('transform','scale(4.2)');
				setTimeout(function(){
					$("#background").addClass('transition');
					$("#background").attr('src',uri);
					$("#background").css('transform','scale(1)');
					
				},50);	
			}
		}
		
	});
</script>

<style>
	#background{
		position:fixed;
		top:0;
		left:0;
		width:100vw;
		height:100vh;
		z-index:-99;
		
	}
	.transition{
		transition:all .5s;	
	}
	#imgSet img{
		width:100px;
		height:100px;
		margin:5px;
		border:1px solid #333;	
		cursor:pointer;
	}
	
	.active{
		filter: sepia(1);	
		cursor:default !important;
	}
</style>

</head>



<body>
	<img id="background" class="transition" />
    <a href="effect.php" class="ui-button">更改特效</a>
	<div id="imgSet">
    	
    </div>

</body>
</html>
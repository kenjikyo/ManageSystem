var pos1 = $('#feature-content').offset().top;
var pos2 = $('#benefit-content').offset().top;
var pos3 = $('#package-content').offset().top;
var pos4 = $('#commission').offset().top;
var pos5 = $('#ecosystem').offset().top;
var pos6 = $('#roadmap').offset().top;

$('.nav-link').click(function(){	
	$('body').css('overflow-y','auto')
	$('body').css('overflow-x','hidden')	
	$('#content-part').addClass('right-content');
	$('#content-part').css('display','block');
})
$('.about-content').click(function(){
	/*$('.intro--text--container,.missing--text').addClass('animated fadeInDown delay-1s');
	$('.missing--text').addClass('animated fadeInDown delay-1s');
	setTimeout(function(){
		$('.intro--text--container,.missing--text').removeClass('animated fadeInDown delay-1s');
		$('.missing--text').removeClass('animated fadeInDown delay-1s');
	},2000)	*/
	var body = $("html, body");
	body.animate({scrollTop:0}, 500, 'swing', function() {
	});
})
$('.feature-content').click(function(){
	$('.feature--part').addClass('animated fadeInUp delay-1s');
	setTimeout(function(){
		$('.feature--part').removeClass('animated fadeInUp delay-1s');
	},2000)
	var body = $("html, body");
	body.animate({scrollTop:$('#feature-content').offset().top}, 500, 'swing', function() {
	});
})
$('.benefit-content').click(function(){
	$('.benefit--container').addClass('animated flipInX delay-1s');
	setTimeout(function(){
		$('.benefit--container').removeClass('animated flipInX delay-1s');
	},2000)
	var body = $("html, body");
	body.animate({scrollTop:$('#benefit-content').offset().top}, 500, 'swing', function() {
	});
})
$('.package-content').click(function(){
	$('.level--design').addClass('animated bounceIn delay-1s');
	setTimeout(function(){
		$('.level--design').removeClass('animated bounceIn delay-1s');
	},2000)	
	var body = $("html, body");
	body.animate({scrollTop:$('#package-content').offset().top}, 500, 'swing', function() {
	});
})
$('.commission').click(function(){
	$("#mCSB_1_container").animate({top: "-"+pos4+"px"}, "slow");
	$('.refferal').addClass('animated fadeInLeft delay-1s');
	$('.commission--img,.commission--title').addClass('animated fadeInDown delay-1s');
	setTimeout(function(){
		$('.refferal').removeClass('animated fadeInLeft delay-1s');
		$('.commission--img,.commission--title').removeClass('animated fadeInDown delay-1s');
	},2000)
	var body = $("html, body");
	body.animate({scrollTop:$('#commission').offset().top}, 500, 'swing', function() {
	});
})
$('.ecosystem').click(function(){
	$("#mCSB_1_container").animate({top: "-"+pos5+"px"}, "slow");
	$('.eco--col,.eco--title--2,.show--chart').addClass('animated bounceInUp delay-1s');
	setTimeout(function(){
		$('.eco--col,.eco--title--2,.show--chart').removeClass('animated bounceInUp delay-1s');
	},2000)
	var body = $("html, body");
	body.animate({scrollTop:$('#ecosystem').offset().top}, 500, 'swing', function() {
	});
})
$('.roadmap').click(function(){
	$("#mCSB_1_container").animate({top: "-"+pos6+"px"}, "slow");
	$('.roadmap--icon').addClass('animated fadeInUp delay-1s');
	setTimeout(function(){
		$('.roadmap--icon').removeClass('animated fadeInUp delay-1s');
	},2000)
	var body = $("html, body");
	body.animate({scrollTop:$('#roadmap').offset().top}, 500, 'swing', function() {
	});
})


// spin feature--part
const canvas = canvasDraw;
const ctx = canvas.getContext('2d');

var p=[];

p[0] = new Path2D();
p[1] = new Path2D();
p[2] = new Path2D();
p[3] = new Path2D();
p[4] = new Path2D();

var spinner = new Image();
spinner.src = '../page/img-new/feature-spin.png';

var animationLoad;
var resVal=0.8;

spinner.onload = function(){
	
	if(window.innerWidth<=1242 && window.innerWidth>=1024){
		resVal=0.7;
	}
	if(window.innerWidth<=417){
		resVal=0.5;
	}
	if(window.innerWidth>=1370){
		resVal=1;
	}
	canvasDraw.width = spinner.width*resVal;
	canvasDraw.height = spinner.height*resVal;

	ctx.scale(resVal,resVal);
	ctx.drawImage(spinner,0,0);
	
	drawPath();
	ctx.fillStyle = 'rgba(22, 135, 194, 0.5)';
	ctx.fill(p[0]);
	
}

function drawPath(){
	
	ctx.beginPath();
	p[0].moveTo(2,125);
	p[0].lineTo(115,210);
	p[0].lineTo(115,400);
	p[0].lineTo(2,475);
	ctx.closePath();
	ctx.fillStyle = 'transparent';
	ctx.fill(p[0]);
	
	ctx.beginPath();
	p[1].moveTo(115,400);
	p[1].lineTo(298,462);
	p[1].lineTo(336,585);
	p[1].lineTo(0,475);
	ctx.closePath();
	ctx.fillStyle = 'transparent';
	ctx.fill(p[1]);
	
	ctx.beginPath();
	p[2].moveTo(336,585);
	p[2].lineTo(298,462);
	p[2].lineTo(412,306);
	p[2].lineTo(542,300);
	ctx.closePath();
	ctx.fillStyle = 'transparent';
	ctx.fill(p[2]);
	
	ctx.beginPath();
	p[3].moveTo(542,300);
	p[3].lineTo(412,306);
	p[3].lineTo(300,152);
	p[3].lineTo(336,17);
	ctx.closePath();
	ctx.fillStyle = 'transparent';
	ctx.fill(p[3]);
	
	ctx.beginPath();
	p[4].moveTo(336,17);
	p[4].lineTo(300,152);
	p[4].lineTo(115,210);
	p[4].lineTo(2,125);
	ctx.closePath();
	ctx.fillStyle = 'transparent';
	ctx.fill(p[4]);
}

var text,icon;
var index=0;

canvasDraw.addEventListener('mousemove',function(e){
	
	mouseX = ((e.clientX-canvasDraw.getBoundingClientRect().left));
	mouseY = ((e.clientY-canvasDraw.getBoundingClientRect().top));
	
	//showVal.innerHTML = mouseX + " -- " + mouseY
	for(var i = 0 ;i <p.length;i++){
		if (ctx.isPointInPath(p[i], mouseX, mouseY)){
			switch (i){
				case(0):
					text="Trustcoin use a mining algorithm called Ouroboros or proof-of-stake (POS) algorithm. Trustcoin was created by a strong development team for the 3rd Blockchain generation."
					icon = "page/img-new/feature/1.png";
					break;
				case(1):
					text="With POS , the creator of new block is chosen in a deterministic way , depending on its wealth, also defined as stake. It means , the more Trustcoin investors own, the more times their assets increase.";
					icon = "page/img-new/feature/3.png";
					break;
				case(2):
					text="However , each investors can only own limited amount of Trustcoin . So that a chance of increasing assets is different.";
					icon = "page/img-new/feature/4.png";
					break;
				case(3):
					text="Investors owning Trustcoin will receive maximum benefits from POS algorithm.";
					icon = "page/img-new/feature/5.png";
					break;
				case(4):
					text="Along with the Trust Box , investors will feel the greatness of the synergy of community power in mining Trustcoin by using the POS algorithm while storing it at http://trustcoinbox.com.";
					icon = "page/img-new/feature/2.png";
					break;
			}
			
			if(i!=index){
				index=i;
				highlight();
			}
			
		}		
	}
})
var count = 0;
function highlight(){
	ctx.clearRect(0,0,canvasDraw.width,canvasDraw.height);
	
	featureShow1.innerHTML = text;
	featureShow2.innerHTML = text;
	
	$(".feature--icon img").attr("src",icon);
	
	$('.real--feature,.responsive--feature').addClass('animated flipInY faster')
	setTimeout(function(){
		$('.real--feature,.responsive--feature').removeClass('animated flipInY faster')
	},500)
	
	ctx.drawImage(spinner,0,0);
	
	ctx.fillStyle = 'rgba(22, 135, 194, 0.5)';
	ctx.fill(p[index]);
}

window.onresize = function() {
	ctx.scale(1,1);
	responsiveFeature()
};

function responsiveFeature(){
	resVal=0.8;
	if(window.innerWidth<=1242 && window.innerWidth>=1024){
		resVal=0.7;
	}
	if(window.innerWidth<=450){
		resVal=0.5;
	}
	if(window.innerWidth>=1500){
		resVal=1;
	}
	canvasDraw.width = spinner.width*resVal;
	canvasDraw.height = spinner.height*resVal;
	
	ctx.scale(resVal,resVal);
	ctx.drawImage(spinner,0,0);
	
	drawPath();
	
	ctx.fillStyle = 'rgba(22, 135, 194, 0.5)';
	ctx.fill(p[index]);
	
	count++;
	
	animationLoad = requestAnimationFrame(responsiveFeature);
	
	if(count==2){
		cancelAnimationFrame(animationLoad);
		count=0;
	}	
}
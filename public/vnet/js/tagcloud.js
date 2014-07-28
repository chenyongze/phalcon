function TagCould(container,options){
		this.radius = 150;
		this.dtr = Math.PI/180;
		this.d = 500;

		this.mcList = [];
		this.active = false;
		this.lasta = 1;
		this.lastb = 1;
		this.distr = true;
		this.tspeed = 5;
		this.size = 250;

		this.mouseX = 0;
		this.mouseY = 0;

		this.howElliptical = 1;

		this.aA = null;
		this.oDiv = null;
		
		this.container = container;
		
		options = options || {};
		for(var p in options){
			this[p] = options[p];
		}
	}
	
	TagCould.prototype.start = function(){
		var i = 0, oTag = null, self = this;
	
		this.oDiv = typeof this.container == "string" ? document.getElementById(this.container) : this.container;
		
		this.aA = this.oDiv.getElementsByTagName('a');
		
		for(i = 0; i < this.aA.length; i++){
			oTag={};
			
			oTag.offsetWidth = this.aA[i].offsetWidth;
			oTag.offsetHeight = this.aA[i].offsetHeight;
			
			this.mcList.push(oTag);
		}
		
		this.sineCosine( 0,0,0 );
		
		this.positionAll();
		
		document.addEventListener('mouseover',function(){
			self.active = true;
		}, false);
		
		document.addEventListener('mouseout',function(){
			self.active = false;
		}, false);
		
		document.addEventListener('mousemove',function(evt){
			//var oEvent=window.event || evt;
			self.onmove(window.event || evt);
		}, false);
		
		document.addEventListener('touchstart',function(){
			self.active = true;
		}, false);
		document.addEventListener('touchmove',function(evt){
			self.onmove(window.event || evt);
		}, false);
		document.addEventListener('touchend',function(){
			self.active = false;
		}, false);
		
		setInterval(function(){
			self.update();
		}, 30);

	}
	
	TagCould.prototype.onmove = function(oEvent){
		//var oEvent=window.event || evt;
		oEvent.preventDefault();  
		
		if(oEvent.touches && oEvent.touches.length > 0){
			oEvent.clientX = oEvent.touches[0].clientX;
			oEvent.clientY = oEvent.touches[0].clientY;
		}

		this.mouseX = oEvent.clientX - (this.oDiv.offsetLeft + this.oDiv.offsetWidth / 2);
		this.mouseY = oEvent.clientY - (this.oDiv.offsetTop + this.oDiv.offsetHeight / 2);
		
		this.mouseX /= 5;
		this.mouseY /= 5;
	}
	
	TagCould.prototype.update = function(){
		var a,b;
		
		if(this.active){
			a = (-Math.min( Math.max( -this.mouseY, -this.size ), this.size ) / this.radius ) * this.tspeed;
			b = (Math.min( Math.max( -this.mouseX, -this.size ), this.size ) / this.radius ) * this.tspeed;
		}
		else{
			a = this.lasta * 0.98;
			b = this.lastb * 0.98;
		}
		
		this.lasta = a;
		this.lastb = b;
		
		if(Math.abs(a)<=0.01 && Math.abs(b)<=0.01){
			return;
		}
		
		var c=0;
		this.sineCosine(a,b,c);
		for(var j = 0; j < this.mcList.length; j++){
			var rx1 = this.mcList[j].cx,
				ry1 = this.mcList[j].cy * this.ca + this.mcList[j].cz * (-this.sa),
				rz1 = this.mcList[j].cy * this.sa + this.mcList[j].cz * this.ca,
				rx2 = rx1 * this.cb + rz1 * this.sb,
				ry2 = ry1,
				rz2 = rx1 * (-this.sb) + rz1 * this.cb,
				rx3 = rx2 * this.cc + ry2 * (-this.sc),
				ry3 = rx2 * this.sc + ry2 * this.cc,
				rz3 = rz2;
			
			this.mcList[j].cx = rx3;
			this.mcList[j].cy = ry3;
			this.mcList[j].cz = rz3;
			
			var per = this.d / (this.d + rz3);
			
			this.mcList[j].x = ( this.howElliptical * rx3 * per ) - ( this.howElliptical * 2 );
			this.mcList[j].y = ry3 * per;
			this.mcList[j].scale = per;
			this.mcList[j].alpha = per;
			
			this.mcList[j].alpha = (this.mcList[j].alpha - 0.6) * ( 10 / 6 );
		}
		
		this.doPosition();
		this.depthSort();
	}

	TagCould.prototype.depthSort = function(){
		var i=0,aTmp=[];
		
		for(i=0;i<this.aA.length;i++){
			aTmp.push(this.aA[i]);
		}
		
		aTmp.sort(
			function (vItem1, vItem2){
				if(vItem1.cz > vItem2.cz){
					return -1;
				}
				else if(vItem1.cz < vItem2.cz){
					return 1;
				}
				else{
					return 0;
				}
			}
		);
		
		for(i=0;i<aTmp.length;i++){
			aTmp[i].style.zIndex=i;
		}
	}

	TagCould.prototype.positionAll = function (){
		var phi = 0,theta = 0,max = this.mcList.length,i = 0,aTmp = [],
			oFragment = document.createDocumentFragment();
		
		//随机排序
		for(i=0;i<this.aA.length;i++){
			aTmp.push(this.aA[i]);
		}
		
		aTmp.sort(
			function (){
				return Math.random()<0.5?1:-1;
			}
		);
		
		for(i=0;i<aTmp.length;i++){
			oFragment.appendChild(aTmp[i]);
		}
		
		this.oDiv.appendChild(oFragment);
		
		for( var i=1; i< max+1; i++){
			if( this.distr ){
				phi = Math.acos(-1+(2*i-1)/max);
				theta = Math.sqrt(max*Math.PI)*phi;
			}
			else{
				phi = Math.random() * (Math.PI);
				theta = Math.random() * (2*Math.PI);
			}
			//坐标变换
			this.mcList[i-1].cx = this.radius * Math.cos(theta) * Math.sin(phi);
			this.mcList[i-1].cy = this.radius * Math.sin(theta) * Math.sin(phi);
			this.mcList[i-1].cz = this.radius * Math.cos(phi);
			
			this.aA[i-1].style.left = this.mcList[i-1].cx + this.oDiv.offsetWidth/2 - this.mcList[i-1].offsetWidth/2+'px';
			this.aA[i-1].style.top = this.mcList[i-1].cy + this.oDiv.offsetHeight/2 - this.mcList[i-1].offsetHeight/2+'px';
		}
	}

	TagCould.prototype.doPosition = function(){
		var l = this.oDiv.offsetWidth / 2,t = this.oDiv.offsetHeight / 2;
		for(var i=0; i < this.mcList.length; i++){
			this.aA[i].style.left = this.mcList[i].cx + l - this.mcList[i].offsetWidth / 2+'px';
			this.aA[i].style.top = this.mcList[i].cy + t - this.mcList[i].offsetHeight / 2+'px';
			
			this.aA[i].style.fontSize = Math.ceil(12 * this.mcList[i].scale / 2) + 8 + 'px';
			
			this.aA[i].style.filter = "alpha(opacity=" + 100 * this.mcList[i].alpha + ")";
			this.aA[i].style.opacity = this.mcList[i].alpha;
		}
	}

	TagCould.prototype.sineCosine = function( a, b, c){
		this.sa = Math.sin(a * this.dtr);
		this.ca = Math.cos(a * this.dtr);
		this.sb = Math.sin(b * this.dtr);
		this.cb = Math.cos(b * this.dtr);
		this.sc = Math.sin(c * this.dtr);
		this.cc = Math.cos(c * this.dtr);
	}
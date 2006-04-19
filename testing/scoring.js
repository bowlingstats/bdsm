var nonums = /^[0-9]*$/;//regex used to test if something is a number.

/*Game is our grand daddy object.  Everything goes on inside of an instance of Game.*/
function Game() {
	this.score = new Array();//totaled scores for each frame(the one under the ball scores)
	this.framescore = new Array();//individual scores for frames
	
	//define methods below.
	this.validate = validate;
	this.scoreGame = scoreGame;
}
/*
scoreGame() does the heavy lifting.  It stores every ball rolled in the framescore array.  It stores strikes as X's spares as #/'s and open frames as ##.
b1, b2, and b3 are the three possible balls in a frame.  They pull this information from the forms.  
It then processes each ball into framescore.
The 10th frame is dealt with specially, there is a quirk identifying 0 as an int, so I had to add a special case.
After filling framescore, it is processed to fill the score array.  
*/
function scoreGame(){
	this.framescore = new Array;//we'll need a fresh slate.
	for(this.frame = 1; this.frame <= 10; this.frame++){
		this.b1 = document.getElementById(this.name+'f'+this.frame+'b1').value;
		this.b2 = document.getElementById(this.name+'f'+this.frame+'b2').value;
		if(this.frame == 10) this.b3 = document.getElementById(this.name+'f10b3').value;
		
		if(this.frame != 10){//fill framescore with the first 9 frame's information.
			if(this.b1 == "X"){
				this.framescore = this.framescore.concat("X");
			}else if (this.b2 == "/") {
				this.framescore = this.framescore.concat(parseInt(this.b1), "/")
			}else if(this.frame == 10 && this.b2 == "X"){
				this.framescore = this.framescore.concat("X", 10);
			}else {
				this.framescore = this.framescore.concat(parseInt(this.b1), parseInt(this.b2));
			}
		}else {
			//turn all three balls in frame 10 into integers, because no bonuses are added in this frame.
			if(this.b1 == "X") this.b1 = 10;
			else if(parseInt(this.b1)) this.b1 = parseInt(this.b1);
			else if(this.b1 == "0") this.b1 = 0;
			if(this.b2 == "X") this.b2 = 10;
			else if(this.b2 == "/") this.b2 = 10 - this.b1;
			else if(parseInt(this.b2)) this.b2 = parseInt(this.b2);
			else if(this.b2 == "0") this.b2 = 0;
			if(this.b3 == "X") this.b3 = 10;
			else if(this.b3 == "/") this.b3 = 10 - this.b2;
			else if(this.b3 == "0") this.b3 = 0;
			else if(parseInt(this.b3)) this.b3 = parseInt(this.b3);
			else this.b3 = 0;
			
			this.framescore = this.framescore.concat(parseInt(this.b1), parseInt(this.b2), parseInt(this.b3));//append the last three balls to framescore
		}
	}
	
	//now chew on the freshly filled framescore array to generate the scores.
	this.score = new Array; //blank any scores that may be in the array, we need a fresh slate.
	this.fsp = 0;//keeps track of where we are in framescore. framescore's position
	for(this.frame = 0; this.frame < 10; this.frame++){
		if(this.frame != 9){//deal with all cases involving strikes.
			if(this.framescore[this.fsp] == "X"){ //the cases for strikes
				if(this.framescore[this.fsp + 1] =="X" && this.framescore[this.fsp + 2] == "X") this.score[this.frame] = 30;
				else if(this.framescore[this.fsp + 1] == "X" && this.framescore[this.fsp + 2] != "X") this.score[this.frame] = 20 + this.framescore[this.fsp + 2];
				else if(this.framescore[this.fsp + 2] == "/") this.score[this.frame] = 20;
				else this.score[this.frame] = 10 + this.framescore[this.fsp+1] + this.framescore[this.fsp+2];
				this.fsp += 1;//advnace fsp for the next pass
			}else if(this.framescore[this.fsp + 1] == "/"){//deal with all cases involving spares
				if(this.framescore[this.fsp + 2] == "X") this.score[this.frame] = 20;
				else this.score[this.frame] = 10 + this.framescore[this.fsp + 2];
				this.fsp += 2;//advance fsp for the next pass
			}else {//deal with an open frame.
				this.score[this.frame] = this.framescore[this.fsp] + this.framescore[this.fsp+1];
				this.fsp += 2;//advance fsp for the next pass
			}
		}else{//score the 10th frame.
			this.score[this.frame] = this.framescore[this.fsp] + this.framescore[this.fsp + 1] + this.framescore[this.fsp + 2];
		}
	}
	//now we use a for loop to calculate the total for each frame.  We then write to the innerHTML of a TD tag.
	this.total = 0;
	for(this.x = 0; this.x < 10; this.x++){
		if(nonums.test(this.total + this.score[this.x])){
			this.total += this.score[this.x];
			document.getElementById(this.name+'score'+ (this.x+1)).innerHTML = this.total;
		}else{
			document.getElementById(this.name+'score'+ (this.x+1)).innerHTML = "&nbsp;";
		}
	}
	document.getElementById(this.name+'score').value = this.total;
	
}

/*
validate() is used to clean up input in the forms.  If a spare is entered as two numbers, it make ball2 a "/"
it makes certain that nothing else is entered with a strike.
It also ensures that no frame has a value higher than 10 entered into it.
*/
function validate(frame){
	//get elements, then get the vaules
	this.ball1 = document.getElementById(this.name + "f" + frame + "b1");
	this.ball2 = document.getElementById(this.name + "f" + frame + "b2");
	if(frame == "10") this.ball3 = document.getElementById(this.name+ "f10b3");
	this.b1 = document.getElementById(this.name + "f" + frame + "b1").value;
	this.b2 = document.getElementById(this.name + "f" + frame + "b2").value;
	if(frame == "10") this.b3 = this.ball3.value;
	//convert -'s to 0's
	if(this.b1 == "-"){
		this.b1 = "0";
		this.ball1.value = "0";
	}
	if(this.b2 == "-"){
		this.b2 = "0";
		this.ball2.value = "0";
	}
	if(this.b3 == "-"){
		this.b3 = "0";
		this.ball3.value = "0";
	}
	//convert x's and *'s to X's
	if(this.b1 == "x" || this.b1 == "*"){
		this.b1 = "X";
		this.ball1.value = "X";
	}
	
	//verify b1 is a valid character.
	if(frame != "10"){		
		//if ball1 is not an X or a number, it is invalid.
		if(!isStrike(this.b1) && !nonums.test(this.b1)){
			alert("Ball 1 must be a number 0-9\nand X, x, or * for strikes");
			this.ball1.value = "";
			this.ball1.focus();
		}
		
		if(this.b1 == "X"){//if b1 is a X there is no 2nd ball
			this.ball1.value = "X";
			this.ball2.value = "";
			document.getElementById(this.name + "f" + frame + "b2").focus();
			document.getElementById(this.name + "f" + (parseInt(frame)+1) + "b1").focus();
		} else if(isSpare(this.b1, this.b2)){//if it's a spare b2 should be /
			this.ball2.value = "/";
		} else if(parseInt(this.b1) + parseInt(this.b2) > 10){//no two balls can be more than 10 pins, blank ball2
			alert("The maximum value for one frame is 10.");
			this.ball2.value = "";
			this.ball1.focus();
		} else if(this.b2 != "/" && !nonums.test(this.b2)){
			alert("Ball 2 must be a number 0-9 or /");
			this.ball2.value = "";
			this.ball1.focus();
		}
	} else{//check ball1 10th frame.
		//if ball1 is not a X or a number it is invalid.
		if(!isStrike(this.b1) && !nonums.test(this.b1)){
			alert("Ball 1 must be a number 0-9\nand X, x, or * for strikes");
			this.ball1.value = "";
			this.ball1.focus();
		}
		//if ball1 is a X ball2 cannot be a /, if it is not a number or a X it is invalid
		if(isStrike(this.b1)){
			if(!isStrike(this.b2) && !nonums.test(this.b2)){
				alert("Ball 1 is strike, ball 2 must be another strike or a number.");
				this.ball2.value = "";
				this.ball1.focus();
			}
		}
		//if ball one is a # then ball 2 cannot be a X
		if(nonums.test(this.b1) && isStrike(this.b2)){
			alert("Ball 1 is not a X, ball 2 cannot be a strike.");
			this.ball2.value = "";
			this.ball1.focus();
		}
		//if balls 1 and 2 are a / ball 3 can only be a # or a X
		if(isSpare(this.b1, this.b2) && this.b3 == "/"){
			alert("Ball 2 is a /, ball 3 cannot also be a /.");
			this.ball3.value = "0";
			this.ball2.focus();
		}
		
		//ball 3 can be a #, a /, or a X; but only in special cases
		if(isStrike(this.b2) && !isStrike(this.b3)){
			if(this.b3 == "/" || !nonums.test(this.b3)){
				alert("Ball 2 is a X, ball 3 cannot be a /");
				this.ball3.value = "";
				this.ball2.focus();
			}
		} else if(isSpare(this.b2, this.b3)){
			this.ball3.value = "/";
		}
		
		if(isSpare(this.b1, this.b2)) this.ball2.value = "/";
		if(!isSpare(this.b2, this.b3) && parseInt(this.b2) + parseInt(this.b3) > 10){
			alert("Ball 2 and ball 3 cannot be greater than 10");
			this.ball3.value = "";
			this.ball2.focus();
		}
		if(!isSpare(this.b1, this.b2) && !isStrike(this.b1)){
			this.ball3.value = "0";
		}
		if(isStrike(this.b1)) this.ball1.value = "X";
		if(isStrike(this.b2)) this.ball2.value = "X";
		if(isStrike(this.b3)) this.ball3.value = "X";
		
		//ball3 cannot be blank, it causes trouble for the database.
		if(this.b3 == "") this.ball3.value = "0";
	}
}


function isStrike(ball){
	if(ball == "X" || ball == "x" || ball == "*"){
		return true;
	} else{
		return false;
	}
}

function isSpare(ball1, ball2){
	if(nonums.test(ball1) && ball2 == "/") return true;
	else if(parseInt(ball1) + parseInt(ball2) == 10) return true;
	else return false;	
}
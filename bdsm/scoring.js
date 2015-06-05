/*
Bowling Database/Statistics Management
Copyright (C) 2006 Jason Francis and BD/SM developement team

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
*/

var nonums = /^[0-9]*$/;//regex used to test if something is a number.

/*Game is our grand daddy object.  Everything goes on inside of an instance of Game.*/
function Game() {
	this.score = new Array();//totaled scores for each frame(the one under the ball scores)
	this.framescore = new Array();//individual scores for frames
	
	//define methods below.
	this.validate = validate;
	this.scoreGame = scoreGame;
	this.pinSums = pinSums;
	this.pinfall = pinfall;
	this.pinfallTenth = pinfallTenth;
	this.incrementPin = incrementPin;
	this.strikePin = strikePin;
	this.missPin = missPin;
	this.showRack = showRack;
	this.hideRack = hideRack;
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
			//document.getElementById(this.name + "f" + frame + "b2").focus();
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
		//if b1 isn't a strike b1 + b2 cannot exceed 10
		if(!isStrike(this.b1) && parseInt(this.b1) + parseInt(this.b2) > 10) {
			alert("Ball 1 and Ball 2 cannot exceed 10 pins.");
			this.ball2.value = "";
			this.ball1.focus();
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
		//avoid problems of a frame like X9X.
		if(!isStrike(this.b2) && !isSpare(this.b1, this.b2) && isStrike(this.b3)){
			alert("Ball 3 may only be a strike off of a strike or a spare.");
			this.ball3.value = 0;
			this.ball3.focus();
		}
		//ball three can have no value if the first two balls left an open frame
		if(parseInt(this.b1) + parseInt(this.b2) < 10){
			this.ball3.value = "0";
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

//pinfall takes the information from from the image of pins and adjust the scores in the score card.
function pinfall(frame){
	
	//create two counters, use the values for each pin to determine the score of each ball.
	this.b1Sum = this.pinSums(0, frame);
	this.b2Sum = this.pinSums(1, frame);
	//this.b3Sum = 0;
		
	if(this.b1Sum == 10) {
		this.b1Sum = "X";
		this.b2Sum = "";
	} else if(this.b2Sum == 10) {
	 this.b2Sum = "/";
	}
	
	//now set those b#Sum values in the form
	//10th frame is a special case.
	if(frame != 10 && frame != 11 && frame !=12) {
		this.ball1 = document.getElementById(this.name + "f" + frame + "b1");
		this.ball2 = document.getElementById(this.name + "f" + frame + "b2");
	} else {
		this.ball1 = document.getElementById(this.name + "f10b1");
		this.ball2 = document.getElementById(this.name + "f10b2");
		this.ball3 = document.getElementById(this.name + "f10b3");
	}
	
	this.ball1.value = this.b1Sum;
	this.ball2.value = this.b2Sum;
	
	//now validate the score, and score the game
	this.validate(frame);
	this.scoreGame();
}

function pinSums(state, frame){
	
	//get elements, then get the vaules
	this.pin1 = document.getElementById(this.name + "f" + frame + "p1");
	this.pin2 = document.getElementById(this.name + "f" + frame + "p2");
	this.pin3 = document.getElementById(this.name + "f" + frame + "p3");
	this.pin4 = document.getElementById(this.name + "f" + frame + "p4");
	this.pin5 = document.getElementById(this.name + "f" + frame + "p5");
	this.pin6 = document.getElementById(this.name + "f" + frame + "p6");
	this.pin7 = document.getElementById(this.name + "f" + frame + "p7");
	this.pin8 = document.getElementById(this.name + "f" + frame + "p8");
	this.pin9 = document.getElementById(this.name + "f" + frame + "p9");
	this.pin10 = document.getElementById(this.name + "f" + frame + "p10");
	
	this.sum = 0;
	
	if(parseInt(this.pin1.value) == state) this.sum += 1;
	if(parseInt(this.pin2.value) == state) this.sum += 1;
	if(parseInt(this.pin3.value) == state) this.sum += 1;
	if(parseInt(this.pin4.value) == state) this.sum += 1;
	if(parseInt(this.pin5.value) == state) this.sum += 1;
	if(parseInt(this.pin6.value) == state) this.sum += 1;
	if(parseInt(this.pin7.value) == state) this.sum += 1;
	if(parseInt(this.pin8.value) == state) this.sum += 1;
	if(parseInt(this.pin9.value) == state) this.sum += 1;
	if(parseInt(this.pin10.value) == state) this.sum += 1;
	
	return this.sum;
}
//operates like pinfall, but handles the 3rd ball.
function pinfallTenth(){
	
	//create two counters, use the values for each pin to determine the score of each ball.
	this.b1Sum = this.pinSums(0, 10);
	if(this.b1Sum == 10){
		//if b1Sum is a strike, we want the score from the second rack's first ball
		this.b2Sum = this.pinSums(0, 11);
		//if b2Sum is 10, it is a strike
		if(this.b2Sum == 10) this.b2Sum = "X";
	}else{
		//otherwise get the pincount for rack 1 ball 2.
		this.b2Sum = this.pinSums(1, 10);
	}
	this.b3Sum = 0;
	
	if(this.b1Sum == 10) {
		this.b1Sum = "X";
	} else if(this.b2Sum == 10) {
	 this.b2Sum = "/";
	} else if(this.b1Sum + this.b2Sum == 10){
		this.b2Sum = "/";
	}
	
	//now set those b#Sum values in the form
	this.ball1 = document.getElementById(this.name + "f10b1");
	this.ball2 = document.getElementById(this.name + "f10b2");
	this.ball3 = document.getElementById(this.name + "f10b3");
	
	this.ball1.value = this.b1Sum;
	this.ball2.value = this.b2Sum;
	
	
	if(this.b1Sum == "X"){//if b1 is a strike open up the 2nd rack to enter scores, then score them appropriately
		this.showRack(11);
		if(this.b2Sum != "X"){
			this.hideRack(12);
		}
		else this.showRack(12);
		//anytime a bonus rack is hidden we need to reset all of it's values to "0" or null, and make the images blank
		
		this.b2Sum = this.pinSums(0, 11);
		this.b3Sum = this.pinSums(1, 11);
		
		if(this.b2Sum == 10){
			this.ball2.value = "X";
			this.showRack(12);//open up a 3rd rack here
			if (this.pinSums(0, 12) == 10) this.ball3.value = "X";
			else this.ball3.value = this.pinSums(0, 12);
		} else if(this.b2Sum + this.b3Sum == 10){
			this.ball2.value = this.b2Sum;
			this.ball3.value = "/";
		} else {
		this.ball2.value = this.b2Sum;
		this.ball3.value = this.b3Sum;
		}
	
	} else if (this.b2Sum == "/"){//if the first rack of the 10th is a spare give them one last ball to work with
		this.showRack(11);
		this.hideRack(12);		
		this.b3Sum = this.pinSums(1, 11);
		
		if(this.b3Sum == 0) this.ball3.value = "X";
		else this.ball3.value = 10 - parseInt(this.b3Sum);
		
	} else {
		this.hideRack(11);
		this.hideRack(12);
		this.ball3.value = 0;
	}
	
	//now validate the score, and score the game
	this.validate(10);
	this.scoreGame();

}

//showRack() takes a frame number, and sets the values of the pins to 0's, works with hide rack(which turns pins to nulls.
function showRack(frame){
	//only change values if it has been hidden, we'll know because values will be ''
	for(n = 1; n <= 10; n++){
		this.status = document.getElementById(this.name + "f" + frame + "p" + n);
		if(this.status.value == ''){
			this.status.value = 0;
			rackImage = document.getElementById(this.name+ "f" + frame + "p" + n + "image");
			rackImage.src = "images/down.png";
		}
	}
	//Frame is now ready to display
	this.frameToShow = document.getElementById(this.name + "pins" + frame).style.visibility = "visible";
}

function hideRack(frame){
	//always change the values when hiding, so as to not poision the database statistics.
	for(n = 1; n <= 10; n++){
		this.status = document.getElementById(this.name + "f" + frame + "p" + n);
		this.status.value = '';
		
		image = document.getElementById(this.name+ "f" + frame + "p" + n + "image");
		image.src = "images/down.png";
	}
	//frame is now ready to be hidden.
	this.frameToHide = document.getElementById(this.name + "pins" + frame).style.visibility = "hidden";
}


function incrementPin(frame, pin, image){

	//this.status = document.getElementById(this.name + "f" + frame + "p" + pin);
	
	//there is a special case where in the 10th frame balls 1 and 2 are a spare, so pins can only be left once.
	if(frame == 11 && document.getElementById(this.name + "f10b2").value == "/"){
		this.status = document.getElementById(this.name + "f11p" + pin);
		if(parseInt(this.status.value) != 0) this.status.value = 0;
		else this.status.value = 1;
	} else if(frame == 12 && document.getElementById(this.name + "f10b2").value == "X"){
		this.status = document.getElementById(this.name + "f12p" + pin);
		if(parseInt(this.status.value) != 0) this.status.value = 0;
		else this.status.value = 1;	
	} else {
		this.status = document.getElementById(this.name + "f" + frame + "p" + pin);
		if(parseInt(this.status.value) == 2) this.status.value = 0;
		else this.status.value = parseInt(this.status.value) + 1;
	}
	
	if(parseInt(this.status.value) == 0) image.src = "images/down.png";
	else if (parseInt(this.status.value) == 1) image.src = "images/ball1.png";
	else image.src = "images/ball2.png";
	
	if(frame == 10 || frame == 11 || frame == 12) this.pinfallTenth();
	else this.pinfall(frame);
}

function strikePin(frame){

	for(count = 1; count <= 10; count++){
		this.status = document.getElementById(this.name + "f" + frame + "p" + count);
		this.status.value = 0;
		image = document.getElementById(this.name+ "f" + frame + "p" + count + "image");
		image.src = "images/down.png";
	}
	if(frame == 10 || frame == 11 || frame == 12) this.pinfallTenth();
	else this.pinfall(frame);

}



function missPin(frame){
	//looks through the pins' states and increments those pins that were missed.
	countZero = new Array();
	countOne = new Array();
	countTwo = new Array();
	for(count = 1; count <= 10; count++){
		this.pinStatus = document.getElementById(this.name + "f" + frame + "p" + count);
		if(this.pinStatus.value == 0 || this.pinStatus.value == '') countZero = countZero.concat(count);
		if(this.pinStatus.value == 1) countOne = countOne.concat(count);
		if(this.pinStatus.value == 2) countTwo = countTwo.concat(count);
	}
	//add control to only increment pins that have been missed(eg 2nd ball only affects remaining pins)
	/*
	for(count = 1; count <= 10; count++){
		image = document.getElementById(this.name+ "f" + frame + "p" + count + "image");
		this.incrementPin(frame, count, image);
	}
	*/
	if(countZero.length == 10){//all pins were missed with first ball, increment all pins
		for(count = 1; count <= 10; count++){
			image = document.getElementById(this.name+ "f" + frame + "p" + count + "image");
			this.incrementPin(frame, count, image);
		}
	} else if(countOne.length > 0 && countTwo.length == 0){//only increment pins that were missed with ball 1, if any are marked as missed with ball2 it is ambiguous so we do nothing.
		for(count = 0; count < countOne.length; count++){
			image = document.getElementById(this.name + "f" + frame + "p" + countOne[count] + "image");
			this.incrementPin(frame, countOne[count], image);
		}
	} else {//since we had pins in multiple states we'll just default the frame back to a strike.
		this.strikePin(frame);
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


/*var src, setCust and restore all have to do with setting a custom location.  A user may use setCust, but they may also revert to the origional list using restore()*/
var src;
function setCust(){
	src = document.getElementById("location").innerHTML;
	document.getElementById("location").innerHTML = "<input type='text'name='location' id='loc'><input type='button' value='cancel' onClick='restore()'>";
}
function restore(){
	document.getElementById("location").innerHTML = src;
}

//val() makes certain all forms are ship shape before being inserted in to the database.
function val(){
	if(parseInt(document.getElementById("year").value) && parseInt(document.getElementById("month").value) && parseInt(document.getElementById("day").value)){
	} else{
		alert("Year, Month, and Day must all be numbers");
		return false;
	}
	
	if(document.getElementById("loc").value != ""){
		return true;
	} else {
		alert("Location cannot be blank");
		return false;
	}
}


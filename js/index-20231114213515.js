$("#leftside-navigation .sub-menu > a").click(function(e) {
  $("#leftside-navigation ul ul").slideUp(),
  $(this).next().is(":visible") || $(this).next().slideDown(),
  
  
  e.stopPropagation()
})

var elem3 = document.getElementById("includefreefrontlinks");
function includefreefrontlinksshow() {
  elem3.classList.toggle('includefreefrontlinksshow');
}

var elem = document.getElementById("chart1");
function slide() {
  elem.classList.toggle('show');
}

var elem2 = document.getElementById("box");
function boxshow() {
//   elem.classList.toggle('show');
    elem2.classList.toggle('showbox');
}

let body = document.querySelector("body");
let dark = document.querySelector("#dark");
dark.onclick = function () {
	if (dark.checked == true) {
		body.classList.add("darkmode");
	} else {
		body.classList.remove("darkmode");
	}
};



var timerbody = document.getElementById("timerbody");
// let timerbody = document.querySelector("timerbody");
let timecheckbox = document.querySelector("#timecheckbox");
timecheckbox.onclick = function () {
	if (timecheckbox.checked == true) {
// 		timerbody.classList.toogle("timervisible");
  timerbody.classList.toggle('timervisible');
	} else {
// 		timerbody.classList.remove("timervisible");
  timerbody.classList.toggle('timervisible');
	}
};






const time = document.getElementById("time");
const day = document.getElementById("day");
const midday = document.getElementById("midday");

let clock = setInterval(
	function calcTime() {
		let date_now = new Date();
		let hr = date_now.getHours();
// 		let hr = 9;
		let min = date_now.getMinutes();
		let sec = date_now.getSeconds();
		let middayValue = "AM";

		let days = [
			"Niedziela",
			"Poniedziałek",
			"Wtorek",
			"Środa",
			"Czwartek",
			"Piątek",
			"Sobota"
		];

		day.textContent = days[date_now.getDay()];

		middayValue = hr >= 12 ? "PM" : "AM";

		if (hr == 0) {
			hr = 12;
		} else if (hr > 12) {
			hr -= 12;
		}

		hr = hr < 10 ? "0" + hr : hr;
		min = min < 10 ? "0" + min : min;
		sec = sec < 10 ? "0" + sec : sec;

		time.textContent = hr + ":" + min + ":" + sec;
		midday.textContent = middayValue;
	},

	1000
);

function lol() {

}

// sleep time expects milliseconds
function sleep (time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}

// Usage!


function addRowNya() {
	var a = document.getElementById('inputtable');
	var b = document.getElementById('feedsel');
	var r = a.insertRow(-1);
	var x = document.createElement("INPUT");
	  x.setAttribute("type", "text");
	  x.id = "regex_reg_" + b.value;
	  x.name = "regex_reg_" + b.value;
	var y = document.createElement("INPUT");
	  y.setAttribute("type", "text");
	  y.id = "regex_rep_" + b.value;
	  y.name = "regex_rep_" + b.value;
	var c0 = r.insertCell(0);
	  c0.appendChild(x);
	var c1 = r.insertCell(1);
	  c1.appendChild(y);
	var c2 = r.insertCell(2);
	  c2.innerHTML = b.options[b.selectedIndex].text ;
}

document.getElementById('slider').addEventListener("freshrssSliderLoadEvent", function (event) {
	document.getElementById("addEntry").addEventListener("click", addRowNya);
});
 
// identify an element to observe
elementToObserve = document.getElementById('slider-content');

// create a new instance of 'MutationObserver' named 'observer',
// passing it a callback function
observer = new MutationObserver(function(mutationsList, observer) {
	sleep(500).then(() => {
		document.getElementById("addEntry").addEventListener("click", addRowNya);
	});
});

// call 'observe' on that MutationObserver instance,
// passing it the element to observe, and the options object
observer.observe(elementToObserve, {characterData: false, childList: true, attributes: false});





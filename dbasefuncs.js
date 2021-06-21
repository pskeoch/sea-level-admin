function rundsetdload(dsetid) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("infoarea").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "rundownload.php?dsetid=" + dsetid, true);
	xhttp.send();
	display_datasetstable()
}

function rundbaseupd(dsetid,tgid) {
	//alert(tgid);
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("dbaseprog").value = document.getElementById("dbaseprog").value + parseInt(this.responseText);
			//document.getElementById("dsetdloadopts").innerHTML = tgid;
			alert(this.responseText);
		}
	};
	xhttp.open("GET", "rundbaseupd.php?dsetid=" + dsetid + "&dsettype=psmsl&tgid=" + tgid, true);
	xhttp.send();
}

function rundbaseupdstart(dsetid) {
	alert(dsetid);
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			alert(this.responseText);
			tglisttxt = this.responseText;
			tglist = JSON.parse(tglisttxt);
			alert("hello");
			alert(tglist);
			document.getElementById("infoarea").innerHTML = "<label for='dbase'>Database update progress:</label><progress id='dbaseprog' value='0' max='" + 1100 + "'> 0% </progress>";
			
			//now trigger iterating loop to run each tg in turn and update progress bar, ends only when progress bar reaches max value
			//alert(progmax);
			for (i=0;i<1100;i++) {
				document.getElementById("dsetdloadopts").innerHTML = i;
				rundbaseupd(dsetid,tglist[i]);
			}
		}
	};
	xhttp.open("GET", "rundbaseupdstart.php?dsetid=" + dsetid + "&dsettype=psmsl", true);
	xhttp.send();
	
}

function display_datasetstable() {
	document.getElementById("dsetdloadopts").style.display = "none";
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("dsetdloadopts").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "getlatestdsets.php", true);
	xhttp.send();
	display_dsetdloadopts();
}

function display_adddset() {
	document.getElementById("adddsetarea").style.display = "block";
	document.getElementById("dsetdloadopts").style.display = "none";
}
function display_dsetdloadopts() {
	document.getElementById("dsetdloadopts").style.display = "block";
	document.getElementById("adddsetarea").style.display = "none";
}

function deletedataset_end(dsetid,tgid) {
	alert("hi there");
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("infoarea").innerHTML = this.responseText;
		}
	};
	xhttp.open("GET", "deldbaseend.php?dsetid=" + dsetid + "&dsettype=psmsl", true);
	xhttp.send();
}
function deletedataset(dsetid,tgid) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if (this.responseText == "1") {
				document.getElementById("dbaseprog").value = document.getElementById("dbaseprog").value + parseInt(this.responseText);
			} else {
				document.getElementById("infoarea").innerHTML = this.responseText;
			}
			document.getElementById("dsetdloadopts").innerHTML = tgid;
		}
	};
	xhttp.open("GET", "deldbase.php?dsetid=" + dsetid + "&dsettype=psmsl&tgid=" + tgid, true);
	xhttp.send();
}
function deletedataset_start(dsetid) {
	//alert("hello");
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//alert(this.responseText);
			tglisttxt = this.responseText;
			tglist = JSON.parse(tglisttxt);
			document.getElementById("infoarea").innerHTML = "<label for='dbase'>Database deletion progress:</label><progress id='dbaseprog' value='0' max='" + tglist.length + "'> 0% </progress>";
			
			//now trigger iterating loop to run each tg in turn and update progress bar, ends only when progress bar reaches max value
			//alert(progmax);
			for (i=0;i<tglist.length;i++) {
				deletedataset(dsetid,tglist[i]);
			}
			
			//now need to finish by deleting dataset index table and dataset reference in datasets table
			alert("helloit");
			deletedataset_end(dsetid);
			
		}
	};
	xhttp.open("GET", "deldbasestart.php?dsetid=" + dsetid + "&dsettype=psmsl", true);
	xhttp.send();
}
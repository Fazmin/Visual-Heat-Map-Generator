<?php
/***************Heat Map Generator v1.1*********************
(c) Fazmin 2011. All Rights Reserved
Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!
Limitations:
- This script cannot be sold.
- This script should have copyright notice intact. Dont remove it please...
- This script may not be provided for download except from its original site.

For further usage, please contact me.

***********************************************************/

include 'fileupload.php';
if(isset($_FILES['image']['name'])) {
	list($file,$error) = upload('image','uploads/','');
	if(isset($file)){ echo '<img id="uploadstatus" style="position:absolute;left:450px;top:8px;" src="images/yes.png">';
	$csvdata=file_get_contents('uploads/'.$file);
	}
	if($error) {print $error;}
}

?>
<html>
<head>
<link href='http://fonts.googleapis.com/css?family=Lato:100,400' rel='stylesheet' type='text/css'>
<style>
body {
	font-family: 'Lato', sans-serif;
	color:#dadada;
	background-color:#1e1e1e;

}

table{
	font-family: 'Lato', sans-serif;
	border-collapse:collapse;
	border-spacing: 0px;
}
#sortnowtable tr td {
	font-family: 'Lato', sans-serif;
	font-size:6px;
	border-collapse:collapse;
	border-spacing: 0px;
}
th{
	font-size:8px;
}

#title_m{
	font-size:34px;
	cursor:pointer;
	color:#ec3f0d;
	position:absolute;
	left:-136px;
	top:255px;
	-webkit-transform: rotate(-90deg);
	-moz-transform: rotate(-90deg);
	-ms-transform: rotate(-90deg);
}

#slidertable td{
	font-size:14px;
	border-collapse:collapse;
}

#slidertable2 td{
	font-size:14px;
	border-collapse:collapse;
}

#sortnowtable td{
	cursor:default;
}

input { 
	background-color:#3b3b3b; 
	color: #000; 
	line-height:15px;
	border:0px;
	font-size:17px;
	font-weight:600;
	color:#cfcfcf;
	-webkit-border-radius: 6px;
	-moz-border-radius: 6px;
	border-radius: 6px;
}

.ui-tooltip-dark .ui-tooltip-content {
	font-size: 18px;
	border-width: 1px;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	border-color: #110f0f;
}

#types{
	font-size:14px;
	border-collapse:collapse;
}



</style>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="csvtotable.js"></script>
<script src="js/heatmap_js.js"></script>
<script src="heat.js"></script>
<script src="js/qtip.min.js"></script>
<script src="js/thecookies.js"></script>
<script src="js/juniform.min.js"></script>
<script src="js/jscolor/jscolor.js"></script>
<script src="clustering/figue.js"></script>
		
<link rel="stylesheet" href='css/jquery-ui-d.css' type='text/css'>
<link rel="stylesheet" href='css/qtip.min.css' type='text/css'>
<link rel="stylesheet" href='css/uniform.css' type='text/css'>
	<link rel="stylesheet" href="css/print.css" type="text/css" media="print" />

<script type="text/javascript">
$(document).ready(function() {
$("#page_color").focusout(function() {
//alert($('#page_color').val());
var bgcol=$('#page_color').val();
$('body').css({'background-color':bgcol});
});
});

function changeBG(whichColor){
document.bgColor = backColor[whichColor];
}


function printmap () {
	var datatoprint=$("#datacontainer").html();
	$('.pdfzone').val(datatoprint);
	$('.sendtocreate').click();
	/*$.post("createpdf.php", {printdata:datatoprint}, function (data) {
			//alert ('pdf Created!  '+ data);
			$('#thepdf').append(data);
		});*/
}
function drawtable (fl) {
	
	if(fl!=1){
	$(document).ready(function() {
	$(function() {
		$('#CSVTable').CSVToTable(fl,{
		tableClass:'sortable',	
		});
	
	});
	});
	} else if(fl==1){
	$(document).ready(function() {
	$(function() {
		$('#CSVTable').CSVToTable('<?php if(isset($file)){ echo "uploads/".$file;} ?>',{
		tableClass:'sortable',	
		});
	
	});
	//populatecsvdata (); //Populate the hidden CSV data zone on page.
	footerhide();	// First table
	$('#uploadstatus').hide();
	$('#pointto').show();
	});
	}	
}

function drawtable2 (fl) {
	
	if(fl!=1){
	$(document).ready(function() {
	$(function() {
		$('#CSVTable2').CSVToTable(fl,{
		tableClass:'sortable',	
		});
	
	});
	});
	} else if(fl==1){
	$(document).ready(function() {
	$(function() {
		$('#CSVTable2').CSVToTable('<?php if(isset($file)){ echo "uploads/".$file;} ?>',{
		tableClass:'sortable',	
		});
	
	});
	//populatecsvdata (); //Populate the hidden CSV data zone on page.
	//footerhide();	// First table
	//$('#uploadstatus').hide();
	//$('#pointto').show();
	});
	}	
}


function loaderoff() {
$('#stat').html('');
}

function loaderon() {
$('#stat').html('<img style="position:absolute;top:10px;left:850px;" src="images/loader.gif">');
}

function drawheat () {
heartmapbaby();
}

function footerhide() {
$('#footer').hide();
}
	
function heartmapbaby () {
	$(document).ready(function() {
	//var percentx= $('#percentcut').val();
		if ($('#percentcut').val()==''){
			var percentx=50;
		} else {
			var percentx= $('#percentcut').val();
		}
		
		if ($('#maxcut').val()==''){
			var maxcut=null;
		} else {
			var maxcut= $('#maxcut').val();
		}
		var designstyle=$('input[name=design]:checked').val();
		var pointtype=$('input[name=points]:checked').val();
		
		//Circle Size
		if ($('#circsize').val()==''){
		//alert ($('#circsize').val());
			var circsize=50;
		} else {
			var circsize= $('#circsize').val();
		}
		//alert($('input[name=design]:checked').val());

			if (pointtype=='all'){
				var setpoints=null;
			} else {
				var setpoints=maxcut;
			}
		
			$('#sortnowtable td').colorheat({
				painter: designstyle, //'fill' | 'bars' | 'bubbles' |'solo' | 'circlelog'
				bubblesDiameter: circsize, // px
				max: setpoints,
				colorMap:'burnit',
				callBeforePaint: function() {
				loaderon();
					// Hide all values under 50%
					if (this.data('percent') < percentx) {
						this.text('');
					}
				loaderoff();
				}
			
			});
	});
}

//Swith inside text to hide
function hideintext () {
$('.intextv').toggle();
}

//Implementing slider functions
$(function() {
	//Percentage Slider
		$( "#slider-range1" ).slider({
			min: 0,
			max: 100,
			value:50,
			//values: [ 75, 300 ],
			slide: function( event, ui ) {
				$( "#percentcut" ).val( ui.value );
				//$( "#maxcut" ).val( ui.values[ 1 ] );
			}
		});
		//$( "#amount" ).val( $( "#slider-range1" ).slider( "value" ) );

	//Max Value Slider
		$( "#slider-range2" ).slider({
			min: 0,
			max: 500,
			value:100,
			//values: [ 75, 300 ],
			slide: function( event, ui ) {
				//$( "#percentcut" ).val( ui.values[ 0 ] );
				$( "#maxcut" ).val( ui.value );
			}
		});
		//$( "#amount" ).val( $( "#slider-range2" ).slider( "value" ) );
		
		//heatplot Width
		$( "#slider-range3" ).slider({
			min: 20,
			max: 60,
			value:30,
			//values: [ 75, 300 ],
			slide: function( event, ui ) {
				//$( "#percentcut" ).val( ui.values[ 0 ] );
				//$('#sortnowtable').val(ui.value + ' px');
						$('#sortnowtable td').css('width', ui.value+'px');
						$( "#heatwidth" ).val( ui.value );
			}
		});
		//$( "#amount" ).val( $( "#slider-range2" ).slider( "value" ) );
		
		//heatplot Height
		$( "#slider-range4" ).slider({
			min: 10,
			max: 80,
			value:15,
			//values: [ 75, 300 ],
			slide: function( event, ui ) {
				//$( "#percentcut" ).val( ui.values[ 0 ] );
				//$('#sortnowtable').val(ui.value + ' px');
            $('#sortnowtable td').css('height', ui.value+'px');
						$( "#heatheight" ).val( ui.value );
			}
		});
		
		
		//Select first column and change text size
		$( "#slider-range5" ).slider({
			min: 8,
			max: 24,
			value:15,
			//values: [ 75, 300 ],
			slide: function( event, ui ) {
				//$( "#percentcut" ).val( ui.values[ 0 ] );
				//$('#sortnowtable').val(ui.value + ' px');
				//$('#sortnowtable td:first').css('font-size', ui.value+'px');
				//The first Td of the table
				//$('td:first', $(this).parents('tr')).css('font-size', ui.value+'px');
				$("#sortnowtable tr td:first-child").css('font-size', ui.value+'px');
				$('#sortnowtable th').css('font-size', ui.value+'px');
            //$('#sortnowtable td').css('height', ui.value+'px');
						$( "#headfont_s" ).val( ui.value );
			}
		});
	});

//Select first column and change text size
//var a = $('td:first', $(this).parents('tr')).text();


//Extrastyles to upload
	$(document).ready(function() {
		$("input,input:radio, input:file").uniform();		
	});
	
	function tooltipon () {
	$(document).ready(function() {
		$('#sortnowtable td').qtip({
		 position: {
				my: 'bottom left',  // Position my top left...
				at: 'top center' // at the bottom right of...
				//target: $('.selector') // my target
		 },
		 style: {
				classes: 'ui-tooltip-dark ui-tooltip-shadow'
		 }
		});
	});
	}
	
	
	//Image swap on mouse over
//Usage : <a href="contactus.php" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image25','','images/althbtn2.jpg',1)"><img src="images/hbtn2.jpg" id="Image25" /></a>
function MM_swapImgRestore() { 
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() {
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { 
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { 
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

//CLUStering

function parseData(data) {
	//var data=$('#csvdata').val();
	
	var labels = new Array() ;
	var vectors = new Array() ;
	lines = data.split("\n") ;
	for (var i = 0 ; i < lines.length ; i++) {
		if (lines[i].length == 0)
			continue ;
		var elements = lines[i].split(",") ;
		var label = elements.shift() ;
		var vector = new Array() ;
		for (j = 0 ; j < elements.length ; j++)
			vector.push(parseFloat(elements[j])) ;
		vectors.push(vector) ;
		labels.push(label) ;
	}
	return {'labels': labels , 'vectors': vectors} ;
}

function runKM() {
var datanow = $('#csvdata').val();
//var datanow=datanow.join('\n').replace(/\n{2,}/g,'\n');
var firstline=datanow.match(/^(.*?)\n/);
//alert(datanow);
//hacker4you
//alert(firstline[1]);
var datanow = datanow.replace(/^(.*?)\n/, "");
//var datanow=datanow.join('\n').replace(/\n{2,}/g,'\n');
//alert(datanow);
	var data = parseData (datanow);
	var vectors = data['vectors'] ;
	var labels = data['labels'] ;
	var domobj = 8;
	var K = 8;
  	var clusters = figue.kmeans(K , vectors);
	var filename='<?php if(isset($file)){ echo $file;} ?>';
	var txt ;
	var txt2 ;
	if (clusters) {
		//txt = 'index,'+firstline ;
		//txt += "<tr><th>Label</th><th>Vector</th><th>Cluster id</th><th>Cluster centroid</th></tr>";
		txt ='Cluster,'+firstline[1]+"\n";
		for (var i = 0 ; i < vectors.length ; i++) {
			var index = clusters.assignments[i] ;
			//txt += "<tr><td>" + labels[i] + "</td><td>" + vectors[i] + "</td><td>" + index + "</td><td>" + clusters.centroids[index] + "</td></tr>";
			txt += index+"c," + labels[i] + "," + vectors[i] + "\n";
			//txt += index+"," + labels[i] + "," + clusters.centroids[index] + "\n";
			
		}
		
		txt2 ='Cluster,'+firstline[1]+"\n";
		for (var i = 0 ; i < vectors.length ; i++) {
			var index = clusters.assignments[i] ;
			//txt += "<tr><td>" + labels[i] + "</td><td>" + vectors[i] + "</td><td>" + index + "</td><td>" + clusters.centroids[index] + "</td></tr>";
			txt2 += index+"c," + labels[i] + "," + clusters.centroids[index] + "\n";
			//txt += index+"," + labels[i] + "," + clusters.centroids[index] + "\n";
			
		}
		//txt += "</table>"
	} else {
		txt2 = "No result (too many clusters/too few different instances (try changing K)" ;
		}
  //document.getElementById('text').innerHTML = txt; 
  //document.getElementById('text').innerHTML = txt; 
  clusterfilemake(txt,filename,firstline[1])
  clusterfilemake2(txt2,filename,firstline[1])
}

function clusterfilemake(d,f,l) {
//uploads/clustered/c-
//alert(l);
   $.post("clusterfile_create.php", {'data':d,'filename':f,'line':'"'+l+'"'}, function(data) {
	    if (data !="none") {
		//alert(data);
	    } else {
		alert('didnt find anything');
		}
		drawtable (data);
	    });
		
}

function clusterfilemake2(d,f,l) {
//uploads/clustered/c-
//alert(l);
f='b-'+f;
   $.post("clusterfile_create.php", {'data':d,'filename':f,'line':'"'+l+'"'}, function(data) {
	    if (data !="none") {
		//alert(data);
	    } else {
		alert('didnt find anything');
		}
		drawtable2 (data);
	    });
		
}

//function populatecsvdata () {
//$('#csvdata').val('');
//}

  </script>
</head>

<body>
<div style="margin-left:110px;" id="topcontainer">
<div style="position:absolute;top:10px;right:10px;align:left">Color:&nbsp;&nbsp;<input style="vertical-align:middle;width:80px;height:12px:font-size:10px;" id="page_color" class="color" value="1E1E1E"></input></div>
	<div id="title_m">
	Heat things up visualy.
</div>
	<img style="position:absolute;top:10px;left:10px;align:left" src="images/thislogo.png" onclick="window.location.reload()"></img>
<img id="pointto" style="position:absolute;top:10px;left:830px;display:none;" src="images/pointto.png">
	<table id="slidertable2">
	<tr>
		<td height="20px" valign="top">
			<font style="font-size:25px;"></font><font style="font-size:60px;line-height:45px;color:#868686;">1</font><!--<img src="images/start.png" style="">-->
		</td>
		<td height="20px">
			<form action="" style="margin-top:0px;font-size:16px;" method="post" enctype="multipart/form-data">
				 <input style="color:#2E2E2E" type="file" name="image"/><input type="submit" value="Upload" name="action"/></br>&nbsp;&nbsp;&nbsp;Choose & Upload CSV File : 
			</form>
		</td>
		<td>
			<div id="stat">
			</div>
		</td>
		<td height="20px" valign="top">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size:60px;line-height:45px;color:#868686;">2</font>
		</td><td width="90px" valign="top">
			<font style="font-size:13px;">Convert and generate the table</font>
		</td><td valign="top">
			<img src="images/table.png" onclick="drawtable ('1');" style="font-size:18px;cursor:pointer;vertical-align:top;" title="NewGenerate table">
		</td>
		<td valign="top">
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size:60px;line-height:45px;color:#868686;">3</font>
		</td>
		<td width="90px" valign="top">
			<font style="font-size:13px;">Set/choose parameter to draw heatmap</font>
		</td>
		<td valign="top">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font style="font-size:60px;line-height:45px;color:#868686;">4</font>
		</td>
		<td width="100px" valign="top">
			<font style="font-size:13px;">Once initial parameters are set Click button.</font>
		</td>
		<td width="90px" valign="top">
			<!--<img src="images/new.png" onclick="window.location.reload()" style="font-size:18px;cursor:pointer;font-weight:400;" title="New">-->
<img src="images/run.png" onclick="loaderon();drawheat ();" onmouseout="MM_swapImgRestore()" id="imagebutton" onmouseover="MM_swapImage('imagebutton','','images/rung.png',1)" style="font-size:18px;cursor:pointer;font-weight:400;" title="Heat things up!">
		</td>
		
	</tr>
	</table>
	<font style="font-size:17px;"><input type="radio" name="design" value="bubbles"/> HeatCirc&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="design" value="fill"/>HeatBox&nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="design" value="bars"/>HeatGraph&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="design" value="circlelog" CHECKED/>CircularLog&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="design" value="solo" CHECKED/>JustColor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Higher Cutoff:&nbsp;&nbsp;<input style="vertical-align:middle;width:40px;height:12px:font-size:11px;" id="cut_val"></input>&nbsp;&nbsp;Color:&nbsp;&nbsp;<input style="vertical-align:middle;width:80px;height:12px:font-size:10px;" id="cut_col" class="color" value="66FF00"></input>&nbsp;&nbsp;|&nbsp;&nbsp;<input type="checkbox" name="points" value="all"/>All</font>
	<table><td>
	<table id="slidertable">
	<tr>
		<td>
			Cutoff (%) :
		</td>
		<td>
			<div style="width:270px;" id="slider-range1">
			</div>
		</td>
		<td>
			&nbsp;<input style="vertical-align:middle;width:40px;height:12px:font-size:13px;" id="percentcut"></input>&nbsp;&nbsp;&nbsp;&nbsp;
		</td>
			<td>
				 Max point :
			</td>
			<td>
				<div style="width:270px;" id="slider-range2">
				</div>
			</td><td>&nbsp;<input style="vertical-align:middle;width:40px;height:12px:font-size:11px;" id="maxcut"></input>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			
			<td>
				Circle size: (30-120) &nbsp;&nbsp;<input style="vertical-align:middle;width:40px;height:12px:font-size:11px;" id="circsize"></input>
			</td>
			</tr></table>
				
				<table id="slidertable"><tr>		
				<td>
				 Width :
			</td>
			<td>
				<div style="width:210px;" id="slider-range3">
				</div>
			</td>
			<td>
				<input style="vertical-align:middle;width:30px;height:12px:font-size:11px;" id="heatwidth"></input>&nbsp;&nbsp;
			</td>
				<td>
				 Height :
			</td>
			<td>
				<div style="width:210px;" id="slider-range4">
				</div>
			</td>
			<td>
				<input style="vertical-align:middle;width:30px;height:12px:font-size:11px;" id="heatheight"></input>&nbsp;&nbsp;
			</td>
			<td>
				 Row/Col Text :
			</td>
			<td>
				<div style="width:210px;" id="slider-range5">
				</div>
			</td>
			<td>
				<input style="vertical-align:middle;width:30px;height:12px:font-size:11px;" id="headfont_s"></input>&nbsp;&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>
				</td></tr></table>
	
	<span onclick="sorttable();" style="cursor:pointer;">Sort Table</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span onclick="tooltipon ();" style="cursor:pointer;">Tool tips</span>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span onclick="hideintext ();" style="cursor:pointer;">Hide text</span>
	&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<span onclick="printmap ();" style="cursor:pointer;">Create PDF</span>	<input type="button" onclick="runKM();" value="Cluster data and display assignments" />
	<div id="types">
		<table><tr><td>

</td><tr></table></div>
</div>
<div style="margin-left:75px;" height="100%" id="datacontainer">
			</br></br>
				<div id="CSVTable">
				</div>
				</br></br></br>
				
				<div id="CSVTable2">
				</div>
				<!--<div id="CSVTable"></div>-->
				</br></br>

			</div>

	<script type="text/javascript">
  var sorter = new sortHeat.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	
	function sorttable() {
		sorter.init("sortnowtable",1);
	}
  </script>
	
			<div align="center" id="footer">
				<font style="color:#9d9d9d;position:absolute;bottom:10px;font-size:10px">© Copyright 2012 Fazmin Nizam. All Rights Reserved.</font>
			</div>
			<div id="output_panel">


<pre id="text"> </pre>

			<div id="thepdf" style='visibility:hidden;'>
				<FORM ACTION="createpdf.php" METHOD=POST target='_blank'>
				<TEXTAREA NAME="printdata" COLS=1 ROWS=1 class='pdfzone'></TEXTAREA>
				<P><INPUT class='sendtocreate' TYPE=SUBMIT VALUE="submit">
				</FORM></div>
				<TEXTAREA style='visibility:hidden;' id="csvdata" NAME="csvdata" COLS=1 ROWS=1 class='pdfzone'><?php if(isset($csvdata)){echo $csvdata;} ?></TEXTAREA>
</body>
</html>
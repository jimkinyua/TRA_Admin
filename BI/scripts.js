var xmlHttp4
var xmlHttp3
var Op;

function save_roles(url,destination,loader,op,id,opv,exPM)
{
    var elLength = document.MyForm.elements.length;
	var mylink ='';
	
	for (i=0; i<elLength; i++)
    {
        var type = MyForm.elements[i].type;
        if (type=="checkbox" && MyForm.elements[i].checked)
		{
        	mylink = mylink + '&'+MyForm.elements[i].name+'=true';     
        } else if (type=="checkbox") 
		{
           mylink = mylink + '&'+MyForm.elements[i].name+'=false';
        }      
    }	
	mylink = url+mylink;

	loadmypage(mylink,destination,loader,op,id,opv,exPM);	
}
function loadoptionalpage(app_id,app_type,current_status,destination,loader,op,id,opv,exPM)
{
	var mypage='';
	if (app_type==1)
	{
		mypage='land_registration.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status;
		opv='LAIFOMS_LAND';
	}else if(app_type==2)
	{
		mypage='house_registration.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status;
		opv='LAIFOMS_HOUSE';
	}else if(app_type==3)
	{
		mypage='permit_registration.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status;
		opv='LAIFOMS_PERMIT';
	}else if(app_type==4)
	{
		mypage='service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status;
		//mypage='hire_application.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status;
		//opv='LAIFOMS_PERMIT';
	}else
	{
		mypage='service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status;
	}
	mylink=mypage	
	loadmypage(mylink,destination,loader,op,id,opv,app_id);	
}
function check_all()
{
	var val;
	if (document.MyForm.chkAll.checked==true)
	{
		val=true;
	}else
	{
		val=false;
	}
    var elLength = document.MyForm.elements.length;
	
	for (i=0; i<elLength; i++)
    {
        var type = MyForm.elements[i].type;
        if (type=="checkbox" && MyForm.elements[i].name!='chkAll') 
		{
           document.MyForm.elements[i].checked=val;
        }      
    }	
		
}

function loadtextpage(url,destination,loader,field, showtoolbar)
{ 
	dest = destination;
	Loader = loader;
	Field = field;
	ShowToolbar = showtoolbar;
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	if (document.getElementById(loader))
		document.getElementById(loader).innerHTML= 'loading....'
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=textpagecontent
	xmlHttp4.open("POST",url,true)
	xmlHttp4.send(null)	
}

function textpagecontent() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		if (document.getElementById(Loader))
			document.getElementById(Loader).innerHTML= ""
		
		document.getElementById(dest).innerHTML=xmlHttp4.responseText;
		if (ShowToolbar)
		{
			CKEDITOR.replace( Field );
		} else
		{
			CKEDITOR.replace( Field, {removePlugins: 'toolbar'} );	
		}
 	} 
}

function swapcustomer(id) 
{
	alert('dsdsd');
	if (document.getElementById(id).checked == true)
	{
    	document.getElementById('Company').style.display = 'block';
    	document.getElementById('lname').style.display = 'none';
		document.getElementById('oname').style.display = 'none';
    	document.getElementById('uname').style.display = 'none';
		document.getElementById('pwd').style.display = 'none';		
	} else
	{
    	document.getElementById('Company').style.display = 'none';
    	document.getElementById('lname').style.display = 'block';
		document.getElementById('oname').style.display = 'block';
    	document.getElementById('uname').style.display = 'block';
		document.getElementById('pwd').style.display = 'block';
		
	}
}

function loadTable2(op,ID,_opv)
{
	GaugeYearly();
	GaugeDaily();
	posToday();
	funnel_agent();
	funnel_service();
	myChart2()
}

function loadTable(op,ID,_opv)
{	
	$(function()
	{
		$('#dataTables-1').dataTable( 
		{
			"bProcessing": true,
			"sAjaxSource": op+"_data.php?i=1&ID="+ID+"&OptionValue="+_opv+"&exParam="+exPm			
		});
		
		$('#posInvoice').dataTable( 
		{
			"bProcessing": true,
			"sAjaxSource": op+"_data.php?i=1&ID="+ID+"&OptionValue="+_opv+"&exParam="+exPm,			
			"footerCallback": function (row, data, start, end, display) 
			{
				var api = this.api(), data;
			   
				// Remove the formatting to get integer data for summation
				var intVal = function (i) 
				{
					return typeof i === 'string' ?
						i.replace(/[\$,]/g, '') * 1 :
						typeof i === 'number' ?
						i : 0;
				};

				// Total over this page
				pageTotal = api
					.column('.sum', { page: 'current' })
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0);
					
				// Total over all pages
				entireTotal = api
					.column('.sum', { page: 'applied' })
					.data()
					.reduce(function (a, b) {
						return intVal(a) + intVal(b);
					}, 0);

				// Update footer
				$(api.column('.sum').footer()).html('Total: '+pageTotal + ' of ('+entireTotal+')');
            }
		} );		
		
		$('#tableToolsTable').dataTable( {
			"sDom": 'T<"clear">lfrtip'
			,"bProcessing": true
			,"sAjaxSource": op+"_data.php?i=1&ID="+ID+"&OptionValue="+_opv+"&exParam="+exPm
			,"tableTools": {
				 "sSwfPath": "http://cdn.datatables.net/tabletools/2.2.4/swf/copy_csv_xls_pdf.swf" //on remote server				 
				 ,"aButtons": [
						{
						 "sExtends": "copy",
						 "sToolTip": "Copy to clipboard"
						},
						"print",
						{
						 "sExtends": "collection",
						 "sButtonText": "Save",
						 "aButtons": [ 
							{
							 "sExtends": "pdf",
							 "sToolTip": "Save as PDF",
							 "sFileName": "*_pdf.pdf",
							 "sTitle": "Project title: ",               
							 "sPdfMessage":"Client: "
							 
							},
							{
							 "sExtends": "xls",
							 "sToolTip": "Save as EXCEL",
							 "sFileName": "*_xls.xls"
							},
							{
							 "sExtends": "csv",
							 "sToolTip": "Save as CSV",
							 "sFileName": "*_csv.csv"
							}
							]
						}           
					]
			}				   
			} );
			
		$.fn.dataTable.ext.search.push
		(
			function( settings, data, dataIndex ) 
			{
/* 				var min = parseInt( $('#min').val(), 10 );
				var max = parseInt( $('#max').val(), 10 ); */
				
				var min = Date.parse($('#min').val());
				var max = Date.parse($('#max').val());				
				
				//alert (max-min);
				
				//var age = parseFloat( data[0] ) || 0; // use data for the age column
				var age = Date.parse( data[0] ) || 0; // use data for the age column
		 
				if ( ( isNaN( min ) && isNaN( max ) ) ||
					 ( isNaN( min ) && age <= max ) ||
					 ( min <= age   && isNaN( max ) ) ||
					 ( min <= age   && age <= max ) )
				{
					return true;
				}
				return false;
			}
		);
		
		$(document).ready(function() 
		{
			var table = $('#posInvoice').DataTable();	
			// Event listener to the two range filtering inputs to redraw on input
			$('#min, #max').keyup( function() {
				table.draw();
			} );
		} );

	});	
}

function loadmytab(url,destination,element,op,id)
{
	Op = op;
	ID = id;
	var lis = document.getElementById('flowertabs').getElementsByTagName('a');
	for (var i = 0; i < lis.length; ++i)
	{
	    lis[i].className = lis[i].className.replace(/\bselected\b/g, '');
		if (element==i)
		{
           lis[i].className = 'selected';
		 }
	}
	dest = destination;
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=mytabpage
	xmlHttp4.open("POST",url,true)
	xmlHttp4.send(null)	
}

function mytabpage() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		document.getElementById(dest).innerHTML=xmlHttp4.responseText;
		loadTable(Op, ID);
 	}
}

function loadtab(url,destination,element)
{
	var lis = document.getElementById('flowertabs').getElementsByTagName('a');
	for (var i = 0; i < lis.length; ++i)
	{
	    lis[i].className = lis[i].className.replace(/\bselected\b/g, '');
		if (element==i)
		{
           lis[i].className = 'selected';
		 }
	}
	dest = destination;
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=tabpage
	xmlHttp4.open("POST",url,true)
	xmlHttp4.send(null)	
}

function tabpage() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		document.getElementById(dest).innerHTML=xmlHttp4.responseText 
 	}
}

function loadnewpage(url,destination)
{ 	
	dest = destination;
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=tabpage
	xmlHttp4.open("POST",url,true)
	xmlHttp4.send(null)	
}

function loadpage(url,destination,loader)
{ 
	dest = destination;
	Loader = loader;
	url=encodeURI(url);
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	
	if (document.getElementById(loader))
		document.getElementById(loader).innerHTML= 'loading....'
		//document.getElementById(loader).innerHTML= '<img src="images/ajax-loader.gif" width="16" height="16" />'
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=contentpage
	xmlHttp4.open("GET",url,true)
	xmlHttp4.send(null)
}

function loadmypage(url,destination,loader,op,id,opv,exPM)
{
	dest = destination;
	Loader = loader;
	Op = op;
	ID = id;
	exPm=exPM;
	_opv = opv;
	url=encodeURI(url);
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	
	if (document.getElementById(loader))
		document.getElementById(loader).innerHTML= 'loading....'
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=mycontentpage
	
	//alert(xmlHttp4.value);
	
	xmlHttp4.open("POST",url,true)	
	xmlHttp4.send(null)	
}

function loadmypage2(url,destination,loader,op,id,opv,exPM)
{ 
	dest = destination;
	Loader = loader;
	Op = op;
	ID = id;
	exPm=exPM;
	_opv = opv;
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	
	
	if (document.getElementById(loader))
		document.getElementById(loader).innerHTML= 'loading....'
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=mycontentpage2	
	
	xmlHttp4.open("POST",url,true)	
	xmlHttp4.send(null)	
}

function deleteConfirm2(msg, url ,destination,loader,option,id,opv) 
{
 	var a = confirm(msg);
	
	dest = destination;
	Loader = loader;
	Op = option;
	ID = id;
	_opv = opv;
 
 	if (a) 
 	{ 
 		loadmypage(url,dest,Loader,Op,ID,_opv);
		return true;		
	}
 	else 
	{ 
		return false; 
	}
}

function mycontentpage() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		if (document.getElementById(Loader))
			document.getElementById(Loader).innerHTML= ""
		document.getElementById(dest).innerHTML=xmlHttp4.responseText;
		loadTable(Op,ID,_opv);
 	} 
}

function mycontentpage2() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		if (document.getElementById(Loader))
			document.getElementById(Loader).innerHTML= ""
		document.getElementById(dest).innerHTML=xmlHttp4.responseText;
		loadTable2(Op,ID,_opv);
 	} 
}

function contentpage() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		if (document.getElementById(Loader))
			document.getElementById(Loader).innerHTML= ""
		document.getElementById(dest).innerHTML=xmlHttp4.responseText 
 	} 
}

function sendpage(url)
{ 
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}
	document.getElementById("loader").innerHTML= "Loading.."
	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=sendpagecontent
	xmlHttp4.open("GET",url,true)
	xmlHttp4.send(null)
}

function sendpagecontent() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		document.getElementById("loader").innerHTML= ""
		document.getElementById("Message").innerHTML=xmlHttp4.responseText 
 	} 
}


function loadsmsoptionpage(url)
{ 
	xmlHttp3=GetXmlHttpObject()
	if (xmlHttp3==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}

	url=url+"&sid="+Math.random()
	xmlHttp3.onreadystatechange=smsoptionpage
	xmlHttp3.open("GET",url,true)
	xmlHttp3.send(null)
}

function smsoptionpage() 
{ 
	if (xmlHttp3.readyState==4 || xmlHttp3.readyState=="complete")
 	{ 
		document.getElementById("sms_number").innerHTML=xmlHttp3.responseText 
 	} 
}

function loadnumbers(url)
{ 
	xmlHttp4=GetXmlHttpObject()
	if (xmlHttp4==null)
 	{
 		alert ("Browser does not support HTTP Request")
 		return
 	}

	url=url+"&sid="+Math.random()
	xmlHttp4.onreadystatechange=numberspage
	xmlHttp4.open("GET",url,true)
	xmlHttp4.send(null)
}

function numberspage() 
{ 
	if (xmlHttp4.readyState==4 || xmlHttp4.readyState=="complete")
 	{ 
		document.getElementById("numbers").innerHTML=xmlHttp4.responseText 
 	} 
}

function GetXmlHttpObject()
{
	var xmlHttp=null;
	try
 	{
 		// Firefox, Soi 8.0+, Safari
 		xmlHttp=new XMLHttpRequest();
 	}
	catch (e)
 	{
 		//Internet Explorer
 		try
  		{
  			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
  		}
 		catch (e)
  		{
  			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
  		}
 	}
	return xmlHttp;
}

function textCounter( field,counter ) 
{
	var max_length = 200;
    if( field.value.length > max_length ) 
	{
		alert( "Too many characters: " + max_length);
        field.value= field.value.substr( 0, max_length );
        counter.value = 0;
    }  else 
	{
        counter.value = max_length - field.value.length;
    }
}
function ck_array(dest) 
{
	var c_value = "";
	var j = document.deleteform.checkbox_name.length;
	for (var i=0; i < j; i++)
    {
		if (document.deleteform.checkbox_name[i].checked)
		{
		  c_value = c_value + "&item" + i + "=" + document.deleteform.checkbox_name[i].value;
		}
   }	
   dest.value = c_value;
}

function ck_selectall(op,dest)
{
	for (var i=0; i < document.deleteform.checkbox_name.length; i++)
    {
		document.deleteform.checkbox_name[i].checked = op;
   }	
   ck_array(dest);
}

function deleteConfirm(msg, url) 
{	
 	var a = confirm(msg);
 
 	if (a) 
 	{ 
 		loadpage(url);
		return true; 		
	}
 	else 
	{ 
		return false; 
	}
}

function flatWindow(){
    $.Dialog({
        overlay: true,
        shadow: true,
        flat: true,
        icon: '<img src="images/excel2013icon.png">',
        title: 'Flat window',
        content: '',
                onShow: function(_dialog){
            var html = ['<iframe width="640" height="480" src="//www.youtube.com/embed/_24bgSxAD9Q" frameborder="0"></iframe>'].join("");
 
            $.Dialog.content(html);
        }
    });
}

function PopupCenterDual(url, title, w, h) {
// Fixes dual-screen position Most browsers Firefox
	var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
	var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;
	width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
	height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

	var left = ((width / 2) - (w / 2)) + dualScreenLeft;
	var top = ((height / 2) - (h / 2)) + dualScreenTop;
	var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

	// Puts focus on the newWindow
	if (window.focus) {
	newWindow.focus();
	}
}

function startUpload()
{
	document.getElementById('f1_upload_process').style.visibility = 'visible';
    document.getElementById('f1_upload_form').style.visibility = 'hidden';
    return true;
}

function stopUpload(success)
{
	var result = '';
	result = '<span class="msg"> ' + success + ' <\/span><br/><br/>';
    document.getElementById('f1_upload_process').style.visibility = 'hidden';
    document.getElementById('f1_upload_form').innerHTML = result;
	 
    document.getElementById('f1_upload_form').style.visibility = 'visible';   

    return true;   
}


function rights_array(dest) 
{
	var c_value = "";

	var j = document.deleteform.checkbox_name.length;
	for (var i=0; i < j; i++)
    {
		if (document.deleteform.checkbox_name[i].checked)
		{
			c_value = c_value + "&view" + i + "=" + document.deleteform.checkbox_name[i].value;
		}
    }	
	var j = document.deleteform.checkbox_name1.length;
	for (var i=0; i < j; i++)
    {
		if (document.deleteform.checkbox_name1[i].checked)
		{
			c_value = c_value + "&edit" + i + "=" + document.deleteform.checkbox_name1[i].value;
		}
    }	
	var j = document.deleteform.checkbox_name2.length;
	for (var i=0; i < j; i++)
    {
		if (document.deleteform.checkbox_name2[i].checked)
		{
			c_value = c_value + "&delete" + i + "=" + document.deleteform.checkbox_name2[i].value;
		}
    }   
   
   dest.value = c_value;
}

function rights_selectall(op,dest)
{
	for (var i=0; i < document.deleteform.checkbox_name.length; i++)
    {
		document.deleteform.checkbox_name[i].checked = op;
		document.deleteform.checkbox_name1[i].checked = op;
		document.deleteform.checkbox_name2[i].checked = op;
   }	
   rights_array(dest);
}

function addtext(newtext,dest) 
{
	/*document.myform.outputtext.value += newtext;*/
	dest.value += newtext;
}


//Regular pie chart example
function myChart()
{
	$(function () {
		// Create the chart
		$('#chart1').highcharts({
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Revenue Collection. January, 2016 to Date'
			},
			subtitle: {
				text: 'Click the slices to drilldown.'
			},
			plotOptions: {
				series: {
					dataLabels: {
						enabled: true,
						format: '{point.name}: {point.y:.1f}%'
					}
				}
			},
	
			tooltip: {
				headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
				pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
			},
			series: [{
				name: "SubCounty",
				colorByPoint: true,
				data: [{
					name: "Ainabkoi",
					y: 56.33,
					drilldown: "Ainabkoi"
				}, {
					name: "Kapseret",
					y: 24.03,
					drilldown: "Kapseret"
				}, {
					name: "Kesses",
					y: 10.38,
					drilldown: "Kesses"
				}, {
					name: "Moiben",
					y: 4.77,
					drilldown: "Moiben"
				}, {
					name: "Soi",
					y: 1.91,
					drilldown: "Soi"
				}, {
					name: "Turbo",
					y: 0.91,
					drilldown: "Turbo"
				}, {
					name: "Proprietary or Undetectable",
					y: 0.2,
					drilldown: null
				}]
			}],
			drilldown: {
				series: [{
					name: "Ainabkoi",
					id: "Ainabkoi",
					data: [
						["Kapsoya", 24.13],
						["Kaptagat", 17.2],
						["Ainabkoi-Olare", 8.11]
					]
				}, {
					name: "Kapseret",
					id: "Kapseret",
					data: [
						["Kapkenyo", 5],
						["Langas", 4.32],
						["Simat-Kapseret", 3.68],
						["Ngeria", 2.96],
						["Megun", 2.53]
					]
				}, {
					name: "Kesses",
					id: "Kesses",
					data: [
						["Race Course", 2.76],
						["Tarakwa", 2.32],
						["Tulwel-Chuiyat", 2.31],
						["Cheptiret-Kipchamo", 1.27]
					]
				}, {
					name: "Moiben",
					id: "Moiben",
					data: [
						["Kimumu", 2.56],
						["Karuna-Moibeki", 0.77],
						["Moiben", 0.42],
						["Sergoit", 0.3],
						["Kapkures", 0.29]
					]
				}, {
					name: "Soi",
					id: "Soi",
					data: [
						["Kuinet-Kapsuswa", 0.34],
						["Kiplombe", 0.24],
						["Kipsomba", 0.17],
						["Soi", 0.16],
						["Ziwa", 0.17],
						["Segero", 0.18],
						["Mois Bridge", 0.19]
					]
				}, {
					name: "Turbo",
					id: "Turbo",
					data: [
						["Kamagut", 0.34],
						["Huruma", 0.24],
						["Ngenyilel", 0.17],
						["Kapsaos", 0.16],
						["Tapsagoi", 0.16]
					]
				}]
			}
		});
	});
}

function myPie()
{
	$(function () 
	{
		var processed_json = new Array();  
		var longData=new Array();			
		$.getJSON('listpages_data.php?i=1&ID=&OptionValue=TestTable', function(results) 
		{			
			var data=results.aaData;
			// Populate series
			for (i = 0; i < data.length; i++){	
				processed_json.push(data[i][0], parseInt(data[i][1]));
			}
			
		// Create the chart
			$('#pie').highcharts(
			{
				chart: {
					type: 'pie'
				},
				title: {
					text: 'Revenue Collection. January, 2016 Date'
				},
				subtitle: {
					text: 'Click the slices to drilldown.'
				},
				plotOptions: {
					series: {
						dataLabels: {
							enabled: true,
							format: '{point.name}: {point.y:.1f}%'
						}
					}
				},
		
				tooltip: {
					headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
					pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
				},
				series: [{
					name: "SubCounty",
					colorByPoint: true,
					//data: [['Ainabkoi',111.0000],['Kapseret',90.0000],['Kesses',343.0000],['Moiben',348.0000],['Turbo',121.0000]]
					data: [processed_json]
				}]				
			});
		});
	});
	
	//myChart2();
}

function myChart2()
{
	$(function () 
	{
                var processed_json = new Array();  
                $.getJSON('listpages_data.php?i=1&ID=&OptionValue=TestTable', function(results) {
					
					var data=results.aaData;
                    // Populate series
                    for (i = 0; i < data.length; i++){
						//alert(data[i][0]);
                        processed_json.push(data[i][0], parseInt(data[i][1]));
                    }					
					//alert(processed_json);
                    // draw chart
					
                    $('#chart2').highcharts({
                    chart: {
                        type: "column"
                    },
                    title: {
                        text: "County Revenue"
                    },
                    xAxis: {
                        type: 'category',
                        allowDecimals: false,
                        title: {
                            text: ""
                        }
                    },
                    yAxis: {
                        title: {
                            text: "Amount"
                        }
                    },
                    series: [{
	                    name: 'Date',
                        data: [processed_json]
                    }]
                }); 
            });
        });
}

function GaugeYearly()
{
	$(function () 
	{
		

		var gaugeOptions = {

			chart: {
				type: 'solidgauge'
			},

			title: null,

			pane: {
				center: ['50%', '85%'],
				size: '140%',
				startAngle: -90,
				endAngle: 90,
				background: {
					backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
					innerRadius: '60%',
					outerRadius: '100%',
					shape: 'arc'
				}
			},
			lang: {
				thousandsSep: ','
			},

			tooltip: {
				enabled: false
			},

			// the value axis
			yAxis: {
				stops: [
					[0.9, '#55BF3B'], // green
					[0.5, '#DDDF0D'], // yellow
					[0.1, '#DF5353'] // red
				],
				lineWidth: 0,
				minorTickInterval: null,
				tickPixelInterval: 400,
				tickWidth: 0,
				title: {
					y: -70
				},
				labels: {
					y: 16
				}
			},

			plotOptions: {
				solidgauge: {
					dataLabels: {
						y: 5,
						borderWidth: 0,
						useHTML: true
					}
				}
			}
		};

		// The speed gauge
		var processed_json = new Array();
		$('#container-speed').highcharts(Highcharts.merge(gaugeOptions, 
		{			
			yAxis: {
				min: 0,
				max: 1000000000,
				title: {
					text: 'Yearly Collection vs Target'
				}
			},

			credits: {
				enabled: false
			},

			series: [{
				name: 'Collection',
				data: [0],
				dataLabels: {
					format: '<div style="text-align:center"><span style="font-size:25px;color:' +
						((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
						   '<span style="font-size:12px;color:silver">Yearly Collection</span></div>'
				},
				tooltip: {
					valueSuffix: ' m'
				}
			}]
		}));
		
		setInterval(function () 
		{
			var chart = $('#container-speed').highcharts(),point,newVal,inc=0;				
			var processed_json=new Array();
			 $.getJSON('listpages_data.php?i=1&ID=&OptionValue=Target', function (data, textStatus) {
				var result=data.aaData;
			// Populate series
				for (i = 0; i < result.length; i++){	
					processed_json.push(result[i][0], parseInt(result[i][1]));
				}
				
				$.each(data, function (key, value) 
				{
					point = chart.series[0].points[0],newVal = key;
					point.update(newVal[0]);
					
					chart.yAxis[0].update(
					{
						max: processed_json[1]/100
					}); 
					
					chart.series[0].update(
					{
						data: [processed_json[0]]
					});
				});

			}); 		
		}, 500);

	});	 
}

function GaugeDaily()
{
	$(function () 
	{
		

		var gaugeOptions = {

			chart: {
				type: 'solidgauge'
			},

			title: null,

			pane: {
				center: ['50%', '85%'],
				size: '140%',
				startAngle: -90,
				endAngle: 90,
				background: {
					backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
					innerRadius: '60%',
					outerRadius: '100%',
					shape: 'arc'
				}
			},

			tooltip: {
				enabled: false
			},
			lang: {
        thousandsSep: ','
			}
			,

			// the value axis
			yAxis: {
				stops: [
					[0.9, '#55BF3B'], // green
					[0.5, '#DDDF0D'], // yellow
					[0.1, '#DF5353'] // red
				],
				lineWidth: 0,
				minorTickInterval: null,
				tickPixelInterval: 400,
				tickWidth: 0,
				title: {
					y: -70
				},
				labels: {
					y: 16
				}
			},

			plotOptions: {
				solidgauge: {
					dataLabels: {
						y: 5,
						borderWidth: 0,
						useHTML: true
					}
				}
			}
		};

		// The speed gauge
		var processed_json = new Array();
		$('#today').highcharts(Highcharts.merge(gaugeOptions, 
		{			
			yAxis: {
				min: 0,
				max: 50000000,
				title: {
					text: 'Today Collection vs Target'
				}
			},

			credits: {
				enabled: false
			},

			series: [{
				name: 'Collection',
				data: [0],
				dataLabels: {
					format: '<div style="text-align:center"><span style="font-size:25px;color:' +
						((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
						   '<span style="font-size:12px;color:silver">Today Collection</span></div>'
				},
				tooltip: {
					valueSuffix: ' m'
				}
			}]
		}));
		
		setInterval(function () 
		{
			var chart = $('#today').highcharts(),point,newVal,inc=0;				
			var processed_json=new Array();
			 $.getJSON('listpages_data.php?i=1&ID=&OptionValue=TodaysCollection', function (data, textStatus) {
				var result=data.aaData;
			// Populate series
				for (i = 0; i < result.length; i++){	
					processed_json.push(result[i][0], parseInt(result[i][1]));
				}
				
				$.each(data, function (key, value) 
				{
					point = chart.series[0].points[0],newVal = key;
					point.update(newVal[0]);
					
					chart.yAxis[0].update(
					{
						max: processed_json[1]/100
					}); 
					
					chart.series[0].update(
					{
						data: [processed_json[0]]
					});
				});

			}); 		
		}, 500);

	}); 
}
function posToday()
{
	$(function () 
	{
		

		var gaugeOptions = {

			chart: {
				type: 'solidgauge'
			},

			title: null,

			pane: {
				center: ['50%', '85%'],
				size: '140%',
				startAngle: -90,
				endAngle: 90,
				background: {
					backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
					innerRadius: '60%',
					outerRadius: '100%',
					shape: 'arc'
				}
			},

			tooltip: {
				enabled: false
			},

			// the value axis
			yAxis: {
				stops: [
					[0.1, '#DF5353'], // red
					[0.5, '#DDDF0D'], // yellow
					[0.9, '#55BF3B'] // green
					
					
				],
				lineWidth: 0,
				minorTickInterval: null,
				tickPixelInterval: 400,
				tickWidth: 0,
				title: {
					y: -70
				},
				labels: {
					y: 16
				}
			},

			plotOptions: {
				solidgauge: {
					dataLabels: {
						y: 5,
						borderWidth: 0,
						useHTML: true
					}
				}
			}
		};

		// The speed gauge
		var processed_json = new Array();
		$('#postoday').highcharts(Highcharts.merge(gaugeOptions, 
		{			
			yAxis: {
				min: 0,
				max: 1000000,
				title: {
					text: 'Daily Collection vs Target'
				}
			},

			credits: {
				enabled: false
			},

			series: [{
				name: 'Collection',
				data: [0],
				dataLabels: {
					format: '<div style="text-align:center"><span style="font-size:25px;color:' +
						((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
						   '<span style="font-size:12px;color:silver">POS Collection Today</span></div>'
				},
				tooltip: {
					valueSuffix: ' m'
				}
			}]
		}));
		
		setInterval(function () 
		{
			var chart = $('#postoday').highcharts(),point,newVal,inc=0;				
			var processed_json=new Array();
			 $.getJSON('listpages_data.php?i=1&ID=&OptionValue=TodaysPosCollection', function (data, textStatus) {
				var result=data.aaData;
			// Populate series
				for (i = 0; i < result.length; i++){	
					processed_json.push(result[i][0], parseInt(result[i][1]));
				}
				
				$.each(data, function (key, value) 
				{
					point = chart.series[0].points[0],newVal = key;
					point.update(newVal[0]);
					
					chart.yAxis[0].update(
					{
						max: processed_json[1]/100
					}); 
					
					chart.series[0].update(
					{
						data: [processed_json[0]]
					});
				});

			}); 		
		}, 500);

	}); 
}

function funnel_agent()
{
	$(function () 
	{
		var processed_json = new Array();  		
		$.getJSON('listpages_data.php?i=1&ID=&OptionValue=TodaysCollection_f', function(results) 
		{ 
			processed_json=results.aaData;
			$('#funnel_agent').highcharts(
			{
				chart: {
					type: 'funnel',
					marginRight: 100
				},
				title: {
					text: 'Revenue Collection Ranking Per Stream',
					x: -50
				},
				plotOptions: {
					series: {
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b> ({point.y:,.0f})',
							color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black',
							softConnector: true
						},
						neckWidth: '30%',
						neckHeight: '25%'
						//-- Other available options
						// height: pixels or percent
						// width: pixels or percent
					}
				},
				legend: {
					enabled: false
				},
				series: [{
					name: 'Revenue Collected',
					data: processed_json
				}]
			});			
		});		

	});
}
function funnel_service()
{
	$(function () 
	{
		var processed_json = new Array();  		
		$.getJSON('listpages_data.php?i=1&ID=&OptionValue=ServiceRanking', function(results) 
		{ 
			processed_json=results.aaData;
			$('#funnel_service').highcharts(
			{
				chart: {
					type: 'funnel',
					marginRight: 100
				},
				title: {
					text: 'Revenue Collection Ranking Per Service',
					x: -50
				},
				plotOptions: {
					series: {
						dataLabels: {
							enabled: true
						},
						neckWidth: '30%',
						neckHeight: '25%'
						//-- Other available options
						// height: pixels or percent
						// width: pixels or percent
					}
				},
				legend: {
					enabled: true
				},
				series: [{
					name: 'Revenue Collected',
					data: processed_json
				}]
			});			
		});		

	});
}
function fetch_data(url)
{
	xmlhttp=GetXmlHttpObject()
	if (xmlhttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	try 
	{
		xmlhttp.open("GET",url,false);
		xmlhttp.send();
		rest = xmlhttp.responseText;
	} 
	catch (e) 
	{
		rest = '[{"Error":1}]';
	}  
	var obj = JSON.parse(rest);
	return obj; 
}




 





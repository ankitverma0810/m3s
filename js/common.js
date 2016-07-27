//Function to check Multiple checkbox on single click.
// Method of calling onClick = "Javascript:checkAll(document.from.fieldName, this.checked );" 
function checkAll( field , val )
{   
    if( val == true )
    {
        for(i=0; i<field.length; i++)
        {
            field[i].checked = true; 
        }
    }
    else
    {
        for(i=0; i<field.length; i++)
        {
            field[i].checked = false;
        }
    }
}

//Function to trim the space in the left side of the string
function ltrim ( s )
{
    return s.replace( /^\s*/, "" );
}

//Function to trim the space in the right side of the string
function rtrim ( s )
{
    return s.replace( /\s*$/, "" );
}   

//Function to trim the space in the  string
function trim_old(s)
{
    var temp = s;
       return temp.replace(/^\s+/,'').replace(/\s+$/,'');
}

function trim(inputString) {
   // Removes leading and trailing spaces from the passed string. Also removes
   // consecutive spaces and replaces it with one space. If something besides
   // a string is passed in (null, custom object, etc.) then return the input.
   if (typeof inputString != "string") { return inputString; }
   var retValue = inputString;
   var ch = retValue.substring(0, 1);
   while (ch == " ") { // Check for spaces at the beginning of the string
      retValue = retValue.substring(1, retValue.length);
      ch = retValue.substring(0, 1);
   }
   ch = retValue.substring(retValue.length-1, retValue.length);
   while (ch == " ") { // Check for spaces at the end of the string
      retValue = retValue.substring(0, retValue.length-1);
      ch = retValue.substring(retValue.length-1, retValue.length);
   }
   while (retValue.indexOf("  ") != -1) { // Note that there are two spaces in the string - look for multiple spaces within the string
      retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length); // Again, there are two spaces in each of the strings
   }
   return retValue; // Return the trimmed string back to the user
} // Ends the "trim" function


//Function to test string passed as argument is integer or not
function isInteger(s)
{
    var i;
    for (i = 0; i < s.length; i++)
    {
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}


var nav4 = window.Event ? true : false;
function codes(e) 
{
    if (nav4) // Navigator 4.0x
        var whichCode = e.which
    else // Internet Explorer 4.0x
        if (e.type == "keypress") // the user entered a character
            var whichCode = e.keyCode;
    return whichCode;
}

function isNumberKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
    {
        return false;
    }
    return true;
}

function isNumberPriceKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode!=46)
    {		
        return false;
    }
    return true;
}
  

function isSpaceKey(evt)
{
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode == 32)
    {
        return false;
    }
    return true;
}


function checkEmail(address)
{
    if ((address == "") || (address.indexOf ('@') == -1) || (address.indexOf ('.') == -1))
        return false;
    return true;
}

function validateEmail(email)
{       
    // a very simple email validation checking. 
    // you can add more complex email checking if it helps 
    if(email.length <= 0)
    {
      return true;
    }
    var splitted = email.match("^(.+)@(.+)$");
    if(splitted == null) return false;
    if(splitted[1] != null )
    {
      var regexp_user=/^\"?[\w-_\.]*\"?$/;
      if(splitted[1].match(regexp_user) == null) return false;
    }
    if(splitted[2] != null)
    {
      var regexp_domain=/^[\w-\.]*\.[A-Za-z]{2,4}$/;
      if(splitted[2].match(regexp_domain) == null) 
      {
        var regexp_ip =/^\[\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\]$/;
        if(splitted[2].match(regexp_ip) == null) return false;
      }// if
      return true;
    }
    return false;
}

/******************************************
Function name : toggleOption
Return type : None
Date created : 21st September 2006
Date last modified : 21st September 2006
Author : Lalit Kumar
Last modified by : 
Comments : Function will toggle the select all checkbox option.
User instruction : toggleOption(spanChk)
******************************************/
function toggleOption(spanChk)
{		
	var xState=spanChk.checked;
	var theBox=spanChk;

	elm=theBox.form.elements;
	for(i=0;i<elm.length;i++)
	{
		if(elm[i].type=="checkbox" && elm[i].id!=theBox.id)
		{
			if(xState == false)
				elm[i].checked = false;
			else
				elm[i].checked = true;
		}
	}
}


/******************************************
Function name : askConfirm
Return type : boolean
Date created : 21st September 2006
Date last modified : 21st September 2006
Author : Lalit Kumar
Last modified by : 
Comments : Function will return the true or false after asking for confirmation
User instruction : askConfirm(type)
******************************************/
function askConfirm(type)
{
	if(type!='')
	{
	var sen = "Are you sure you want to  "+type+" this record?";
	if(confirm(sen))
		return true;
	else
		return false;
	}
}

/******************************************
Function name : validator
Return type : boolean
Date created : 21st September 2006
Date last modified : 21st September 2006
Author : Lalit Kumar
Last modified by : 
Comments : Function will return the true or error message after validating checkboxes
User instruction : validator(btnType)
******************************************/
var btnType;
function validator(btnType,formname)
{
	
	var obj = formname;
	var error="", flagCheck=0;
	
	var len = obj.elements.length;
	var i=0;
	for(i=0;i<len;i++) 
	{
		if(obj.elements[i].type=='checkbox')
		{
			if(obj.elements[i].checked)
			{
				//if(btnType == 'Delete')
					return askConfirm(btnType);
				//else
					//return true;
			}
			else
			{
				flagCheck = 1;
			}
		}
	}
	//alert(flagCheck);
	/*if(flagCheck == 1)
		error += "\nPlease select at least one record.";
			
	return checkError(error);*/
	if(flagCheck == 1)
	{
		error += "Please select at least one record.";
		alert(error);
		return false;
	}
	else
	{
		return checkError(error);
	}
}

function setSelected(valA,valB,valC)
{
	//alert(valA);return false;
	var selObj = document.getElementById('searchCat');
	for (var i=0; i<selObj.options.length; i++)
	{ 
		if (selObj.options[i].value==valA)
		{
			selObj.options[i].selected=true;
 		}
	}
	document.getElementById('levelBCat').value=valB;
	document.getElementById('levelCCat').value=valC;
	document.getElementById('searchFrm').submit();
}

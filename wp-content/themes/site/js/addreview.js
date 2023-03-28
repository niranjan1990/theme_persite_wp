function ConvertFormName(item)
{
	if (item.name == "User_Hometown"){ return "Hometown"; }
	if (item.name == "user_uame"){ return "User Name"; }

	return item.name;
}

function CheckForChars(item)
{
	var regex = new RegExp("[^A-Z^a-z^0-9| _-]", "i");
	return regex.test(item.value);
}

function CheckEmail(item)
{

	var invalidChars = " /:,;";
	var sMessage = "";

	if (item == "")
	{
		//cannot be empty
		sMessage = sMessage + "Email address is a required field.\n";
		return sMessage;
	}
	else
	{
		for (i = 0; i < invalidChars.length; i++)
		{
			badChar = invalidChars.charAt(i)
			if (item.value.indexOf(badChar,0) > -1)
			{
				sMessage = sMessage + "Email address appears invalid.\n";
				return sMessage;
			}
		}

		atPos = item.value.indexOf("@",1);
		if (atPos == -1)
		{
			sMessage = sMessage + "Email address appears invalid.\n";
			return sMessage;		
		}
		if (item.value.indexOf("@",atPos+1) != -1)
		{
			sMessage = sMessage + "Email address appears invalid.\n";
			return sMessage;		
		}
		periodPos = item.value.indexOf(".",atPos)
		if (periodPos == -1)
		{
			sMessage = sMessage + "Email address appears invalid.\n";
			return sMessage;
		}
		if (periodPos+3 > item.value.length)
		{
			sMessage = sMessage + "Email address appears invalid.\n";
			return sMessage;		
		}
		
		return sMessage;
	}
}
        
function CheckInteger(item)
{
    var bError = false;
    var sMessage = "";
    
    //item.value = item.value.toUpperCase();
        
    if (item.value.length > 8)
    {
        bError = true;
        sMessage = sMessage + item.name + " exceeds 8 digits.\n";
    }   
    else
    {
		for (i = 0; i < item.value.length; i++)
		{
			if (!(item.value.charAt(i) >= "0" && item.value.charAt(i) <= "9"))
			{
                bError = true;
                sMessage =  sMessage + item.name + " only accepts numbers 0-9 (no commas or periods).\n";
				break;
			}
		}
	}     

    if (bError)
    {           
		item.focus();
    }
    
	return sMessage;
}
function MaxLength(name, max)
{
	var theForm = document.PostReviewForm;
	if (theForm.elements[name].value.length > max-2)
	{
		alert("Please note, you have exceeded the maximum length.");
	}
}

function txtCount(field, countfield, maxlimit) 
{ 
	if (field.value.length > maxlimit)
		field.value = field.value.substring(0, maxlimit); 
	else
		countfield.value = maxlimit - field.value.length; 
}

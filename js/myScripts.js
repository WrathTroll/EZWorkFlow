function getTodayDateYYYYMMDD()
{
    var today = new Date();var dd = today.getDate();
    var mm = today.getMonth()+1;var yyyy = today.getFullYear();
    if(dd<10){dd='0'+dd;} if(mm<10){mm='0'+mm;}
    var today = yyyy+'-'+mm+'-'+dd;	
    return today;
}

/*function validate(form)
{
fail = validateForename(form.forename.value)
fail += validateSurname(form.surname.value)
fail += validateUsername(form.username.value)
fail += validatePassword(form.password.value)
fail += validateAge(form.age.value)
fail += validateEmail(form.email.value)
if (fail == "") return true
else { alert(fail); return false }
}
function validateForename(field)
{
return (field == "") ? "No Forename was entered.\n" : ""
}
function validateSurname(field)
{
return (field == "") ? "No Surname was entered.\n" : ""
}
function validateUsername(field)
{
if (field == "") return "No Username was entered.\n"
else if (field.length < 5)
return "Usernames must be at least 5 characters.\n"
else if (/[^a-zA-Z0-9_-]/.test(field))
return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\n"
return ""
}
function validatePassword(field)
{
if (field == "") return "No Password was entered.\n"
else if (field.length < 6)
return "Passwords must be at least 6 characters.\n"
else if (!/[a-z]/.test(field) || ! /[A-Z]/.test(field) ||
!/[0-9]/.test(field))
return "Passwords require one each of a-z, A-Z and 0-9.\n"
return ""
}
function validateAge(field)
{
if (isNaN(field)) return "No Age was entered.\n"
else if (field < 18 || field > 110)
return "Age must be between 18 and 110.\n"
return ""
}
function validateEmail(field)
{
if (field == "") return "No Email was entered.\n"
else if (!((field.indexOf(".") > 0) &&
(field.indexOf("@") > 0)) ||
/[^a-zA-Z0-9.@_-]/.test(field))
return "The Email address is invalid.\n"
return ""
}*/
/*----------------------------------------------------------------------------*/
//                     Cross-Browser AJAX Function
/*----------------------------------------------------------------------------*/

function ajaxRequest()
{
    try // Non IE Browser ? 
    {   // yes
        var request = new XMLHttpRequest();
    }
    catch(e1)
    {
        try // IE 6+?
        {   // yes
            request = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e2)
        {
            try // IE 5?
            {   // Yes
                request = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e3) // There is no AJAX support at all
            {
                request = false;
            }
        }            
    }
    return request;
}
/*----------------------------------------------------------------------------*/
//                          More AJAX Functions
/*----------------------------------------------------------------------------*/

//O function -> first letter of object which is what will be returned when 
//the function is called 
/*
function O(i)
{
    return typeof i == 'object' ? i : document.getElementById(i); 
}

//S function -> first letter of Style which returns either the style or
//subobject of the element refered to.

function S(i)
{
    return O(i).style;
}

//C function to access a CSS class 

function C(i)
{
    return document.getElementsByClassName(i);
}

//Create an Array of all CSS classes
//assign something to all the arrays
function changeCSSArray(classI,thingI)
{
    myarray = C(classI);
    if(thingI==='underline')
    {
    for(i = 0; i < myarray.length ; ++i)
        S(myarray[i]).textDecoration ='underline';
    }
    else if(thingI==='fontSize')
    {
    for(i = 0; i < myarray.length ; ++i)
        S(myarray[i]).fontSize ='20pt';
    }    
    else if(thingI==='none')
    {
    for(i = 0; i < myarray.length ; ++i)
    {
        S(myarray[i]).textDecoration ='none';
        S(myarray[i]).fontSize ='12pt';    
    }
    }
}
*/
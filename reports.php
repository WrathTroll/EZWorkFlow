<?php

function display_reportChoices()
{ ?>
<body id="bground">
	<form id="reportForm"
            method = "POST" class="form-inline"
            action="reportIt.php" target="_blank">
<div class="input-group">
<span class="input-group-addon">Reports !</span>
    <select id="reportsDD" onchange="reportOnchange()" name="reportChoice" size="1"
      class="input-group-addon w3-pale-blue">
        <option value="1">No Report</option>
        <option value="2">Customer Report</option>
        <option value="3">User Report</option>
        <option value="4">Work Report</option>
        <option value="5">Job Report</option>
        <option value="6">User Logs</option>
        <!--<option value="6">Attendance Report</option>-->
    </select>
</div>
    <script>
    function reportOnchange()
    {
        var x = document.getElementById("reportsDD").value;
        switch(x)
        {
            case '1':
               document.getElementById("reportHide").
                       style.display= 'none';
            break;          
            case '2':case '3':case '4':case '5':case '6':case '7':
                
               document.getElementById("reportHide").
                       style.display= 'inline';
            break;
        }
    }
    </script>
<?php }


function display_report_choices()
{
     ?>
<br>
<div class="container" id="reportHide" style="display:none">
            <div class="input-group">
                <span class="input-group-addon">CSV&nbsp;&nbsp;
                    <input id="rCheck" type="checkbox"></input></span>
<?php insertTab();insertTab();insertTab();insertTab();insertTab();
insertTab();insertTab();insertTab();insertTab();insertTab();insertTab();?>
	    </div>
                <input id="rButton" class="input-group" type="submit" name="reportButton"
                       value="Accept" disabled="true" class="btn btn-primary btn-lg">                     
                </input>
</div>
	</form>
<script>
    $("#rCheck").click(function(){        
    $("#rButton").attr('disabled',!this.checked);    
    if(this.checked){$("#rButton").css("background-color","#00aeb7");}    
    else{$("#rButton").css("background-color","");}
    });
</script>    
</body>
	<?php 
}


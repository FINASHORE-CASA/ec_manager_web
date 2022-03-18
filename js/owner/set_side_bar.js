$(document).ready(function()
{

    var btnOpenSideBar =  $("#sidebarToggle");
    var SideBar =  $("#accordionSidebar");

    btnOpenSideBar.on('click',function()
    {
        var data1 = 
            {
                sideBar: SideBar.hasClass('toggled') ? 'toggled' : ''             
            }


        $.post('./proccess/ajax/side_bar_toogle.php',   // url
        { myData: JSON.stringify(data1) }, // data to be submit
        function(data, status, jqXHR) 
        {            
            if(data == "success")
            {
                // console.log('message : sideBar change');
            }
            else
            {
                // console.log('message error : ' + data);
            }
        }
    );
    });    
});
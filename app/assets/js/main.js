$(document).ready(function(){

    // mobil function
    $( "header nav .dropdown-bars" ).click(function() 
    {
        $( this ).toggleClass( "active" );

        if ($( ".header-pos" ).length == 0)
        {
            $( "header" ).toggleClass( "header-pos" );
        }
        
        $( ".navbar-collapse" ).toggleClass( "show" );
    });

    $(window).on('scroll', function()
    {
        if ($(window).scrollTop()) 
        {
            $('header').addClass('header-pos');
        }
        else 
        {
            $('header').removeClass('header-pos');
        }
    })
    
});
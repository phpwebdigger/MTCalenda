$(function() {

 
   var page = 1; //track user scroll as page number, right now page number is 1
   load_hour_more(page); //initial content load
   var nearToBottom = 4;
var lastScrollLeft = 0;
   $(window).scroll(function() { //detect page scroll
      var documentScrollLeft = $(document).scrollLeft();
      if($(window).scrollTop() + $(window).height() >= $(document).height() - nearToBottom) { //if user scrolled from top to bottom of the page
      page++; //page number increment
      load_hour_more(page); //load content   
      }
       if (lastScrollLeft != documentScrollLeft) {
        console.log('scroll x');
        lastScrollLeft = documentScrollLeft;
         page++; //page number increment
      load_days_more(page); //load content   
    }
        // alert($(this).scrollLeft());
    }); 

    function load_hour_more(page){
    	$("#results_days,.Days").hide();
    
        $.ajax({
           url: "loadcalapidata?page=" + page,
           type: "get",
           datatype: "html",
           beforeSend: function()
           {
              $('.ajax-loading').show();
            }
        })
        .done(function(data)
        {
            if(data.length == 0){
            console.log(data.length);
            //notify user if nothing to load
            $('.ajax-loading').html("No more records!");
            return;
          }
          $('.ajax-loading').hide(); //hide loading animation once data is received
          $("#results").append(data); //append data into #results element          
           console.log('data.length');
       })
       .fail(function(jqXHR, ajaxOptions, thrownError)
       {
          alert('No response from server');
       });
    }

    function load_days_more(page){
    	$("#results,.hours").hide();
        $.ajax({
           url: "loadcalapidaysdata?page=" + page,
           type: "get",
           datatype: "html",
           beforeSend: function()
           {
              $('.ajax-loading').show();
            }
        })
        .done(function(data)
        {
            if(data.length == 0){
            console.log(data.length);
            //notify user if nothing to load
            $('.ajax-loading').html("No more records!");
            return;
          }
          $('.ajax-loading').hide(); //hide loading animation once data is received
          $('#results_days,.Days').show();
          $("#results_days").append(data); //append data into #results element          
           console.log('data.length');
       })
       .fail(function(jqXHR, ajaxOptions, thrownError)
       {
          alert('No response from server');
       });
    }




}); 

 
         setTimeout(function() {
             $('#box_1').addClass('active');
         }, 1000);
         setTimeout(function() {
             $('#habbo').addClass('dance');
         }, 1000);
         setTimeout(function() {
             $('#box_2').addClass('active');
         }, 6500);
         setTimeout(function() {
             $('#room_2').addClass('active');
         }, 4000);
               setTimeout(function() {
             $('#box_1').removeClass('active');
         }, 4000);
         setTimeout(function() {
             $('#room_1').removeClass('active');
         }, 4500);
         setTimeout(function() {
             $('#room_3').addClass('active');
         }, 5500);
         setTimeout(function() {
             $('#box_2').removeClass('active');
         }, 5000);
               setTimeout(function() {
             $('#box_3').addClass('active');
         }, 6000);
                     setTimeout(function() {
             $('#box_3').removeClass('active');
         }, 9500);
         setTimeout(function() {
             $('#room_4').addClass('active');
         }, 9000);
         setTimeout(function() {
             $('#box_3').removeClass('active');
         }, 10000);
         setTimeout(function() {
             $('#box_4').addClass('active');
         }, 11000);
         setTimeout(function() {
             $('#friends').addClass('active');
         }, 11000);
          setTimeout(function() {
             $('.alert').remove();
         }, 7500);        
         
         
         function changeAvatar(url, callback){
           if (url.length > 2) {
            console.log('test');
            var dataString = 'post=' + url;
            $.ajax({
                type: "POST",
                url: "/login/userlook",
                data: dataString,
                cache: false,
                success: function(data) {
                    if (data.status == "error") {
                        $("#imager").css("background-image", "url(/templates/brain/style/images/ghost.png");
                    } else if (data.status == "success") {
                        $("#imager").css("background-image", "url(https://www.habbo.nl/habbo-imaging/avatarimage?figure=" + data.look + "&action=std&direction=4&head_direction=3&gesture=std&size=&img_format=gif");  
                    }
                }
            });
             //
             
           }else{
              $("#imager").css("background-image", "url(/templates/brain/style/images/ghost.png");
           }
                 
         }
         
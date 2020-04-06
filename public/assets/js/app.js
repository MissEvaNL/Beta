$("body").undelegate(".post-feed", "click").delegate(".post-feed", "click", function() {
  $('#feed-post').modal('show');
});

$("body").undelegate(".fc-like", "click").delegate(".fc-like", "click", function() {
    var post = $(this).attr("data-id");
    var dataString = {post: post};
    $.ajax
    ({ 
        url: '/feed/like',
        data: dataString,
        type: 'POST',
        success: function(result)
        {
            if(result.status == "liked"){
             new PNotify({
                 title: 'Whoops!',
                 text: 'Je vind deze feed al leuk!',
                 type: 'error',
             });
            } else if(result.status == "success"){
             $('.fa-heart[data-id='+post+']').addClass("pulsateOnce");
             $('.likes-count[data-id='+post+']').text(parseInt($('.likes-count[data-id='+post+']').text())+1);
            }
        }
    });
});

$("body").undelegate(".fc-react", "click").delegate(".fc-react", "click", function() {
  var post = $(this).attr("data-id");
  var count = $('.comments-count[data-id='+post+']').text();
  
  if(!$('.feed-item[data-id='+post+']').data('clicked')) {
      $('.fc-replies[data-id='+post+']').append('<div class="input-group"> <input type="text" class="form-control reply mentions" name="reply" data-id="'+post+'" placeholder="Post a reply..." style="background: #F9F9F9; border: 0; -webkit-box-shadow: none; box-shadow: none;"> <span class="input-group-btn"> <input type="submit" class="btn btn-primary feed-reply" value="Reply" data-id="'+post+'"> </span> </div>');
      $('.feed-item[data-id='+post+']').data('clicked', true);
  }
});


$("body").undelegate(".hotel-settings", "click").delegate(".hotel-settings", "click", function() {
var post = $(this).attr("data-id");
var type = this.checked;

var dataString = {post: post, type: type};
if (post != null) {
  $.ajax({
      type: "POST",
      url: "/account/hotel-settings",
      data: dataString,
      cache: false,
      success: function(data) {
          if (data.status == "error") {
              console.log(data);
          } else if (data.status == "success") {
                new PNotify({
                    title: 'Gelukt!',
                    text: 'Je instellingen zijn opgeslagen',
                    type: 'success',
                });
          }
      }
  });
}
});

$("body").undelegate(".tag-by-me", "click").delegate(".tag-by-me", "click", function() {
    var post = $(this).attr("data-id");
    var dataString = 'post=' + post;
    if (post != null) {
        $.ajax({
            type: "POST",
            url: "/tag/all",
            data: dataString,
            cache: false,
            success: function(data) {
                if (data.status == "error") {
                    console.log('test');
                } else if (data.status == "success") {
                    $('#tag').empty();
                    $('#tagres').empty();
                    $('#showtags').modal('show');

                    var tableContent = "";

                    for (var i = 0; i < data.tag.length; i++) {
                        var date = new Date(data.tag[i].last_login * 1000);

                        tableContent += '<tr>';
                        tableContent += '<td style="vertical-align: middle;">';
                        tableContent += '<div style="background: url(https://www.habbo.nl/habbo-imaging/avatarimage?figure=' + data.tag[i].look + '&head_direction=3&headonly=1&size=s); height: 30px; width: 27px; display: inline-block;" data-toggle="tooltip" data-placement="right" title="Amnion"></div>';
                        tableContent += '</td>';
                        tableContent += '<td style="vertical-align: middle;"><a href="#" target="_blank">' + data.tag[i].username + '</a> </td>';
                        tableContent += '<td style="vertical-align: middle;"><span data-livestamp="1549198800" data-toggle="tooltip" title="3 Feb (Sun) 09:00 PM (Asia/Singapore)">' + date.toLocaleString() + '</span></td>';
                        tableContent += '</tr>';

                    }

                    $('#tagres').append(tableContent);
                    $('#tag').append(post);
                    // Instantiate pagination after data is available    
                    pager = new Pager('tagres', 10);
                    pager.init();
                    pager.showPageNav('pager', 'pageNavPosition');
                    pager.showPage(1);

                }
            }
        });
    }
    return false;
});
$("body").undelegate(".add-tag", "click").delegate(".add-tag", "click", function() {
    $('.tag-list').replaceWith('<div class="form-group row" style="padding-top: 15px"><div class="col-xs-8"><input data-id="tagadd" class="form-control" onfocus="this.value=\'\'" value="Aan welke #tag denk jij?" id="ex1" type="text"></div></div>');
});
$(document).on("keypress", ":input[data-id='tagadd']:not(textarea):not([type=submit])", function(event) {
    if (event.keyCode == 13) {
        var post = 'post=' + $(this).val();

        $.ajax({
            type: "POST",
            url: "/tag/addtag",
            data: post,
            cache: false,
            success: function(data) {
                if (data.status == "error") {
                    new PNotify({
                        title: 'Whoops!',
                        text: data.message,
                        type: 'error',
                    });
                } else if (data.status == "success") {
                    new PNotify({
                        title: 'Toegevoegd!',
                        text: data.message,
                        type: 'success',
                    });
                    $('.form-group').remove();
                    var byme = "";
                    for (var i = 0; i < data.tagbyme.length; i++) {
                        byme += '<li><a class="tag-by-me" href="#" data-id="' + data.tagbyme[i].tag + '">#' + data.tagbyme[i].tag + '</a></li>';
                    }
                    var alltags = "";
                    for (var x = 0; x < data.tags.length; x++) {
                        alltags += '<li><a class="tag-by-me" href="#" data-id="' + data.tags[x].tag + '">#' + data.tags[x].tag + '</a></li>';
                    }
                    $('.tab-pane[id="1"]').append('<div class="tag-list"><ul class="tag-list">' + byme + '<i class="fas fa-plus-square add-tag"></i></ul></div>');
                    $('.tab-pane[id="2"]').append('<div class="tag-list"><ul class="tag-list">' + alltags + '</ul></div>');
                } else {}
            }
        });

    }
});
$("body").undelegate("#rticle-reply", "click").delegate("#article-reply", "click", function() {
    var dataString = {
        id: $(this).attr("data-id"),
        message: $('input:input').val()
    };
    $.ajax({
        type: "POST",
        url: "/article/reply",
        data: dataString,
        cache: false,
        success: function(data) {
            if (data.status == "error") {
              if(data.messages)
                {
                  for (var x = 0; x < data.messages.length; x++) {
                      new PNotify({
                          title: 'Whoops!',
                          text: data.messages[0].body,
                          type: 'error',
                      });
                  }  
                }else{
          
                    new PNotify({
                        title: 'Whoops!',
                        text: data.message,
                        type: 'error',
                    });
                }
            console.log(data);

            } else if (data.status == "success") {
                location.reload();
            }
        }
    });

});
$("body").undelegate("#delete-reply", "click").delegate("#delete-reply", "click", function() {
    var id = $(this).attr("data-id");
    var dataString = 'id=' + id;
    $.ajax({
        type: "POST",
        url: "/article/delete",
        data: dataString,
        cache: false,
        success: function(data) {
            if (data.status == "error") {
              new PNotify({
                        title: 'Whoops!',
                        text: 'Het is niet gelukt om je reactie te verwijderen',
                        type: 'error',
                    });
            } else if (data.status == "success") {
                new PNotify({
                        title: 'Gelukt!',
                        text: data.message,
                        type: 'success',
                });
                $(".ac-item[data-id=" + id + "]").remove();
            }
        }
    });

});
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip(), $("body").undelegate(".forum-post-like", "click").delegate(".forum-post-like", "click", function() {
        var t = $(this).attr("data-id"),
            e = "post=" + t;
        return null != t ? $.ajax({
            type: "POST",
            url: "/forum/thread/like",
            data: e,
            cache: !1,
            success: function(e) {
                "error" == e.status ? new PNotify({
                    title: "Whoops..",
                    type: "error",
                    text: "Je vind deze post al leuk!"
                }) : "success" == e.status && (1 === e.data.length ? ($(".forum-post-like[data-id=" + t + "]").replaceWith('<i class="fa fa-heart" aria-hidden="true"></i>'), $(".forum-likes-container[data-id=" + t + "]").prepend($('<div class="forum-likes"><a href="/user/profile/{{current_user_information.username}}"><b>{{current_user_information.username}}</b></a>, vindt dit leuk! <div class="forum-likes-icon" style="float:left;"></div></div>')).fadeIn("slow")) : $(".replace[data-id=" + t + "]").prepend('<a href="/user/profile/{{current_user_information.username}}"><b>{{current_user_information.username}}</b></a>,'))
            }
        }) : new PNotify({
            title: "Error",
            type: "error",
            text: "Er is iets misgegaan.."
        }), !1
    }), $("body").undelegate(".forum-thread-quote", "click").delegate(".forum-thread-quote", "click", function() {
        var t = $(this).attr("data-id"),
            e = $(".content-body[data-id=" + t + "]").text(),
            i = $.trim(e);
        $("#reply").val($("#reply").val() + "\n[quote=" + t + "]" + i + "[/quote]\n"), $("#reply").focus(), $("html,body").animate({
            scrollTop: 9999
        }, "slow")
    }), $("body").undelegate(".forum-close-thread", "click").delegate(".forum-close-thread", "click", function() {
        var t = $(this).attr("data-id"),
            e = $(this).text(),
            i = "data=" + t;
        return null != t ? $.ajax({
            type: "POST",
            url: "/forum/thread/close",
            data: i,
            cache: !1,
            success: function(t) {
                "error" == t.status ? new PNotify({
                    title: "Whoops..",
                    type: "error",
                    text: "Er is iets misgegaan!"
                }) : "success" == t.status && ("Sluit topic" == e ? (new PNotify({
                    type: "success",
                    text: "Topic is gesloten!"
                }), $(".forum-close-thread").text("Open topic"), $(".reply").attr("disabled", "disabled"), $(".forum-reply-box").hide()) : (new PNotify({
                    type: "success",
                    text: "Topic is heropend!"
                }), $(".forum-close-thread").text("Sluit topic"), $(".reply").removeAttr("disabled"), $(".forum-reply-box").show("slow")))
            }
        }) : new PNotify({
            title: "Whoops..",
            type: "error",
            text: "Er is iets misgegaan"
        }), !1
    }), $("body").undelegate(".forum-sticky-thread", "click").delegate(".forum-sticky-thread", "click", function() {
        var t = $(this).attr("data-id"),
            e = $(this).text(),
            i = "data=" + t;
        return null != t ? $.ajax({
            type: "POST",
            url: "/forum/thread/sticky",
            data: i,
            cache: !1,
            success: function(t) {
                "error" == t.status ? new PNotify({
                    title: "Whoops..",
                    type: "error",
                    text: "Er is iets misgegaan.."
                }) : "success" == t.status && ("Maak sticky" == e ? (new PNotify({
                    type: "success",
                    text: "Je hebt dit topic een sticky gegeven!"
                }), $(".forum-sticky-thread").text("Verwijder sticky")) : (new PNotify({
                    type: "success",
                    text: "Sticky is verwijderd van dit topic!"
                }), $(".forum-sticky-thread").text("Maak sticky")))
            }
        }) : new PNotify({
            title: "Whoops..",
            type: "error",
            text: "Er is iets misgegaan!"
        }), !1
    }), $("body").undelegate(".forum-post-edit", "click").delegate(".forum-post-edit", "click", function() {
        var t = $(this).attr("data-id");
        $(".forum-post-edit[data-id=" + t + "]").data("clicked") ? ($(".forum-edit-box[data-id=" + t + "]").html(), $(".forum-edit-box[data-id=" + t + "]").hide(), $(".forum-post-edit[data-id=" + t + "]").data("clicked", !1)) : ($(".forum-edit-box[data-id=" + t + "]").load("/forum/thread/edit/" + t), $(".forum-edit-box[data-id=" + t + "]").show(), $(".forum-post-edit[data-id=" + t + "]").data("clicked", !0), $("html, body").animate({
            scrollTop: $(".forum-post-edit[data-id=" + t + "]").position().top
        }))
    }), $("body").undelegate(".forum-delete-thread", "click").delegate(".forum-delete-thread", "click", function() {
        var t = $(this).attr("data-id"),
            e = $(this).text(),
            i = "data=" + t;
        return null != t ? $.ajax({
            type: "POST",
            url: "/forum/thread/delete",
            data: i,
            cache: !1,
            success: function(t) {
                "error" == t.status ? new PNotify({
                    title: "Whoops..",
                    type: "error",
                    text: "Er is iets misgegaan!"
                }) : "success" == t.status && ("Verwijder topic" == e ? (new PNotify({
                    type: "success",
                    text: "Topic is verwijderd! Mocht je hem in de toekomst nog actief maken, raad pleeg invisible topics."
                }), $(".forum-delete-thread").text("Open topic")) : (new PNotify({
                    type: "success",
                    text: "Topic is weer heropend!"
                }), $(".forum-delete-thread").text("Verwijder topic")))
            }
        }) : new PNotify({
            title: "Whoops..",
            type: "error",
            text: "Er is iets misgegaan!"
        }), !1
    })
});
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
         
$("body").undelegate(".add-discord", "click").delegate(".add-discord", "click", function() {
var post = $("#discord").val();
var dataString = 'invite=' + post;
if (post != null) {
  $.ajax({
      type: "POST",
      url: "/housekeeping/widgets/discord",
      data: dataString,
      cache: false,
      success: function(data) {
          if (data.status == "error") {
                new PNotify({
                    title: 'Oops!',
                    text: data.msg,
                    type: 'error',
                });
          } else if (data.status == "success") {
                new PNotify({
                    title: 'Gelukt!',
                    text: data.msg,
                    type: 'success',
                });
          }
      }
  });
}
});

$("body").undelegate(".add-spotlight", "click").delegate(".add-spotlight", "click", function() {
var post = $("form").serializeArray();
  
if (post != null) {
  $.ajax({
      type: "POST",
      url: "/housekeeping/widgets/spotlight",
      data: post,
      cache: false,
      success: function(data) {
          if (data.status == "error") {
                new PNotify({
                    title: 'Oops!',
                    text: data.msg,
                    type: 'error',
                });
          } else if (data.status == "success") {
                new PNotify({
                    title: 'Gelukt!',
                    text: data.msg,
                    type: 'success',
                });
          }
      }
  });
}
});


$("#inputGroupFile01").change(function(event) {  
  RecurFadeIn();
  readURL(this);    
});
$("#inputGroupFile01").on('click',function(event){
  RecurFadeIn();
});
function readURL(input) {    
  if (input.files && input.files[0]) {   
    var reader = new FileReader();
    var filename = $("#inputGroupFile01").val();
    filename = filename.substring(filename.lastIndexOf('\\')+1);
    reader.onload = function(e) {  
      $('#uploadImage').attr('src', e.target.result);
      $('#uploadImage').hide();
      $('#uploadImage').fadeIn(500);      
      $('.custom-file-label').text(filename);             
    }
    reader.readAsDataURL(input.files[0]);    
  } 
  $(".alert").removeClass("loading").hide();
}
function RecurFadeIn(){ 
  FadeInAlert("Wordt geladen..");  
}
function FadeInAlert(text){
  $(".alert").show();
  $(".alert").text(text).addClass("loading");  
}

function uploadFile(recipient, path, target){
  var input = document.getElementById("inputGroupFile01");
  file = input.files[0];
  if(file != undefined){
    formData= new FormData();
    if(!!file.type.match(/image.*/)){
      formData.append("image", file);
      formData.append("id", recipient);
      formData.append("path", path);
      formData.append("target", target);

      $.ajax({
        url: "/housekeeping/api/upload-image",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(data){
          if(data.status == 'success')
            {
              new PNotify({
                 title: 'Jeuj!',
                 type: 'success',
                 text: 'Je afbeelding is gewijzigd!',
             });
            }else{
              new PNotify({
                 title: 'Oops!',
                 type: 'error',
                 text: 'Er is iets misgegaan.. probeer opnieuw',
             });         
            }
           
        }
      });
    }else{
       new PNotify({
             title: 'Oops!',
             type: 'error',
             text: 'Bestandstype wordt niet ondersteund!',
         });
  }
  }else{
     new PNotify({
           title: 'Oops!',
           type: 'error',
           text: 'Kies je bestand om te uploaden!',
       });
  }
}

$('#uploadModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var image = button.data('image')
    var path = button.data('path')
    var route = button.data('route')

    var modal = $(this)
    
    if(recipient != null) {
      
        $("#uploadImage").attr("src", image)
        $("#path").attr("value", path)

        modal.find('.modal-title').text('Upload forum image')
        modal.find('.modal-footer').html('<a onclick="uploadFile(' + recipient + ', \'' + path + '\', \'' + route + '\');" class=\"btn btn-success\">Save</a>')
    }
});

$('#deletePermissionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var id = button.data('id')
    var value = button.data('value')
    var role = button.data('role')
    var modal = $(this)

    if(id != null) {
        modal.find('.modal-title').text('Action to ' + value)
        modal.find('.modal-footer').html('<a href=\"/housekeeping/manage/role/' + role + '/users\" class=\"btn btn-success\">See all users</a><a href=\"/housekeeping/manage/permissions/' + id + '/delete\" class=\"btn btn-danger\">Delete</a>');
        modal.find('.modal-body input').val(id)
    }

});

$('#widgetModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var deleteid  = button.data('value')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Action to ' + recipient)
        modal.find('.modal-footer').html('<a href=\"#\" class=\"add-spotlight btn btn-success\" data-id=\"' + recipient + '\">Save</a><a href=\"/housekeeping/widget/' + deleteid + '/delete\" class=\"btn btn-danger\">Delete</a>')
        modal.find('.modal-body').empty();
        modal.find('.modal-body').load('/housekeeping/api/getwidget/' + recipient);
        modal.find('.modal-body input').val(recipient)
    }
});

$('#addWidgetModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var name  = button.data('name')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Action to ' + name)
        modal.find('.modal-footer').html('<a href=\"#\" class=\"add-spotlight btn btn-success\"">Save</a><a href=\"/housekeeping/widget/" class=\"btn btn-danger\">Delete</a>')
        modal.find('.modal-body input').val(recipient)
    }
});

$('#actionModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Action to ' + recipient)
        modal.find('.modal-footer').html('<a href=\"#\" data-toggle=\"modal\" data-target=\"#alertModal\" class=\"btn btn-success\" data-id=\"' + recipient + '\">Alert</a><a href=\"#\" class=\"btn btn-danger\" data-toggle=\"modal\" data-target=\"#banModal\" data-id=\"' + recipient + '\">Ban</a><a href=\"/housekeeping/remote/user/' + recipient + '\" class=\"btn btn-secondary\">Manage User</a>')
        modal.find('.modal-body input').val(recipient)
    }
});

$('#unbanModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    if(recipient != null) {
        modal.find('.modal-title').text('Are you sure to unban ' + recipient + '?')
        modal.find('.modal-footer').html('<a href=\"/housekeeping/remote/user/' + recipient + '/unban"\" class=\"btn btn-success\">Unban</a>')
        modal.find('.modal-body input').val(recipient)
    }
});

$('#alertModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});

$('#banModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});

$('#manageModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});

$('#resetModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var recipient = button.data('id')
    var modal = $(this)

    modal.find('.modal-body #inputUsername').val(recipient)
});
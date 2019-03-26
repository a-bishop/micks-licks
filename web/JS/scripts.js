$(document).ready(function(){

  setTimeout(function(){
    $('.alert-danger').fadeOut();
    $('.alert-success').fadeOut();
  }, 2000);

$('.more-info').click(function(){
  var record = "https://embed.spotify.com/?uri=";
  record += $(this).attr('link');
  var description = $(this).attr('description');
  var title = $(this).attr('title');
  var artist = $(this).attr('artist');
  var titleArtist = title + " - " + artist;
  var releaseDate = $(this).attr('releaseDate');
  $('#titleArtist').html(titleArtist);
  $('#link').attr('src', record);
  $('#description').html(description);
  $('#releaseDate').html(releaseDate);
});

$('#myModal').on('hide.bs.modal', function () {
  f = navigator.userAgent.search("Firefox");
  if (f > -1) {
    console.log('Your browser is Firefox');
    setTimeout(function() {
  		window.location='index.php';
  	});
  }
});

function validateRecord() {
  // VALIDATION CODE HERE!
  	if(document.getElementById('artist').value ==''){
  		alert("You must include the artist's name");
  		return false;
  	} if(document.getElementById('albumTitle').value ==''){
  		alert("You must include an album title");
  		return false;
  	} if(document.getElementById('genre').value ==''){
  		alert("You must include a genre");
  		return false;
  	} if(document.getElementById('price').value ==''){
  			alert("Please insert a valid price");
  			return false;
  	} if(document.getElementById('quality').value ==''){
  			alert("You must specify used or new quality");
  			return false;
    }
}

function registrationValidation() {

if(document.getElementById('USEREMAIL').value ==''){
  alert("You must include a valid Email Address");
  return false;
} if(document.getElementById('firstName').value ==''){
  alert("Please include your first name");
  return false;
} if(document.getElementById('lastName').value ==''){
	alert("Please include your first name");
	return false;
} if(document.getElementById('DOB').value < 1998-01-01){
  alert("You must be at least 18 years old");
  return false;
} if(document.getElementById('password').value.length < 4){
  alert("Password must have at least 4 characters");
  return false;
} if (document.getElementById('password2').value ==''){
  alert("Please re-enter your password");
  return false;
} if(document.getElementById('address').value ==''){
	alert("Please include your address");
	return false;
} if(document.getElementById('city').value ==''){
	alert("Please include your city");
	return false;
}if(document.getElementById('postal_code').value ==''){
  	alert("Please include your postal code");
  	return false;
  }
}

function dismiss() {
  var decline = document.getElementById('decline');
  decline.onclick = function() {
    document.getElementById('modal').style.display = "none";
  }
}

});

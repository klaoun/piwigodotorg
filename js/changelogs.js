$(document).ready(function(){

  var maj = $('.version-major');

  if (maj.css('background-color') === 'rgb(239, 236, 250)') {
    $(".version-major h2").css({"color": "#463283"});
    $(".version-major p").css({"color": "#463283"});
    $(".version-major-content li ").css({"color": "#463283"});
    $(".version-major .read-more a").css({"background-color": "#463283"}).css({"color": "white"});
  }
  
  if(maj.css('background-color') === 'rgb(255, 222, 229)') {
    $(".version-major h2").css({"color": "#ff3363"});
    $(".version-major p").css({"color": "#ff3363"});
    $(".version-major-content li ").css({"color": "#ff3363"});
    $(".version-major .read-more a").css({"background-color": "#ff3363"}).css({"color": "white"});
  }

});
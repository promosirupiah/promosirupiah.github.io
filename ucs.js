function massgallery(divselect,aselector,tagselect,fadetime,changetime){
var interval;
var imgsrc;
var idnumber = 0;
$(divselect + ' ' + tagselect).each(function(){
idnumber = idnumber + 1;
$(this).attr('id',divselect + idnumber)
var imgsrc = $(this).find('img').attr('src');
//$(aselector).append(' <a href="'+divselect + idnumber+'" id="'+divselect + idnumber+'">' + idnumber +' </a>');
});

$(aselector + ' a').each(function(){$(this).click(function(e){
e.preventDefault();
cur.fadeOut(fadetime);cur.find('a').html('');
clearInterval( interval );
var theid = $(this).attr("id");
cur = $(divselect + ' ' + tagselect + '[id="' + theid +'"]');
cur.fadeIn(fadetime);


})});



$(divselect + ' ' + tagselect + ':gt(0)').hide();
$(divselect + ' ' + tagselect + ':last').addClass('last');
$(divselect + ' ' + tagselect + ':first').addClass('first');



var cur = $(divselect + ' ' + tagselect + ':first');
imgsrc = cur.find('a').attr('href');

}

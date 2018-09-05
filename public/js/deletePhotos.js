function hideCross() {
    var cross = document.getElementById('crossDismiss');
    const thumb = document.getElementById('divThumb');



            cross.classList.add("disabledCross");



}



function showCross() {
    const cross = document.getElementById('crossDismiss');
    const thumb = document.getElementById('divThumb');


        thumb.addEventListener('mouseover',function (e) {
            // e.preventDefault();
            cross.classList.remove('disabledCross');
            cross.classList.add('visibleCross');
        });










}


showCross();
//hideCross();


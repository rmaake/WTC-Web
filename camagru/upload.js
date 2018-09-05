(function() {
    var video = document.getElementById('video'),
    canvas = document.getElementById('canvas'),
    context = canvas.getContext('2d'),
    photo = document.getElementById('photo'),
    sup = document.getElementById('supImage');
    document.getElementById('capture').addEventListener('click', function()
    {
        context.drawImage(video, 0, 0, 400, 300);
        context.drawImage(sup, 0, 0, 400, 300);
        var element = document.getElementById('picture');
        var img = canvas.toDataURL('image/jpeg');
        element.value = img;
        document.getElementById('capture-form').submit();
    });
})();

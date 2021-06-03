if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    $('body').addClass('dark-mode');
    $('#dark-mode').attr('Checked','Checked');
}

$('#dark-mode').change(function(){
    if ($(this).prop('checked')) {
        $('body').addClass('dark-mode');
    } else{
        $('body').removeClass('dark-mode');
    }
});

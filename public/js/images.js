function loadImages(form_page)
{
    const images_row = form_page ?
        $('<div class="field-array form-group images-row"><label>Images</label><div><ul></ul></div></div>') :
        $('<div class="data-row images-row"><dd>Images</dd><dt><ul></ul></dt></div>');
    const iri_elem = $('.field-iri');
    const entity_iri = form_page ? (iri_elem.length ? iri_elem.val().trim() : undefined) : iri_elem.find('dt').text().trim();
    if(/\d$/.test(entity_iri)){
        $.getJSON(entity_iri + '/images', function(data){
            $.each( data, function( key, val ) {
                images_row.find('ul').append('<li><a data-fancybox="gallery" href="' + entity_iri + '/image/' + val + '"><img\n' +
                    '                            width="400" src="' + entity_iri + '/image/' + val + '"/></a></li>');
            });
        });
    }
    if(form_page){
        $('.content-panel-body').append(images_row);
    } else {
        $('.datalist').append(images_row);
    }
}

function createPasteArea(form_page)
{
    const iri_elem = $('.field-iri');
    let entity_iri = form_page ? (iri_elem.length ? iri_elem.val().trim() : undefined) : iri_elem.find('dt').text().trim();
    entity_iri = entity_iri.replace(/\/$/g, '');
    $('.images-row div').prepend('<div id="paste-area">You can paste' +
        ' images on this page.</div>');
    $('#paste-area')
        .pastableNonInputable()
        .on('pasteImage', function (ev, data) {
            $('<div class="result"><img src="' + data.dataURL + '" ></div>').insertAfter($(this).closest('div'));
            var fd = new FormData();
            fd.append('data', data.blob);
            $.ajax({
                type: 'POST',
                url: entity_iri + '/upload',
                data: fd,
                processData: false,
                contentType: false
            });
        }).on('pasteImageError', function (ev, data) {
        alert('Oops: ' + data.message);
        if (data.url) {
            alert('But we got its url anyway:' + data.url)
        }
    })
        .click();
    $('*').blur(function(){
        $('#paste-area').click();
    });
}

$(document).ready(function () {
    const form_page = !!$('#main>form.ea-new-form, #main>form.ea-edit-form').length;
    loadImages(form_page);
    if(form_page){
        createPasteArea(form_page);
    }
});




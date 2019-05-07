// Comportamientos generales de nuestros componentes de la aplicacion
// Nuevo comportamiento de los links
$(document)
.on('click', 'a', function (e) {
    e.preventDefault();
    var section = $(this).attr('href'), data = {};
    // Comprobamos si tiene parametros get 
    // Significa que se solicita redirección
    if(section.indexOf('?') != -1){
        let arr = section.split('?')
        let get = arr[1].split('=')
        location.href ="?" + get[0] + "=" + get[1];
    } else {
        // Comprueba que  la seccion existe o no 
        if($('section#' + section).length){
            // Si existe oculta todas menos la solicitada
            app.sections.toggle(section);
        }else{
            // Manda una petición de la nueva vista
            data.controller = section
            data.action = 'view' 
            app.get(data);
        }
    }
})
.on('submit', 'form', function (e, i) {
    e.preventDefault();
    let data = app.toObject(e.currentTarget);
    if(exist(data.password)){ 
        data.password = sha256(data.password)
    }
    app.post({
        controller: $(this).attr('controller'),
        action: $(this).attr('action'),
        data: JSON.stringify(data)
    });
})
.on('change', 'select', function () {
    var selclass = 'valid';
    if ($(this).val() != "")
        $(this).addClass(selclass);
    else
        $(this).removeClass(selclass);
});

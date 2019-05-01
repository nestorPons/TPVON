
// Comportamientos generales de nuestros componentes de la aplicacion
// Nuevo comportamiento de los links
$(document).on('click', 'a', function(e){
    e.preventDefault()
    let section: string = $(this).attr('href')
    // Comprueba que  la seccion existe o no 
    if($('section#'+section).length){ 
        // Si existe oculta todas menos la solicitada
        app.sections.toggle(section)
    } else {
        // Si no manda una petición de la nueva vista
        app.get({
            controller: section,
            action: 'view'
        })
    }
})
.on('submit', 'form', function(e, i){
    e.preventDefault()    
    app.post({
        controller: $(this).attr('controller'), 
        action: $(this).attr('action'), 
        data: app.toJSONString(e.currentTarget)
    }); 
})
.on('change', 'select', function(){
    let selclass = 'valid'
    if($(this).val() != "")
        $(this).addClass(selclass)
    else
        $(this).removeClass(selclass)
})
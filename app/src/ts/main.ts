
// Comportamientos generales de nuestros componentes de la aplicacion
// Nuevo comportamiento de los links
$(document).on('click', 'a', function(e){
    e.preventDefault()
    let section: string = $(this).attr('href')
    if($('section#'+section).length){ 
        app.sections.toggle(section)
    }
    app.get({
        controller: section,
        action: 'view'
    })
})
.on('submit', 'form', function(e, i){
    e.preventDefault()    
    app.post({
        controller: $(this).attr('controller'), 
        action: $(this).attr('action'), 
        data: app.toJSONString(e.currentTarget)
    }); 
})
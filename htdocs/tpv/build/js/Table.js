
class Table{
    constructor(id){
        this.el = document.getElementById(id)
        this.$table = $('#' + id)
        this.$template =  $( $('#template_' + id)[0].innerHTML )
    }
    line(){
        return parseInt(this.el.rows.length)
    }
    /**
     * arrData = array de datos en orden de salida
     */
    addLine(id, arrData){
        return this.$template
            .clone()
            .attr('idline', id || this.$table.find('tr').length)
            .find('td').each(function(i, el){
                $(this)
                    .html(arrData[i])
                    .attr('data-label', arrData[i])
            })
            .end()
            .prependTo(this.$table.find('tbody')) 
    }
    endScroll(){
        this.$table.animate({ scrollTop: this.$table.height() }, 100);
    }
    clearLines(){
        this.$table.find('tbody').find('tr').remove()
    }
    clear(){
        this.clearLines()
    }
    delLine(id){
        this.$table.find(`[idline="${id}"]`).remove()
    }
    updateLine(id, data){
        this.delLine(id)
        return this.addLine(id, data)
    }
    total(arg){
        const total = this.el.getElementsByClassName('id_total')[0]
        if(arg) total.innerHTML = arg
        return total.innerHTML
    }
    hoverable(value){
        echo('Hoverable...')
        if(value != undefined){
            if(value) this.el.classList.add('hoverable')
            else this.el.classList.remove('hoverable')
        }
        return this.el.classList.contains('hoverable')
    }
    
}

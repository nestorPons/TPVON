class Ticket{

    constructor(data){
        if (data == undefined) {
            this.id = null
            this.lines =  []
            this.id_usuario = null
            this.id_cliente =  null
            this.fecha = null
            this.iva = null
            this.estado = 1;
        } else {
            this.id = data.id || null
            this.lines = data.lines || []
            this.id_usuario = data.id_usuario || null
            this.id_cliente = data.id_cliente || null
            this.fecha = data.fecha || null
            this.iva = data.iva || null
            this.estado = (data.estado != undefined) ? data.estado : 1 ; 
        }
    }
    addLine(articulo, des, cantidad, precio, dto, amo, iva){ 
        let newLine = new Line(this.idLine, articulo, des, cantidad, precio, dto, amo, iva)
        this.lines.push(newLine)
        return newLine
    }
    total(){        
        let total = 0.00
        for(let l of this.lines){
            total += parseFloat(l.amo ||  l.cantidad * l.precio * (1 - l.dto / 100))
        }
        return total.toFixed(2)
    }
    validate(){
        if(
            this.lines.length == 0 ||
            this.id_usuario == '' ||
            this.id_cliente == '' ||
            this.fecha == '' 
        ) return false
        else return true
    }
    deleteLine(index){
        this.lines = this.lines.filter(e => e.id != index)
        return this.lines
    }
    getLine( index ){
        return this.lines.filter(e => e.id == index)[0]
    }
}
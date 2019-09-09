class Ticket{

    constructor(data = {}){

        this.id = data.id || null
        this.lines = data.lines || []
        this.id_usuario = data.id_usuario || null
        this.id_cliente = data.id_cliente || null
        this.fecha = data.fecha || null
        this.iva = data.iva || null
        if(data.estado != undefined) this.estado = data.estado
    }
    addLine(articulo, des, cantidad, precio, dto, amo, iva){ 
        let newLine = new Line(this.idLine, articulo, des, cantidad, precio, dto, amo, iva)
        this.lines.push(newLine)
        return newLine
    }
    total(){        
        let total = 0.00
        for(let i in this.lines){
            total += parseFloat(this.lines[i].amo || 0)
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
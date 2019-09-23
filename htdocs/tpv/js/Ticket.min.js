class Ticket{

    constructor(data){
        this.id = null
        this.lines =  []
        this.id_usuario = null
        this.id_cliente =  null
        this.fecha = null
        this.iva = 21
        this.estado = 1;
        if (data != undefined) {  
            this.id = data.id
            this.id_usuario = data.id_usuario
            this.id_cliente = data.id_cliente
            this.fecha = data.fecha
            this.estado = (data.estado != undefined) ? data.estado : 1 ; 
            this.addLines(data.lines)
        }
    }
    addLines(dataLines){
        for(let i in dataLines){
            const d = dataLines[i]
            this.addLine(d.id, d.articulo, d.cantidad, d.precio, d.dto)
        }
        return true
    }
    addLine(id, articulo, cantidad, precio, dto){ 
        let newLine = new Line(id, articulo, cantidad, precio, dto)

        this.lines.push(newLine)

        return newLine
    }
    total(){        
        let total = 0.00
        for(let i in this.lines){
            const l = this.lines[i]
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

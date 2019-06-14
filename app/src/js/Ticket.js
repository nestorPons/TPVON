class Ticket{

    constructor(){
        this.id = null
        this.lines = []
        this.lineId = 0
        this.id_empleado = null
        this.id_cliente = null
        this.fecha = null
    }
    addLine(articulo, des, cantidad, precio, dto, amo){ 

        let newLine = new Line(this.idLine, articulo, des, cantidad, precio, dto, amo)
        newLine.id = this.lineId
        this.lines.push(newLine)
        this.lineId++
        return newLine
    }
    total(){        
        let total = 0.00
        for(let i in this.lines){
            total += parseFloat(this.lines[i].amo || 0)
        }
        return total
    }
    validate(){
        if(
            this.lines.length == 0 ||
            this.id_empleado == '' ||
            this.id_cliente == '' ||
            this.fecha == '' 
        ) return false
        else return true
    }
    deleteLine(index){
        let i = this.lines.indexOf(index) // filtramos
        return this.lines.splice(i,1)
    }
}
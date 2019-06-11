class Ticket{

    constructor(){
        this.id = null
        this.lines = []
        this.lineId = 0
        this.employee = null
        this.client = null
        this.date = null
    }
    addLine(cod, des, qua, pri, dto, amo){ 

        let newLine = new Line(this.idLine, cod, des, qua, pri, dto, amo)
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
            this.id == '' ||
            this.lines.length == 0 ||
            this.employee == '' ||
            this.client == '' ||
            this.date == '' 
        ) return false
        else return true
    }
    deleteLine(index){
        let i = this.lines.indexOf(index) // filtramos
        return this.lines.splice(i,1)
    }
    changeToLocaleDate(){
        this.date = this.date.toLocaleString()
    }
}
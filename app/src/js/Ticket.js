class Ticket{

    constructor(){
        this.id = null
        this.lines = []
        this.idLine = 0
        this.employee = null
        this.client = null
        this.date = null
    }
    addLine(cod, des, qua, pri, dto, amo){
        this.idLine++; 
        return this.lines[this.idLine] = new Line(this.idLine, cod, des, qua, pri, dto, amo)
    }
    total(){
        let total = 0
        for(let i in this.lines){
            var line = this.lines[i]           
            total += parseInt(line.amo)
        }

        return total || 0
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

        arreglo = this.lines.filter( (line, i) =>  line.id == ); // filtramos

        console.log( arreglo );
    }
}
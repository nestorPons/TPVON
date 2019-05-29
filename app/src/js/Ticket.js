class Ticket{

    constructor(){
        this.lines = []
        this.idLine = 0; 
    }
    addLine(cod, des, qua, pri, dto){
        this.idLine++; 
        return this.lines[this.idLine] = new Line(this.idLine, cod, des, qua, pri, dto)
         
    }
}
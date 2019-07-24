
    class Select extends Component{
        constructor(id){
            super()
            this.el = document.getElementById(id).getElementsByTagName('select')[0]
            this.o =  this.el.getElementsByTagName('option')
            echo(id)
        }
        addOption(value, option){
            let line = document.createElement('option')
                line.value = value 
                line.innerHTML = option
            this.el.append(line)
        }
        default(){
            this.el.value = 0
        }
        clear(){
            let ops = this.el.querySelector('option:not(:first-child)')
            for(let i in ops){
                if(ops[i]) this.el.remove(ops[i].selectedIndex)
            }
        }
    }

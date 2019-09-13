
    class Select extends Component{
        constructor(id){
            super()
            this.el = document.getElementById(id).getElementsByTagName('select')[0]
            this.o =  this.el.getElementsByTagName('option')
            this.CLASS_SELECTED = 'valid'
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
        reset(){

            this.el.classList.remove(this.CLASS_SELECTED)
            
            let options = this.el.getElementsByTagName('option')

            for (let option of options) {
                option.selected = false 
            }
        }
        value(v){
            if(v != undefined){
                this.el.value = v
                this.el.seletedIndex = v
            }
            return  this.el.value
        }
        // Seleciona una opcion por su valor
        option(data){
            let options = this.el.getElementsByTagName('option'),
                optionselected = false

            for (let option of options) {
                if(option.getAttribute('value') == data) {
                    option.selected = true 
                    optionselected = true
                } else option.selected = false 
            }

            if(optionselected && data != 0 ) this.el.classList.add(this.CLASS_SELECTED)
            else this.el.classList.remove(this.CLASS_SELECTED)
        }
    }


    class Select extends Component{
        constructor(id){
            super()
            this.el = document.getElementById(id).getElementsByTagName('select')[0]
            this.o =  this.el.getElementsByTagName('option')
            this.CLASS_SELECTED = 'valid' // Constante del estado seleccionado del select
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
        // Elimina todas las opciones
        clear(){
            this.reset()
            let ops = this.el.querySelectorAll('option:not(:first-child)')
            for(let o of ops){
                if(o) this.el.removeChild(o)
            }
        }
        // Deselecciona todas las opciones
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
            
            if (data != undefined) {
                let optionselected = false
                
                for (let option of this.o) {
                    if(option.getAttribute('value') == data) {
                        option.selected = true 
                        optionselected = true
                    } else option.selected = false 
                }
                if(optionselected && data != 0 ) this.el.classList.add(this.CLASS_SELECTED)
                else this.el.classList.remove(this.CLASS_SELECTED)
            } 
            /* return this.value() */
        }

    }


    class Select{
        constructor(id){
            this.$el = $('#'+id).find('select') 
        }
        addOption(value, option){
            const line = `<option value="${value}">${option}</option>`
            this.$el.append(line)
        }
        clear(){
            this.$el.find('option:not(:first-child)').remove()
        }
    }

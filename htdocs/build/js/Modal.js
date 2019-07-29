
    class Modal{
      constructor(id){

        this.el = document.getElementById(id)
        this.$el = $('#' + id)
        this.$switch = $('#control_' + id)
        this.$el.keyup(e => (e.key === 'Escape') && this.close())
      }
      clear(){
        this.el.getElementsByTagName('form')[0].reset()
        return this
      }
      setTile(tile){
        this.el.getElementsByClassName('tile')[0].innerHTML = tile
        return this
      }
      open(data = null){
        if(data) this.load(data)
        this.$switch.prop('checked',true)
        let o = this.el.querySelector('[tabindex="1"]') && o.focus() 
        return this
      }
      close(){
        this.$switch.prop('checked',false)
        return this
      }
      load(data){
        const inputs = this.$el.find('input'),
              selects = this.$el.find('select'),
              d = exist(data.id) ? data : data[0]

        selects.each(function(){

          $(this).find('[selected]').attr('selected',false)
          if(d.id != -1)
            $(this)
              .find('option[value="'+d[$(this).attr('name')]+'"]').attr('selected', true)    
          else
            $(this)
              .find('[default]').attr('selected',true)
        })
        inputs.each(function(){
          if($(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio'){
            if(d[$(this).attr('name')] == $(this).val()) $(this).prop('checked', true)
            else $(this).prop('checked', false)  

          }
          else $(this).val(d[$(this).attr('name')])
        })
        return this
      }
      getData(){ 
        return app.formToObject(this.el)
      }
    } 
  
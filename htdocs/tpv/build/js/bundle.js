class Line{constructor(id,articulo,cantidad,precio,dto){this.id=id;this.articulo=articulo;this.cantidad=cantidad||0;this.precio=precio||0;this.dto=dto||0;this.amo=this.total()};total(){return(this.cantidad*this.precio*(1-this.dto/100)).toFixed(2)}};class Ticket{constructor(data){this.id=null;this.lines=[];this.id_usuario=null;this.id_cliente=null;this.fecha=null;this.iva=21;this.estado=1;if(data!=undefined){this.id=data.id;this.id_usuario=data.id_usuario;this.id_cliente=data.id_cliente;this.fecha=data.fecha;this.iva=data.iva;this.estado=(data.estado!=undefined)?data.estado:1;this.addLines(data.lines)}};addLines(dataLines){for(let i in dataLines){const d=dataLines[i]
this.addLine(d.id,d.articulo,d.cantidad,d.precio,d.dto)}
return!0}
addLine(id,articulo,cantidad,precio,dto){let newLine=new Line(id,articulo,cantidad,precio,dto)
this.lines.push(newLine)
return newLine}
total(){let total=0.00
for(let i in this.lines){const l=this.lines[i]
total+=parseFloat(l.amo||l.cantidad*l.precio*(1-l.dto/100))}
return total.toFixed(2)}
validate(){if(this.lines.length&&this.id_usuario&&this.id_cliente&&this.fecha)return!0
else return!1}
deleteLine(index){this.lines=this.lines.filter(e=>e.id!=index)
return this.lines}
getLine(index){return this.lines.filter(e=>e.id==index)[0]}}class Present{constructor($id){this.el=document.getElementById($id)
this.style=this.el.getElementsByTagName('style')[0]
this.desc=this.el.querySelector('#descripcion')
this.date=this.el.querySelector('#fecha')
this.day=this.el.querySelector('#dia')
this.month=this.el.querySelector('#mes')
this.year=this.el.querySelector('#anyo')}
description(arr=[]){arr.foreach(text=>{this.desc.innerHTML+=text+'<br>'})
return this.desc.innerHTML}
date(d,m,y){this.day.innerHTML=d
this.month.innerHTML=m
this.year.innerHTML=y
return this.desc.innerHTML}
clear(){this.day.innerHTML=''
this.month.innerHTML=''
this.year.innerHTML=''
this.desc.innerHTML=''}}class Table{constructor(id){this.el=document.getElementById(id)
this.$table=$('#'+id)
this.$template=$($('#template_'+id)[0].innerHTML)}
line(){return parseInt(this.el.rows.length)}
addLine(id,arrData){return this.$template.clone().attr('idline',id||this.$table.find('tr').length).find('td').each(function(i,el){$(this).html(arrData[i]).attr('data-label',arrData[i])}).end().prependTo(this.$table.find('tbody'))}
endScroll(){this.$table.animate({scrollTop:this.$table.height()},100)}
clearLines(){this.$table.find('tbody').find('tr').remove()}
clear(){this.clearLines()}
showLines(exp){this.$table.find(exp).show()}
hideLines(){this.$table.find('tbody').find('tr').hide()}
delLine(id){this.$table.find(`[idline="${id}"]`).remove()}
updateLine(id,data){this.delLine(id)
return this.addLine(id,data)}
total(arg){const total=this.el.getElementsByClassName('id_total')[0]
if(arg)total.innerHTML=arg
return total.innerHTML}
hoverable(value){echo('Hoverable...')
if(value!=undefined){if(value)this.el.classList.add('hoverable')
else this.el.classList.remove('hoverable')}
return this.el.classList.contains('hoverable')}
html(){return this.el.innerHTML}}class Select extends Component{constructor(id){super()
this.el=document.getElementById(id).getElementsByTagName('select')[0]
this.o=this.el.getElementsByTagName('option')
this.CLASS_SELECTED='valid'
this.el.addEventListener('change',fn=>this.el.classList.add(this.CLASS_SELECTED))}
addOption(value,text){let opt=document.createElement('option')
opt.appendChild(document.createTextNode(text))
opt.value=value
this.el.appendChild(opt)}
default(){this.el.value=0}
clear(){this.reset()
let ops=this.el.querySelectorAll('option:not(:first-child)')
for(let o of ops){if(o)this.el.removeChild(o)}}
reset(){this.el.classList.remove(this.CLASS_SELECTED)
let options=this.el.getElementsByTagName('option')
for(let option of options){option.selected=!1}}
value(v){if(v!=undefined){this.el.value=v
this.el.seletedIndex=v}
return this.el.value}
option(data){if(data!=undefined){let optionselected=!1
for(let option of this.o){if(option.getAttribute('value')==data){option.selected=!0
optionselected=!0
break}else option.selected=!1}
if(optionselected&&data!=0)this.el.classList.add(this.CLASS_SELECTED)
else if(this.el.hasAttribute('hidden'))this.el.classList.remove(this.CLASS_SELECTED)}}}class Modal{constructor(id){this.el=document.getElementById(id)
this.$el=$('#'+id)
this.$switch=$('#control_'+id)
this.$el.keyup(e=>(e.key==='Escape')&&this.close())}
clear(){this.el.getElementsByTagName('form')[0].reset()
return this}
setTile(tile){this.el.getElementsByClassName('tile')[0].innerHTML=tile
return this}
open(data=null){if(data)this.load(data)
this.$switch.prop('checked',!0)
let o=this.el.querySelector('[tabindex="1"]')
o!=null&&o.focus()
return this}
close(){this.$switch.prop('checked',!1)
return this}
load(data){const inputs=this.$el.find('input'),selects=this.$el.find('select'),d=exist(data.id)?data:data[0]
selects.each(function(){if(d.id!=-1)
$(this).find('[selected]').attr('selected',!1).end().find('option[value="'+d[$(this).attr('name')]+'"]').attr('selected',!0)
else $(this).find('[default]').attr('selected',!0)})
inputs.each(function(){if($(this).attr('type')=='checkbox'||$(this).attr('type')=='radio'){if(d[$(this).attr('name')]==$(this).val())$(this).prop('checked',!0)
else $(this).prop('checked',!1)}else $(this).val(d[$(this).attr('name')])})
return this}
getData(){return app.formToObject(this.el)}}
class Line{constructor(id,articulo,cantidad,precio,dto){this.id=id;this.articulo=articulo;this.cantidad=cantidad||0;this.precio=precio||0;this.dto=dto||0;this.amo=this.setTotal()};setTotal(){return(this.cantidad*this.precio*(1-this.dto/100)).toFixed(2)}};class Ticket{constructor(data){this.id=null;this.lines=[];this.id_usuario=null;this.id_cliente=null;this.fecha=null;this.estado=1;this.iva=null;this.total=0.00;this.new=!0;if(data!=undefined){this.id=data.id;this.id_usuario=data.id_usuario;this.id_cliente=data.id_cliente;this.fecha=data.fecha;this.iva=data.iva;this.estado=(data.estado!=undefined)?data.estado:1;this.addLines(data.lines);this.setTotal()}};addLines(dataLines){for(let i in dataLines){const d=dataLines[i]
this.addLine(d.id,d.articulo,d.cantidad,d.precio,d.dto)}
return!0}
addLine(id,articulo,cantidad,precio,dto){let newLine=new Line(id,articulo,cantidad,precio,dto)
this.lines.push(newLine)
return newLine}
setTotal(){for(let i in this.lines){const l=this.lines[i]
this.total+=parseFloat(l.amo||l.cantidad*l.precio*(1-l.dto/100))}
return this.total.toFixed(2)}
validate(){if(this.lines.length&&this.id_usuario&&this.id_cliente&&this.fecha)return!0
else return!1}
deleteLine(index){this.lines=this.lines.filter(e=>e.id!=index)
return this.lines}
getLine(index){return this.lines.filter(e=>e.id==index)[0]}
sendData(){let props=this
delete props.total
delete props.new
return props}}class Present{constructor($id){this.el=document.getElementById($id)
this.style=this.el.getElementsByTagName('style')[0]
this.desc=this.el.querySelector('#descripcion')
this.day=this.el.querySelector('#dia')
this.month=this.el.querySelector('#mes')
this.year=this.el.querySelector('#anyo')}
description(arr=[]){if(arr!=[]){this.desc.innerHTML=''
for(let txt of arr){this.desc.innerHTML+=txt+'<br>'}}
return this.desc.innerHTML}
set date(date){let m=date.getMonth()+1,month=1
switch(m.toString()){case '1':month='Enero';break;case '2':month='Febrero';break;case '3':month='Marzo';break;case '4':month='Abril';break;case '5':month='Mayo';break;case '6':month='Junio';break;case '7':month='Julio';break;case '8':month='Agosto';break;case '9':month='Septiembre';break;case '10':month='Octubre';break;case '11':month='Noviembre';break;case '12':month='Diciembre';break}
this.day.innerHTML=date.getDate()
this.month.innerHTML=month
this.year.innerHTML=date.getFullYear()
return this.desc.innerHTML}
clear(){this.description.innerHTML=''
this.day.innerHTML=''
this.month.innerHTML=''
this.year.innerHTML=''
this.desc.innerHTML=''}}class Table{constructor(id){this.el=document.getElementById(id).querySelector('table')
this.body=this.el.getElementsByTagName('tbody')[0]
this.template=document.getElementById(id).querySelector('template')
this.$table=$('#'+id).find('table')
this.$template=$(`#${id}`).find('template')
this.data=[]}
line(){return parseInt(this.el.rows.length)}
addLine(id,arrData){this.data.push(arrData)
const c=this.$template.clone().html()
return $(c).attr('idline',id||this.$table.find('tr').length).find('td').each(function(i,el){$(this).html(arrData[i]).attr('data-label',arrData[i])}).end().prependTo(this.$table.find('tbody'))}
endScroll(){this.$table.animate({scrollTop:this.$table.height()},100)}
clearLines(){this.$table.find('tbody').find('tr').remove()}
clear(){this.data=[]
this.clearLines()}
showLine(exp){const e=this.el.querySelector(exp);e.classList.remove('hidden');return this}
hiddenRows(){const trs=this.body.getElementsByTagName('tr');for(const tr of trs){tr.classList.add('hidden')}}
delLine(id){this.$table.find(`[idline="${id}"]`).remove()}
updateLine(id,data){this.delLine(id)
return this.addLine(id,data)}
total(arg){const total=this.el.getElementsByClassName('id_total')[0]
if(arg)total.innerHTML=arg
return total.innerHTML}
hoverable(value){if(value!=undefined){if(value)this.el.classList.add('hoverable')
else this.el.classList.remove('hoverable')}
return this.el.classList.contains('hoverable')}
html(){return this.el.innerHTML}}class Select extends Component{constructor(id){super();this.el=(typeof id=='string')?document.getElementById(id).querySelector('select'):id.querySelector('select');this.o=this.el.getElementsByTagName('option');this.CLASS_SELECTED='valid';this.el.addEventListener('change',fn=>this.el.classList.add(this.CLASS_SELECTED))}
addClass(myclass){this.el.classList.add(myclass)
return this}
addOption(value,text){let opt=document.createElement('option')
opt.appendChild(document.createTextNode(text))
opt.value=value
this.el.appendChild(opt)
return this}
default(){this.el.value=0}
clear(){this.reset()
let ops=this.el.querySelectorAll('option:not(:first-child)')
for(let o of ops){if(o)this.el.removeChild(o)}
return this}
reset(){this.el.classList.remove(this.CLASS_SELECTED)
let options=this.el.getElementsByTagName('option')
for(let option of options){option.selected=!1}}
value(v){if(v!=undefined){this.el.value=v
this.el.seletedIndex=v
this.addClass('valid')}
return this.el.value}
option(data){if(data!=undefined){let optionselected=!1
for(let option of this.o){if(option.getAttribute('value')==data){option.selected=!0
optionselected=!0
break}else option.selected=!1}
if(optionselected&&data!=0)this.el.classList.add(this.CLASS_SELECTED)
else if(this.el.hasAttribute('hidden'))this.el.classList.remove(this.CLASS_SELECTED)}}
required(){this.el.removeChild(this.o[0])}}class Modal{constructor(id){this.el=document.getElementById(id).querySelector(`.card`);this.$el=$('#'+id).find('.card')
this.$switch=$('#control_'+id)
this.$el.keyup(e=>(e.key==='Escape')&&this.close())
this.attrState=!1
this.data=[]}
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
this.data=d
selects.each(function(){if(d.id!=-1)
$(this).find('[selected]').attr('selected',!1).end().find('option[value="'+d[$(this).attr('name')]+'"]').attr('selected',!0)
else $(this).find('[default]').attr('selected',!0)})
inputs.each(function(){if($(this).attr('type')=='checkbox'||$(this).attr('type')=='radio'){if(d[$(this).attr('name')]==$(this).val())$(this).prop('checked',!0)
else $(this).prop('checked',!1)}else $(this).val(d[$(this).attr('name')])})
return this}
async getData(rsp){const r=await app.formToObject(this.el);return{r,...rsp}}
state(value){if(value){this.attrState=value
const el=this.el.querySelector(`[name="id"]`)
if(el){if(value=='new')el.parentElement.classList.add('hidden')
else el.parentElement.classList.remove('hidden')}
return this}else{return this.attrState}}}
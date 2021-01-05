var atu={}
atu.form={}
atu.form.ini=function(form,d,v,fun){
    var _this=this
    _this.field=[]
    for(i in d){
        console.log(i,d[i])
        if(fun){
            fun(i,d[i])
        }else{
            $(form + ' .d').append('<span>' + d[i] + ':</span><input type="text" name="' + i +'" value="" autoComplete="off"/><br />')
        }
        _this.field.push(i)
    }
    if(v){
        for (i in v) {
            $(form + ' input[name=' + i + ']').val(v[i])
        } 
    }
    $(form + ' .close').on("click",function(){
        _this.hide()
    })
    $(form + ' .btnSava').on("click", function () {
        _this.save()
    })
    console.log(this.field )
    this.formname = form

    $(form+",.field").css({ "margin-top": -$(form).height()*0.5-50})

    $('.field .close').on("click", function () {
        $('.field').hide()
    })

}
atu.form.show=function(v){
    console.log(this.formname)
    if (v) {
        for (i in v) {
            $(this.formname + ' input[name=' + i + ']').val(v[i])
        }
    }
    $(this.formname).show()
}
atu.form.hide = function () {
    $(this.formname).hide()
}
atu.form.save=function(){}
atu.form.getVal=function(){
   var d={}
    
    for (var i = 0; i < this.field.length;i++){
        d[this.field[i]] = $(this.formname + ' input[name=' + this.field[i] + ']').val()
    } 
  
    return d;
}
atu.form.empty = function () { 
    $(this.formname + ' input').val('')
}

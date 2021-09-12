<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="http://static.alextu.com/lib/jquery/1.12.4.min.js"></script>
    <link rel="stylesheet" href="static/form.css?v=1.22">
</head>
<body>
   <table>
       
   </table> 
    <div class="box">
        <div class="form">
            <div class="d">

            </div>
            <a class="btn btnSava" href="javascript:;">保存</a>
            <a class="close" href="javascript:;">【关闭】</a>
        </div>
        <div class="field">
            <div class="d">
            
            </div>
            <br/>
            <a class="btn btnSava" href="javascript:;">保存</a>
            <a class="close" href="javascript:;">【关闭】</a>
        </div>
    </div>
<br /><br /><br />
    <a href="javascript:add()" class="btn btnAdd">添加</a>
    <div class="sort">
        <a href="javascript:sort(0);">国家（联合国成员）</a>
        <a href="javascript:sort(1);">地区</a>
        
        <a href="javascript:sort(2);" style="background: #001f7e;">1.英联邦</a>
        <a href="javascript:sort(3);" style="background: #fecc00;">2.欧盟</a>
        <a href="javascript:sort(4);" style="background: #006233;">3.阿拉伯国家联盟</a>
        <a href="javascript:sort(5);" style="background: #e30a17;">4.突厥国家联盟</a>
        
    </div>
    <div class="dbt">
        
        <a href="javascript:getField();">字段</a>
    </div>
   <script src="static/jdb.js?v=1"></script>
   <script>
       var d=<?php echo $this->_var['d']; ?>

       var t=<?php echo $this->_var['t']; ?>

       
       var _form= atu.form
       _form.ini(".form",t,{},function(i,n){
           $(".form .d").append('<span>' + n + ':</span>')
           c=""
           var i50=["zm2s","zm3","num","tel", "time", "time2","currency","currency2","currency3"], i100 = ["zm2","en","belong", "money", "money2", "continent", "population", "area", "un", "untime","fz","fz2"], ibr = ["en", "zm3", "tel", "time2", "untime", "continent", "population","money2","currency3", "fz2"]

           
           if(i50.indexOf(i)>=0|| i100.indexOf(i) >= 0){
               c= i50.indexOf(i) >= 0?50:100
               $(".form .d").append('<input type="text" name="' + i + '" value="" class="w' + c + '" autoComplete="off"/>')
               
           }else{
                $(".form .d").append('<input type="text" name="' + i + '" value="" autoComplete="off"/><br />')
           }
        
           if (ibr.indexOf(i)>=0) {
               $(".form .d").append('<br/>')
           }
       })

       var th='<tr>'
        for (j in t) {
            th += '<td>'+ t[j]+'</td>'
        }
        th += '<td></td></tr>'
        $("table").append(th)
        $("<a href='javascript:getBaidu()'>百度百科</a>").insertAfter(".form input[name=cn]")
        $("<a href='javascript:getFlag()'>获取</a>").insertAfter(".form input[name=flag]")
         $("<a href='javascript:getBirth()'>获取</a>").insertAfter(".form input[name=birthtime]")
 

       for(i=0;i<d.length;i++){
         
          for (j in t) {
               if (d[i][j] == null) {
                   d[i][j] = ""
               }

           }
        
          
          
           $("table").append(iniRow(d[i]))
       }
       function getField(){
            $.get("?act=getField", "", function (d) {
                $(".field .d").html("")
               
              
                var str1='',str2 = '',str3= '',j = 0;
                for(i in d["t"]){
                   if(j<18){
                     str1 +='<td><input name="k" value="'+i+'" class="w40"/></td>'
                     str2 += '<td><input name="v" value="' + d["t"][i] + '" class="w40"/></td>'
                     str3 += '<td><input name="s" value="' + d["s"][i] + '" class="w40"/></td>'
                    }
                    j++
                }
                str = '<table><tr><td>字段</td>' + str1 + '</tr><tr><td>字段名称</td>' + str2 + '</tr><tr><td>是否显示</td>' + str3 + '</tr>'
                
                if(j>=18){
                     str += '<tr><td colspan=19>&nbsp;</td></tr>'
                    var str1 = '', str2 = '', str3 = '', j = 0;
                    for (i in d["t"]) {
                        if (j >= 18) {
                            str1 += '<td><input name="k" value="' + i + '" class="w40"/></td>'
                            str2 += '<td><input name="v" value="' + d["t"][i] + '" class="w40"/></td>'
                            str3 += '<td><input name="s" value="' + d["s"][i] + '" class="w40"/></td>'
                        }
                        j++
                    }
                    for (k = j; k < 36; k++) {
                        str1 += '<td><input name="k" value="" class="w40"/></td>'
                        str2 += '<td><input name="v" value="" class="w40"/></td>'
                        str3 += '<td><input name="s" value="" class="w40"/></td>'
                    }
                     str += '<tr><td>字段</td>' + str1 + '</tr><tr><td>字段名称</td>' + str2 + '</tr><tr><td>是否显示</td>' + str3 + '</tr>'
                }else{
                    for(k=j;k<18;k++){
                         str1 += '<td><input name="k" value="" class="w40"/></td>'
                        str2 += '<td><input name="v" value="" class="w40"/></td>'
                        str3 += '<td><input name="s" value="" class="w40"/></td>'
                    }
                }
                 str += '</table>'
                  $(".field .d").append(str)
               $(".field").show()
           }, "json")
       }

        $('.field .btnSava').on("click", function () {
            var _d={k:[],v:[],s:[]}
             $(".field input[name=k]").each(function(){
               _d.k.push($(this).val())
             })
              $(".field input[name=v]").each(function () {
                _d.v.push($(this).val())
            })
             $(".field input[name=s]").each(function () {
                _d.s.push($(this).val())
            })
             $.get("?act=saveField", _d, function (d) {
                 $('.field').hide()
                 location.reload()
            }, "json")
        })

       function sort(id){
         if(id==0){
             $("tr").hide()
             $(".c1,.c2").show()
         }
         if (id == 1) {
               $("tr").show()
               $(".c1,.c2").hide()
           }
            if (id >1) {
               $("tr").hide()
               $(".e"+(id-1)).show()
           }
         $("tr").eq(0).show()
       }
       function iniRow(_d,noTr){
          
            id = _d.id
            var tr= noTr?'':'<tr id="t' + id + '" class="c' + _d.un + ' e' + _d.eun + '">'
            for(k in t){
                v= _d[k]
                 _d[k] = v.toString().replace('\\&quot;', "'")
                if(k=="un"){
                     tr += '<td class="u' + _d.un + '">' + _d.un + '</td>'
                }else if (k == "eun") {
                    tr += '<td class="eu' + _d.eun + '">' + _d.eun + '</td>'
               
                }else if(k == "flag"){
                    tr +='<td>' + (_d.flag ? '<a href="' + _d.flag + '" target="_blank"><img src="' + _d.flag + '" width=60/></a>' : "") + '</td>'
                } else if (k == "readme") {
                     tr += '<td class="readme" title="' + _d.readme + '"><span>' + _d.readme + '</span></td>'
                }else{
                    tr+='<td>' + _d[k] + '</td>'
                }
               
            }
            tr += '<td><a href="javascript:edit(' + id + ');">编辑</a></td>'
            tr+= noTr?'':'</tr>'
         return tr
       }
       _form.save=function(){
       
            var _d= _form.getVal()
     
           $.get("?act=save", _d, function (d) {
               _form.hide()
       
               if(d.result==2){
                  
                   $("table").append(iniRow(_d))
               }else{
                
                    $("#t"+ _d.id).html(iniRow(_d,1))
                    console.log(iniRow(_d, 1))
               }
           
           }, "json")
       }
       function edit(id){
           console.log("edit", id)
           _form.empty()
           $.get("?act=info",{"id": id},function(d){
                _form.show(d)
           },"json")
       }
       function add(){
            _form.empty()
            _form.show()
            id=$("tr").last().find("td").eq(0).html()*1+1
             $("input[name=id]").val(id)
       }
       function getBaidu(){
            var cn = $("input[name=cn]").val(), zm2 = $("input[name=zm2]").val()


           $.get("?act=getBaidu", {"cn": cn, "zm2": zm2}, function (d) {
              window.open(d.url)
           }, "json")
           
       }
       function getFlag(){
           var cn=$("input[name=cn]").val(),zm2= $("input[name=zm2]").val()
          
        
            $.get("?act=getFlag", {"cn": cn,"zm2": zm2}, function (d) {
                $("input[name=flag]").val(d.flag)
                $("input[name=tel]").val(d.tel)
                $("input[name=time]").val(d.time)
                $("input[name=area]").val(d.area)
                $("input[name=population]").val(d.population)
                window.focus()
           }, "json")
           window.open("https://baike.baidu.com/item/" + cn)
       }
       function getBirth(){
           var cn = $("input[name=cn]").val(), zm2 = $("input[name=zm2]").val()


           $.get("?act=getBirth", {"cn": cn, "zm2": zm2}, function (d) {
               $("input[name=birthtime]").val(d.birthtime)
               if($("input[name=capital]").val()==""){
                   $("input[name=capital]").val(d.capital)
               }
               
               window.focus()
           }, "json")
       }
        
   </script>
</body>
</html>
/************************************************************\
*
\************************************************************/

function ologin() {
      $(".login-hidden").load(root+"/login&ac=mini .login-box-div2",function(){$("#ref-url-l").attr("value",document.location.href);}).fadeIn(700);
      $(".opc-box").fadeIn();
}
function removeNotificacao()
{
      $("#nt-1").remove();
      var t_nt = setTimeout(function(){ $("#drop-down-notifications").slideUp('fast'); },1100)
            
}

function pesquisaNoticia()
{
   var query = $("#pr-search-input").val();
   window.location=root+"/portal/listn/"+query;
}
function validaSenha(osenha)
{
   if(osenha != ""){ 
      url = root+"/async/validate";
      var ab = $.ajax(
      {
         type: 'POST',
         url: url,
         async:false,
         data:
         {
            action: "valida_senha",
            token: 1,
            senha: osenha
         }
         ,
         error: function()
         {
            showWarn("Ocorreu um erro no processamento.");
         }
      }
      );
      ba = ab.success(function(data)
      { 
         data = eval("("+data+")");
         if(data.response == 1)
         {
            cd = true;
         }
         else if(data.response == 0)
         {
            cd = false;
         }
      }
      );
      return cd;
   }
   else{
      a = document.getElementById("senha").value.length;
      b = document.getElementById("senha").value;
      c = document.getElementById("senha-c").value;
  
    
      if(a < 6)
      {
         erro = "Senha Pequena";
         return erro;
      }
      if(b != c)
      {
         erro = "Confira Senhas";
         return erro;
      }
      else
      {
         return true;
      }
   }

}

/************************************************************\
*
\************************************************************/
function validaTelefone(tel)
{
   var caracteres;
   caracteres = tel.substring(0,tel.length);
   if(caracteres[0] != "(" || caracteres[3] != ")" || caracteres[8] != "-" || tel.length < 13)
   {
      erro = "Telefone Inv&aacute;lido.";
      return erro;
   }
   for(i=0;i<tel.length;i++)
   {
      if(caracteres[i] != 0 && caracteres[i] != 1 && caracteres[i] != 2 && caracteres[i] != 3 && caracteres[i] != 4 && caracteres[i] != 5 && caracteres[i] != 6 && caracteres[i] != 7 && caracteres[i] != 8  && caracteres[i] != 9 && caracteres[i] != ")" && caracteres[i] != "(" && caracteres[i] != "-")
      {
         erro = "Telefone Inv&aacute;lido.";
         return erro;
      }
   
   }
   return true;
}

function validaDia(d)
{
   var bissexto = false;
   m = document.getElementById("n-mes").value;
   a = document.getElementById("n-ano").value;
    
   if(d.value == null || d.value == 0)
   {
      return false;
   }
   if(a % 400 == 0 || ( a % 100 != 0 && a % 4 == 0 ))
   {
      bissexto = true;
   }
      
   if(d.value <= 31 && (m == 01 || m == 03 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) )
   {
      return true;
   }
   else if(d.value <= 30 && (m == 04 || m == 06 || m == 9 ||m == 11))
   {
      return true;
   }
   else if(( d.value <= 29 && bissexto == true && m == 02) || ( d.value <= 28 && bissexto == false && m == 02) )
   {
      return true; 
   }    
   else
   {
      return false;
   }
      

}

function validaParticipantes()
{
     var a = $("#participantes-add").val();
     if(a.length == 0) return true;
     a = a.split(";");
     for(i=0;i<a.length;i++)
         {b = validaUsername(a[i]);
            if(b != "J&aacute; Cadastrado") {
                 $("#envia-error").html("<span class='side-tip'>Você inseriu participantes que não são válidos.</span>");
                 return false;}
         }
         $("#envia-error").html("<span class='side-tip-ok'>Participantes válidos.</span>");
         return true;
}

function validaTelefone2(tel)
{
   var caracteres;
   caracteres = tel.substring(0,tel.length);
   if(tel.length == 0)
   {
      return true;
   }
   for(i=0;i<tel.length;i++)
   {
      if(caracteres[i] != 0 && caracteres[i] != 1 && caracteres[i] != 2 && caracteres[i] != 3 && caracteres[i] != 4 && caracteres[i] != 5 && caracteres[i] != 6 && caracteres[i] != 7 && caracteres[i] != 8  && caracteres[i] != 9 && caracteres[i] != ")" && caracteres[i] != "(" && caracteres[i] != "-")
      {
         erro = "Telefone Inv&aacute;lido.";
         return erro;
      }
   
   }
   return true;
}

/************************************************************\
*
\************************************************************/
function validaUsername(user)
{
   var er = /^[a-zA-z1-9][^\\\']+$/;
   if(er.test(user) == false)
       {
           c = "Nome Inválido"
           return c;
       }
   url = root+"/async/validate";
   var a = $.ajax(
   {
      type: 'POST',
      url: url,
      async:false,
      data:
      {
         login: user,
         action: "valida_login",
         token: 1
      }
      ,
      error: function()
      {
         showWarn("Ocorreu um erro no processamento.");
      }
   }
   );
   b = a.success(function(data)
   {
      data = eval("("+data+")");
      if(data.response == 1)
      {
           c = "J&aacute; Cadastrado";
      }
      else if(data.response == 0)
      {
         c = true;
				
      }
   }
   );
   if(user.length < 6)
   {
      c = "Login Pequeno";
   }
   return c;
}


/************************************************************\
*
\************************************************************/
function validaEmail(email,id)
{
   emailer = /^[A-Za-z0-9]+([A-Za-z0-9\.-_]*)*@[A-Za-z0-9]+([.]{1}[A-Za-z0-9]+)+$/;
   if(emailer.test(email) == false)
       {
      c = "Email Inv&aacute;lido";
      return c;
   }
   else
   {
      url = root+"/async/validate";
      a = $.ajax(
      {
         type: 'POST',
         url: url,
         async:false, 
         data:
         {
            action: "valida_email",
            token: 1,
            email: email, 
            id: id
         }
         ,
         error: function()
         {
            showWarn("Ocorreu um erro no processamento.");
         }
      }
      );
      b = a.success(function(data)
      {                      
         data = eval("("+data+")");
         if(data.response == 1)
         {
            c = "J&aacute; Cadastrado";
            return c;
         }
         else if(data.response == 0)
         {
            c = true;
            return c;
				
         }
         return "Já cadastrado";
      }
      );
      return c;
   }
  
}

function trim(str) {
return str.replace(/^\s+|\s+$/g,"");
}

function htmlEncode(string)
{
    /**
     *  Converte caracteres especiais para seus respectivos códigos html.
     *  OBS: Isso é utilizado para requisições AJAX, já que o javascript não reconhece os caracteres por padrão.
     */
    
    string = string.replace(/&/g, "&amp;");
    string = string.replace(/'/g, "&#039;");
    string = string.replace(/"/g, "&quot;");
    return string;
    
}

 function getPos() {
              $("#getloc").html("Pegando sua localização...");
              var ts = setTimeout(function(){ $("#getloc").html("Não foi possível te localizar (Tentar Novamente)!"); },10000);
         navigator.geolocation.getCurrentPosition(function(position) {
         geocoder = new google.maps.Geocoder();
         var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
         if (geocoder) {
         geocoder.geocode({'latLng': latlng}, function(results, status) {
         if (status == google.maps.GeocoderStatus.OK) {
         if (results[0]) {
         var ende = results[0].formatted_address;
         var teste = "Av. Vilarinho, 1325 - Venda Nova, Belo Horizonte - MG, 31615-250, Brasil";
         ende = ende.split(",");
         var bnumero = ende[1].split("-");
         var cestado = ende[2].split("-");
         var logd = trim(ende[0])+" - "+trim(bnumero[0]);
         var bairro = trim(bnumero[1]);
         var cidade = trim(cestado[0]);
         var estado = trim(cestado[1]).toLowerCase();
         $("#logd").val(logd);
         $("#bairro").val(bairro);
         $("#cidade").val(cidade);
         $("#"+estado).attr("selected", "selected");       
         $("#getloc").html("Ok");
         }
         else
         {
           $("#getloc").html("Não foi possível te localizar!");      
         }
         } else {
         $("#getloc").html("Não foi possível te localizar!");
         }
         });
         }

         })
  }
  
  function mapaEvent(t)
  {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(-34.397, 150.644);
    var myOptions = {
      zoom: 16,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    if(t == 1)
    map = new google.maps.Map(document.getElementById("mapevento"), myOptions);
    else if(t == 2)
    map = new google.maps.Map(document.getElementById("mapevento2"), myOptions);
  }
  function mapaPega()
  {
   var address = $("#adress").val();
     if (geocoder) {
      geocoder.geocode( {'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          var marker = new google.maps.Marker({
              map: map, 
              position: results[0].geometry.location
          });
        } else {
          showWarn("Não foi possível abrir o mapa.");
        }
      });
    }


  }

function setCookie(c_name,value,expiresecs) {  
    var exdate = new Date();  
    exdate.setTime(exdate.getTime()+ ((expiresecs) ? expiresecs*1000: 0));  
    document.cookie = c_name+ "=" +escape(value)+  
        ((expiresecs==null) ? "" : ";expires="+exdate.toGMTString())+";path=/";  
}    
  
function getCookie(c_name) {  
    if (document.cookie.length > 0)  {  
        c_start = document.cookie.indexOf(c_name + "=");  
        if (c_start != -1) {   
            c_start = c_start + c_name.length + 1;   
            c_end = document.cookie.indexOf(";", c_start);  
            if (c_end == -1) c_end = document.cookie.length;  
            return unescape(document.cookie.substring(c_start,c_end));  
        }   
    }  
    return "";  
} 

function desceAbas()
{
    if(getCookie("_#box-ranking-artista-wrapper") == "hide")
    {
        $("#box-ranking-artista-wrapper").css("display","none");
        $("#slide-box-ranking-artista").html("+");
    }
    if(getCookie("_#box-ranking-contratante-wrapper") == "hide")
    {
        $("#box-ranking-contratante-wrapper").css("display","none");
        $("#slide-box-ranking-contratante").html("+");
    }
    if(getCookie("_#box-eventos-proximos-wrapper") == "hide")
    {
        $("#box-eventos-proximos-wrapper").css("display","none");
        $("#slide-box-eventos-proximos").html("+");
    }
    if(getCookie("_#box-eventos2-recentes-wrapper") == "hide")
    {      
        $("#box-eventos2-recentes-wrapper").css("display","none");
        $("#slide-box-eventos2-recentes").html("+");
    }
    if(getCookie("_#box-contatos-recentes-wrapper") == "hide")
    {   
        $("#box-contatos-recentes-wrapper").css("display","none");
        $("#slide-box-contatos-recentes").html("+");
    }
    if(getCookie("_#box-comentarios-h-wrapper") == "hide")
    {      
        $("#box-comentarios-h-wrapper").css("display","none");
        $("#slide-box-comentarios-h").html("+");
    }
    if(getCookie("_#box-home-info-wrapper") == "hide")
    {      
        $("#box-home-info-wrapper").css("display","none");
        $("#slide-box-home-info").html("+");
    }
    if(getCookie("_#box-musicas-recentes-wrapper") == "hide")
    {      
        $("#box-musicas-recentes-wrapper").css("display","none");
        $("#slide-box-musicas-recentes").html("+");
    }
    if(getCookie("_#box-contratos-recentes-wrapper") == "hide")
    {      
        $("#box-contratos-recentes-wrapper").css("display","none");
        $("#slide-box-contratos-recentes").html("+");
    }
}

/**
 * Funções de warning para errors.
 *
 */

function showWarn(string)
{
    /**
     * Exibe uma div na área inferior da tela informando que ocorreu um erro ao processar a ação.
     */ 
    
    var t;
    
    if(string == "") // Não existe mensagem de erro.
          return false;
    clearTimeout(t);  
    $("#error-log").html("<div class='error-warn'>"+string+"</div>");
    t = setTimeout(function()
    {
      $(".error-warn").remove();    
    },3000);
    return true;
}

/*
 *  Função para alterar abas do perfil
 */

function alterarEditTab()
{
   
$(document).ready(function () {
      var myurl = window.location;
      myurl = myurl.toString();
      myurl = myurl.split("#");
      
      if (myurl[1] == "redes") {
         $(".sub-area-edit").load(root + "/home/edit/&mode=redes .edit-wrapper");
      } else if (myurl[1] == "local") {
         $(".sub-area-edit").load(root + "/home/edit/&mode=local .edit-wrapper");
      } else if (myurl[1] == "secu") {
         $(".sub-area-edit").load(root + "/home/edit/&mode=secu .edit-wrapper");
      } 
      $("#aba-profile").live("click",function () {
         location.href = '#perfil';
         $(".sub-area-edit").load(root + "/home/edit/&mode=geral .edit-wrapper");
         a = new Array("profile","redes","local","secu");            
         for(i=0;i<3;i++){
            if(i != 0)
               $("#aba-"+a[i]).attr("class","menu-abas-edit");
            else
               $("#aba-"+a[i]).attr("class","menu-abas-edit2");   
         }
      })
      $("#aba-redes").bind("click",function () {
         location.href = '#redes';
         $(".sub-area-edit").load(root + "/home/edit/&mode=redes .edit-wrapper");
         a = new Array("profile","redes","local","secu");             
         for(i=0;i<=3;i++){
            if(i != 1)
               $("#aba-"+a[i]).attr("class","menu-abas-edit");
            else
               $("#aba-"+a[i]).attr("class","menu-abas-edit2");   
         }
      }) 
      $("#aba-local").bind("click",function () {
         location.href = '#local';
         $(".sub-area-edit").load(root + "/home/edit/&mode=local .edit-wrapper");
         a = new Array("profile","redes","local","secu");         
         for(i=0;i<=3;i++){
            if(i != 2)
               $("#aba-"+a[i]).attr("class","menu-abas-edit");
            else
               $("#aba-"+a[i]).attr("class","menu-abas-edit2");    
         }
      }) 
      $("#aba-secu").bind("click",function() {
         location.href = '#secu';
         $(".sub-area-edit").load(root + "/home/edit/&mode=secu .edit-wrapper");
         a = new Array("profile","redes","local","secu");            
         for(i=0;i<=3;i++){
            if(i != 3)
               $("#aba-"+a[i]).attr("class","menu-abas-edit");
            else
               $("#aba-"+a[i]).attr("class","menu-abas-edit2");  
         }
      })
})
}



/*
 *  Class da tooltip
 */

function toolTipClass()
{
    /*
     * ToolTip Class
     * Por Paulo Felipe
     */
    
var obj;
var ttclass;
var time;

function pegaMPos(obj)
{
    /*
     * Parametros
     * @obj - Objeto [ Informações do elemento DOM que chamou o método ]
     */
    var top = 0, left = 0;
    do
    {
        top+= obj.offsetTop || 0;
        left+= obj.offsetLeft || 0;
        obj = obj.offsetParent;
    }
    while(obj);
       top += 15;
       left -= 45;
    return { top: top, left: left };
}


this.toolTipCreator = function(obj,ttclass,time,callback)
{
    /*
     *  Parametros
     *  @obj - Objeto [ Informações do elemento DOM que chamou o método ]
     *  @ttclass - Mixed [ Classe utilizada no ToolTip ]
     *  @time - Int [ Tempo em MS para ativar o tooltip ]
     */
    if(document.getElementById(obj.id+"-tt") == null)
        {
    
    $("."+ttclass).remove();
    
    $("#"+obj.id).bind("mouseleave",function(){
        var ttp = new toolTipClass(); 
        ttp.toolTipDestroyer(this,90);  
    })
    
    t = setTimeout(function()
    {
    $("."+ttclass).remove();      
    $("body").after("<div class='"+ttclass+"' id='"+obj.id+"-tt' rel='is_tooltip'></div>");
    var p = pegaMPos(obj);
         $("#"+obj.id+"-tt").css("top",p.top);
         $("#"+obj.id+"-tt").css("left",p.left);
    callback();
    },time)
        }
}

this.toolTipDestroyer = function(obj,time)
{
    /*
     *  Parametros
     *  @obj - Object [ Informações do elemento DOM que chamou o método ]
     *  @time - Int [ Tempo em MS para desaparecer a tooltip ]
     */
    clearTimeout(t);
    t = setTimeout(function()
        {
         if(document.getElementById(obj.id+"-tt") != null)
         $("#"+obj.id+"-tt").fadeOut().remove();
        },time)    
}
}
$("[rel=is_tooltip]").live("mouseleave",function(){
    clearTimeout(t);
     $("#"+this.id).remove();
})

$("[rel=is_tooltip]").live("mouseenter",function(){
    clearTimeout(t);
})


/*
 *  Classe dos comentários
 */

function commentsClass()
{
   /*   
    *  Comments Class
    *  por Paulo Felipe
    */
   
var tipo = "";
var id = "";

this.getInfo = function()
{
      /*
       * Pega o tipo de comentário e o id
       */

      if($("#user-profile-id").val() != undefined)
      {
          id = $("#user-profile-id").val();
          tipo = 0;
      }
      else if($("#event-id").val() != undefined)
      { 
          id = $("#event-id").val();
          tipo = 2;
      }
      else if($("#music-id").val() != undefined)
      { 
          id = $("#music-id").val();
          tipo = 1;
      }
      else if($("#contato-id").val() != undefined)
      {
          id = $("#contato-id").val();
          tipo = 3;              
      }
      else if($("#noticia-id").val() != undefined)
      {
          id = $("#noticia-id").val();
          tipo = 4;              
      }
      else
      {
          tipo = ""
          return false;
      }
      return true;
      
}

this.showComments = function(page,count)
{
      /*
       *  Parametros
       *  @page - INT [ Página para ser exibida ]
       */
      if(this.tipo == "")
            return false;
      
      page = parseInt(page);
      count = parseInt(count);
      var url;
      $("#more-comments").html("Carregando...");
      switch(tipo)
      {
            case 0: url="profile"; break;
            case 1: url="music"; break;
            case 2: url="events"; break;
            case 3: url="contato"; break;
            case 4: url="portal"; break;
      }
      
      $.ajax
      ({
      type: "POST",
      url: root+"/async/"+url+"/",
      data:
      {
         action: "comment_request",
         tipo_c: tipo,
         id_u: id,
         page: count,
         token: 1
      },
      success: function(data)
      {
         if($(data).html() != null)
               {
                     var count_new = $(data).find("#comment-count").html();
                     count_new = parseInt(count_new);
                     var list = $(data).find("#comment-list").html();
                     $("#results-c-m"+page).html(list+"<div id='results-c-m"+(page+1)+"'></div>");
                     $("#comentario-page").attr("value",page+1);
                     $("#comentario-count").attr("value",count+count_new);
                     $("#more-comments").html("Comentários carregados.");
               }
         else
               {
                     $("#more-comments").html("Não foi possível encontrar novos comentários");
               }
      }
      })
      return true;
}

this.deleteComment = function(id)
{
      /*
       *  Parametros
       *  @id - INT [ Id do comentário que será deletado ]
       */
       
       if($("#dc-"+id).html() != "Aguarde...")
       {
             $.ajax
             ({
             type: "POST",
             url: root+"/async/main/",
             data: 
             {
                action: "delete_comment",
                id_c: id,
                token: 1
             },
             success: function(data)
             {
                var response = $(data).html();
                if(response == 1)
                {
                      $("#comentario-box-t-"+id).remove();
                      var count = parseInt($("#comentario-count").val())
                      $("#comentario-count").attr("value",count-1);
                }
                else
                      $("body").append("fail");
             }
             });
       }
       $("#dc-"+id).html("Aguarde...");
}

this.editComment = function(obj)
{
      /*
       *  Parametros
       *  @obj - OBJETO [ Id do comentário que será deletado ]
       */      
       
       id = obj.id;
       var id_splited = obj.id.split("-");
       
       
       // Abre TEXTBOX
       if($("#"+obj.id).html() == "Editar")
             {
                   var value = $("#content-ajax-"+id_splited[1]).val();
                   $("#content-"+id_splited[1]).html("<textarea class='ct-edit"+tipo+"' id='edit-token-"+id_splited[1]+"'>"+value+"</textarea>");
                   $("#"+id).html("Ok");
             }
       // Faz a requisição
       else if($("#"+obj.id).html() == "Ok")
             {
                   
                   var newtext = $("#edit-token-"+id_splited[1]).val()
                   $.ajax
                   ({
                   type: "POST",
                   url: root+"/async/main/",
                   data: 
                   {
                      action: "edit_comment",
                      id_c: id_splited[1],
                      token: 1,
                      text_c: newtext
                   },
                   success: function(data)
                   {
                      if(data == "" || data == undefined)
                          showWarn("Falha ao editar comentário.");
                      else
                      {
                            $("#content-ajax-"+id_splited[1]).attr("value",newtext);
                            $("#content-"+id_splited[1]).html(data);
                            $("#"+id).html("Editar");
                      }
                   }
                   })
             }
}

this.addComment = function(text)
{
    var url;
    if(trim(text) == "")
        {
            $("#c-error").html("<span class='side-tip'>Não é possível deixar um comentário em branco.</span>");
            return false;
        }
    
    if(this.id == "" || this.tipo == "")
        {
            $("#c-error").html("<span class='side-tip'>Ocorreu um erro durante a insersão do comentário.</span>");
            return false;
        }
    switch(tipo)
        {
            case 0: url="profile"; break;
            case 1: url="music"; break;
            case 2: url="events"; break;
            case 3: url="contato"; break;
            case 4: url="portal"; break;
        }
      
    $("#true-wrap").prepend("<div class='loading-comment'>Criando Comentário</div>");
    $.ajax
       ({
       type: "POST",
       url: root+"/async/"+url+"/",
       data: 
       {
          action: "add_comment",
          id_r: id,
          token: 1,
          tipo: tipo,
          text_c: text
       },
       success: function(data)
       {
          $(".loading-comment").remove();
          
          if(data == "" || data == undefined)
          {
              showWarn("Você digitou um comentário inválido.");
              return;
          }
          var list = $(data).find("#comment-list").html();
          var id = $(data).find(".cmt-id").val();
          $("#bx-cmt").after(list);
          var count = parseInt($("#comentario-count").val())
          $("#comentario-count").attr("value",count+1);
          $("#ultimo-comentario").attr("value",id);
          $("#c-error").html("");
       }
       })
    return true;
        
}

this.reloadComments = function()
{
    /**
     *  Faz a função de recarregar a página com os novos comentários.
     *
     */
    
    this.getInfo(); // Pega informações do contato.
    var url;
    switch(tipo)
    {
        case 0: url="profile"; break;
        case 1: url="music"; break;
        case 2: url="events"; break;
        case 3: url="contato"; break;
        case 4: url="portal"; break;
    }
    
    // Cria um looping infinito com um timer de 10 segundos    
    t = setTimeout(function()
    {
      var lastc = $("#ultimo-comentario").val();  
      $.ajax
      ({
      type: "POST",
      url: root+"/async/"+url+"/",
      data: 
      {
         action: "reload_comment",
         max_id: lastc,
         rid: id,
         token: 1
      },
      success: function(data)
      {
         var b = new commentsClass();
         if(data == "" || data == undefined)
         {             
             b.reloadComments();
             return;
         }
         var last_id = parseInt($(data).find("#last-id").html());
         var list = $(data).find("#comment-list").html();
         
         $("#bx-cmt").after(list);
         var count_new = $(data).find("#comment-count").html();
         count_new = parseInt(count_new);
         var count = parseInt($("#comentario-count").val());
         $("#comentario-count").attr("value",count+count_new);
         $("#ultimo-comentario").attr("value",last_id);
         b.reloadComments();  
         
      }
      })
      
             
    },9000)
    
 
}

}

/*
 *  Classe Contatos
 */
 
 function contatoClass()
 {
 /*
  * Classe geral para contatos, métodos AJAX e gerenciamento de JavaScript 
  */
 
 this.showDescricao = function()
 {
       /*
        *  Mostra descrição na página de informações do contato.
        */
       $("#bci-hide-content").slideUp(300,function()
       {
             $("#hide-termos").css("display","none");
             if($("#hide-descricao").is(":hidden"))
                        $("#hide-descricao").css("display","block");
             $("#bci-hide-content").slideDown(300);
             $("#bci-hide-content").css("display","block");
       });
       
 }
 
 this.showTermos = function()
 {
       /*
        *  Mostra termos na página de informações do contato.
        */
       $("#bci-hide-content").slideUp(300,function()
       {
             $("#hide-descricao").css("display","none");
             if($("#hide-termos").is(":hidden"))
                        $("#hide-termos").css("display","block");
             $("#bci-hide-content").slideDown(300); 
             $("#bci-hide-content").css("display","block");
             
       });
       
 }
 
 this.hideDiv = function()
 {
       /*
        *  Esconde a div na página de informações do contato
        */
       $("#bci-hide-content").slideUp(300,function()
       {
             $("#hide-descricao").css("display","none");
             $("#hide-termos").css("display","none");
       });
       $("#bci-hide-content").css("display","none");
 }
 
 this.startContato = function(step)
 {
     /**
      *  Inicia processo de contato
      *  @param INT step
      */
     $('.ajax-box-contato').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
     var id_r = parseInt($("#user-profile-id").val())
     $.ajax
       ({
       type: "POST",
       url: root+"/async/contato/",
       data: 
       {
          action: "contato_start",
          id_r: id_r,
          token: 1,
          step: step
       },
       success: function(data)
       {
           $(".ajax-box-contato").fadeIn();
           $(".ajax-box-contato").html(data);
       }
       })
 }
 
 this.createContato = function(assunto,dia,mes,ano,valor,descricao)
 {
     /**
      *  Cria um contato
      *  @param string assunto
      *  @param int dia
      *  @param int mes
      *  @param int ano
      *  @param double valor
      *  @param string descricao
      */
     
     var id_r = parseInt($("#user-profile-id").val());
     if(dia.length == 1)
         dia = "0"+1;
     parseInt(dia);
     var data2 = ano+"-"+mes+"-"+dia;
     $.ajax
       ({
       type: "POST",
       url: root+"/async/contato/",
       data: 
       {
          action: "contato_start",
          id_r: id_r,
          assunto: assunto,
          data: data2,
          valor: valor,
          descricao: descricao,
          token: 1,
          step: 3
       },
       success: function(data)
       {
           $(".ajax-box-contato").fadeIn();
           $(".ajax-box-contato").html(data);
       }
       });
     
 }
 
 this.contatoBack = function(assunto,data,descricao,valor)
 {
     /**
      *  Volta para a tela de contato pós ocorrência de erro.
      *  @param string assunto
      *  @param int dia
      *  @param int mes
      *  @param int ano
      *  @param double valor
      *  @param string descricao
      */
     
     var id_r = parseInt($("#user-profile-id").val());
     data = data.split("-");
     $.ajax
       ({
       type: "POST",
       url: root+"/async/contato/",
       data: 
       {
          action: "contato_start",
          id_r: id_r,
          assunto: assunto,
          data: data,
          valor: valor,
          descricao: descricao,
          token: 1,
          step: 2
       },
       success: function(data)
       {
           $(".ajax-box-contato").fadeIn();
           $(".ajax-box-contato").html(data);
       }
       });
     
 }
 
 this.closeContato = function(step)
 {
     /**
      *  Fecha um contato que já está ativo.
      */
     
     $(".opc-box").fadeIn();     
     $('.ajax-box-edit').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
     $(".ajax-box-edit").fadeIn();
     var id_r = parseInt($("#contato-id").val());
     $.ajax
       ({
       type: "POST",
       url: root+"/async/contato/",
       data: 
       {
          action: "contato_close",
          step: step,
          id_r: id_r,
          token: 1
       },
       success: function(data)
       {
           var response = $(data).find("#ajax-response").html();
           var content = $(data).find("#ajax-content").html();
           $('.ajax-box-edit').html(content);
           if(response == 1 && step == 1)
               {
                   $('#status-change').html("<span class = 'color-red'>Fechado</span>");
                   $('#mci-main').remove();                   
                   $("#bci-cancel").remove();
                   var atuantes = parseInt($("#atuantes-count").html())-1;
                   var fechados = parseInt($("#fechados-count").html())+1;
                   $("#atuantes-count").html(atuantes);
                   $("#fechados-count").html(fechados);
               }
       }
       });
     
 }
 
 
 }
 
 /**
  *  Classe de Curriculum
  *  
  */
function curriculumClass()
{
    
    var id;
    
    this.newCurriculum = function()
    {
        /**
         *  Realiza a criação de um novo curriculum, não vamos passar nenhum parâmetro pois ele cria o curriculum baseado em variáveis de sessão.
         */
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "new_curriculum",
           token: 1
        },
        success: function(data)
        {
            data = $(data).html();
            if(data == 1)
                $("#cib-true-wrap").load(root+"/contato/curriculum #cib-true-wrap");
            else
                showWarn("Não foi possível cadastrar seu curriculum.");
        }
        });
    }
    
    this.getId = function()
    {
        /**
         *  Pega o id do curriculum no qual estamos trabalho.
         */
        
        this.id = $("#id-curriculum").val();
        
    }
    
    this.editCampo = function(campo,valor)
    {
        /**
         *  Realiza a requisição para edição do campo, os parametros passados já estão formatados e serão válidados via servidor.
         */
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "edit_campo",
           campo: campo,
           valor: valor,
           id_c: this.id,
           token: 1
        },
        success: function(data)
        {
            var response = $(data).find("#response").html();
            var content = $(data).find("#response-content").html();
            if(valor == "")
                content = "<span class='italic'>Editar</span>";
            if(response == 1)
                $("#ce-"+campo).html("<span class='c-event-edit' id='cedit-"+campo+"'>"+content+"</span>");
            else
                showWarn("Erro ao editar campo");
        }
        });        
    }
    
    this.startEdit = function(objeto)
    {
      /**
       *  Realiza o início do processo para editar um campo.
       *  @param DOM objeto
       */
      var campo = objeto.id.split("-");
      var length;
      campo = campo[1];
      var ovalue = ($(objeto).html());
      if(ovalue == "<span class=\"italic\">Editar</span>" || ovalue == "<SPAN class=italic>Editar</SPAN>")
          ovalue = "";
      // Valida o maxlength dos campos
      switch(campo)
      {
          case 'area': length=20; break;
          case 'instrumento': length=20; break;
          case 'grupo_musical': length=30; break;
          case 'regiao': length=20; break;
      }
      
      // Remove caracteres que possam comprometer o DOM
      ovalue = htmlEncode(ovalue);
      
      if(campo == 'sexo')
      $("#ce-"+campo).html("<select id='"+campo+"-edit-cid' class='cid-txt-edit' onkeyup='c= new curriculumClass(); c.keyEnter(this,event.keyCode)'><option value='Masculino'>Masculino</option><option value='Feminino'>Feminino</option><option value='Não Informar'>Não Informar</option></select>");
      else
      $("#ce-"+campo).html("<input type='text' id='"+campo+"-edit-cid' value='"+ovalue+"' class='cid-txt-edit' maxlength='"+length+"' onkeyup='c= new curriculumClass(); c.keyEnter(this,event.keyCode)'/><span class='enter-info' id='"+campo+"-edit-cid-c'>Confirme com \"Enter\"</span>");
      $("#"+campo+"-edit-cid").focus();
    }
    
    this.keyEnter = function(object,key)
    {
        
      //else if(e)key = e.which();
      if(key == 13)
          {
              $("#"+object.id+"-c").css("background","#a00");
              var id = object.id.split("-");
              id = id[0];
              var value = $("#"+object.id).val();
              c = new curriculumClass();
              c.getId();
              c.editCampo(id,value);
          }
    }
    
    this.deleteCurriculum = function()
    {
        /**
         * Deleta o curriculum baseado no ID
         */
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "delete_curriculum",
           id_c: this.id,
           token: 1
        },
        success: function(data)
        {
            data = $(data).html();
            if(data == 1)
                $("#cib-true-wrap").load(root+"/contato/curriculum #cib-true-wrap");
            else
                showWarn("Não foi possível deletar o curriculum.");
        }
        });  
        
    }
    
    this.addItemLista = function(tipo,item)
    {
        /**
         *  Adiciona o item em uma lista
         *  @param int tipo
         *  @param string item
         */
        
        var tipo_string;
        switch(tipo)
        {
            case 0: tipo_string = "musicas"; tipo = '0'; break;
            case 1: tipo_string = "contatos"; tipo = '1'; break;
            case 2: tipo_string = "referencias"; tipo = '2'; break;
        }
        
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "additemlista_curriculum",
           id_c: this.id,
           tipo: tipo,
           item: item,
           token: 1
        },
        success: function(data)
        {   
            var response = $(data).find("#response").html();  
            var content = $(data).find('#response-content-ajax').html();
            if(response == 1)
                {                  
                  $("#ci-list-"+tipo_string).append(content);
                }
            else
                  {
                showWarn("Erro ao inserir item na lista, tente novamente.");
                  }
            var b = new curriculumClass();
            b.getId();
            b.reloadInsert(tipo);      
                  
        }
        });  
        
    }
    
    this.delItemLista = function(tipo,pos)
    {
        /**
         * Deleta um item da lista, baseado em tipo e posição.
         */
        
        var tipo_string;
        switch(tipo)
        {
            case '0': tipo_string = "musicas"; break;
            case '1': tipo_string = "contatos"; break;
            case '2': tipo_string = "referencias"; break;
        }
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "delitemlista_curriculum",
           id_c: this.id,
           tipo: tipo,
           pos: pos,
           token: 1
        },
        success: function(data)
        {   
            data = $(data).html();
            if(data == 1)
            {
                $("#box-"+tipo_string+"-wrap-"+pos).remove();
                
                // Precisamos reorganizar os índices dos elementos, vamos tentar usar uma pequena função para isso.
                var options = document.getElementsByTagName("div");
                var i;
                for(i=0;i < options.length;i++)
                {
                  var id2 = options[i].id;
                  id2 = id2.split("-");
                  var pos2 = id2[3];
                  pos = parseInt(pos);
                  pos2 = parseInt(pos2);
                  var tipo2 = id2[1];                 
                  if(pos2 > pos && tipo_string == tipo2)
                  { 
                     var pos3 = pos2-1;
                     $("#"+options[i].id).attr("id","box-"+tipo_string+"-wrap-"+pos3);
                     $("#cidel-"+tipo+"-"+pos2).attr("id","cidel-"+tipo+"-"+pos3);
                  }                            
                }
                var b = new curriculumClass();  
                b.getId();
                b.reloadInsert(tipo);   
            }
            else
                showWarn("Erro ao deletar item da lista.");
        }
        });        
    }
    
    this.atualizaCurriculum = function()
    {
        /**
         *  Atualiza o curriculum com informações do perfil.
         */
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "atualiza_curriculum",
           id_c: this.id,
           token: 1
        },
        success: function(data)
        { 
            
            data = $(data).html();
            if(data == 1)
                $("#cib-true-wrap").load(root+"/contato/curriculum #cib-true-wrap");
            else
                showWarn("Erro ao atualizar o curriculum com informações de seu perfil.");
        }
        });
        
    }
    
    this.reloadInsert = function(tipo)
    {
        /**
         *  Recarrega os campos para inserir novo conteúdo.
         */
        
        var tipo_string;
        switch(tipo)
        {
            case '0': tipo_string = "musicas"; break;
            case '1': tipo_string = "contatos"; break;
            case '2': tipo_string = "referencias"; break;
        }
        
        $.ajax
        ({
        type: "POST",
        url: root+"/async/curriculum/",
        data: 
        {
           action: "loadinsert_curriculum",
           id_c: this.id,
           tipo: tipo,
           token: 1
        },
        success: function(data)
        {
            $("#ci-insert-"+tipo_string).html(data);                
        }
        });
    }
}

/**
  *  Classe do portal
  *  
  */
function portalClass()
{
    this.novaNoticia = function()
    {
        /**
         * Abre o formulário para gerar novas notícias.
         */
        
        $('#portal-noticias-wrap').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
        $.ajax
        ({
        type: "POST",
        url: root+"/async/portal/",
        data: 
        {
           action: "new_noticia",
           token: 1
        },
        success: function(data)
        {
            $("#portal-noticias-wrap").html(data);                
        }
        });        
    }
    
    this.deletaNoticia = function(id,step)
    {
        /**
         *  Deleta uma notícia se basendo no id.
         *  @param int id
         *  @param int step
         */
        
        $(".ajax-box-edit").fadeIn();
        $(".opc-box").fadeIn();
        $.ajax
        ({
        type: "POST",
        url: root+"/async/portal/",
        data: 
        {
           action: "deleta_noticia",
           step: step,
           id: id,
           token: 1
        },
        success: function(data)
        {
            var response = $(data).find("#response").html();
            var content = $(data).find("#ajax-content").html();
            
            if(step == 0)
                $(".ajax-box-edit").html(content);
            else if(step == 1)
            {
                $(".ajax-box-edit").fadeOut();
                $(".opc-box").fadeOut();
                if(response == 0)
                {
                    showWarn("Falha ao deletar notícia.");
                }
                else if(response == 1)
                {
                    $("#portal-full-wrap").html(content);
                }
                    
            }
        }
        });
    }
    
    this.boxCreate = function(step,tipo,noticia)
    {
        /**
         *  Função para adicionar uma box na área de notícias.
         *  @param int step
         *  @param int tipo
         *  @param int noticia
         *
         */
        
        $(".opc-box,.ajax-box-edit").fadeIn();
        $('.ajax-box-edit').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
        switch(step)
        {
            case 1:
                $.ajax
                ({
                type: "POST",
                url: root+"/async/portal/",
                data: 
                {
                   action: "create_box",
                   token: 1,
                   step: 1,
                   id: "",
                   noticia: ""
                },
                success: function(data)
                {
                    $(".ajax-box-edit").html(data);                
                }
                });                
                break;
        }
    }
    this.editNoticia = function(step,id)
    {
        /**
         *  Edita a notícia
         */
        
        if(step == 0)
        {
            var content = $("#noticia-edit-nobb").html();
            $(".noticia-content").html("<textarea id='edit-noticia2'>"+content+"</textarea><p><input type='button' value='Enviar' id='edit-noticia-btn' class='btn' />");           
        }
        else if(step == 1)
        {
            var text = $("#edit-noticia2").val();
            $.ajax
            ({
            type: "POST",
            url: root+"/async/portal/",
            data: 
            {
               action: "edit_noticia",
               texto: text,
               id: id,
               token: 1
            },
            success: function(data)
            {
                var response = $(data).find("#response").html();
                var content = $(data).find("#ajax-content").html(); 
                if(response == 0)
                    {
                        showWarn("Falha ao editar notícia.");
                        $(".noticia.content").html(content);
                    }
                else
                    {
                        $("#noticia-edit-nobb").html(text);
                        $(".noticia-content").html("<div id='noticia-edit' class='pointer' title='Clique para editar' >"+content+"</div>");
                    }
            }
            });
        }
        
    }
    
}

/**
  *  Classe das Box
  *  
  */
function boxClass()
{
    this.alterarPagina = function(tipo)
    {
        /**
         *  Altera as notícias da lista para criar box.
         *  @param int tipo
         */  
        
        var pagina_atual = parseInt($("#bxl-page").val());
        var nova_pagina;
        switch(tipo)
        {
              // Adiciona uma página caso estejamos avançando.
              case 0: nova_pagina = pagina_atual +1; 
                      $("#bxl-page").attr("value",nova_pagina);
                      break;
              case 1: if(pagina_atual > 0)
                      {
                           nova_pagina = pagina_atual -1;   
                           $("#bxl-page").attr("value",nova_pagina); 
                      }
                      break;
        }
        
        // Realiza a requisição AJAX para puchar as novas notícias.
        $.ajax
       ({
       type: "POST",
       url: root+"/async/portal/",
       data: 
       {
          action: "bcr_page",
          page: nova_pagina,
          token: 1
       },
       success: function(data)
       {
           $(".box-layout-list").html(data);                
       }
       });
    }
    
    this.selectBox = function(tipo)
    {
       /**
        *  Seleciona uma das três alternativas de box e carrega a página de notícias.
        */
       
       var box_selecionada = parseInt($("#bc-selected-box").val());
       
       // Verifica se a box selecionada é a mesma que está sendo utilizada
       if(box_selecionada == tipo)
       {
          // Primeiro removemos a classe dos selecionados
          $("#choicebx-"+tipo).attr("class","box-choice");
          
          // Agora removemos as notícias
          $("#noticia-select-bx-wrap").html("<div class='choice-tip'>Selecione a caixa antes de escolher a notícia.</div>");
          
          // Paramos a ação.
          return;
                   
       }
       
       
        var i;
        
        // Altera a classe da div selecionada
        for(i=1;i<=3;i++)
        {
           if(i == tipo)
                 $("#choicebx-"+i).attr("class","box-choice-selected")
           else
                 $("#choicebx-"+i).attr("class","box-choice")
        }
        
        // Coloca o valor da div no textbox
        $("#bc-selected-box").attr("value",tipo);
        
        // Faz a requisição AJAX para chamar as notícias.
        $('#noticia-select-bx-wrap').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
        $.ajax
        ({
        type: "POST",
        url: root+"/async/portal/",
        data: 
        { 
           action: "bcr_loadnoticia",
           token: 1
        },
        success: function(data)
        {
            $("#noticia-select-bx-wrap").html(data);                
        }
        });
        
    }
    
    this.addBox = function(noticia)
    {
        /**
         *  Adiciona uma nova box
         *  @param int noticia
         */  
        
        var tipo_box;
        
        if(noticia == "")
        {
           showWarn("Notícia inválida.");
           return;                   
        }
        tipo_box = parseInt($("#bc-selected-box").val());
        
        if(tipo_box < 1 || tipo_box > 3)
        {
           showWarn("Você precisa selecionar uma box.");
           return;
        }
        $.ajax
        ({
        type: "POST",
        url: root+"/async/portal/",
        data: 
        { 
           action: "create_box",
           tipo: tipo_box,
           noticia: noticia,
           step: 2,
           token: 1
        },
        success: function(data)
        {
            var response = $(data).find("#response").html();
            var content = $(data).find("#ajax-content").html();
            if(response == '1')
            {
               $("#noticia-layout-bx-wrap").html(content);                    
            }
            else
            {
               showWarn(content);
               return;
            }
        }
        });       
    }
    
    this.deleteBox = function(id)
    {
        /**
         *  Deleta uma box a partir do ID
         *  @param int id
         */
        
        $.ajax
        ({
        type: "POST",
        url: root+"/async/portal/",
        data: 
        { 
           action: "delete_box",
           id: id,
           token: 1
        },
        success: function(data)
        {
            var response = $(data).find("#response").html();
            if(response == '1')
            {
               $("#box-mini-"+id).remove();                    
            }
            else
            {
               showWarn("Falha ao deletar");
               return;
            }
        }
        }); 
    }
}

/**
  *  Classe para autocomplete
  *  
  */
function acompleteClass()
{
    
    var object;
    var value;
    var id;
    
    this.abrirAutoComplete = function(object, id, value,callback)
    {
        /**
         *  Inícia a classe de auto complete
         *  @param DOM Object object
         *  @param string tipo
         *  @param int id
         *  @param string value
         *  @param function callback
         */
        
        
        this.id = id;
        this.object = object;
        this.value = value; 
        this.enviarQuery(callback);
        
    }
    
    this.enviarQuery = function(callback)
    {
        /**
         *  Faz a requisição AJAX e pega o xml de retorno.
         *  @param function callback
         */
        
        
        var id = this.id;
        var valor = this.value;
        var object = this.object;
        var t_nt;
         
        $.ajax
        ({
        type: "POST",
        url: root+"/async/autocomplete/",
        data: 
        { 
           action: "auto_complete",
           id: id,
           valor: valor,
           token: 1
        },
        success: function(json)
        {
          var n_valor = $("#"+object.id).val();
             
          if(valor != n_valor)
          {
              return;
          }
           
          json = eval(json);
          
          if(json == undefined)
          {
              $("#ac-list-"+object.id).remove();
              return;
          } 
          var width = object.offsetWidth;
          var height = object.offsetHeight;
          var top = object.offsetTop + height;
          var left = object.offsetLeft;
          if($("#ac-list-"+object.id).length == 0)
          {
              $("#"+object.id).after("<div class='autocomplete-bigidiv' id='ac-list-"+object.id+"' style=\"width: "+width+"px; \"></div>");
              $("#ac-list-"+object.id).css("top",top);
              $("#ac-list-"+object.id).css("left",left);
          }
          else
              $("#ac-list-"+object.id).html("");
          $("#"+object.id).bind("blur",function()
          {
              clearTimeout(t_nt);
              t_nt = setTimeout(function(){$("#ac-list-"+object.id).remove(); },1100);            
          })

          $("#ac-list-"+object.id).bind("mouseenter",function()
          {
              clearTimeout(t_nt);
          })
          $("#ac-list-"+object.id).bind("mouseleave",function()
          {
              clearTimeout(t_nt);
              t_nt = setTimeout(function(){$("#ac-list-"+object.id).remove(); },1100);
          })  
          
          var valores;
          var contador = 0;
          $.each(json,function(id,valores)
          {
              $(".autocomplete-bigidiv").append("<li valor='"+valores.valor+"' class='autocomplete-li' id='ac-list-li-"+object.id+"-"+contador+"' >"+valores.item+"</li>");
              $("#ac-list-li-"+object.id+"-"+contador).bind("click",function()
              {
                  var valor = $("#"+this.id).attr('valor'); 
                  var item = $("#"+this.id).html();
                  if(callback != undefined)
                  {
                       callback(item,valor);
                  }
                  else
                  {
                       $("#"+object.id).attr("value",valor);                  
                  }
                  $("#ac-list-"+object.id).remove();
              });
              contador++;
          })
        },
        error: function(e,m,s)
        {
            $("#ac-list-"+object.id).remove();
        }
            
        }); 
        
            
        
    }

}

/**
  *  Classe para gerenciar o evento
  *  
  */
function eventoClass()
{
      
    this.moreEventosVisitados = function()
    {
          /**
           *  Carrega mais eventos que serão visitados pelo usuário
           *  @param int id
           *  @param int step
           */
         
         
         var page = parseInt($("#mel-page").val());
         $.ajax
         ({
         type: "POST",
         url: root+"/async/events/",
         data: 
         { 
            action: "update_eventosvisitados",
            page: page,
            token: 1
         },
         success: function(data)
         {   
            data = eval("("+data+")");
            if(data.response != '1')
            {
                $(".mel-more").html("Nenhum Evento Encontrado");  
                return;
            }
             
            var i = 0; 
            var lista;
            $.each(data.itens,function(key,item)
            {
                i++;
                lista = "<a href='"+root+"/site/evento/"+item.id+"'> \n\
                <div class='me-list'> \n\
                <div class='mel-title'>"+item.nome+"</div> \n\
                <div class='mel-data'>"+item.data+"</div> \n\
                </div></a>"
                $("#mel-wrap").append(lista);
             
            })
            
            $("#mel-page").attr("value",page+i);
                 
             
         }
         });           
          
    }
    
    this.deletaEvento = function(step,id)
    {
          /**
           *  Deleta um evento a partir do id
           *  @param int id
           *  @param int step
           */
         
         
         $(".opc-box").fadeIn();
         $(".ajax-box-edit").html("<img src='"+root+"/imagens/site/loading.gif' alt='Loading' />").fadeIn();
         $.ajax
         ({
         type: "POST",
         url: root+"/async/events/",
         data: 
         { 
            action: "delete_evento",
            id: id,
            step: step,
            token: 1
         },
         success: function(data)
         {
             var response = $(data).find("#response").html();
             var content = $(data).find("#content").html();
             if(response == '1' && step == 1)
             {
                $(".opc-box,.ajax-box-edit").fadeOut();
                $("#evento-info-main-wrap").html(content);
                $("#side-list-"+id).remove();
                $("#boxev-"+id).remove();
                                  
             }
             else
             {
                 $(".ajax-box-edit").html(content);                   
             }
         }
         });           
          
    }
    
    this.deleteParticipante = function(evento,participante)
    {
        /**
         *  Deleta um participante.
         *  @param int evento
         *  @param int participante
         */
        
        $.ajax
         ({
         type: "POST",
         url: root+"/async/events/",
         data: 
         { 
            action: "delete_participante",
            id_e: evento,
            id_p: participante,
            token: 1
         },
         success: function(data)
         {
             var response = $(data).find("#response").html();
             if(response == '1')
             {
                $("#show-participantes-"+participante).remove();
                                  
             }
             else
             {
                 showWarn("Ocorreu um erro ao deletar o participante.");                
             }
         }
         });         
       
    }
    
    this.loadEdit = function(id,action)
    {
        /**
         *  Carrega o layout para editar um evento
         *  @param int id
         *  @param int action
         */
        
        var acao
        if(action == 0)
           acao = "load_edit";
       else
           acao = "load_create";
        
        $(".opc-box").fadeIn();
        $(".ajax-box-edit").html("<img src='"+root+"/imagens/site/loading.gif' alt='Loading' />").fadeIn();
        $.ajax
         ({
         type: "POST",
         url: root+"/async/events/",
         data: 
         { 
            action: acao,
            id: id,
            token: 1
         },
         success: function(data)
         {
             var content = $(data).find("#content").html();
             $(".ajax-box-edit").html(content);
         }
         });         
    }
    
    this.editaEvento = function()
    {
        /**
         *  Envia uma requisição AJAX para a interface editar o evento.
         *  
         */
      
      var id = $("#e-edit-id").val();
      var nome = $("#nome").val();
      var descricao = $("#descricao").val();
      var cidade = $("#cidade").val();
      var estado = $("#estado").val();
      var logradouro = $("#logradouro").val();
      var bairro = $("#bairro").val();
      var dia = $("#n-dia").val();
      var dia2 = document.getElementById("n-dia");
      var mes = $("#n-mes").val();
      var participantes = $("#participantes-add").val();
      var minuto = $("#e-minuto").val();
      var hora = $("#e-hora").val();
      var ano = $("#n-ano").val();
      var banner = $("#banner-e-envia").val();
      banner = banner.substr(banner.length-4);
      if(banner != "" && banner != ".jpg" && banner != ".png" && banner != ".gif")
      {
        $("#envia-error").html("<span class='side-tip'>Banner inválido.</span>");
         return; 
      }
      banner = banner.substr(banner.length-3);
      var genero = $("#genero-e").val();

      if(nome == "" || descricao == "" || cidade == "" || estado == "0" || logradouro == "" || bairro == "" || dia == "")
      {
         $("#envia-error").html("<span class='side-tip'>Dados necessários não foram preenchidos.</span>");
         return;
      }
    
      else if(validaParticipantes() == false)
      {
         $("#envia-error").html("<span class='side-tip'>Participantes Inválidos.</span>");
         return;

      }
      else if(validaDia(dia2) == false)
      {
         $("#envia-error").html("<span class='side-tip'>Dia Inválido</span>");
         return;
      }
    
      else{
         $("#envia-error").html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
         var param = new Array(nome, mes, dia, hora, minuto, descricao, logradouro, bairro, cidade, estado, banner, participantes,genero, ano);
         $.ajax({
          type: 'POST',
          url: root+"/async/events/",
          data: {
             id: id,
             action: "edit_evento",
             token: 1,
             param: param
          },
          success: function(data){
             data = eval("("+data+")");
             if(data.response == '1')
             {
                 // Sucesso, vamos apresentar a mensagem e recarregar a página de informações.
                 $("#envia-error").html("<span class='side-tip-ok'>Edição Completa</span>");
                 $("#evento-info-main-wrap").load(root+"/eventos/"+id+" #evento-info-main-wrap");
             }    
             else
             {
                 // Não foi possível editar o evento.
                 $("#envia-error").html("<span class='side-tip'>"+data.error+"</span>");
             }
          }
         })
      }
    }
    
    this.editaRede = function(obj)
    {
      /**
       *  Edita as informações das redes sociais no evento.
       *  @param object obj 
       */
      
      var rede = obj.id.split("-")[0];
      var rede_n; 
      var valor;
      
      // Coloca um id numérico nas redes.
      switch(rede)
      {
          case 'twitter': rede_n = 0; break;
          case 'facebook': rede_n = 1; break;
          case 'lastfm': rede_n = 2; break;
          case 'youtube': rede_n = 3; break;
          default: return;
      }
      
      // Pega o valor atual da rede
      valor = $("#"+obj.id).html();
      if(valor == "<span class=\"italic\">editar</span>" || valor == "<SPAN class=italic>editar</SPAN>")
          valor = "";  
      
      valor = htmlEncode(valor);
      
      
      
      // Gera a textbox
      $("#"+rede+"-ev-wrap").html("<input type='text' id='"+rede_n+"-rede-txt' class='rd-list-txt' value='"+valor+"' />");
      
      // Bindamos um evento
      $("#"+rede_n+"-rede-txt").bind("keyup",function(event)
      {
          var id;
          var valor;
          var rede;
          var this_id;
          
          if(event.keyCode != '13')
              return;
          
          // Atribuimos o id
          this_id = this.id;
          
          
          // Criamos Animação
          $("#"+this.id).attr("class","rd-list-txt2");
          
          // O enter foi pressionado, vamos enviar o valor.
          valor = this.value;
               
          
          // Pegamos o id da rede e transformamos em string novamente
          id = this.id.split("-")[0];
          switch(id)
          {
              case '0': rede = 'twitter'; break;
              case '1': rede = 'facebook'; break;
              case '2': rede = 'lastfm'; break;
              case '3': rede = 'youtube'; break;
          }
          
          // Pegamos o ID do evento
          id = $("#event-id").val();
          
          $.ajax({
          type: 'POST',
          url: root+"/async/events/",
          data: {
             id: id,
             action: "edit_rede",
             token: 1,
             rede: rede,
             valor: valor
          },
          success: function(data){
            
             var response = $(data).find('#response').html();
             var content = $(data).find('#content').html();
             if(response == '1')
             {
                 if(content == "")
                     content = "<span class=\"italic\">editar</span>";
                 $("#"+rede+"-ev-wrap").html("<div id='"+rede+"-ev-insert' class='edita-redes-ev' title='Clique para editar'>"+content+"</div>")
             }    
             else
             {
                 showWarn("Falha ao inserir campo.");
                 $("#"+this_id).attr("class","rd-list-txt");
             }
          }
         })
      })
    }
    
    this.validaAdicao = function()
    {
       /**
        *  Verifica informações antes de adicionar um novo evento.
        */
       
      var id = $("#e-edit-id").val();
      var nome = $("#nome").val();
      var descricao = $("#descricao").val();
      var cidade = $("#cidade").val();
      var estado = $("#estado").val();
      var logradouro = $("#logradouro").val();
      var bairro = $("#bairro").val();
      var dia = $("#n-dia").val();
      var dia2 = document.getElementById("n-dia");
      var mes = $("#n-mes").val();
      var participantes = $("#participantes-add").val();
      var minuto = $("#e-minuto").val();
      var hora = $("#e-hora").val();
      var banner = $("#banner-e-envia").val();
      banner = banner.substr(banner.length-4);
      if(banner != "" && banner != ".jpg" && banner != ".png" && banner != ".gif")
      {
        $("#envia-error").html("<span class='side-tip'>Banner inválido.</span>");
         return false; 
      }
      banner = banner.substr(banner.length-3);
      var genero = $("#genero-e").val();

      if(nome == "" || descricao == "" || cidade == "" || estado == "0" || logradouro == "" || bairro == "" || dia == "")
      {
         $("#envia-error").html("<span class='side-tip'>Dados necessários não foram preenchidos.</span>");
         return false;
      }
    
      else if(validaParticipantes() == false)
      {
         $("#envia-error").html("<span class='side-tip'>Participantes Inválidos.</span>");
         return false;

      }
      else if(validaDia(dia2) == false)
      {
         $("#envia-error").html("<span class='side-tip'>Dia Inválido</span>");
         return false;
      }       
       
    }
    
    this.carregaParticipantes = function(id,tipo,pagina)
    {
        /**
         *  Carrega a lista de usuários que participam ou visitam um evento.
         *  @param int id
         *  @param int tipo
         *  @param int pagina
         */
        
        $.ajax({
          type: 'POST',
          url: root+"/async/events/",
          data: {
             id: id,
             action: "carrega_membros",
             tipo: tipo,
             pagina: pagina,
             token: 1
          },
          success: function(data)
          {
              
          }
         })
        
        
        
    }
      
}

/**
  *  Classe para gerenciar músicas
  *  
  */
function musicaClass()
{
    this.validaForm = function()
    {
        /**
         *  Valida se as informações necessárias no formulário de música foram preenchidas corretamente.
         */
        
        var nome = $("#titulo").val();
        var genero = $("#genero").val();
        var mp3 = $("#mp3").val();
        
        nome = trim(nome);
        genero = trim(genero);
        
        if(nome == "")
        {
           $("#form-error").html("<strong class='side-tip'>Nome inválido.");  
           return false;
        }
        
        if(genero == "")
        {
           $("#form-error").html("<strong class='side-tip'>Gênero inválido.");  
           return false;
        }
        
        mp3 = mp3.substr(mp3.length-3);
        if(mp3 != "mp3")
        {
           $("#form-error").html("<strong class='side-tip'>Arquivo mp3 inválido..");  
           return false;
        }
        
        return true;
    }
    
    this.showIcons = function(id,event)
    {
        /**
         *  Mostra ou esconde os ícones das músicas
         *
         */
        
        if(event == "show")
            $('#music-show-info-' + id+',#music-show-del-' + id).css("display", "inline");
        else
            $('#music-show-info-' + id+',#music-show-del-' + id).css("display", "none");
            
    }
    
    this.autoLoadMusica = function()
    {
        /**
         *  Carrega automaticamente uma música a partir de uma URL
         *
         */
        
        var url;
        // Pega as informações da URL
        url = window.location;
        url = url.toString();
        url = url.split("#");
        if(url[1] != undefined && url[1] != "")
            this.loadMusica(url[1]);
    }
    
    this.loadMusica = function (id)
    {
        /**
         *  Carrega informações de uma música.
         *  @param int id
         */
        
        var root = document.getElementById("dir-root").value;
        $("#me-content").html("Carregando...");
        $.ajax({
          type: 'POST',
          url: root+"/async/music/",
          data: {
             id: id,
             action: "carrega_info",
             token: 1
          },
          success: function(data)
          {
             var content = $(data).find("#me-content").html();
             if(content == "")
             {
                $("#me-content").html("Música não encontrada.");
                
             }
             else
             {
                location.href = '#' + id;
                $("#me-content").html(content);   
             }
              
          }
         })
    }
    
    this.pesquisaMusica = function (novo)
    {
        /**
         *   Pesquisa uma música
         *   @param bool novo
         */  
        
        $('#loading-info').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
        var nome,genero,page;
        if(novo == true)
        {
              nome = $("#ms-nome-input").val();
              genero = $("#ms-genero-input").val();
              page = 0;
        }
        else
        {
              nome = $("#ms-nome").val();
              genero = $("#ms-genero").val();
              page = parseInt($("#ms-page-atual").val());
        }   
        
        $.ajax({
          type: 'POST',
          url: root+"/async/music/",
          data: {
             action: "pesquisa_musica",
             nome: nome,
             genero: genero,
             page: page,
             token: 1
          },
          success: function(data)
          {
             // Passa a data para objeto JSON
             $('#loading-info').html("");
             if(data == "" || data == undefined)
             {
                if(novo == true)
                {
                     $("#ms-response-content").html("<div class='music-search-notfound'>Nenhum resultado encontrado</div>"); 
                     $("#more-musicas").remove();
                }
                else
                     $("#more-musicas").html("Nenhuma música encontrada.");
                return;
             }
             
             $("#more-musicas").html("Mais");
             data = eval("("+data+")");
             if(novo == true)
             {
                 if($("#ms-nome-input").val() != nome || $("#ms-genero-input").val() != genero)
                       return;
                 
                 $("#more-musicas").remove();
                 $("#ms-response-content").html("");
                 // Recarrega o cabeçalho, caso seja uma pesquisa nova.
                 $("#ms-nome").attr("value",nome);
                 $("#ms-genero").attr("value",genero);
             }
             
             $.each(data,function(id,itens)
             {
                 var lista = "<a href='"+root+"/site/musica/"+itens.id+"'>\n\
                  <div class='mser-list-main'><div class='mser-list-nome'>"+itens.nome+"</div>\n\
                  <div class='mser-list-genero'>"+itens.genero+"</div>\n\
                  <div class='mser-list-artista'>"+itens.artista+"</div>\n\
                  <div class='mser-list-duracao'>"+itens.duracao+"</div>\n\
                  </div>\n\
                  </a>";
                 
                 $("#ms-response-content").append(lista);
             })
             
             if(novo == true)
             {
                 $("#music-search-content").append("<div id='more-musicas'>Mais</div>");  
             }
             
             var resposta_conta = data.length+page;
             $("#ms-page-atual").attr("value",resposta_conta);
             
          }
         })
        
        
        
        
          
    }
    
    this.editMusica = function(step,id,nome,genero,classificacao,privacidade,clipe)
    {
        /**
         *  Realiza a edição da música
         *  @param int step
         *  @param int id
         *  @param int nome
         *  @param int genero
         *  @param int classificacao
         *  @param int privacidade
         *  @param int clipe
         */
        
        if(step == 1)
        {
            /**
             *   Verifica se as informações são válidas
             *   
             */
            
            if(trim(nome) == "" || trim(genero) == "")
            {
                $("#form-error").html("Valores necessários não foram preenchidos.");
                return;
            }
        }
        else
        {
            $(".opc-box,.ajax-box-edit").fadeIn();
            $('.ajax-box-edit').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
        }
        
        $.ajax({
          type: 'POST',
          url: root+"/async/music/",
          data: {
             id: id,
             step: step,
             nome: nome,
             genero: genero,
             classificacao: classificacao,
             privacidade: privacidade,
             clipe: clipe,
             action: "edit_musica",
             token: 1
          },
          success: function(data)
          {
             switch(step)
             {
                 case 0:
                     var content = $(data).find("#form-content").html();
                     $(".ajax-box-edit").html(content);
                     break;
                 case 1:
                     data = eval("("+data+")");
                     if(data.response == '1')
                     {
                         $("#form-error").html("Edição completada");
                         var ms = new musicaClass();
                         ms.loadMusica(id);
                     }
                     else
                     {
                         $("#form-error").html(data.error);
                     }
                     break;
             }
              
          }
         })
    }
    
    this.deleteMusica = function(step,id)
    {
        /**
         *  Deleta uma música
         *  @param int step
         *  @param int id
         */
        
        $.ajax({
          type: 'POST',
          url: root+"/async/music/",
          data: {
             id: id,
             step: step,
             action: "delete_musica",
             token: 1
          },
          success: function(data)
          {
              var response = $(data).find("#response").html();
              if(response == '1')
              {
                  switch(step)
                  {
                      case 0: 
                          var content = $(data).find("#form-content").html();
                          $(".opc-box,.ajax-box-edit").fadeIn();
                          $(".ajax-box-edit").html(content);
                          break;
                      case 1:
                          $("#nem-bigdiv-"+id).remove();
                          var id_atual = $("#medit-id").val();
                          if(id_atual == id)
                          {
                              $("#me-content").html("<div class='ms-show-info'>A música que você estava visualizando foi deletada.</div>");
                          }
                          $(".opc-box,.ajax-box-edit").fadeOut();
                          break;
                  }
              }
              else
              {
                  showWarn("Falha ao deletar música."); 
              }
          }
         })
    }
    
    this.editLetra = function(step)
    {
        /**
         *  Função para editar a letra
         *  @param step
         *
         */
        
        switch(step)
        {
            case 0:
                var letras = $("#nobr-letras").html();
                $(".mec-letras").html("<textarea id='letras-edit'>"+letras+"</textarea><input type='button' id='music-btn-letras' value='Alterar' class='btn' >");
                break;
            case 1:
                var nova_letras = $("#letras-edit").val();
                var id = $("#medit-id").val();
                $.ajax({
                  type: 'POST',
                  url: root+"/async/music/",
                  data: {
                     id: id,
                     letras: nova_letras,
                     action: "edit_letras",
                     token: 1
                  },
                  success: function(data)
                  {
                     data = eval("("+data+")"); 
                     if(data.response == '1')
                     {
                         $(".mec-letras").html(data.letra);
                         $("#nobr-letras").html(data.letranobr);
                     }
                     else
                     {
                         showWarn("Falha ao alterar a letra.");
                     }

                  }
                 })
                break;
            
        }
        
    }
      
}

/**
  *  Classe da Playlost
  *  
  */
function playlistClass()
{
    var lista_musicas;
    var duracao;
    var tocadas;
    var timer;
    var id;
    var root = document.getElementById("dir-root").value;
    
    this.getId = function()
    {
       this.id = $("#playlist-id").val();
    }
    
    this.deletePlaylist = function(id)
    {
        /**
         *  Deleta a playlist
         *  @param int id
         * 
         */
        
        this.getId();
        var id_p = this.id
        
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "delete_playlist",
            id: id,
            token: 1
         },
         success: function(data)
         {
             var response = $(data).find("#response").html();
             if(response == '1')
             {
                $("#playlist-box-"+id).fadeOut(function(){ $("#playlist-box-"+id).remove(); });
                if(id_p == id)
                {
                    $("#playlist-listam-wrapper").html("<div class='playlist-no-playlist'>Nenhuma Playlist Selecionada</div>");
                }
                                  
             }
             else
             {
                 showWarn("Não foi possível deletar a playlist.");                
             }
         }
         }); 
        
    }
    
    this.deleteMusica = function(id)
    {
        /**
         *  Deleta uma música
         *  @param int id
         * 
         */
        
        this.getId();
        var id_p = this.id
        
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "delete_musica",
            id_p: id_p,
            id_m: id,
            token: 1
         },
         success: function(data)
         {
             var response = $(data).find("#response").html();
             if(response == '1')
             {
                $("#musica-lista-pl-"+id).fadeOut(function(){ $("#musica-lista-pl-"+id).remove(); });
             }
             else
             {
                 showWarn("Não foi possível deletar a música.");                
             }
         }
         }); 
        
    }
    
    this.addMusica = function(step,id_m,id_p)
    {
        /**
         *  Adiciona uma música
         *  @param int step
         *  @param int id_m
         *  @param int id_p
         * 
         */
                
        if(step != '0')
        { 
            $('.amp-left').html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
        }
        $(".ajax-box-playlist,.opc-box").fadeIn();
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "add_musica",
            step: step,
            id_p: id_p,
            id_m: id_m,
            token: 1
         },
         success: function(data)
         {
             switch(step)
             {
                 case 0:
                     $(".ajax-box-playlist").html(data);
                     break;
                 case 1:
                     $(".amp-left").html(data);
                     break;                     
                 case 2:
                     var response = $(data).find("#response").html();
                     var content = $(data).find("#content").html();
                     if(response == '1')
                     {
                         $(".amp-left").html(content);
                     }
                     else
                     {
                         $(".amp-left").html("<strong>Selecione a playlist</strong>");
                         showWarn(content);
                     }
             }
         }
         }); 
    }
    
    this.createPlaylist = function(step,id,musid)
    {
        /**
         *  Cria uma nova playlist
         *  @param int step
         *  @param int id
         *  @param int musid
         */
        
        var nome = ""
        var response;
        if(step == 1)
        {
            nome = $("#pc-nome").val();
        }
        $(".ajax-box-playlist,.opc-box").fadeIn();
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "add_playlist",
            step: step,
            id_p: id,
            id_m: musid,
            nome: nome,
            token: 1
         },
         success: function(data)
         {
             switch(step)
             {
                 case 0:
                     $(".ajax-box-playlist").html(data);
                     break;
                 case 1:
                     response = $(data).find("#response").html();
                     var pl_id = $(data).find("#pl-id").html();
                     if(response == '1')
                     {
                         $(".pc-ajax-wrap").animate({ 'margin-left': '-500'},250);
                         $("#id-play-tobanner").attr("value",parseInt(pl_id));
                     }
                     else
                     {
                         if(trim(nome) == "")
                            showWarn("Nome invalido");
                        else
                            showWarn("Falha ao criar playlist");
                     }
                     break;
                 case 2:
                     var id = $("#id-play-tobanner").val();
                     var musica = $("#music-id").val();
                     var imagem = $("#pc-imagem").val();
                     imagem = imagem.substr(imagem.length-4)
                     if(imagem != '.jpg' && imagem != '.png' && imagem != '.gif' && imagem != '')
                     {
                        showWarn("Imagem inválida.");
                        return false;
                     }
                     else
                     {
                        if(musica != null)
                        {
                            var pl = new playlistClass();                            
                            pl.addMusica(0,musica,"");
                            pl.addMusica(1,musica,id);
                        }
                        else
                        {
                            $("#lista-playlist-header").load(root+"/playlists/ #lista-playlist-header");
                            $(".ajax-box-playlist,.opc-box").fadeOut();
                        }
                           
                     }
                     break;
             }
         }
         }); 
    }
    
    this.rollPlaylist = function(obj)
    {
        /**
         *  Setas de rolagem para a playlist
         *  @param object btn
         */
       
       var margin = parseInt($("#lista-playlist-rollwrap").css("margin-left"));
       var width = document.getElementById("lp-b-rp").offsetWidth;
       var id = obj.id.split("-");       
       if(id[2] == "right")
       {
              margin = (margin*-1)+780;
              if(margin < (width))
              {                
                    $("#lista-playlist-rollwrap").animate({ 'margin-left': '-=95'},150);
              }
       }
       else
       {      margin +=790;
              if(margin < width && margin < 780)
              {
                    $("#lista-playlist-rollwrap").animate({ 'margin-left': '+=95'},150); 
              }
       }
    }
    
    this.replicaPlaylist = function(id)
    {
       /**
        *  Replica uma playlist
        *
        */
       
       $(".btn-reply-playlist").remove();
       $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "playlist_reply",
            id_p: id,
            token: 1
         },
         success: function(data)
         {
             var response = $(data).find("#response").html();
             var content = $(data).find("#error").html();
             if(response == '1')
             {
                 var box = $(data).find("#box").html();  
                 $("#reply-playlist").html("Playlist replicada com sucesso.");
                 $("#addnpl").after(box);
             }
             else
             {
                 showWarn(content);  
             }
         }
         });
    }
    
    this.trocarPrivacidade = function(id,privacidade)
    {
        /**
         *  Altera a privacidade de uma playlist
         *  @param int id
         *  @param int privacidade
         *
         */
        
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "playlist_privacidade",
            id_p: id,
            privacidade: privacidade,
            token: 1
         },
         success: function(data)
         {
             data = eval("("+data+")");
             if(data.response == '1')
             {
                 if(privacidade == '1')
                    $(".playlist-privacy").html("Playlist Privada (alterar)").attr("id","playlist-privacy-0");
                 else
                    $(".playlist-privacy").html("Playlist Pública (alterar)").attr("id","playlist-privacy-1");
             }
             else
             {
                 showWarn("Ocorreu um erro ao alterar a privacidade");  
             }
         }
         });
    }
    
    this.sortPlaylist = function(id,tipo)
    {
        /**
         * Organiza uma playlist
         * @param int tipo
         */
        
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "sort_playlist",
            id_p: id,
            tipo: tipo,
            token: 1
         },
         success: function(data)
         {
             data = eval("("+data+")");
             if(data.response != '1')
             {
                 showWarn(data.error);
                 return;
             }
             
             var musicas = new Array;
             // Não ocorreu nenhum erro ao tentar reorganizar a playlist.
             $.each(data.lista,function(i,id)
             {
                 musicas[i] = $("#musica-lista-pl-"+id).html();
                 $("#musica-lista-pl-"+id).fadeOut(function(){ $("#musica-lista-pl-"+id).remove(); });
             });
             
             $.each(musicas,function(i,html)
             {
                    // Criamos os elementos denovo, agora na nova ordem.
                    $("#ul-music-list").append("<li class='music-li-main' id='musica-lista-pl-"+data.lista[i]+"'>"+html+"</li>");
             });
         }
         });
    }
    
    /**
     *  Início das funções para a organização de playlist por Drag and Drop
     *
     */
    
    this.startDrag = function()
    {
        /**
         *  Adiciona o status de drag & drop em todas as listas 
         */
        
        
        $("#opm-list").slideUp();
        $("#ul-music-list" ).disableSelection();
        $("#true-wrap").append("<div id='end-sortof-playlist'>Arraste os itens da playlist para organizar. (Clique aqui para concluir)</div>")
        $("#ul-music-list" ).sortable();
        
    }       
    
    this.endDrag = function()
    {
        /**
         *  Finaliza o drag & drop e realiza o update das posições no db.
         */
        
        var positions = $("#ul-music-list").sortable('toArray');
        var id = $("#playlist-id").val();
        $("#ul-music-list").sortable('destroy');
        
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "ddsort_playlist",
            id_p: id,
            positions: positions,
            token: 1
         },
         success: function(data)
         {
             data = eval("("+data+")");
             if(data.response == '1')
             {
                 $("#end-sortof-playlist").fadeOut(function(){$("#end-sortof-playlist").remove()}); 
             }
             else
             {
                 showWarn("Erro: "+data.error);
             }
         }
         });
        
        
    }
        
            
    
    /**
     *  Início das funções de execução da playlist
     *
     */
    
    this.startPlaylist = function(id,mus_start)
    {
        /**
         *  Inícia uma playlista
         *  @param int id  [ ID da playlist para ser iniciada ]
         *  @param int mus_start [ ID da música para iniciar a playlist ]
         * 
         */
        
        // Texto informando carregamento
        $("#playlist-listam-wrapper").html("<div class='playlist-no-playlist'>Carregando Playlist...</div>");
        // Transforma os dois atributos em valores numéricos
        id = parseInt(id);
        mus_start = parseInt(mus_start);
        
        // Requisição AJAX para pegar informações do servidor.
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "start_playlist",
            id_p: id,
            id_m: mus_start,
            token: 1
         },
         success: function(data)
         {
             // Tivemos sucesso na requisição, vamos verificar se a playlist é válida.
             var response = $(data).find("#response").html();
             var musica = $(data).find("#musica").html();
             var content = $(data).find("#content").html(); // Todo o conteúdo da página da playlist
             var bar = $(data).find("#barra").html(); // Barra de informações
             
             if(response != '1')
             {
                showWarn("Ocorreu um erro ao carregar a playlist"); 
                return;
             }
             
             // A playlist foi aceita, vamos carregar as músicas.
             $("#true-wrap").append(bar);
             
             // Carrega o conteúdo necessário na página principal
             $("#playlist-body-wrapper").html(content);
             
             pt = new playlistClass();
             pt.loadPlayer(musica);
         }
         });        
    }
    
    this.trocaMusica = function(id_m)
    {
        /**
         *  Troca a música em uma playlist já ativa
         *  @param int id_m [ ID da música para ser trocada ]
         */
        
        var aleatorio; 
        
        if(id_m == "")
        {
            showWarn("Troca de música inválida.");
            return;
        }       
        
        if($("#is-aleatorio").is(":checked"))
        {
            aleatorio = '1';          
        }
        else
        {
            aleatorio = '0';
        }
        
        if(id_m == "next")
        {
            if(aleatorio == '0')
            {
                id_m = $("#nextm-ipt").val();                
                if(id_m == "")
                {
                    showWarn("Repetindo playlist.");
                }            
            }
            else
                id_m = "";
        }
        
        if(id_m == "back")
        {
            id_m = $("#backm-ipt").val();
            if(id_m == "")
            {
                showWarn("Não existem músicas anteriores.");
                return;
            }
            if(aleatorio == '1')
                id_m = "";
        }
        
        var id_p = $("#playlist-id").val();
        
        // Requisição AJAX para pegar informações do servidor.
        $.ajax
         ({
         type: "POST",
         url: root+"/async/playlist/",
         data: 
         { 
            action: "trocar_musica",
            id_p: id_p,
            id_m: id_m,
            aleatorio: aleatorio,
            token: 1
         },
         success: function(data)
         {
             // Pegamos as variáveis necessária para alterar a música
             
             var response = $(data).find("#response").html();
             var musica = $(data).find("#musica").html();
             var letra = $(data).find("#letra").html();
             var proxima = $(data).find("#proximo").html();
             var anterior = $(data).find("#anterior").html();
             var atual = $(data).find("#atual").html();
             
             // Verificamos se a resposta do servidor foi negativa.
             if(response != '1')
             {
                 showWarn("Falha ao alterar a música.");
                 return;
             }    
             
             // A música foi alterada com sucesso, vamos fazer as alterações básicas.
             
             // Altera letra
             $("#playlist-content-letras").html(letra);
             
             // Altera o player
             $("#playlist-content-player").fadeOut(function()
             {
                 pt = new playlistClass();
                 pt.loadPlayer(musica);
                 $("#playlist-content-player").fadeIn();
             })
             
             // Altera a lista de músicas da playlist
             $(".playlist-content-listamusicas-selected").attr("class","playlist-content-listamusicas");
             $("#"+musica+"-pcl").attr("class","playlist-content-listamusicas-selected");
             
             // Altera as informações das músicas, que vamos pegar via JSON
             proxima = eval("("+proxima+")");
             anterior = eval("("+anterior+")");
             atual = eval("("+atual+")");
             
             // Alteramos as informações da próxima música
             $("#pmus-nome").html(proxima.nome);
             $("#nextm-ipt").attr("value",proxima.id);
             
             // Alteramos as informações da música anterior
             $("#amus-nome").html(anterior.nome);
             $("#backm-ipt").attr("value",anterior.id);
             
             // Alteramos as informações da música atual
             $(".pbar-mtitle").html(atual.nome);
             $(".ma-genero").html(atual.genero);
             $(".ma-artista").html(atual.artista);
             
                 
         }
         });      
        
    }
    
    this.loadPlayer = function(id)
    {
        /**
         *  Carrega o player via javascript estático.
         *  Desculpem a pedreiragem, mas o IE está de sacanagem.
         *  @param int id [ id da música ]
         */
        

        var player_string;
        
        player_string = "<object type=\"application/x-shockwave-flash\" value=\"transparent\" data=\""+root+"/swf/music.swf?dir="+root+"/files/musicas/"+id+"&autoplay=true&playlist=1\" width=\"257\" height=\"130\" >"
        player_string += "<param name='wmode' value='transparent' />";
        player_string += "<param name='movie' value='"+root+"/swf/music.swf?dir="+root+"/files/musicas/"+id+"&autoplay=true&playlist=1' />";
        player_string += "<param name=\"allowScriptAccess\" value=\"always\"/>";
        player_string += "<embed allowScriptAccess=\"always\" src=\""+root+"/swf/music.swf?dir="+root+"/files/musicas/"+id+"&autoplay=true&playlist=1\" type=\"application/x-shockwave-flash\"  widt=\"257\" height=\"130\" wmode=\"transparent\" quality=\" \" />";
        player_string += "</object>";  
        $("#playlist-content-player").html(player_string);
          
    }
    
    this.aleatorioOn = function()
    {
        /**
         * Desabilita o nome da próxima música e da música anterior caso o aleatório esteja ligado.
         *  
         */
        
        if($("#is-aleatorio").is(":checked"))
        {
            $("#amus-nome,#pmus-nome").fadeOut();      
        }
        else
        {
            $("#amus-nome,#pmus-nome").fadeIn();
        }
    }
   

}

/**
  *  Classe dos feeds
  *  
  */
function feedClass()
{
      this.loadFeeds = function(is_feed)
      {
            /**
             *  Carrega o feed dos usuários via JSON.
             */
            
            var root = document.getElementById("dir-root").value;
            var first_feed = parseInt($("#fi-feed").val());
            var id = parseInt($("#user-profile-id").val());

            if(is_feed == true)
                  $("#more-full-feed").remove();
            else
                  $("#more-full-atualizacoes").remove();
            $.ajax
            ({
            type: "POST",
            url: root+"/async/feed/",
            data: 
            { 
               action: "load_feed",
               id_u: id,
               first_feed: first_feed,
               is_feed: is_feed,
               token: 1
            },
            success: function(data)
            {
                var box;
                data = eval("("+data+")");
                if(data.response == '1')
                {
                      $.each(data.feed,function(id, item)
                      {
                         box =  "<div class='feed-full-list2'>";
                         box += "<div class='ffl-imagem'>";
                         box += item.imagem_usuario;
                         box += "</div>";
                         box += "<div class='ffl-content'>";
                         box += "<a href='"+root+"/profile/"+item.login_usuario+"'><div class='ffl-title'>"+item.nome_usuario+"</div></a>";
                         box += item.escopo_box;
                         box += "</div>";
                         box += "<div class='ffl-date'>"+item.data_formatada+"</div>";
                         box += "<div class='clear'></div>";
                         box += "</div>";
                         $("#feed-list-true-wrap").append(box);
                      })
                      $("#fi-feed").attr("value",data.first_feed);
                      
                      if(is_feed == true)
                            $("#feed-list-true-wrap").after("<div id='more-full-feed'>Carregar mais atualizações</div>");
                      else
                            $("#feed-list-true-wrap").after("<div id='more-full-atualizacoes'>Carregar mais atualizações</div>");
                      
                }
                else
                {
                      // Ocorreu um erro ao carregar os feeds
                      if(is_feed == true)
                            $("#feed-list-true-wrap").after("<div id='more-full-feed'>Não foi possível carregar mais atualizações. (Tentar Novamente)</div>");
                      else
                            $("#feed-list-true-wrap").after("<div id='more-full-atualizacoes'>Não foi possível carregar mais atualizações. (Tentar Novamente)</div>");
                            
                }
            }
            });
        }
        
        this.newestFeed = function()
        {
            /**
             *  Procura por novos feeds.
             */
            
            var root = document.getElementById("dir-root").value;
            
            var id = parseInt($("#user-profile-id").val());
            var last_id = parseInt($("#lastf-value").val());
            var page = parseInt($("#pm-feed").val());
            
            $.ajax
            ({
            type: "POST",
            url: root+"/async/feed/",
            data: 
            { 
               action: "load_newest",
               id_u: id,
               last_id: last_id,
               token: 1
            },
            success: function(data)
            {
                setTimeout(function(){ var fc = new feedClass(); fc.newestFeed(); },16000)
                
                data = eval("("+data+")");
                
                if(data.response == '1')
                {
                    var last_id2 = parseInt(data.last_feed);
                    var count = parseInt(data.count_feeds);
                    var newest_atual = parseInt($("#nf-number").val())+count;
                    var string_warn;
                    
                    $("#nf-number").attr("value",newest_atual);
                    $("#lastf-value").attr("value",last_id2);
                    $("#pm-feed").attr("value",page+count);
                    
                    if(newest_atual == 1){ string_warn = "nova atualização."}
                    else { string_warn = "novas atualizações."}
                    
                    if(document.getElementById("newest-feed-warning") == null)
                    {
                         $("#nf-number").after("<div id='newest-feed-warning'>"+newest_atual+" "+string_warn+"</div>"); 
                         $("#newest-feed-warning").bind("click",function(){ var fc = new feedClass(); fc.showItens(); })
                    }
                    else
                    {
                         $("#newest-feed-warning").html(newest_atual+" "+string_warn);
                    }
                    
                    $("#newest-feed-content").prepend("<div id='newest-feeds-"+last_id+"'></div>");
                    var box;
                    $.each(data.feeds,function(id, item)
                    {
                         if(item.nome_usuario != null)
                         {
                         box =  "<div class='feed-full-list2'>";
                         box += "<div class='ffl-imagem'>";
                         box += item.imagem_usuario;
                         box += "</div>";
                         box += "<div class='ffl-content'>";
                         box += "<a href='"+root+"/profile/"+item.login_usuario+"'><div class='ffl-title'>"+item.nome_usuario+"</div></a>";
                         box += item.escopo_box;
                         box += "</div>";
                         box += "<div class='ffl-date'>"+item.data_formatada+"</div>";
                         box += "<div class='clear'></div>";
                         box += "</div>";
                         $("#newest-feeds-"+last_id).append(box);
                         }
                    })
                }
            }
            });
        }
        
        this.showItens = function()
        {
            /**
             * Mostra todos os novos feeds.
             * 
             */  
            
            $("#feed-list-true-wrap").prepend($("#newest-feed-content").html());
            $("#newest-feed-content").html("");
            $("#nf-number").attr("value",0);
            $("#newest-feed-warning").unbind("click").remove();
        }
        
        this.acompanhaUser = function()
        {
            /**
             *  Acompanha um usuário
             */
            
            var user_id = $("#user-profile-id").val();
            $.ajax
             ({
             type: "POST",
             url: root+"/async/feed/",
             data: 
             { 
                action: "acompanhar_user",
                user_id: user_id,
                token: 1
             },
             success: function(data)
             {
                 data = eval("("+data+")");
                 if(data.response == '1')
                 {
                     $("#acompanha-btn-1").attr("id","acompanha-btn-2");
                     $("#acompanha-btn-2").html("Acompanhando");
                 }
                 else
                 {
                     showWarn(data.error);
                 }
             }
             });
        }
        
        this.desacompanhaUser = function()
        {
            /**
             *  Acompanha um usuário
             */
            
            var user_id = $("#user-profile-id").val();
            $.ajax
             ({
             type: "POST",
             url: root+"/async/feed/",
             data: 
             { 
                action: "desacompanhar_user",
                user_id: user_id,
                token: 1
             },
             success: function(data)
             {
                 data = eval("("+data+")");
                 if(data.response == '1')
                 {
                     $("#acompanha-btn-2").attr("id","acompanha-btn-1");
                     $("#acompanha-btn-1").css("background","url(\"../imagens/site/acompanha_btn.png\") no-repeat");
                     $("#acompanha-btn-1").html("Acompanhar");
                 }
                 else
                 {
                     showWarn(data.error);
                 }
             }
             });
        }
        
        
      
      
      
}
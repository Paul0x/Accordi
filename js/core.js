/* Accordi - Nucleo JavaScript============================*/

$.ajaxSetup({
   type: 'POST',
   jsonp: null,
   jsonpCallback: null,
   data:
   {
      majax: true
   }
})

$(document).ready(function () {
   root = document.getElementById("dir-root").value;
   $('#cadastro-tipo1').click(function () {
      $('.cadastro-div-2').fadeOut("fast");
      $('#cadastro-escolha').fadeOut("fast");
      $('#cadastro-contratante').fadeOut("fast");
      $('#cadastro-artista').fadeIn("slow");
      $('.cadastro-div-2').fadeIn("slow");
      $('#CNPJ').value = "";
   });
   $('#cadastro-tipo2').click(function () {
      $('.cadastro-div-2').fadeOut("fast");
      $('#cadastro-artista').fadeOut("fast");
      $('#cadastro-escolha').fadeOut("fast");
      $('#cadastro-contratante').fadeIn("slow");
      $('.cadastro-div-2').fadeIn("slow");
      $('#CPF').value = "";
   });
   $('#tel').mask('(99)9999-9999');
   $('#telefone2').mask('(99)9999-9999');
   $('#duracao').mask('99:99');

   $('#login').blur(function () {
      a = validaUsername(this.value);
      if (a != true) {
         $("#login-error").html("<strong class='side-tip'>" + a + "</strong>");
      } else {
         $("#login-error").html("<strong class='side-tip-ok'>Ok</strong>");
      }
   }) 

   $("#tool-login,#tool-senha").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() {$("#"+id+"-tt").html("Coloque o mínimo de 6 caracteres.");});
   })
   
   $("#tool-tel").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() {$("#"+id+"-tt").html("Utilize o padrão: (XX)XXXX-XXXX");});
   })
   
   $("#tool-cpf").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() {$("#"+id+"-tt").html("Coloque apenas os 11 dígitos numéricos do seu CPF.");});
   })
   
   $("#tool-id").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() {$("#"+id+"-tt").html("Coloque seu CNPJ ou CPF. <br /> <b>CNPJ</b> 14 dígitos numéricos. <br /> <b>CPF</b> 11 dígitos numéricos.");});
   }) 
   
   $(".mer-add-pl").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-home-small",100,
       function() {$("#"+id+"-tt").html("Adicionar em uma playlist");});
   }) 
   
   $("#contato-btn-1").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() {$("#"+id+"-tt").html("Clique para enviar um pedido de contato.");});
   })
   
   $("#acompanha-btn-1").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() {$("#"+id+"-tt").html("Clique para assinar o feed do usuário.");});
   })
   
   $("#seta_1,#seta_2").live("mouseenter",function(){
       var id = this.id;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function() 
       {
             if(id == 'seta_2')
                   $("#"+id+"-tt").html("Mês anterior");
             else
                   $("#"+id+"-tt").html("Próximo mês");                   
             $("#seta_1,#seta_2").bind("click",function(){$("#"+id+"-tt").remove();})
       });
   })
    
   // Carregando Mapa
  
   if (document.getElementById('mapevento')) { 
      mapaEvent(1);
      mapaPega();
   } 
   if (document.getElementById('mapevento2')) { 
      mapaEvent(2);
      mapaPega();
   } 
    
   
   $('.botao-cadastro').click(function() {
      var login, senha, nome, email, tel, logd, bairro, cidade, estado, website , apelido , sobrenome , tipo;
      login = document.getElementById("login").value;
      senha = document.getElementById("senha").value;
      nome = document.getElementById("nome").value;
      email = document.getElementById("email").value;
      tel = document.getElementById("tel").value;
      logd = document.getElementById("logd").value;
      bairro = document.getElementById("bairro").value;
      cidade = document.getElementById("cidade").value;
      estado = document.getElementById("estado").value;
      
      if (login == "" || senha == "" || nome == "" || email == "" || tel == "") 
      {
         $("#requisito-necessario").html("<strong class='side-tip'>É necessário preencher todos os campos com *</strong>");
         return false;
      }
      if (validaSenha("") != true || validaUsername(login) != true || validaEmail(email) != true) 
      {
         $("#requisito-necessario").html("<strong class='side-tip'>Dados Inválidos</strong>");
         return false;
      }
      if (this.id == "botao-artista") 
      {
         sobrenome = document.getElementById("sobrenome").value;
         apelido = document.getElementById("apelido").value;
         tipo = 1
      } 
      
      else if (this.id == "botao-contratante") 
      {
         website = document.getElementById("website").value;
         tipo = 2
      }
      
      $(".ajax-box-edit").fadeIn();
      url = root + "/async/validate/";
      $.ajax({
         type: 'POST',
         url: url,
         data: {
            action: "cadastra_usuario",
            token: 1,
            login: login,
            senha: senha,
            nome: nome,
            email: email,
            tel: tel,
            logd: logd,
            bairro: bairro,
            cidade: cidade,
            estado: estado,
            website: website,
            apelido: apelido,
            sobrenome: sobrenome,
            tipo: tipo
         },
         success: function (data) {
             alert(data);
            data = eval("("+data+")");
            if (data.response == 1) {
               //window.location = root + "/login/&ac=csucess";
            } else {
               //window.location = root + "/login/&ac=falha";
            }
         },
         error: function () {
            showWarn("Não foi possível realizar seu cadastro.");
         }
      });
      $("#requisito-necessario").empty();
      return true;
   });
   

    
   $(".slideDown").click(function(){
      var a = this.id.split("-");
      var id = "#box-"+a[2]+"-"+a[3]+"-wrapper";
      if($(id).is(':visible')){
         $(id).slideUp("fast");
         setCookie("_"+id,"hide",60*60*60*60*60);
         $("#"+this.id).html("+");
      }
      if($(id).is(':hidden')){
         $(id).slideDown("fast");
         setCookie("_"+id,"visible",60*60*60*60*60);
         $("#"+this.id).html("-");
      }
            
   })
    
   
   $("#atualiza-sobe,#atualiza-desce").live("click",function()
   {
       var margin = parseInt($("#atualiza-slide-change").css("margin-top"));
       var height = document.getElementById("atualiza-slide-change").offsetHeight;
       var id = this.id.split("-");
       if(id[1] == "desce")
       {
              margin = (margin*-1)+215;
              if(margin < (height))
              {                
                    $("#atualiza-slide-change").animate({'margin-top': '-=90'},150);
              }
       }
       else
       {margin +=245;
              if(margin < height && margin < 245)
              {
                    $("#atualiza-slide-change").animate({'margin-top': '+=90'},150); 
              }
       }
   })
   
   
   $(".mem-bigdiv").live("hover", function () {
      var b = this.id.split("-");
      var ms = new musicaClass();
      ms.showIcons(b[2],"show");
   })
   
   $(".mem-bigdiv").live("mouseleave", function () {
    
      var b = this.id.split("-");
      var ms = new musicaClass();
      ms.showIcons(b[2],"hidden");     
     
   }) 
 
  $("#query,.search-overlay1").click(function()
  {
       $(".search-overlay1").css("display","none");
       $("#query").focus();
  })
  $("#query").blur(function()
  {
      if($("#query").val() == "")
          $(".search-overlay1").css("display","block");    
  })
   $("#query-form-busca,#query-form-busca2").submit(function()
   {
      var sq;
      if(this.id == "query-form-busca2")
         sq = $("#query-s").val();  
      else
         sq = $("#query").val();
      window.location=root+"/busca/&s="+sq;
      return false;
   })
}) 
$("#ajax-close").live("click",function(){
   $(".ajax-box-edit").fadeOut();
   $(".opc-box").fadeOut();
})


$("#e-s-button").live("click",function()
{
   $('#real-result-evento-wrap').html("");   
   $("#loading-more").html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
   var c = $("#input-search-e2").val();
   var g = $("#input-search-e3").val();
   var n = $("#input-search-e1").val();
   var np = 15;
   $("#page-query").attr("value",np);
   $("#cidade-query").attr("value",c);
   $("#genero-query").attr("value",g);
   $("#nome-query").attr("value",n);
   $.ajax({
      type: "POST",
      url: root+"/site/eventos",
      data: {
         cidade: c,
         genero: g,
         nome: n,
         page: 0
      },
      success: function(data){
         var content = $(data).find('#result-evento-wrap').html();
         $('#result-evento-wrap').html(content);   
         $("#loading-more").html("");
         $(".more-wrapper").html("<span id='more-events'>+ Mais</span>");
      }
   })   
})

$("#more-events").live("click",function()
{
   $("#loading-more").html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
   var p = parseInt($("#page-query").val());
   var c = $("#cidade-query").val();
   var g = $("#genero-query").val();
   var n = $("#nome-query").val();
   var np = p+15;
   $("#page-query").attr("value",np);
   $.ajax({
      type: "POST",
      url: root+"/site/eventos",
      data: {
         cidade: c,
         genero: g,
         nome: n,
         page: p
      },
      success: function(data){
         var content = $(data).find('#real-result-evento-wrap').html();
         var results = $(data).find('#result-n').html();
         $("#loading-more").html("");
         if(results == "0") {
            $(".more-wrapper").html("Não existe mais resultados.");
         }
         else $("#more-results-"+p).append("<div id='more-results-"+np+"'>"+content+"</div>");
      }
   })   
})


$("#comment-button,#comment-button2,#comment-button3,#comment-button4").live("click",function()
{
    var cm = new commentsClass;
    cm.getInfo();
    cm.addComment($("#text-comment-n").val());
    $("#text-comment-n").val("");
})

$(".del-comment,.del-comment2").live("click",function()
{
      var id = this.id.split("-")[1];   
      var cm = new commentsClass;
      cm.deleteComment(id);
})

$(".edit-comment,.edit-comment2").live("click",function()
{
      var cm = new commentsClass;
      cm.getInfo();
      cm.editComment(this);
})

$("#tudo-box-menu,#mus-box-menu,#eve-box-menu,#contra-box-menu,#com-box-menu,#plays-box-menu").live("click",function()
{
   $("#profile-mid-content").html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
   var mode;
   var id;
   id = $("#user-profile-id").val();
   switch(this.id)
   {
      case "eve-box-menu":
         mode = "eventos";
         $("#mode-page").attr("value",mode)
         break;
      case "mus-box-menu":
         mode = "musicas";
         $("#mode-page").attr("value",mode)
         break;
      case "contra-box-menu":
         mode="contatos";
         $("#mode-page").attr("value",mode)
         break;
      case "com-box-menu":
         mode="comentarios"
         $("#mode-page").attr("value",mode)
         break;
      case "plays-box-menu":
          mode="playlists";
          $("#mode-page").attr("playlists",mode);
   }
   $.ajax({
      type: "POST",
      url: root+"/async/profile",
      data: {
         action: "changeaba_profile",   
         aba: mode,
         id: id,
         token: 1
      },
      success: function(data){
         $("#profile-mid-content").html(data);
         return false;
      },
      error: function(data)
      {
        return true;    
      }
   })
   return false;
})

$('#email').live("blur",function () {
   a = validaEmail(this.value);
   if (a != true) {
      $("#email-error").html("<strong class='side-tip'>" + a + "</strong>");
   } else {
      $("#email-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
}) 
$('#email2').live("blur",function () {
   var b = document.getElementById("mid").value;
   a = validaEmail(this.value, b);
   if (a != true) {
      $("#email-error").html("<strong class='side-tip'>" + a + "</strong>");
   } else {
      $("#email-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
}) 
$('#tel').live("blur",function () {
   a = validaTelefone(this.value);
   if (a != true) {
      $("#telefone-error").html("<strong class='side-tip'>" + a + "</strong>");
   } else {
      $("#telefone-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
}) 
$('#nome').live("blur",function () {
   if (this.value == "") {
      $("#nome-error").html("<strong class='side-tip'>Digite um nome.</strong>");
   } else {
      $("#nome-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
}) 
$('#telefone2').live("blur",function () {
   a = validaTelefone2(this.value);
   if (a != true) {
      $("#telefone2-error").html("<strong class='side-tip'>" + a + "</strong>");
   } else {
      $("#telefone2-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
}) 
$("#n-dia").live("blur",function () {
   a = validaDia(this);
   if (a != true) {
      $("#dia-error").html("<strong class='side-tip'>Dia Inválido</strong>");
   } else {
      $("#dia-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
}) 
$("#form-edit-pessoal").live("submit",function() {
   var b = document.getElementById("mid").value;
   var email = document.getElementById("email2").value;
   var tel1 = document.getElementById("tel").value;
   var telefone2 = document.getElementById("telefone2").value;
   var dia = document.getElementById("n-dia");
   var nome = document.getElementById("nome").value;
   if (validaTelefone2(telefone2) == true && validaTelefone(tel1) == true && validaEmail(email, b) == true && validaDia(dia) == true && nome != "") {
      return true;
   } else {
      $("#submit-error").html("<div class='query-fail'>Você possui informações inválidas.</div>");
      return false;
   }
}) 
$("#old-pw").live("blur",function () {
   a = validaSenha(this.value);
   if (a != true || this.value == "") {
      $("#senha-error2").html("<strong class='side-tip'>Senha Antiga Inválida</strong>");
      return false;
   } else {
      $("#senha-error2").html("<strong class='side-tip-ok'>Ok</strong>");
      return true;
   }
}) 
$("#form-senha").live("submit",function () {
   var oldsenha = document.getElementById("old-pw").value;
   var nsenha = document.getElementById("senha").value;
   var nsenha2 = document.getElementById("senha-c").value;
   if (validaSenha(oldsenha) != true) {
      $("#senha-error2").html("<strong class='side-tip'>Senha Antiga Inválida</strong>");
      return false;
   }
   if (validaSenha("") != true) {
      valida = validaSenha("");
      $("#senha-edit-error").html("<strong class='side-tip'>" + valida + "</strong>");
      return false;
   }
   return true;
}) 

$("#edit-pw").live("click",function () {
   location.href = '#secu';
   $("html").css("overflow", "hidden");
   $(".opc-box").fadeIn();
   $("#edit-senha-box").fadeIn();
}) 
$("#fecha-senha,.opc-box").live("click",function () {
   $("html").css("overflow", "scroll");
   $(".opc-box").fadeOut();
   $("#edit-senha-box").fadeOut();
   $(".ajax-box-edit").fadeOut();
   $(".ajax-box-playlist").fadeOut();
   $(".login-hidden").fadeOut();
})
   
$('#senha,#senha-c').live("blur",function () {
   a = validaSenha("");
   if (a != true) {
      $("#senha-error").html("<strong class='side-tip'>" + a + "</strong>");
   } else {
      $("#senha-error").html("<strong class='side-tip-ok'>Ok</strong>");
   }
})
   
$('#participantes-add').live("blur",function(){
   validaParticipantes();
})

$('.aval-star,.aval-star2').live("hover",function()
{
var id;
var i;
id = this.id.split("-");
id = id[1];
for(i=1;i<=5;i++)
    {
       if(i<=id)
           $("#aval-"+i).attr("class","aval-star2");
       else
           $("#aval-"+i).attr("class","aval-star");
    }
})
$('.aval-star,.aval-star2').live("mouseleave",function()
{
  var trueaval = $("#aval-true").val();
  var i;
  for(i=1;i<=5;i++)
    {
       if(i<=trueaval)
           $("#aval-"+i).attr("class","aval-star2");
       else
           $("#aval-"+i).attr("class","aval-star");
    }  
})

$('.aval-star,.aval-star2').live("click",function()
{

    var url;
    var id;
    id = this.id.split("-");
    id = id[1];
    url=$("#music-id").val();
      $.ajax({
      type: "POST",
      url: root+"/site/musica/"+url,
      data: {
         mode: "aval",
         val: id,
         id: url
      },
      success: function(data){
         var content = $(data).find('#music-avaliacao-wrapper').html();
         $("#music-avaliacao-wrapper").html(content);
      } 
   })
})
$("#music-show-comments,#music-show-letra,#music-show-todas,#evento-show-p").live("click",function(){
  var thisid = this.id.split("-");
  var mode;
  var id;
  var url;
  var div;
  thisid = thisid[2];
  switch(thisid)
  {
      case "comments":
          mode = "comentario";
          id = $("#music-id").val();
          url = root+"/async/music/";
          div = "#music-sub-right";
          break;
      case "letra":
          mode = "letra";
          id = $("#music-id").val();
          url = root+"/async/music/";
          div = "#music-sub-right";
          break;
      case "todas":
          id = $("#music-id").val();
          url = root+"/async/music/";
          div = "#music-sub-right";
          mode = "";
          break;
      case "p":
          mode = "visitantes";
          id = $("#event-id").val();
          url = root+"/site/evento/"+id;
          div = "#evento-body-wrap";
  }
  $.ajax({
      type: "POST",
      url: url,
      data: {
         id: id,
         token: 1,
         action: "reload_page",
         mode: mode
      },
      success: function(data){
         var content = $(data).find(div).html();
         $(div).html(content);
      } 
   })
   return false;
})

$(".e-participar2").live("hover",function(){
  $(".e-participar2").html("Cancelar");
})
$(".e-participar2").live("mouseleave",function()
{
  $(".e-participar2").html("Participando");    
})
   $(".login-show").live("click",function(){
       ologin();
   })

$("#ranking-show").live("click",function()
{
      if($("#ranking-down-menu").is(":visible"))
      $("#ranking-down-menu").slideUp();
      else
      $("#ranking-down-menu").slideDown();
})

$(".calendario-space-n-small").live("hover",function(){
    var ttp = new toolTipClass();
    var idint = this.id;
    var id = this.id.split("-");
    ttp.toolTipCreator(this,"ajax-tt-box",100,
    function()
    {
        var content = $("#hide-e-c-"+id[3]).html();
        $("#"+idint+"-tt").html(content);
    
    });
})

$(".calendario-space-n").live("hover",function(){
    var ttp = new toolTipClass();
    var idint = this.id;
    var id = this.id.split("-");
    ttp.toolTipCreator(this,"ajax-tt-box",100,
    function()
    {
        var content = $("#hide-e-c-"+id[3]).html();
        $("#"+idint+"-tt").html(content);
    
    });
})


$("[rel=thumb_box]").live("mouseenter",function(){
    var ttp = new toolTipClass();
    var idint = this.id
    var id = this.id.split("-");
    var token = "1";
    var mode = "profile_info";
    ttp.toolTipCreator(this,"ajax-tt-box",100,
    function()
    {
      $.ajax({
      type: "POST",
      url: root+"/async/profile",
      data: {
         action: mode,
         id_u: id[2],
         token: token
      },
      success: function(data){
         $("#"+idint+"-tt").html(data);
      }
      
   })
    });
})

$(".e-participar,.e-participar2").live("click",function(){
    $("#btn-p-wrap").html("<img src='" + root + "/imagens/site/loading.gif' alt='loading'>");
    var eid = $("#event-id").val();
    var classe = this.id.split("-");
    classe = classe[0];
    var tipo;
    
    if(classe == "participar")
        tipo = 0;
    else if(classe == "pcd")
        tipo = 1;
    $.ajax({
        type: "POST",
        url: root+"/site/evento/"+eid,
        data: {
            mode: "pm",
            eid: eid,
            tipo: tipo
        },
        success: function(data)
        {
          var r = $(data).find("#response").html()
          if(r == 2)
              {
                  ologin();
                  $("#btn-p-wrap").html("<div class='e-participar' id='participar-"+eid+"'>Participar</div>");
              }
          else
              {
              var content = $(data).find('#evento-head-wrap').html();
              $("#evento-head-wrap").html(content);
              }
        }
    })
})

$("#more-comments").live("click",function(){
  var cm = new commentsClass();
  cm.getInfo();
  cm.showComments($("#comentario-page").val(),$("#comentario-count").val());
})

$("#bci-termos,#bci-descri").live("click",function(){
      
    var contato = new contatoClass();
    switch(this.id)
    {
          case "bci-termos":
                if($("#hide-termos").is(":hidden"))
                      contato.showTermos();
                else
                      contato.hideDiv();
                break;
          case "bci-descri":
                if($("#hide-descricao").is(":hidden"))
                      contato.showDescricao();
                else
                      contato.hideDiv();
                break;
    }
})

$("#contato-btn-1,#contato-btn-2").live("click",function()
{ 
   var contato = new contatoClass();
   var tipo = this.id.split("-")
   contato.startContato(0);
})

$("#contato-next-step-1,#contato-next-step-2,#contato-next-step-4,#contato-next-step-5").live("click",function(){
   var step = this.id.split("-");
   var contato = new contatoClass();
   contato.startContato(step[3]);
})

$("#contato-next-step-3").live("click",function()
{
    var assunto = $("#cf-assunto").val();
    var dia = $("#cf-dia").val();
    var mes = $("#cf-mes").val();
    var ano = $("#cf-ano").val();
    var valor = $("#cf-valor").val();
    var descricao = $("#cf-descricao").val();
    var contato = new contatoClass();
    contato.createContato(assunto,dia,mes,ano,valor,descricao);    
})

$("#contato-close-back").live("click",function()
{
    var assunto = $("#old-assunto").val();
    var valor = $("#old-valor").val();
    var data = $("#old-data").val();
    var descricao = $("#old-descricao").val();
    var contato = new contatoClass();
    contato.contatoBack(assunto,data,descricao,valor);
})

$("#contato-close").live("click",function(){
    $(".ajax-box-contato").fadeOut();   
})

$("#calendario-next,#calendario-back,#calendario-next2,#calendario-back2").live("click",function()
{
    var mes = $("#mes-atual").val();
    var ano = $("#ano-atual").val();
    var tipo = "profile";
    mes = parseInt(mes);
    ano = parseInt(ano);

    switch(this.id)
    {
        case "calendario-next":
        case "calendario-next2":
            if(mes == 12)
            {
                ano = ano+1;
                mes = 1;
            }
            else
                mes = mes+1; 
            break;
        case "calendario-back":
        case "calendario-back2":
            if(mes == 1)
            {
                ano = ano-1;
                mes = 12;
            }            
            else
                mes = mes-1;
            break;
    }
    
    if(this.id == "calendario-next2" || this.id == "calendario-back2")
        tipo = "portal";
    
    $.ajax
    ({
    type: "POST",
    url: root+"/async/"+tipo+"/",
    data: 
    {
       action: "eventos_mes",
       mes: mes,
       ano: ano,
       token: 1
    },
    success: function(data)
    {
       $("#c-box").html(data);
    }
    })
})
$('#btn-c-green, #btn-c-red').live("click", function(){
    var id = $('#contato-id').val();
    var status = this.id.split("-");
    switch(status[2])
    {
          case 'green':
                status = 0;
                break;
          case 'red':
                status = 1;
                break;          
    }
    $.ajax
    ({
    type: "POST",
    url: root+"/async/contato/",
    data: 
    {
       action: "update_status",
       id: id,
       status: status,
       token: 1
    },
    success: function(data)
    {
       $('.bci-aceita').slideUp();
       if(status == 0)
       {
            $('#status-change').html("<span class = 'color-green'>Atuante</span>");
       }
      else
          {
            $('#status-change').html("<span class = 'color-red'>Fechado</span>");
            $('#mci-main').remove();
            $("#bci-cancel").remove();
          }
      }
    })

});
$('#termos-edit').live("click", function(){
    $('#exibe-termos').html("<textarea id = 'txt-edit-termos'></textarea><input type = 'button' \n\
id = 'btn-edita-termos' class = 'btn' value = 'Salvar' />");
    var termos = $('#termos-hidden').html();
    $('#txt-edit-termos').attr("value",termos);
});

$('#btn-edita-termos').live("click",function(){
      var termos = $('#txt-edit-termos').val();
      $.ajax({
          type: "POST",
          url: root+"/async/contato/",
          data: 
          {
             action: "update_termos",
             termos: termos,
             token: 1
          },
          success: function(data)
          {
               data = $(data).html();
               $("#exibe-termos").html("<div id='termos-edit'>"+data+"</div>");
               $("#termos-hidden").html(termos);
          }
      });      
});

$("#ex-btn-edit").live("click",function()
{
    var idade = $("#ex-idade-min").val();
    var cidade = $("#ex-cidade").val();
    var estado = $("#ex-estado").val();
    var pagamento = $("#ex-pagamento").val();
    var descricao = $("#ex-descricao").val();
    $.ajax({
          type: "POST",
          url: root+"/async/contato/",
          data: 
          {
             action: "update_exigencias",
             idade: idade,
             cidade: cidade,
             estado: estado,
             pagamento: pagamento,
             descricao: descricao,
             token: 1
          },
          success: function(data)
          {
               data = $(data).html();
               $("#ex-response").html(data);
          }
      });

})

$("#nt-1").live("click",function()
{
    if($("#drop-down-notifications").is(":visible"))
    {
       $("#drop-down-notifications").slideUp('fast');   
    }
    else
    {
    $.ajax({
          type: "POST",
          url: root+"/async/contato/",
          data: 
          {
             action: "show_notificacoes",
             token: 1
          },
          success: function(data)
          {
               $("#drop-down-notifications").html(data);
               $("#drop-down-notifications").slideDown('fast'); 
          }
      });  
    }
})

$("#curriculum-new").live("click",function()
{
   c = new curriculumClass();
   c.newCurriculum();
})  
$(".c-event-edit").live("click",function()
{
   c = new curriculumClass();
   c.startEdit(this);
})
$("#del-curriculum").live("click",function()
{
   c = new curriculumClass();
   c.getId();
   c.deleteCurriculum();
})

$("#atual-curriculum").live("click",function()
{
   c = new curriculumClass();
   c.getId();
   c.atualizaCurriculum();
})

$("#ci-list-btn-musica").live("click",function()
{
   var item = $("#music-list-curriculum-add").val();
   c = new curriculumClass();
   c.getId();
   c.addItemLista(0,item);
})   

$("#ci-list-btn-referencia").live("click",function()
{
    var item = $("#referencias-list-curriculum-add").val();
    c = new curriculumClass();
    c.getId();
    c.addItemLista(2,item);
})
$("#ci-list-btn-contato").live("click",function()
{
   var item = $("#contato-list-curriculum-add2").val();  
   var tipo = $("#contato-list-curriculum-add1").val();
   item = Array(item,tipo);
   c = new curriculumClass();
   c.getId();
   c.addItemLista(1,item);
})
      
$(".ci-del-list").live("click",function()
{
    var id = this.id.split("-");
    var pos = id[2];
    var tipo = id[1]
    c = new curriculumClass();
    c.getId();
    c.delItemLista(tipo,pos);
})

$("#bci-cancel,#bci-cancel2").live("click",function()
{
    var step = 0;
    if(this.id == "bci-cancel2")
        step = 1;
    c = new contatoClass();
    c.closeContato(step);
})

$("#btn-nova-noticia").live("click",function()
{
   var pt = new portalClass();
   pt.novaNoticia();   
})

$("#btn-gerencia-portal").live("click",function()
{
   var pt = new portalClass();
   pt.boxCreate(1,"",""); 
})



$("#noticia-delete,#noticia-delete2").live("click",function()
{
   var id = $("#noticia-id").val();
   var step = 0;
   if(this.id == "noticia-delete2")
       step = 1;
   var pt = new portalClass();
   pt.deletaNoticia(id,step);
})

$("#noticia-edit").live("click",function()
{
   var id = $("#noticia-id").val();
   var pt = new portalClass();
   pt.editNoticia(0,id);
})
$("#edit-noticia-btn").live("click",function()
{
   var id = $("#noticia-id").val();
   var pt = new portalClass();
   pt.editNoticia(1,id);
})
$("#pr-search-button").live("click",function()
{
   pesquisaNoticia();
})
$("#pr-search-input").live("keyup",function(event)
{
    if(event.keyCode == 13)
        pesquisaNoticia();    
})
$("#bln-back,#bln-next").live("click",function()
{
    var bx = new boxClass();
    var tipo = 0
    if(this.id == "bln-back")
          tipo = 1;
    
    bx.alterarPagina(tipo);
})
$("#choicebx-1,#choicebx-2,#choicebx-3").live("click",function()
{
    id = this.id.split("-");
    switch(id[1])
    {
       case '1':tipo = 1;break;
       case '2':tipo = 2;break;
       case '3':tipo = 3;break;
    }
    
    bx = new boxClass();
    bx.selectBox(tipo);
})
$(".box-layout-list-li").live("click",function()
{
    var noticia = this.id.split("-");
    var bx = new boxClass();
    bx.addBox(noticia[2]);
})

$(".delete-box-mini").live("click",function()
{
   var id = this.id.split("-");
   var bx = new boxClass();
   bx.deleteBox(id[1]);
})

$("#genero,#genero-e").live("keyup",function()
{
    var ac = new acompleteClass();
    var value = this.value;
    ac.abrirAutoComplete(this, 1, value)
});

$("#ms-genero-input").live("keyup",function()
{
    var ac = new acompleteClass();
    var value = this.value;
    ac.abrirAutoComplete(this, 6, value)
});

$("#input-search-e1").live("keyup",function()
{
    var ac = new acompleteClass();
    var value = this.value;
    ac.abrirAutoComplete(this,2,value);
});

$("#input-search-e2").live("keyup",function()
{
    var ac = new acompleteClass();
    var value = this.value;
    ac.abrirAutoComplete(this,4,value);
});

$("#input-search-e3").live("keyup",function()
{
    var ac = new acompleteClass();
    var value = this.value;
    ac.abrirAutoComplete(this,5,value);
});

$("#pr-search-input").live("keyup",function()
{
    var ac = new acompleteClass();
    var value = this.value;
    ac.abrirAutoComplete(this,3,value,function(item,valor)
    {     
        window.location = root + "/portal/listn/"+item;  
    });
});

$(".e-show-del,.e-show-del2").live("click",function()
{
    var ev = new eventoClass();
    var id = this.id.split("-");
    ev.deletaEvento(0,id[1]);
});

$("#dbuttonacao").live("click",function()
{
    var ev = new eventoClass();
    var id = $("#evento-d-id").val();
    ev.deletaEvento(1,id);    
})

$(".delparticipante").live("click",function()
{
    var ev = new eventoClass();
    var participante = this.id.split('-');
    var evento = $(".wrap-lista-membros").attr("id");
    ev.deleteParticipante(evento,participante[1]);      
})

$(".e-show-edit").live("click",function()
{
    var ev = new eventoClass();
    var id = this.id.split("-")[1];
    ev.loadEdit(id,0);
})

$("#editbuttone").live("click",function()
{
    var ev = new eventoClass();
    ev.editaEvento();
})

$(".edita-redes-ev").live("click",function()
{
    var ev = new eventoClass();
    ev.editaRede(this);
})

$("#envia-evento").live("submit",function()
{
    var ev = new eventoClass();
    var a = ev.validaAdicao();
    if(a == false)
        return false;
    else
        return true;
})

$("#create-ev").live("click",function()
{
    var ev = new eventoClass();
    ev.loadEdit(0,1);
})

$("#add-musica").live("submit",function()
{
    var ms = new musicaClass();
    if(ms.validaForm() == false)
      return false
    else
      return true;
})

$(".del-playlist1").live("click",function()
{
    var id = this.id.split("-");
    var pl = new playlistClass();
    pl.deletePlaylist(id[1]);
})

$("#seta-playlist-left,#seta-playlist-right").live("click",function()
{
    var pl = new playlistClass();
    pl.rollPlaylist(this);
})

$(".mlm-delete").live("click",function()
{
    var id = this.id.split("-");
    var pl = new playlistClass();
    pl.deleteMusica(id[1]);
})

$(".btn-playlist").live("click",function()
{
    var id = this.id.split("-");
    var pl = new playlistClass();
    pl.addMusica(0,id[1],"");
})

$(".amp-list-playlist").live("click",function()
{
    var ms = $("#music-id-add-playlist").val();
    var play = this.id.split("-");
    var pl = new playlistClass();
    pl.addMusica(1,ms,play[1]);
})

$(".mer-add-pl").live("click",function()
{
    $(".ajax-box-playlist").remove();
    $("#true-wrap").append("<div class='ajax-box-playlist'></div>");
    var pl = new playlistClass();
    var musica = this.id.split("-");
    pl.addMusica(0,musica[2],"");
})

$(".btn-add-ms-confirma").live("click",function()
{
    var ms = $("#music-id-add-playlist").val();
    var play = this.id.split("-");
    var pl = new playlistClass();
    pl.addMusica(2,ms,play[1]);
})

$(".btn-add-playlist-new,#addnpl").live("click",function()
{
    var pl = new playlistClass();
    pl.createPlaylist(0,"","");
})

$("#pc-input-stp1").live("click",function()
{
    var pl = new playlistClass();
    pl.createPlaylist(1,"","");
})

$("#musica-add-image").live("submit",function()
{
    var pl = new playlistClass();
    pl.createPlaylist(2,"","");
})

$(".music-li-main").live("mouseenter",function()
{
    var id = this.id.split("-");
    $("#start-m-"+id[3]).fadeIn('fast');
})

$(".music-li-main").live("mouseleave",function()
{
    var id = this.id.split("-");
    $("#start-m-"+id[3]).fadeOut('fast');
})

$(".start-playlist").live("click",function()
{
    var id = $("#playlist-id").val();
    var mus_start = this.id.split("-");
    var pl = new playlistClass();
    pl.startPlaylist(id,mus_start[2]);
})

$(".playlist-content-listamusicas").live("click",function()
{
    var id = this.id.split("-");
    var pl = new playlistClass();
    pl.trocaMusica(id[0]);
})

$("#pbar-next,#pbar-back").live("click",function()
{
    var id = this.id;
    var pl = new playlistClass();
    if(id == 'pbar-next')
        pl.trocaMusica("next");
    else
        pl.trocaMusica("back");
})

function proximaMusica()
{
    var pl = new playlistClass();
    pl.trocaMusica("next");
}

$("#is-aleatorio").live("change",function()
{
    var pl = new playlistClass();
    pl.aleatorioOn();
    
})

$(".hide-playlist").live("click",function()
{
   if($("#lph-wrap").is(":visible"))
   {
        $("#lph-wrap").css("display","none");
        $(".hide-playlist").html("Mostrar suas playlists");
   }
   else
   {
        $("#lph-wrap").css("display","block");
        $(".hide-playlist").html("Esconder");
   }     
})


$("#fb-ev-logo,#twt-ev-logo,#yt-ev-logo,#lm-ev-logo").live("mouseenter",function(){
       var id = this.id;
       var id2 = id.split("-");
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-cadastro",100,
       function()  {$("#"+id+"-tt").html($("#"+id2[0]+"-site").html());});
   })
   
$(".btn-reply-playlist  ").live("click",function()
{
    var pl = new playlistClass();
    var id = this.id.split("-");
    pl.replicaPlaylist(id[2]);
      
})

$(".playlist-privacy").live("click",function()
{
    var pl = new playlistClass();
    var id = $("#playlist-id").val();
    var privacidade = this.id.split("-");
    pl.trocarPrivacidade(id,privacidade[2]);
      
})

$(".mem-link").live("click",function()
{
    var ms = new musicaClass();
    var id = this.id.split("-");
    ms.loadMusica(id[1]);
      
})

$(".music-show-info,.ms-edit-aba2").live("click",function()
{
    var ms = new musicaClass();
    var id = this.id.split("-");
    ms.editMusica(0,id[3]);
})

$("#music-btn-editar").live("click",function()
{
    var ms = new musicaClass();
    
    // Pega as informações
    var id = $(".form-edit").attr("id").split("-");
    var nome = $("#titulo").val();
    var genero = $("#genero").val();
    var classificacao = $("#classificacao").val();
    var clipe = $("#clipe").val();
    var permissao;
    if (($("#p-musica-1").is(':checked')) == true) permissao = 0;
    else if (($("#p-musica-2").is(':checked')) == true) permissao = 1;
    
    ms.editMusica(1,id[2],nome,genero,classificacao,permissao,clipe);
})

$(".music-show-del,.ms-del-aba2").live("click",function()
{
    var ms = new musicaClass();
    var id = this.id.split("-");
    ms.deleteMusica(0,id[3]);
})

$("#music-btn-deletar").live("click",function()
{
    var ms = new musicaClass();
    var id = $(".form-edit").attr("id").split("-");
    ms.deleteMusica(1,id[2]);
})

$("#edit-letras").live("click",function()
{
    var ms = new musicaClass()
    ms.editLetra(0);
})

$("#music-btn-letras").live("click",function()
{
    var ms = new musicaClass()
    ms.editLetra(1);
})

$("#more-musicas,#search-ms").live("click",function()
{
     var ms = new musicaClass()
     if(this.id == "search-ms") ms.pesquisaMusica(true);
     else ms.pesquisaMusica(false);
})

$("#opm-main").live("click",function()
{
     if($("#opm-list").is(":hidden") == true)
           $("#opm-list").slideDown();
     else
           $("#opm-list").slideUp();
})

$("#opm-alfabeto,#opm-genero").live("click",function()
{
    var tipo;
    var id = $("#playlist-id").val();
    if(this.id == 'opm-alfabeto')
        tipo = 1;
    else
        tipo = 2;
    
    var pl = new playlistClass();
    pl.sortPlaylist(id,tipo)

});

$("#opm-personalizada").live("click",function()
{
    var pl = new playlistClass();
    pl.startDrag();
})

$(".mel-more").live("click",function()
{
    var ev = new eventoClass();
    ev.moreEventosVisitados();
})

$("#end-sortof-playlist").live("click",function()
{
    var pl = new playlistClass();
    pl.endDrag();
})

$(".mer-li").live("mouseenter",function()
{
    var id = this.id.split("-");
    $("#mer-add-"+id[1]).fadeIn();
})


$(".mer-li").live("mouseleave",function()
{
    var id = this.id.split("-");
    $("#mer-add-"+id[1]).fadeOut();
})

$("#more-full-feed").live("click",function()
{
      var fc = new feedClass();
      fc.loadFeeds(true);     
})

$("#more-full-atualizacoes").live("click",function()
{
      var fc = new feedClass();
      fc.loadFeeds(false);     
})

$(".prf-pl-list").live("mouseenter",function(){
       var id = this.id;
       var obj = this;
       tt = new toolTipClass();
       tt.toolTipCreator(this,"tip-home-small",100,
       function() { 
          var pl_id = id.split("-");
          
          if(document.getElementById("pr-hidden-minfo-"+id) == null)
          {
              $.ajax({
              type: "POST",
              url: root+"/async/playlist/",
              data: 
              {
                 action: "show_musicas_tip",
                 id: pl_id[2],
                 token: 1
              },
              success: function(data)
              {
                  data = eval("("+data+")");

                  if(data.response != '1')
                  {
                      tt.toolTipDestroyer(obj,90);  
                      return;
                  }

                  $("body").append("<div id='pr-hidden-minfo-"+id+"' class='hidden'><b>Músicas na Playlist</b><br /><p></div>");
                  $.each(data.musicas,function(asd,item)
                  {
                      $("#pr-hidden-minfo-"+id).append(item.nome+"<br />");
                  })
                  $("#pr-hidden-minfo-"+id).append("</p>");

                  $("#"+id+"-tt").html($("#pr-hidden-minfo-"+id).html());  
              }
              });
          }
          else
          {
             $("#"+id+"-tt").html($("#pr-hidden-minfo-"+id).html());
          }
   
        });
   }) 
   
$("#acompanha-btn-2").live("hover",function()
{ 
    $("#acompanha-btn-2").css("background","url(\"../imagens/site/acompanha_btn2.png\") no-repeat");
    $("#acompanha-btn-2").html("Desacompanhar");
})

$("#acompanha-btn-2").live("mouseleave",function()
{ 
    $("#acompanha-btn-2").css("background","url(\"../imagens/site/acompanha_btn.png\") no-repeat");
    $("#acompanha-btn-2").html("Acompanhando");
})
$("#acompanha-btn-1").live("click",function()
{
    var fd = new feedClass();
    fd.acompanhaUser();
})
$("#acompanha-btn-2").live("click",function()
{
    var fd = new feedClass();
    fd.desacompanhaUser();
})

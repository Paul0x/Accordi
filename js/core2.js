$.ajaxSetup({type:"POST",jsonp:null,jsonpCallback:null,data:{majax:!0}});
$(document).ready(function(){root=document.getElementById("dir-root").value;$("#cadastro-tipo1").click(function(){$(".cadastro-div-2").fadeOut("fast");$("#cadastro-escolha").fadeOut("fast");$("#cadastro-contratante").fadeOut("fast");$("#cadastro-artista").fadeIn("slow");$(".cadastro-div-2").fadeIn("slow");$("#CNPJ").value=""});$("#cadastro-tipo2").click(function(){$(".cadastro-div-2").fadeOut("fast");$("#cadastro-artista").fadeOut("fast");$("#cadastro-escolha").fadeOut("fast");$("#cadastro-contratante").fadeIn("slow");
$(".cadastro-div-2").fadeIn("slow");$("#CPF").value=""});$("#tel").mask("(99)9999-9999");$("#telefone2").mask("(99)9999-9999");$("#duracao").mask("99:99");$("#login").blur(function(){a=validaUsername(this.value);a!=!0?$("#login-error").html("<strong class='side-tip'>"+a+"</strong>"):$("#login-error").html("<strong class='side-tip-ok'>Ok</strong>")});$("#tool-login,#tool-senha").live("mouseenter",function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+
b+"-tt").html("Coloque o m\u00ednimo de 6 caracteres.")})});$("#tool-tel").live("mouseenter",function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+b+"-tt").html("Utilize o padr\u00e3o: (XX)XXXX-XXXX")})});$("#tool-cpf").live("mouseenter",function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+b+"-tt").html("Coloque apenas os 11 d\u00edgitos num\u00e9ricos do seu CPF.")})});$("#tool-id").live("mouseenter",
function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+b+"-tt").html("Coloque seu CNPJ ou CPF. <br /> <b>CNPJ</b> 14 d\u00edgitos num\u00e9ricos. <br /> <b>CPF</b> 11 d\u00edgitos num\u00e9ricos.")})});$(".mer-add-pl").live("mouseenter",function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-home-small",100,function(){$("#"+b+"-tt").html("Adicionar em uma playlist")})});$("#contato-btn-1").live("mouseenter",function(){var b=this.id;
tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+b+"-tt").html("Clique para enviar um pedido de contato.")})});$("#acompanha-btn-1").live("mouseenter",function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+b+"-tt").html("Clique para assinar o feed do usu\u00e1rio.")})});$("#seta_1,#seta_2").live("mouseenter",function(){var b=this.id;tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){b=="seta_2"?$("#"+
b+"-tt").html("M\u00eas anterior"):$("#"+b+"-tt").html("Pr\u00f3ximo m\u00eas");$("#seta_1,#seta_2").bind("click",function(){$("#"+b+"-tt").remove()})})});document.getElementById("mapevento")&&(mapaEvent(1),mapaPega());document.getElementById("mapevento2")&&(mapaEvent(2),mapaPega());$(".botao-cadastro").click(function(){var b,d,e,f,g,h,i,k,l,m,n,o,j;b=document.getElementById("login").value;d=document.getElementById("senha").value;e=document.getElementById("nome").value;f=document.getElementById("email").value;
g=document.getElementById("tel").value;h=document.getElementById("logd").value;i=document.getElementById("bairro").value;k=document.getElementById("cidade").value;l=document.getElementById("estado").value;if(b==""||d==""||e==""||f==""||g=="")return $("#requisito-necessario").html("<strong class='side-tip'>\u00c9 necess\u00e1rio preencher todos os campos com *</strong>"),!1;if(validaSenha("")!=!0||validaUsername(b)!=!0||validaEmail(f)!=!0)return $("#requisito-necessario").html("<strong class='side-tip'>Dados Inv\u00e1lidos</strong>"),
!1;if(this.id=="botao-artista")o=document.getElementById("sobrenome").value,n=document.getElementById("apelido").value,j=1;else if(this.id=="botao-contratante")m=document.getElementById("website").value,j=2;$(".ajax-box-edit").fadeIn();url=root+"/async/validate/";$.ajax({type:"POST",url:url,data:{action:"cadastra_usuario",token:1,login:b,senha:d,nome:e,email:f,tel:g,logd:h,bairro:i,cidade:k,estado:l,website:m,apelido:n,sobrenome:o,tipo:j},success:function(b){b=eval("("+b+")");window.location=b.response==
1?root+"/login/&ac=csucess":root+"/login/&ac=falha"},error:function(){showWarn("N\u00e3o foi poss\u00edvel realizar seu cadastro.")}});$("#requisito-necessario").empty();return!0});$(".slideDown").click(function(){var b=this.id.split("-"),b="#box-"+b[2]+"-"+b[3]+"-wrapper";$(b).is(":visible")&&($(b).slideUp("fast"),setCookie("_"+b,"hide",7776E5),$("#"+this.id).html("+"));$(b).is(":hidden")&&($(b).slideDown("fast"),setCookie("_"+b,"visible",7776E5),$("#"+this.id).html("-"))});$("#atualiza-sobe,#atualiza-desce").live("click",
function(){var b=parseInt($("#atualiza-slide-change").css("margin-top")),d=document.getElementById("atualiza-slide-change").offsetHeight;this.id.split("-")[1]=="desce"?b*-1+215<d&&$("#atualiza-slide-change").animate({"margin-top":"-=90"},150):(b+=245,b<d&&b<245&&$("#atualiza-slide-change").animate({"margin-top":"+=90"},150))});$(".mem-bigdiv").live("hover",function(){var b=this.id.split("-");(new musicaClass).showIcons(b[2],"show")});$(".mem-bigdiv").live("mouseleave",function(){var b=this.id.split("-");
(new musicaClass).showIcons(b[2],"hidden")});$("#query,.search-overlay1").click(function(){$(".search-overlay1").css("display","none");$("#query").focus()});$("#query").blur(function(){$("#query").val()==""&&$(".search-overlay1").css("display","block")});$("#query-form-busca,#query-form-busca2").submit(function(){var b;b=this.id=="query-form-busca2"?$("#query-s").val():$("#query").val();window.location=root+"/busca/&s="+b;return!1})});
$("#ajax-close").live("click",function(){$(".ajax-box-edit").fadeOut();$(".opc-box").fadeOut()});
$("#e-s-button").live("click",function(){$("#real-result-evento-wrap").html("");$("#loading-more").html("<img src='"+root+"/imagens/site/loading.gif' alt='loading'>");var b=$("#input-search-e2").val(),d=$("#input-search-e3").val(),e=$("#input-search-e1").val();$("#page-query").attr("value",15);$("#cidade-query").attr("value",b);$("#genero-query").attr("value",d);$("#nome-query").attr("value",e);$.ajax({type:"POST",url:root+"/site/eventos",data:{cidade:b,genero:d,nome:e,page:0},success:function(b){b=
$(b).find("#result-evento-wrap").html();$("#result-evento-wrap").html(b);$("#loading-more").html("");$(".more-wrapper").html("<span id='more-events'>+ Mais</span>")}})});
$("#more-events").live("click",function(){$("#loading-more").html("<img src='"+root+"/imagens/site/loading.gif' alt='loading'>");var b=parseInt($("#page-query").val()),d=$("#cidade-query").val(),e=$("#genero-query").val(),f=$("#nome-query").val(),g=b+15;$("#page-query").attr("value",g);$.ajax({type:"POST",url:root+"/site/eventos",data:{cidade:d,genero:e,nome:f,page:b},success:function(d){var e=$(d).find("#real-result-evento-wrap").html(),d=$(d).find("#result-n").html();$("#loading-more").html("");
d=="0"?$(".more-wrapper").html("N\u00e3o existe mais resultados."):$("#more-results-"+b).append("<div id='more-results-"+g+"'>"+e+"</div>")}})});$("#comment-button,#comment-button2,#comment-button3,#comment-button4").live("click",function(){var b=new commentsClass;b.getInfo();b.addComment($("#text-comment-n").val());$("#text-comment-n").val("")});$(".del-comment,.del-comment2").live("click",function(){var b=this.id.split("-")[1];(new commentsClass).deleteComment(b)});
$(".edit-comment,.edit-comment2").live("click",function(){var b=new commentsClass;b.getInfo();b.editComment(this)});
$("#tudo-box-menu,#mus-box-menu,#eve-box-menu,#contra-box-menu,#com-box-menu,#plays-box-menu").live("click",function(){$("#profile-mid-content").html("<img src='"+root+"/imagens/site/loading.gif' alt='loading'>");var b,d;d=$("#user-profile-id").val();switch(this.id){case "eve-box-menu":b="eventos";$("#mode-page").attr("value",b);break;case "mus-box-menu":b="musicas";$("#mode-page").attr("value",b);break;case "contra-box-menu":b="contatos";$("#mode-page").attr("value",b);break;case "com-box-menu":b=
"comentarios";$("#mode-page").attr("value",b);break;case "plays-box-menu":b="playlists",$("#mode-page").attr("playlists",b)}$.ajax({type:"POST",url:root+"/async/profile",data:{action:"changeaba_profile",aba:b,id:d,token:1},success:function(b){$("#profile-mid-content").html(b);return!1},error:function(){return!0}});return!1});$("#email").live("blur",function(){a=validaEmail(this.value);a!=!0?$("#email-error").html("<strong class='side-tip'>"+a+"</strong>"):$("#email-error").html("<strong class='side-tip-ok'>Ok</strong>")});
$("#email2").live("blur",function(){var b=document.getElementById("mid").value;a=validaEmail(this.value,b);a!=!0?$("#email-error").html("<strong class='side-tip'>"+a+"</strong>"):$("#email-error").html("<strong class='side-tip-ok'>Ok</strong>")});$("#tel").live("blur",function(){a=validaTelefone(this.value);a!=!0?$("#telefone-error").html("<strong class='side-tip'>"+a+"</strong>"):$("#telefone-error").html("<strong class='side-tip-ok'>Ok</strong>")});
$("#nome").live("blur",function(){this.value==""?$("#nome-error").html("<strong class='side-tip'>Digite um nome.</strong>"):$("#nome-error").html("<strong class='side-tip-ok'>Ok</strong>")});$("#telefone2").live("blur",function(){a=validaTelefone2(this.value);a!=!0?$("#telefone2-error").html("<strong class='side-tip'>"+a+"</strong>"):$("#telefone2-error").html("<strong class='side-tip-ok'>Ok</strong>")});
$("#n-dia").live("blur",function(){a=validaDia(this);a!=!0?$("#dia-error").html("<strong class='side-tip'>Dia Inv\u00e1lido</strong>"):$("#dia-error").html("<strong class='side-tip-ok'>Ok</strong>")});
$("#form-edit-pessoal").live("submit",function(){var b=document.getElementById("mid").value,d=document.getElementById("email2").value,e=document.getElementById("tel").value,f=document.getElementById("telefone2").value,g=document.getElementById("n-dia"),h=document.getElementById("nome").value;return validaTelefone2(f)==!0&&validaTelefone(e)==!0&&validaEmail(d,b)==!0&&validaDia(g)==!0&&h!=""?!0:($("#submit-error").html("<div class='query-fail'>Voc\u00ea possui informa\u00e7\u00f5es inv\u00e1lidas.</div>"),
!1)});$("#old-pw").live("blur",function(){a=validaSenha(this.value);return a!=!0||this.value==""?($("#senha-error2").html("<strong class='side-tip'>Senha Antiga Inv\u00e1lida</strong>"),!1):($("#senha-error2").html("<strong class='side-tip-ok'>Ok</strong>"),!0)});
$("#form-senha").live("submit",function(){var b=document.getElementById("old-pw").value;document.getElementById("senha");document.getElementById("senha-c");if(validaSenha(b)!=!0)return $("#senha-error2").html("<strong class='side-tip'>Senha Antiga Inv\u00e1lida</strong>"),!1;return validaSenha("")!=!0?(valida=validaSenha(""),$("#senha-edit-error").html("<strong class='side-tip'>"+valida+"</strong>"),!1):!0});
$("#edit-pw").live("click",function(){location.href="#secu";$("html").css("overflow","hidden");$(".opc-box").fadeIn();$("#edit-senha-box").fadeIn()});$("#fecha-senha,.opc-box").live("click",function(){$("html").css("overflow","scroll");$(".opc-box").fadeOut();$("#edit-senha-box").fadeOut();$(".ajax-box-edit").fadeOut();$(".ajax-box-playlist").fadeOut();$(".login-hidden").fadeOut()});
$("#senha,#senha-c").live("blur",function(){a=validaSenha("");a!=!0?$("#senha-error").html("<strong class='side-tip'>"+a+"</strong>"):$("#senha-error").html("<strong class='side-tip-ok'>Ok</strong>")});$("#participantes-add").live("blur",function(){validaParticipantes()});$(".aval-star,.aval-star2").live("hover",function(){var b,d;b=this.id.split("-");b=b[1];for(d=1;d<=5;d++)d<=b?$("#aval-"+d).attr("class","aval-star2"):$("#aval-"+d).attr("class","aval-star")});
$(".aval-star,.aval-star2").live("mouseleave",function(){var b=$("#aval-true").val(),d;for(d=1;d<=5;d++)d<=b?$("#aval-"+d).attr("class","aval-star2"):$("#aval-"+d).attr("class","aval-star")});$(".aval-star,.aval-star2").live("click",function(){var b,d;d=this.id.split("-");d=d[1];b=$("#music-id").val();$.ajax({type:"POST",url:root+"/site/musica/"+b,data:{mode:"aval",val:d,id:b},success:function(b){b=$(b).find("#music-avaliacao-wrapper").html();$("#music-avaliacao-wrapper").html(b)}})});
$("#music-show-comments,#music-show-letra,#music-show-todas,#evento-show-p").live("click",function(){var b=this.id.split("-"),d,e,f,g,b=b[2];switch(b){case "comments":d="comentario";e=$("#music-id").val();f=root+"/async/music/";g="#music-sub-right";break;case "letra":d="letra";e=$("#music-id").val();f=root+"/async/music/";g="#music-sub-right";break;case "todas":e=$("#music-id").val();f=root+"/async/music/";g="#music-sub-right";d="";break;case "p":d="visitantes",e=$("#event-id").val(),f=root+"/site/evento/"+
e,g="#evento-body-wrap"}$.ajax({type:"POST",url:f,data:{id:e,token:1,action:"reload_page",mode:d},success:function(b){b=$(b).find(g).html();$(g).html(b)}});return!1});$(".e-participar2").live("hover",function(){$(".e-participar2").html("Cancelar")});$(".e-participar2").live("mouseleave",function(){$(".e-participar2").html("Participando")});$(".login-show").live("click",function(){ologin()});
$("#ranking-show").live("click",function(){$("#ranking-down-menu").is(":visible")?$("#ranking-down-menu").slideUp():$("#ranking-down-menu").slideDown()});$(".calendario-space-n-small").live("hover",function(){var b=new toolTipClass,d=this.id,e=this.id.split("-");b.toolTipCreator(this,"ajax-tt-box",100,function(){var b=$("#hide-e-c-"+e[3]).html();$("#"+d+"-tt").html(b)})});
$(".calendario-space-n").live("hover",function(){var b=new toolTipClass,d=this.id,e=this.id.split("-");b.toolTipCreator(this,"ajax-tt-box",100,function(){var b=$("#hide-e-c-"+e[3]).html();$("#"+d+"-tt").html(b)})});$("[rel=thumb_box]").live("mouseenter",function(){var b=new toolTipClass,d=this.id,e=this.id.split("-");b.toolTipCreator(this,"ajax-tt-box",100,function(){$.ajax({type:"POST",url:root+"/async/profile",data:{action:"profile_info",id_u:e[2],token:"1"},success:function(b){$("#"+d+"-tt").html(b)}})})});
$(".e-participar,.e-participar2").live("click",function(){$("#btn-p-wrap").html("<img src='"+root+"/imagens/site/loading.gif' alt='loading'>");var b=$("#event-id").val(),d=this.id.split("-"),d=d[0],e;d=="participar"?e=0:d=="pcd"&&(e=1);$.ajax({type:"POST",url:root+"/site/evento/"+b,data:{mode:"pm",eid:b,tipo:e},success:function(d){$(d).find("#response").html()==2?(ologin(),$("#btn-p-wrap").html("<div class='e-participar' id='participar-"+b+"'>Participar</div>")):(d=$(d).find("#evento-head-wrap").html(),
$("#evento-head-wrap").html(d))}})});$("#more-comments").live("click",function(){var b=new commentsClass;b.getInfo();b.showComments($("#comentario-page").val(),$("#comentario-count").val())});$("#bci-termos,#bci-descri").live("click",function(){var b=new contatoClass;switch(this.id){case "bci-termos":$("#hide-termos").is(":hidden")?b.showTermos():b.hideDiv();break;case "bci-descri":$("#hide-descricao").is(":hidden")?b.showDescricao():b.hideDiv()}});
$("#contato-btn-1,#contato-btn-2").live("click",function(){var b=new contatoClass;this.id.split("-");b.startContato(0)});$("#contato-next-step-1,#contato-next-step-2,#contato-next-step-4,#contato-next-step-5").live("click",function(){var b=this.id.split("-");(new contatoClass).startContato(b[3])});
$("#contato-next-step-3").live("click",function(){var b=$("#cf-assunto").val(),d=$("#cf-dia").val(),e=$("#cf-mes").val(),f=$("#cf-ano").val(),g=$("#cf-valor").val(),h=$("#cf-descricao").val();(new contatoClass).createContato(b,d,e,f,g,h)});$("#contato-close-back").live("click",function(){var b=$("#old-assunto").val(),d=$("#old-valor").val(),e=$("#old-data").val(),f=$("#old-descricao").val();(new contatoClass).contatoBack(b,e,f,d)});$("#contato-close").live("click",function(){$(".ajax-box-contato").fadeOut()});
$("#calendario-next,#calendario-back,#calendario-next2,#calendario-back2").live("click",function(){var b=$("#mes-atual").val(),d=$("#ano-atual").val(),e="profile",b=parseInt(b),d=parseInt(d);switch(this.id){case "calendario-next":case "calendario-next2":b==12?(d+=1,b=1):b+=1;break;case "calendario-back":case "calendario-back2":b==1?(d-=1,b=12):b-=1}if(this.id=="calendario-next2"||this.id=="calendario-back2")e="portal";$.ajax({type:"POST",url:root+"/async/"+e+"/",data:{action:"eventos_mes",mes:b,ano:d,
token:1},success:function(b){$("#c-box").html(b)}})});
$("#btn-c-green, #btn-c-red").live("click",function(){var b=$("#contato-id").val(),d=this.id.split("-");switch(d[2]){case "green":d=0;break;case "red":d=1}$.ajax({type:"POST",url:root+"/async/contato/",data:{action:"update_status",id:b,status:d,token:1},success:function(){$(".bci-aceita").slideUp();d==0?$("#status-change").html("<span class = 'color-green'>Atuante</span>"):($("#status-change").html("<span class = 'color-red'>Fechado</span>"),$("#mci-main").remove(),$("#bci-cancel").remove())}})});
$("#termos-edit").live("click",function(){$("#exibe-termos").html("<textarea id = 'txt-edit-termos'></textarea><input type = 'button' \nid = 'btn-edita-termos' class = 'btn' value = 'Salvar' />");var b=$("#termos-hidden").html();$("#txt-edit-termos").attr("value",b)});
$("#btn-edita-termos").live("click",function(){var b=$("#txt-edit-termos").val();$.ajax({type:"POST",url:root+"/async/contato/",data:{action:"update_termos",termos:b,token:1},success:function(d){d=$(d).html();$("#exibe-termos").html("<div id='termos-edit'>"+d+"</div>");$("#termos-hidden").html(b)}})});
$("#ex-btn-edit").live("click",function(){var b=$("#ex-idade-min").val(),d=$("#ex-cidade").val(),e=$("#ex-estado").val(),f=$("#ex-pagamento").val(),g=$("#ex-descricao").val();$.ajax({type:"POST",url:root+"/async/contato/",data:{action:"update_exigencias",idade:b,cidade:d,estado:e,pagamento:f,descricao:g,token:1},success:function(b){b=$(b).html();$("#ex-response").html(b)}})});
$("#nt-1").live("click",function(){$("#drop-down-notifications").is(":visible")?$("#drop-down-notifications").slideUp("fast"):$.ajax({type:"POST",url:root+"/async/contato/",data:{action:"show_notificacoes",token:1},success:function(b){$("#drop-down-notifications").html(b);$("#drop-down-notifications").slideDown("fast")}})});$("#curriculum-new").live("click",function(){c=new curriculumClass;c.newCurriculum()});$(".c-event-edit").live("click",function(){c=new curriculumClass;c.startEdit(this)});
$("#del-curriculum").live("click",function(){c=new curriculumClass;c.getId();c.deleteCurriculum()});$("#atual-curriculum").live("click",function(){c=new curriculumClass;c.getId();c.atualizaCurriculum()});$("#ci-list-btn-musica").live("click",function(){var b=$("#music-list-curriculum-add").val();c=new curriculumClass;c.getId();c.addItemLista(0,b)});
$("#ci-list-btn-referencia").live("click",function(){var b=$("#referencias-list-curriculum-add").val();c=new curriculumClass;c.getId();c.addItemLista(2,b)});$("#ci-list-btn-contato").live("click",function(){var b=$("#contato-list-curriculum-add2").val(),d=$("#contato-list-curriculum-add1").val(),b=[b,d];c=new curriculumClass;c.getId();c.addItemLista(1,b)});$(".ci-del-list").live("click",function(){var b=this.id.split("-"),d=b[2],b=b[1];c=new curriculumClass;c.getId();c.delItemLista(b,d)});
$("#bci-cancel,#bci-cancel2").live("click",function(){var b=0;this.id=="bci-cancel2"&&(b=1);c=new contatoClass;c.closeContato(b)});$("#btn-nova-noticia").live("click",function(){(new portalClass).novaNoticia()});$("#btn-gerencia-portal").live("click",function(){(new portalClass).boxCreate(1,"","")});$("#noticia-delete,#noticia-delete2").live("click",function(){var b=$("#noticia-id").val(),d=0;this.id=="noticia-delete2"&&(d=1);(new portalClass).deletaNoticia(b,d)});
$("#noticia-edit").live("click",function(){var b=$("#noticia-id").val();(new portalClass).editNoticia(0,b)});$("#edit-noticia-btn").live("click",function(){var b=$("#noticia-id").val();(new portalClass).editNoticia(1,b)});$("#pr-search-button").live("click",function(){pesquisaNoticia()});$("#pr-search-input").live("keyup",function(b){b.keyCode==13&&pesquisaNoticia()});$("#bln-back,#bln-next").live("click",function(){var b=new boxClass,d=0;this.id=="bln-back"&&(d=1);b.alterarPagina(d)});
$("#choicebx-1,#choicebx-2,#choicebx-3").live("click",function(){id=this.id.split("-");switch(id[1]){case "1":tipo=1;break;case "2":tipo=2;break;case "3":tipo=3}bx=new boxClass;bx.selectBox(tipo)});$(".box-layout-list-li").live("click",function(){var b=this.id.split("-");(new boxClass).addBox(b[2])});$(".delete-box-mini").live("click",function(){var b=this.id.split("-");(new boxClass).deleteBox(b[1])});
$("#genero,#genero-e").live("keyup",function(){var b=this.value;(new acompleteClass).abrirAutoComplete(this,1,b)});$("#ms-genero-input").live("keyup",function(){var b=this.value;(new acompleteClass).abrirAutoComplete(this,6,b)});$("#input-search-e1").live("keyup",function(){var b=this.value;(new acompleteClass).abrirAutoComplete(this,2,b)});$("#input-search-e2").live("keyup",function(){var b=this.value;(new acompleteClass).abrirAutoComplete(this,4,b)});
$("#input-search-e3").live("keyup",function(){var b=this.value;(new acompleteClass).abrirAutoComplete(this,5,b)});$("#pr-search-input").live("keyup",function(){var b=this.value;(new acompleteClass).abrirAutoComplete(this,3,b,function(b){window.location=root+"/portal/listn/"+b})});$(".e-show-del,.e-show-del2").live("click",function(){var b=new eventoClass,d=this.id.split("-");b.deletaEvento(0,d[1])});
$("#dbuttonacao").live("click",function(){var b=new eventoClass,d=$("#evento-d-id").val();b.deletaEvento(1,d)});$(".delparticipante").live("click",function(){var b=new eventoClass,d=this.id.split("-"),e=$(".wrap-lista-membros").attr("id");b.deleteParticipante(e,d[1])});$(".e-show-edit").live("click",function(){var b=new eventoClass,d=this.id.split("-")[1];b.loadEdit(d,0)});$("#editbuttone").live("click",function(){(new eventoClass).editaEvento()});$(".edita-redes-ev").live("click",function(){(new eventoClass).editaRede(this)});
$("#envia-evento").live("submit",function(){return(new eventoClass).validaAdicao()==!1?!1:!0});$("#create-ev").live("click",function(){(new eventoClass).loadEdit(0,1)});$("#add-musica").live("submit",function(){return(new musicaClass).validaForm()==!1?!1:!0});$(".del-playlist1").live("click",function(){var b=this.id.split("-");(new playlistClass).deletePlaylist(b[1])});$("#seta-playlist-left,#seta-playlist-right").live("click",function(){(new playlistClass).rollPlaylist(this)});
$(".mlm-delete").live("click",function(){var b=this.id.split("-");(new playlistClass).deleteMusica(b[1])});$(".btn-playlist").live("click",function(){var b=this.id.split("-");(new playlistClass).addMusica(0,b[1],"")});$(".amp-list-playlist").live("click",function(){var b=$("#music-id-add-playlist").val(),d=this.id.split("-");(new playlistClass).addMusica(1,b,d[1])});
$(".mer-add-pl").live("click",function(){$(".ajax-box-playlist").remove();$("#true-wrap").append("<div class='ajax-box-playlist'></div>");var b=new playlistClass,d=this.id.split("-");b.addMusica(0,d[2],"")});$(".btn-add-ms-confirma").live("click",function(){var b=$("#music-id-add-playlist").val(),d=this.id.split("-");(new playlistClass).addMusica(2,b,d[1])});$(".btn-add-playlist-new,#addnpl").live("click",function(){(new playlistClass).createPlaylist(0,"","")});
$("#pc-input-stp1").live("click",function(){(new playlistClass).createPlaylist(1,"","")});$("#musica-add-image").live("submit",function(){(new playlistClass).createPlaylist(2,"","")});$(".music-li-main").live("mouseenter",function(){var b=this.id.split("-");$("#start-m-"+b[3]).fadeIn("fast")});$(".music-li-main").live("mouseleave",function(){var b=this.id.split("-");$("#start-m-"+b[3]).fadeOut("fast")});
$(".start-playlist").live("click",function(){var b=$("#playlist-id").val(),d=this.id.split("-");(new playlistClass).startPlaylist(b,d[2])});$(".playlist-content-listamusicas").live("click",function(){var b=this.id.split("-");(new playlistClass).trocaMusica(b[0])});$("#pbar-next,#pbar-back").live("click",function(){var b=this.id,d=new playlistClass;b=="pbar-next"?d.trocaMusica("next"):d.trocaMusica("back")});function proximaMusica(){(new playlistClass).trocaMusica("next")}
$("#is-aleatorio").live("change",function(){(new playlistClass).aleatorioOn()});$(".hide-playlist").live("click",function(){$("#lph-wrap").is(":visible")?($("#lph-wrap").css("display","none"),$(".hide-playlist").html("Mostrar suas playlists")):($("#lph-wrap").css("display","block"),$(".hide-playlist").html("Esconder"))});
$("#fb-ev-logo,#twt-ev-logo,#yt-ev-logo,#lm-ev-logo").live("mouseenter",function(){var b=this.id,d=b.split("-");tt=new toolTipClass;tt.toolTipCreator(this,"tip-cadastro",100,function(){$("#"+b+"-tt").html($("#"+d[0]+"-site").html())})});$(".btn-reply-playlist  ").live("click",function(){var b=new playlistClass,d=this.id.split("-");b.replicaPlaylist(d[2])});$(".playlist-privacy").live("click",function(){var b=new playlistClass,d=$("#playlist-id").val(),e=this.id.split("-");b.trocarPrivacidade(d,e[2])});
$(".mem-link").live("click",function(){var b=new musicaClass,d=this.id.split("-");b.loadMusica(d[1])});$(".music-show-info,.ms-edit-aba2").live("click",function(){var b=new musicaClass,d=this.id.split("-");b.editMusica(0,d[3])});
$("#music-btn-editar").live("click",function(){var b=new musicaClass,d=$(".form-edit").attr("id").split("-"),e=$("#titulo").val(),f=$("#genero").val(),g=$("#classificacao").val(),h=$("#clipe").val(),i;$("#p-musica-1").is(":checked")==!0?i=0:$("#p-musica-2").is(":checked")==!0&&(i=1);b.editMusica(1,d[2],e,f,g,i,h)});$(".music-show-del,.ms-del-aba2").live("click",function(){var b=new musicaClass,d=this.id.split("-");b.deleteMusica(0,d[3])});
$("#music-btn-deletar").live("click",function(){var b=new musicaClass,d=$(".form-edit").attr("id").split("-");b.deleteMusica(1,d[2])});$("#edit-letras").live("click",function(){(new musicaClass).editLetra(0)});$("#music-btn-letras").live("click",function(){(new musicaClass).editLetra(1)});$("#more-musicas,#search-ms").live("click",function(){var b=new musicaClass;this.id=="search-ms"?b.pesquisaMusica(!0):b.pesquisaMusica(!1)});
$("#opm-main").live("click",function(){$("#opm-list").is(":hidden")==!0?$("#opm-list").slideDown():$("#opm-list").slideUp()});$("#opm-alfabeto,#opm-genero").live("click",function(){var b,d=$("#playlist-id").val();b=this.id=="opm-alfabeto"?1:2;(new playlistClass).sortPlaylist(d,b)});$("#opm-personalizada").live("click",function(){(new playlistClass).startDrag()});$(".mel-more").live("click",function(){(new eventoClass).moreEventosVisitados()});$("#end-sortof-playlist").live("click",function(){(new playlistClass).endDrag()});
$(".mer-li").live("mouseenter",function(){var b=this.id.split("-");$("#mer-add-"+b[1]).fadeIn()});$(".mer-li").live("mouseleave",function(){var b=this.id.split("-");$("#mer-add-"+b[1]).fadeOut()});$("#more-full-feed").live("click",function(){(new feedClass).loadFeeds(!0)});$("#more-full-atualizacoes").live("click",function(){(new feedClass).loadFeeds(!1)});
$(".prf-pl-list").live("mouseenter",function(){var b=this.id,d=this;tt=new toolTipClass;tt.toolTipCreator(this,"tip-home-small",100,function(){var e=b.split("-");document.getElementById("pr-hidden-minfo-"+b)==null?$.ajax({type:"POST",url:root+"/async/playlist/",data:{action:"show_musicas_tip",id:e[2],token:1},success:function(e){e=eval("("+e+")");e.response!="1"?tt.toolTipDestroyer(d,90):($("body").append("<div id='pr-hidden-minfo-"+b+"' class='hidden'><b>M\u00fasicas na Playlist</b><br /><p></div>"),
$.each(e.musicas,function(d,e){$("#pr-hidden-minfo-"+b).append(e.nome+"<br />")}),$("#pr-hidden-minfo-"+b).append("</p>"),$("#"+b+"-tt").html($("#pr-hidden-minfo-"+b).html()))}}):$("#"+b+"-tt").html($("#pr-hidden-minfo-"+b).html())})});$("#acompanha-btn-2").live("hover",function(){$("#acompanha-btn-2").css("background",'url("../imagens/site/acompanha_btn2.png") no-repeat');$("#acompanha-btn-2").html("Desacompanhar")});
$("#acompanha-btn-2").live("mouseleave",function(){$("#acompanha-btn-2").css("background",'url("../imagens/site/acompanha_btn.png") no-repeat');$("#acompanha-btn-2").html("Acompanhando")});$("#acompanha-btn-1").live("click",function(){(new feedClass).acompanhaUser()});$("#acompanha-btn-2").live("click",function(){(new feedClass).desacompanhaUser()});
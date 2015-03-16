<?
if ($IN_ACCORDI != true)
{
    exit();
}
?>
<div class='ajax-box-edit'><img src='<? echo $root; ?>/imagens/site/loading.gif' alt='Loading' /> Aguarde...</div>
<div class='default-box2' id='cadastro-div-1'>
    <div class='gra_top1'>Cadastro</div>
    <p class='smalltip'>Campos com asterisco são obrigatórios, insira um e-mail válido para futuras consultas.</p>
      <form method='get' action=''>
        <fieldset class='cadastro-ul'>
            <p class='right-cadastro'>Informações Gerais</p>
            <ul class='cadastro-ul'>
                <li class='cadastro-ul'><label class='cadastro-ul' id='tool-login'>Usu&aacute;rio*</label><input type='text' class='cadastro-ul' name='login' id='login' maxlength="30" />
                    <span id='login-error'><span class='smalltip'>Letras e números.</span></span></li>
                <li class='cadastro-ul'><label class='cadastro-ul' id='tool-senha'>Senha*</label><input type='password' class='cadastro-ul' name='senha' id='senha' maxlength="30" />
                    <span id='senha-error'><span class='smalltip'>Mínimo de 6 caracteres.</span></span></li>
                <li class='cadastro-ul'><label class='cadastro-ul' id='tool-senha'>Confirmar Senha</label><input type='password' class='cadastro-ul' name='senha-c' id='senha-c' />
                <li class='cadastro-ul'><label class='cadastro-ul' >Nome*</label><input type='text' class='cadastro-ul' name='nome' id='nome' maxlength="20" /></li>
                <li class='cadastro-ul'><label class='cadastro-ul'>E-mail*</label><input type='text' class='cadastro-ul' name='email' id='email' maxlength="55" /><span id='email-error'><span class='smalltip'>Insira um e-mail válido.</span></span></li>
            </ul>
        </fieldset>
        <fieldset class='cadastro-ul'>
            <p class='right-cadastro'>Informações Pessoais</p>
            <ul class='cadastro-ul'>
                <li class='cadastro-ul'><label class='cadastro-ul' id='tool-tel'>Telefone*</label><input type='text' class='cadastro-ul' name='tel' id='tel' maxlength="13" value="(XX)0000-0000"/><span id='telefone-error'></span></li>
                <li class='cadastro-ul'><label class='cadastro-ul'>Endereço</label><input type='text' class='cadastro-ul' name='logd' id='logd' maxlength="90" /></li>
                <li class='cadastro-ul'><label class='cadastro-ul'>Bairro</label><input type='text' class='cadastro-ul' name='bairro' id='bairro' maxlength="60" /></li>
                <li class='cadastro-ul'><label class='cadastro-ul'>Cidade</label><input type='text' class='cadastro-ul' name='cidade' id='cidade'  maxlength="60"/></li>
                <li class='cadastro-ul'><label class='cadastro-ul'>Estado</label>
                    <select name="estado" id="estado" class='cadastro-ul'>
                        <option value="0">Selecione o Estado</option>
                        <option value="ac">Acre</option>
                        <option value="al">Alagoas</option>
                        <option value="ap">Amapá</option>
                        <option value="am">Amazonas</option>
                        <option value="ba">Bahia</option>
                        <option value="ce">Ceará</option>
                        <option value="df">Distrito Federal</option>
                        <option value="es">Espirito Santo</option>
                        <option value="go">Goiás</option>
                        <option value="ma">Maranhão</option>
                        <option value="ms">Mato Grosso do Sul</option>
                        <option value="mt">Mato Grosso</option>
                        <option value="mg">Minas Gerais</option>
                        <option value="pa">Pará</option>
                        <option value="pb">Paraíba</option>
                        <option value="pr">Paraná</option>
                        <option value="pe">Pernambuco</option>
                        <option value="pi">Piauí</option>
                        <option value="rj">Rio de Janeiro</option>
                        <option value="rn">Rio Grande do Norte</option>
                        <option value="rs">Rio Grande do Sul</option>
                        <option value="ro">Rondônia</option>
                        <option value="rr">Roraima</option>
                        <option value="sc">Santa Catarina</option>
                        <option value="sp">São Paulo</option>
                        <option value="se">Sergipe</option>
                        <option value="to">Tocantins</option>
                    </select>
                </li>
            </ul>
        </fieldset>
</div>
<div class='default-box2' id='cadastro-div-2' name='cadastro-div-2'>
    <div class='gra_top1'>Escolha seu tipo de conta</div>
    <div id='cadastro-escolha'>
        <div class='info'>No Accordi você pode se registrar como Artista ou como Contratante, ambos possuem características próprias. Escolha abaixo em qual tipo de conta você mais se encaixa.</div>
            <ul class='cadastro-ul2'>
                <li class='cadastro-ul2'><a href="#" class='nochange' ><div class='master-title-cadastro' id='cadastro-tipo1'><div id='ctimg1'></div>Sou um Artista</div></a></li>
                <li class='cadastro-ul2'><a href="#" class='nochange' ><div class='master-title-cadastro' id='cadastro-tipo2'><div id='ctimg2'></div>Sou um Contratante/Produtor</div></a></li>
            </ul>
        </fieldset>
    </div>
    <div id='cadastro-artista'>
        <img src='<? echo $root; ?>/imagens/site/ico_artista.png' alt="Artista" />
        <p>O artista no Accordi pode fazer upload de m&uacute;sicas, procurar contrantes interessados em seu trabalho e visualizar o trabalho de outros colegas de profiss&atilde;o!</p>
        <fieldset class='cadastro-ul'>
            <p class='right-cadastro'>Artistas</p>
            <ul class='cadastro-ul'>
                <li class='cadastro-ul'><label class='cadastro-ul'>Sobrenome</label><input type='text' class='cadastro-ul' name='sobrenome' id='sobrenome' /></li>
                <li class='cadastro-ul'><label class='cadastro-ul'>Apelido</label><input type='text' class='cadastro-ul' name='apelido' id='apelido' /></li>
            </ul>
        </fieldset>
        <fieldset class='cadastro-ul'>
            <p class='right-cadastro'>Termos</p>
            <textarea class='cadastro-ul' readonly="readonly">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mollis mattis ipsum, eget hendrerit risus auctor non. Aenean accumsan nunc sit amet lorem tempus congue. Mauris sapien elit, semper et sodales eget, lobortis non enim. Donec lacinia felis non massa eleifend condimentum. Mauris interdum quam id orci euismod ut blandit orci ullamcorper. Aliquam tincidunt nisl vel purus condimentum congue. Duis id urna nec justo malesuada egestas. Aliquam euismod gravida velit nec pretium. Suspendisse elementum ante ut erat placerat auctor. Vivamus lacus nulla, semper sed vulputate nec, euismod eget augue. Maecenas cursus sollicitudin velit, eu placerat tellus ultrices sit amet. Maecenas a neque at quam mattis pretium non ornare mauris. Nunc congue tempus ante, vel imperdiet sapien rhoncus sit amet. Maecenas dolor tellus, sodales at ultricies eget, pellentesque eu velit. Sed pellentesque rutrum purus eu pharetra. Suspendisse potenti. Fusce in odio orci. Aliquam ut fringilla nisi. Nunc felis libero, imperdiet quis vehicula vel, rutrum molestie velit.
            </textarea>
            Ao cadastrar você está aceitando todos os termos de uso citados acima.
        </fieldset>
        <button type='button' id='botao-artista' class='botao-cadastro'>Cadastrar</button>
    </div>
    <div id='cadastro-contratante'>
        <img src='<? echo $root; ?>/imagens/site/ico_contratante.png' alt="Contratante" />
        <p>Procure artistas do seu interesse! O contratante no Accordi tem a oportunidade de encontrar novos talentos, fazer contratos e se atualizar sobre o mercado musical brasileiro.</p>
        <fieldset class='cadastro-ul'>
            <p class='right-cadastro'>Informações Contratante</p>
            <ul class='cadastro-ul'>
                <li class='cadastro-ul'><label class='cadastro-ul'>Website</label><input type='text' class='cadastro-ul' name='website' id='website' /></li>
             </ul>
        </fieldset>
        <fieldset class='cadastro-ul'>
            <p class='right-cadastro'>Termos</p>
            <textarea class='cadastro-ul' readonly="readonly">
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed mollis mattis ipsum, eget hendrerit risus auctor non. Aenean accumsan nunc sit amet lorem tempus congue. Mauris sapien elit, semper et sodales eget, lobortis non enim. Donec lacinia felis non massa eleifend condimentum. Mauris interdum quam id orci euismod ut blandit orci ullamcorper. Aliquam tincidunt nisl vel purus condimentum congue. Duis id urna nec justo malesuada egestas. Aliquam euismod gravida velit nec pretium. Suspendisse elementum ante ut erat placerat auctor. Vivamus lacus nulla, semper sed vulputate nec, euismod eget augue. Maecenas cursus sollicitudin velit, eu placerat tellus ultrices sit amet. Maecenas a neque at quam mattis pretium non ornare mauris. Nunc congue tempus ante, vel imperdiet sapien rhoncus sit amet. Maecenas dolor tellus, sodales at ultricies eget, pellentesque eu velit. Sed pellentesque rutrum purus eu pharetra. Suspendisse potenti. Fusce in odio orci. Aliquam ut fringilla nisi. Nunc felis libero, imperdiet quis vehicula vel, rutrum molestie velit.
            </textarea>
            Ao cadastrar você está aceitando todos os termos de uso citados acima.
        </fieldset>
        <button type='button' id='botao-contratante' class='botao-cadastro'>Cadastrar</button>
    </div>
    <div id="requisito-necessario"></div>
</div>
<div class='cadastrado'>Já é cadastrado? <a href='<? echo $root; ?>/login/' class='login-list'>Fazer Login</a></div>





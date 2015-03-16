<?
  /*   * *******************************************
   * 
   * 
   *    Accordi© - 2011
   *    Arquivo: public.php - Página para pesquisar músicas
   *    Desenvolvida por Paulo Felipe Possa Parreira
   *    Descrição: 
   *      Página pública que permite pesquisa através de nome e gênero.
   *    Principais funções:
   *      - Listar músicas
   *      - Filtrar músicas por nome e gênero
   *      - Mostrar sugestões de música
   *
   * 
   * ******************************************* */

  if (IN_ACCORDI != true)
  {
        exit();
  }
  
  class search_musica
  {
      private $nome;
      private $genero;
      private $page;
      private $search;
      
      public function __construct($nome,$genero,$page)
      {
          /**
           *  Carrega as classes e os atributos.
           */
          
          $this->search = new buscar();
          $this->nome = $nome;
          $this->genero = $genero;
          $this->page = $page;
          
          $this->loadBody();
          
      }
      
      private function loadBody()
      {
          /**
           *  Carrega o escopo da página
           * 
           */
          
          
          echo "<div id='music-search-wrap'>";
          
          echo "<div id='music-search-content' class='default-box2'>";
          $this->loadSearch();
          echo "</div>";
          
          echo "<div id='music-search-menu' class='default-box2'>";
          $this->loadMenu();
          echo "</div>";
          echo "<div class='clear'></div>";
          echo "</div>";
      }
      
      private function loadMenu()
      {
          /**
           *  Menu contendo o botão e os inputs para pesquisar
           */
          echo "<div class='gra_top1'>Pesquisar Músicas</div>";
          echo "<div class='music-search-label'><label for='ms-nome-input'>Nome</label></div>";
          echo "<div><input name='ms-nome' id='ms-nome-input' type='text' maxlength='25' class='music-search-input' /></div>";
          
          echo "<div class='music-search-label'><label for='ms-genero-input'>Gênero</label></div>";
          echo "<div><input name='ms-genero-input' id='ms-genero-input' type='text' maxlength='25' class='music-search-input' /></div>";
          echo "<div class='music-search-btn'><input type='button' id='search-ms' value='Pesquisar' class='btn' /><span id='loading-info'></span></div>";
          
      }
      
      private function loadSearch()
      {
          echo "<div class='gra_top1'>Resultados</div>";
          
          try
          {
              // Pega os resultados da busca por música
              $musicas = $this->search->filtroMusica($this->nome,$this->genero,$this->page);
              $root = $_SESSION['root'];
              
              echo "<div class='mser-list-main-title'>";
              echo "<div class='mser-list-nome'>Nome</div>";
              echo "<div class='mser-list-genero'>Gênero</div>";
              echo "<div class='mser-list-artista'>Artista</div>";
              echo "<div class='mser-list-duracao'>Duração</div>";
              echo "</div>";
              echo "<div id='ms-response-content'>";
              foreach($musicas as $i => $v)
              {
                  echo "<a href='$root/site/musica/".$v['id']."'>";
                  echo "<div class='mser-list-main'>";
                  echo "<div class='mser-list-nome'>".$v['nome']."</div>";
                  echo "<div class='mser-list-genero'>".$v['genero']."</div>";
                  echo "<div class='mser-list-artista'>".$v['artista']."</div>";
                  echo "<div class='mser-list-duracao'>".$v['duracao']."</div>";
                  echo "</div>";
                  echo "</a>";
              }
              echo "</div>";
              echo "<div id='more-musicas'>Mais</div>";
              $page_atual = count($musicas);          
              echo "<input type='hidden' id='ms-page-atual' value='$page_atual' />";
              echo "<input type='hidden' id='ms-nome' value='$this->nome' />";
              echo "<input type='hidden' id='ms-genero' value='$this->genero' />";
          
              
              
          }
          catch(Exception $a)
          {
              echo "<div id='ms-response-content'>";
              echo "<div class='music-search-notfound'>Nenhum resultado encontrado</div>";
              echo "</div>";
              echo "<input type='hidden' id='ms-page-atual' value='0' />";
              echo "<input type='hidden' id='ms-nome' value='$this->nome' />";
              echo "<input type='hidden' id='ms-genero' value='$this->genero' />";
          }
          
          
      }
      
      
        
      
  }
  
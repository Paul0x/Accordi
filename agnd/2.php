<? /** *  Realiza as tarefas agendadas diárias *  */$IN_ACCORDI = true;define(IN_ACCORDI, true);require("../includes/classes/agendado.php");require("../includes/classes/sqlcon.php");require("../includes/classes/eventos.php");require("../includes/classes/contatos.php");$a = new agendado(1);?>
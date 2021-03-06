<?php

class EvenementsPage extends Layout{
	
	private $date_ev="";
	
	public function __construct() {
		$this->pageTitle = "Evènements";
		
		if(isset($_GET['date_ev']))
			$this->date_ev = $_GET['date_ev'];
	}
	
	public function headerPlus() {

	}
	
	public function bodyHTML() {
// -----------------------------------
// Debut Body
// -----------------------------------
?>

<script type="text/javascript">

function addEvt(param) {
	var jeuxl = new Array();
	$("#jeuxliste :checked").each(function() {
 		 jeuxl.push($(this).val());
		});
		
	$.post("post.php", { action: "addevt", date: param[0].value, lieu: param[1].value, nb_part: param[2].value, id: param[3].value, jeux:jeuxl },
	  function(data){
	    $( "#evt-added" ).html(data); 
	    $( "#evt-added" ).dialog( "open" );
	  });
}

function open_info( id ) {
	$.get("get.php", { action: "get_evt_info", id_evt: id },
	  function(data){
	    $( "#dialog-info" ).html(data);
   		 $( "#dialog-info" ).dialog( "open" );
	  });
}

function open_comments( id ) {
	$.get("get.php", { action: "get_evt_comments", id_evt: id },
	  function(data){
	    $( "#dialog-info" ).html(data);
   		 $( "#dialog-info" ).dialog( "open" );
	  });
}

function delete_evt( id, nom ) {
	if(confirm("Etes vous sur de vouloir supprimer l'évènement "+nom+" ?"))
	{
		$.get("get.php", { action: "delete_evt", id_el: id },
	  function(data){
	    alert(data);
	    location.reload();
	  });
	}
	
}

function edit( id ) {
	$("#id_evt").val(id);
	$("#dialog-form").dialog("option", "title", "Modifier évènement");
	$.get("get.php", { action: "get_evt_data", id_el: id },
	  function(data){
	    $("#date").val(data.date);
	    $("#lieu").val(data.lieu);
	    $("#nb_part").val(data.nb_part);
	    $('#jeuxliste :checkbox:checked').removeAttr('checked');
	    $.each(data.jeux, function(i, val) {
	    	 $('#jeuxliste input:checkbox[value="'+val+'"]').attr('checked',true);
	    });
	  } , "json");
	$( "#dialog-form" ).dialog( "open" );
}
        
$(function() {
    var date = $( "#date" ),
        lieu = $( "#lieu" ),
        nb_part = $( "#nb_part" ),
        id = $( "#id_evt" ),
        allFields = $( [] ).add( date ).add( lieu ).add( id ).add( nb_part ) ;
 
        function updateTips( t ) {
            tips
                .text( t )
                .addClass( "ui-state-highlight" );
        setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
            }, 500 );
        }
 
        function checkLength( o, n, min, max ) {
            if ( o.val().length > max || o.val().length < min ) {
                o.addClass( "ui-state-error" );
           		updateTips( "La taille de " + n + " doit etre comprise entre " +
                min + " et " + max + "." );
                return false;
            } else {
                return true;
            }
        }
 
        function checkRegexp( o, regexp, n ) {
            if ( !( regexp.test( o.val() ) ) ) {
                o.addClass( "ui-state-error" );
                updateTips( n );
                return false;
            } else {
                return true;
            }
        }
 
        $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 500,
        width: 350,
        modal: true,
        show: "explode",
        buttons: {
            "Creer": function() {
                var bValid = true;
                
                bValid = bValid && checkLength( lieu, "lieu", 2, 20 );
 
                    if ( bValid ) {

                    	addEvt(allFields);
                        $( this ).dialog( "close" );
               		}
            },
            Cancel: function() {
                $( this ).dialog( "close" );
            }
        },
        close: function() {
            allFields.val( "" ).removeClass( "ui-state-error" );
            $('#jeuxliste :checkbox:checked').removeAttr('checked');
            }
        });
 
        $( "#create-evt" )
        .button()
        .click(function() {
        	$("#id_evt").val(-1);
        	$("#dialog-form").dialog("option", "title", "Creer un nouvel évènement");
            $( "#dialog-form" ).dialog( "open" );
        });
        
        $( "#evt-added" ).dialog({
            autoOpen: false,
            show: "blind",
            hide: "explode",
            buttons: {
            "Ok": function() {
  				 location.reload();
               		}
            }
        });
        
        $( "#dialog-info" ).dialog({
	        autoOpen: false,
	        height: 500,
	        width: 400,
	        modal: true,
	        show: "explode",
	        buttons: {
	            "Fermer": function() {
	                $( this ).dialog( "close" );
	            }}
        	});
 
        $( "#open-info" )
        .button()
        .click(function() {
            $( "#dialog-info" ).dialog( "open" );
        });
              
        
        $( "button" ).button();
        $( "input[type=submit]" ).button();
        $("#all_evt").click(function(event){
        	 event.preventDefault();
		     document.location.href='index.php?action=evt';
		  });
		  
		$( "#date_ev" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
		$( "#date" ).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd"
        });
        
        $('.bubu').button({
            icons: {
                primary: "ui-icon-search"
            },
            text: false
        });
        $('.bubu_del').button({
            icons: {
                primary: "ui-icon-trash"
            },
            text: false
        });
        $('.bubu_edit').button({
            icons: {
                primary: "ui-icon-contact"
            },
            text: false
        });
        
        $('.cal').button({
            icons: {
                primary: "ui-icon-calendar"
            }
        });
        $('.bubu_com').button({
            icons: {
                primary: "ui-icon-script"
            },
            text: false
        });
 		
});
</script>




<div id="liste" class="ui-widget">
	<?php
	if(isset($_GET['historique']))
		echo "<h1>Historique des Membres du bureau :</h1>";
	else 
		echo "<h1>Liste des évènements:</h1>";
    ?>
    <form method=GET action="index.php">
    	<input type="hidden" name="action" id="action" value="evt">
        <input type="text" name="date_ev" id="date_ev" value="<?php echo $this->date_ev; ?>" class="ui-widget-content ui-corner-all"/>
        <button class="cal">Filtrer par date</button>
        <button id="all_evt">Tous les évènements</button> 
    </form> 
	<button id="create-evt">Ajouter un nouvel évènement</button>
	<div id="evt-added" title="Status">
	    <p>L'évènement a bien été ajouté.</p>
	</div>
    <table id="users" class="ui-widget ui-widget-content">
        <thead class="ui-widget-header ">
				<th width="4em">Id</th>
				<th>Date</th>
				<th>Nb Participants</th>
				<th>Nb Participants Max.</th>
				<th>Lieu</th>
				<th>Infos</th>
        </thead>
        <tbody>
		<?php
			$result=Evenement::getListe();
			if(!($result))
			{
				echo "Erreur de requete : ".Database::errorMsg();
			}
			else
			{
				while($res = Database::fetch($result))
				{
					$evenement = new Evenement($res);
					if(empty($this->date_ev) || $evenement->date == $this->date_ev)
					{
						echo "<tr>";
						echo "<td>".$evenement->id."</td>";
						echo "<td>".$evenement->date."</td>";
						echo "<td>".$evenement->nbParticipants."</td>";
						echo "<td>".$evenement->nbParticipantsMax."</td>";
						echo "<td>".$evenement->lieu."</td>";
						echo "<td>
						<button title='Commentaires' class='bubu_com' onclick='javascript:open_comments(".$evenement->id.")'></button>
						<button title='Jeux utilisés' class='bubu' onclick='javascript:open_info(".$evenement->id.")'></button>
						<button title='Editer' class='bubu_edit' onclick='javascript:edit(".$evenement->id.")'></button>
						<button title='Supprimer' class='bubu_del' onclick='javascript:delete_evt(".$evenement->id.", \"".$evenement->id."\")'></button>
						</td>";
						echo "</tr>";
					}
				} 
			}
		?>
        </tbody>
    </table>
</div>

<div id="dialog-form" title="Ajouter un évènement">
    <p class="validateTips">Tous les champs sont requis.</p>
 
    <form>
    <fieldset>
        <label for="date">Date</label>
        <input type="text" name="date" id="date" class="text ui-widget-content ui-corner-all" />
        <label for="date">Lieu</label>
        <input type="text" id="lieu"  class="text ui-widget-content ui-corner-all"/>
        <label for="nb_part">Nombre de participants maximum</label>
        <input type="text" id="nb_part"  class="text ui-widget-content ui-corner-all"/>
        <label for="jeux">Jeux utilisés</label><br />
        <div id="jeuxliste">
        <?php
        $query = "SELECT * FROM JEU";
        Database::query($query);
		$results=Database::query($query);
		if(!($results))
		{
			echo "Erreur de requete : ".Database::errorMsg();
		}
		else
		{
			while($res = Database::fetch($results))
			{
				echo "<input type='checkbox' name='jeux[]' value='".$res['id_jeu']."'>".$res['nom_jeu']."<br />";
			} 
		}
        ?>
        </div>
        <input type="hidden" id="id_evt" name="id_evt" value="-1" />
    </fieldset>
    </form>
</div>

<div id="dialog-info" title="Informations sur un évènement">
   infos
</div>
		
		<?php
// -----------------------------------
// Fin Body
// -----------------------------------
	}
}
?>

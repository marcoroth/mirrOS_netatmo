<link rel="stylesheet" type="text/css" href="bower_components/jquery/dist/jquery-ui.min.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript" src="bower_components/jquery/dist/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/image-picker/0.3.0/image-picker.min.js" charset="utf-8"></script>

<?php

  $config = json_decode(getConfigValue("netatmo_config"), true);
  $url = "https://api.netatmo.com/api/getstationsdata";
  file_get_contents('http://'.getConfigValue('ip').'/modules/netatmo/assets/getNewAccessToken.php');

  $netatmo_url_fav = $url . "?" . "access_token" . "=" . getConfigValue('netatmo_access_token') . "&get_favorites=true";
  $result_fav = file_get_contents($netatmo_url_fav);
  $json_fav = json_decode($result_fav, true);

  $netatmo_url_own = $url . "?" . "access_token" . "=" . getConfigValue('netatmo_access_token') . "&get_favorites=false";
  $result_own = file_get_contents($netatmo_url_own);
  $json_own = json_decode($result_own, true);

  echo "<h5>Station</h5>";
  echo "<select name='station' id='station'>";
  echo "<option value='' >" . _("netatmo_own_stations") . "</option>";

  foreach ($json_own["body"]["devices"] as $key => $station) {
    if ($config["id"] == $station["_id"]) {
      echo "<option type='own' value='". $station["_id"]."' selected /> ". $station["station_name"]. "</option>";
    } else {
      echo "<option type='own' value='". $station["_id"]."'/> ". $station["station_name"]. "</option>";
    }
  }

  if (count($json_own["body"]["devices"]) == 0) {
    echo "<option value='' disabled>" . _("netatmo_no_own_stations") . "</option>";
  }

  echo "<option value='' disabled></option>";
  echo "<option value='' disabled>" . _("netatmo_public_stations") . "</option>";

  foreach ($json_fav["body"]["devices"] as $key => $station) {
    if (!in_array($station, $json_own["body"]["devices"])){
      if ($config["id"] == $station["_id"]) {
        echo "<option type='public' value='". $station["_id"]."' selected /> ". $station["station_name"]. "</option>";
      } else {
        echo "<option type='public' value='". $station["_id"]."'/> ". $station["station_name"]. "</option>";
      }
    }
  }

  if (count($json_fav["body"]["devices"]) == count($json_own["body"]["devices"])) {
    echo "<option value='' disabled>" . _("netatmo_no_public_stations") . "</option>";
  }
  echo "</select>";

  // Settings after selected station
  if (!empty($config["id"]) && $config["id"] != ""){

    echo "<h5>"._('netatmo_modules')."</h5>";

    // Selected Station in correct module Order
    if (count($config["modules"]) != 0) {
      echo "<div class='sortable' base='".$config["id"]."'>";
      echo '<ul id="sortable">';

      foreach ($config["modules"] as $key => $module) {
        echo '<li class="ui-state-default" type="'.$module["type"].'" value="'.$module["id"].'">
          <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
          '._("netatmo_".$module["type"]).' ('.$module["id"].')
        </li>';
      }

      echo "</ul><br>";
      echo "</div>";
    }

    // Not-Selected Station for module Order
    foreach ($json_fav["body"]["devices"] as $index => $base) {
      if ($base["_id"] != $config["id"] || count($config["modules"]) == 0){
        if ($base["_id"] != $config["id"]) {$style = "style='display: none'";} else { $style = "";}
        echo "<div class='sortable' base='".$base["_id"]."' ".$style.">";
        echo '<ul id="sortable">';

        if (array_key_exists('wifi_status', $base)){
          array_push($base["modules"], $base);
        }

        foreach ($base["modules"] as $key => $module) {
          echo '<li class="ui-state-default" type="'.$module["type"].'" value="'.$module["_id"].'">
            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
            '._("netatmo_".$module["type"]).' ('.$module["_id"].')
          </li>';
        }

        echo "</ul><br>";
        echo "</div>";
      }
    }

    // Icon-Selecter
    foreach ($json_fav["body"]["devices"] as $index => $base) {
      @$base_id = $base["_id"];
      if ($base_id == $config["id"]){  $style = ""; }
      else { $style = "style='display: none'"; }

      if (array_key_exists('wifi_status', $base)){
        array_push($base["modules"], $base);
      }

      foreach ($base["modules"] as $index => $module) {

        echo "<div id='".$module["type"]."' mac='".$module["_id"]."' base='".$base_id."' class='types' ".$style.">";
        echo '<h5>' . _('netatmo_select_'.$module["type"].'_icons') .' '.  @$module["module_name"]. '</h5>';
        echo '<select class="image-picker '.$module["type"].'-picker" mac="'.$module["_id"].'">';

        $base = "/var/www/html";
        $path = "/modules/netatmo/assets/icons/".$module["type"]."/";
        $dir = $base . $path;
        $files = preg_grep('/^([^.])/', scandir($dir));

        foreach ($files as $key => $value) {
          if (!is_dir($dir . '/' . $value)) {
            echo '<option data-img-src="'.$path.'/'.$value.'" data-img-alt="'.$value.'" value="'.$value.'">'.$value.'</option>';
          }
        }
        echo '</select>';
        echo "</div>";
      }
    }
    echo "<input type='hidden' class='reload' value='false'>";
  } else {
    echo "<input type='hidden' class='reload' value='true'>";
}
?>

<a href="/modules/netatmo/assets/resetConfig.php"><?php echo _("netatmo_reset_config"); ?></a><br /><br />

<div class="block__add" id="netatmo_save_step2">
	<button class="netatmo_save_step2--button" href="#">
		<span><?php echo _('save'); ?></span>
	</button>
</div>

<script>
	<?php include '_step2.js' ?>
</script>

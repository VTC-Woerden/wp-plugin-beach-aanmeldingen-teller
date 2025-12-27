<?php

/*
 * Plugin Name: Aanmeldingen Teller
 */
 

add_action('admin_menu', 'aanmeldingen_setup_menu');
 
function aanmeldingen_setup_menu(){
	add_menu_page('Aanmeldingen', 'Aanmeldingen', 'manage_options', 'aanmeldingen', 'renderAanmeldingen', 'dashicons-list-view');
}

function renderAanmeldingen() {

  global $wpdb;
    
  $zaterdag4x4 = $wpdb->get_results("
  SELECT
      r.id as registration_id,
      rf.value as teamnaam,
      voornaam.value as voornaam,
      achternaam.value as achternaam,
      teamsamenstelling.value as teamsamenstelling
  FROM `wp_mollie_forms_payments` as p 
      JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
      JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
      JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id 
      JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as teamsamenstelling ON teamsamenstelling.registration_id = r.id AND teamsamenstelling.field = 'Teamsamenstelling'
      LEFT JOIN `wp_mollie_forms_registration_fields` as voornaam ON voornaam.registration_id = r.id AND voornaam.field = 'Voornaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as achternaam ON achternaam.registration_id = r.id AND achternaam.field = 'Achternaam'
  WHERE rp.description = 'Zaterdagtoernooi (4x4)' 
      AND p.payment_mode <> 'test' 
      AND (p.payment_status = 'paid' OR p.payment_status = '') 
      AND YEAR(r.created_at) = YEAR(CURDATE())");

  $zondag4x4 = $wpdb->get_results("
  SELECT
      r.id as registration_id,
      rf.value as teamnaam,
      voornaam.value as voornaam,
      achternaam.value as achternaam,
      teamsamenstelling.value as teamsamenstelling
  FROM `wp_mollie_forms_payments` as p 
      JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
      JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
      JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id 
      JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as teamsamenstelling ON teamsamenstelling.registration_id = r.id AND teamsamenstelling.field = 'Teamsamenstelling'
      LEFT JOIN `wp_mollie_forms_registration_fields` as voornaam ON voornaam.registration_id = r.id AND voornaam.field = 'Voornaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as achternaam ON achternaam.registration_id = r.id AND achternaam.field = 'Achternaam'
  WHERE rp.description = 'Zondagtoernooi (4x4)' 
      AND p.payment_mode <> 'test' 
      AND (p.payment_status = 'paid' OR p.payment_status = '') 
      AND YEAR(r.created_at) = YEAR(CURDATE())");

  $king = $wpdb->get_results("
  SELECT
      r.id as registration_id,
      rf.value as teamnaam,
      voornaam.value as voornaam,
      achternaam.value as achternaam,
      teamsamenstelling.value as teamsamenstelling
  FROM `wp_mollie_forms_payments` as p 
      JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
      JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
      JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id
      JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as teamsamenstelling ON teamsamenstelling.registration_id = r.id AND teamsamenstelling.field = 'Teamsamenstelling'
      LEFT JOIN `wp_mollie_forms_registration_fields` as voornaam ON voornaam.registration_id = r.id AND voornaam.field = 'Voornaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as achternaam ON achternaam.registration_id = r.id AND achternaam.field = 'Achternaam'
  WHERE rp.description = 'King of the Court (zaterdag)' 
      AND p.payment_mode <> 'test' 
      AND (p.payment_status = 'paid' OR p.payment_status = '') 
      AND YEAR(r.created_at) = YEAR(CURDATE())");

  $bedrijven = $wpdb->get_results("
  SELECT
      r.id as registration_id,
      rf.value as teamnaam,
      bbq.quantity as bbq,
      munten.quantity as munten
  FROM `wp_mollie_forms_payments` as p 
      JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
      JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
      JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id
      JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
      LEFT JOIN `wp_mollie_forms_registration_price_options` as bbq ON bbq.registration_id = r.id AND bbq.description = 'Deelname BBQ (per persoon)'
      LEFT JOIN `wp_mollie_forms_registration_price_options` as munten ON munten.registration_id = r.id AND munten.description = 'Munten (per stuk)'
  WHERE rp.description like '%Deelname Bedrijventoernooi%' 
      AND p.payment_mode <> 'test' 
      AND (p.payment_status = 'paid' OR p.payment_status = '') 
      AND YEAR(r.created_at) = YEAR(CURDATE())");

  $submissions = Ninja_Forms()->form(4)->get_subs();
  // $submissions = [];
  $inschrijvingen = [];

  foreach($submissions as $submission) {
    array_push($inschrijvingen,$submission->get_field_values());
  }

  $jeugdTeams = [];
  $miniTeams = [];

  foreach ($inschrijvingen as $entry) {
    $team = $entry['team_1731618629217'] ?? '';

    if (str_starts_with($team, 'meisjes-') || str_starts_with($team, 'jongens-')) {
        $jeugdTeams[] = $entry;
    } elseif ($team === 'mini-s') {
        $miniTeams[] = $entry;
    }
  }

  function renderTable($data) {
  ?>
  <table class="widefat striped fixed">
    <thead>
      <tr>
        <th>Id</th>
        <th>Teamnaam</th>
      </tr>
    </thead>
      <?php foreach ($data as $row){ ?>
      <tr>
          <td><?php echo $row->registration_id ?></td>
          <td><?php echo $row->value ?></td>
      </tr>
      <?php } ?>
  </table>
  <?php
  }

  function renderTableBedrijven($data) {
  ?>
  <table class="widefat striped fixed">
    <thead>
      <tr>
        <th>#</th>
        <th>Teamnaam</th>
        <th>Bbq bonnen</th>
        <th>Aantal munten</th>
        <th>Id</th>
      </tr>
    </thead>
      <?php foreach ($data as $key => $value){ ?>
      <tr>
        <td><?php echo $key + 1 ?></td>
        <td><?php echo $value->teamnaam ?></td>
        <td><?php echo $value->bbq ?></td>
        <td><?php echo $value->munten ?></td>
        <td><?php echo $value->registration_id ?></td>
      </tr>
      <?php } ?>
  </table>
  <?php
  }

  function renderTableZaterdagZondag($data) {
  ?>
  <table class="widefat striped fixed">
      <thead>
        <tr>
          <th>#</th>
          <th>Teamnaam</th>
          <th>Voornaam</th>
          <th>Achternaam</th>
          <th>Teamsamenstelling</th>
          <th>Id</th>
        </tr>
      </thead>
          <?php foreach ($data as $key => $value) {
          ?>
          <tr>
            <td><?php echo $key + 1 ?></td>
            <td><?php echo $value->teamnaam ?></td>
            <td><?php echo $value->voornaam ?></td>
            <td><?php echo $value->achternaam ?></td>
            <td><?php echo $value->teamsamenstelling ?></td>
            <td><?php echo $value->registration_id ?></td>
          </tr>
          <?php } ?>
  </table>
  <?php
  }

  function renderTableNina($data) {
  ?>
  <table class="widefat striped fixed">
        <thead>
          <tr>
            <th>Voornaam</th>
            <th>Achgternaam</th>
            <th>Team</th>
          </tr>
        </thead>
          <?php foreach ($data as $row){ ?>
          <tr>
              <td><?php echo $row["voornaam_1731618363543"] ?></td>
              <td><?php echo $row["achternaam_1731618372881"] ?></td>
              <td><?php echo $row["team_1731618629217"] ?></td>
          </tr>
          <?php } ?>
  </table>
  <?php
  }

  ?>
  
  <link rel="stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" type="text/css" media="all" />
  
  <div class="custom-plugin-content" style="margin: 20px;">
      <h2>Zaterdag 4x4 (totaal <?= count($zaterdag4x4) ?>)</h2>
      <?= renderTableZaterdagZondag($zaterdag4x4) ?>
      <hr>
      
      <h2>Zondag 4x4 (totaal <?= count($zondag4x4) ?>)</h2>
      <?= renderTableZaterdagZondag($zondag4x4) ?>
      <hr>
      
      <h2>King of the Court (totaal <?= count($king) ?>)</h2>
      <?= renderTableZaterdagZondag($king) ?>
      <hr>
      
      <h2>Bedrijven (totaal <?= count($bedrijven) ?>)</h2>
      <?= renderTableBedrijven($bedrijven) ?>
      <hr>
  
      <h2>Jeugd (totaal <?= count($jeugdTeams) ?>)</h2>
      <?= renderTableNina($jeugdTeams) ?>
      <hr>
  
      <h2>Mini (totaal <?= count($miniTeams) ?>)</h2>
      <?= renderTableNina($miniTeams) ?>
      <hr>
  </div>
  
  <?php
  
  }

// teller template
// register_activation_hook(__FILE__, 'create_teller_page');
// register_deactivation_hook(__FILE__, 'disable_teller_page');
// add_filter('page_template', 'load_custom_teller_plugin_template');
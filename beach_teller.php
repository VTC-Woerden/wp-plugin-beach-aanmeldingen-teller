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
      teamsamenstelling.value as teamsamenstelling,
      rp.quantity as aantal_teams
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
      teamsamenstelling.value as teamsamenstelling,
      rp.quantity as aantal_teams
  FROM `wp_mollie_forms_payments` as p 
      JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
      JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
      JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id 
      JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as teamsamenstelling ON teamsamenstelling.registration_id = r.id AND teamsamenstelling.field = 'Teamsamenstelling'
      LEFT JOIN `wp_mollie_forms_registration_fields` as voornaam ON voornaam.registration_id = r.id AND voornaam.field = 'Voornaam'
      LEFT JOIN `wp_mollie_forms_registration_fields` as achternaam ON achternaam.registration_id = r.id AND achternaam.field = 'Achternaam'
  WHERE rp.description = 'Lazy sunday toernooi (4x4)' 
      AND p.payment_mode <> 'test' 
      AND (p.payment_status = 'paid' OR p.payment_status = '') 
      AND YEAR(r.created_at) = YEAR(CURDATE())");

    $zondag2x2 = $wpdb->get_results("
    SELECT
        r.id as registration_id,
        rf.value as teamnaam,
        voornaam.value as voornaam,
        achternaam.value as achternaam,
        teamsamenstelling.value as teamsamenstelling,
        rp.quantity as aantal_teams
    FROM `wp_mollie_forms_payments` as p 
        JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
        JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
        JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id 
        JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
        LEFT JOIN `wp_mollie_forms_registration_fields` as teamsamenstelling ON teamsamenstelling.registration_id = r.id AND teamsamenstelling.field = 'Teamsamenstelling'
        LEFT JOIN `wp_mollie_forms_registration_fields` as voornaam ON voornaam.registration_id = r.id AND voornaam.field = 'Voornaam'
        LEFT JOIN `wp_mollie_forms_registration_fields` as achternaam ON achternaam.registration_id = r.id AND achternaam.field = 'Achternaam'
    WHERE rp.description = 'Lazy sunday toernooi (2x2)' 
        AND p.payment_mode <> 'test' 
        AND (p.payment_status = 'paid' OR p.payment_status = '') 
        AND YEAR(r.created_at) = YEAR(CURDATE())");

  $king = $wpdb->get_results("
  SELECT
      r.id as registration_id,
      rf.value as teamnaam,
      voornaam.value as voornaam,
      achternaam.value as achternaam,
      teamsamenstelling.value as teamsamenstelling,
      rp.quantity as aantal_teams
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
      munten.quantity as munten,
      rp.quantity as aantal_teams
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

  $jeugdInschrijvingen = $wpdb->get_results("
    SELECT
      p.ID AS submission_id,
      p.post_date AS submitted_at,
      seq.meta_value AS visible_submission_number,
      form_id.meta_value AS form_id,

      MAX(CASE WHEN f.`key` = 'voornaam_1731618363543' THEN pm.meta_value END) AS voornaam,
      MAX(CASE WHEN f.`key` = 'achternaam_1731618372881' THEN pm.meta_value END) AS achternaam,
      MAX(CASE WHEN f.`key` = 'email' THEN pm.meta_value END) AS email,
      MAX(CASE WHEN f.`key` = 'team_1731618629217' THEN pm.meta_value END) AS team,
      MAX(CASE WHEN f.`key` = 'opmerkingen_1731618872761' THEN pm.meta_value END) AS opmerkingen,
      MAX(CASE WHEN f.`key` = 'categorie_1747165107337' THEN pm.meta_value END) AS categorie,
      MAX(CASE WHEN f.`key` = 'pannenkoeken_1747165394429' THEN pm.meta_value END) AS pannenkoeken

    FROM wp_posts p

    JOIN wp_postmeta form_id
      ON form_id.post_id = p.ID
    AND form_id.meta_key = '_form_id'

    LEFT JOIN wp_postmeta seq
      ON seq.post_id = p.ID
    AND seq.meta_key = '_seq_num'

    JOIN wp_postmeta pm
      ON pm.post_id = p.ID

    JOIN wp_nf3_fields f
      ON pm.meta_key = CONCAT('_field_', f.id)
    AND f.parent_id = form_id.meta_value

    WHERE p.post_type = 'nf_sub'
      AND form_id.meta_value = '4'
      AND YEAR(p.post_date) = YEAR(CURDATE())

    GROUP BY
      p.ID,
      p.post_date,
      seq.meta_value,
      form_id.meta_value

    ORDER BY p.ID DESC;
  ");

  $jeugdInschrijving = [];
  $miniInschrijving = [];

  var_dump($jeugdInschrijvingen);

  foreach ($jeugdInschrijvingen as $submission) {
    if (str_starts_with($submission->categorie, 'jeugd')) {
      $jeugdInschrijving[] = $submission;
    } elseif ($submission->categorie === 'mini-s') {
      $miniInschrijving[] = $submission;
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
        <th>Aantal teams</th>
        <th>Id</th>
      </tr>
    </thead>
      <?php foreach ($data as $key => $value){ ?>
      <tr>
        <td><?php echo $key + 1 ?></td>
        <td><?php echo $value->teamnaam ?></td>
        <td><?php echo $value->bbq ?></td>
        <td><?php echo $value->munten ?></td>
        <td><?php echo $value->aantal_teams ?></td>
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
          <th>Aantal teams</th>
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
            <td><?php echo $value->aantal_teams ?></td>
            <td><?php echo $value->registration_id ?></td>
          </tr>
          <?php } ?>
  </table>
  <?php
  }

  function renderTableNinaJeugd($data) {
  ?>
  <table class="widefat striped fixed">
        <thead>
          <tr>
            <th>Voornaam</th>
            <th>Achternaam</th>
            <th>Opmerkingen</th>
            <th>Team</th>
          </tr>
        </thead>
          <?php foreach ($data as $row){ ?>
          <tr>
              <td><?php echo $row->voornaam ?></td>
              <td><?php echo $row->achternaam ?></td>
              <td><?php echo $row->opmerkingen ?></td>
              <td><?php echo $row->team ?></td>
          </tr>
          <?php } ?>
  </table>
  <?php
  }

    function renderTableNinaMini($data) {
  ?>
  <table class="widefat striped fixed">
        <thead>
          <tr>
            <th>Voornaam</th>
            <th>Achternaam</th>
            <th>Opmerkingen</th>
            <th>Pannenkoeken</th>
            <th>Team</th>
          </tr>
        </thead>
          <?php foreach ($data as $row){ ?>
          <tr>
              <td><?php echo $row->voornaam ?></td>
              <td><?php echo $row->achternaam ?></td>
              <td><?php echo $row->opmerkingen ?></td>
              <td><?php echo $row->pannenkoeken ?></td>
              <td><?php echo $row->team ?></td>
          </tr>
          <?php } ?>
  </table>
  <?php
  }

  function countTeams($data) {
    $count = 0;
    foreach ($data as $row) {
        $count += $row->aantal_teams;
    }
    return $count;
  }

  ?>
  
  <link rel="stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" type="text/css" media="all" />
  
  <div class="custom-plugin-content" style="margin: 20px;">
      <h2>Zaterdag 4x4 (totaal <?= countTeams($zaterdag4x4) ?>)</h2>
      <?= renderTableZaterdagZondag($zaterdag4x4) ?>
      <hr>
      
      <h2>Zondag 4x4 (totaal <?= countTeams($zondag4x4) ?>)</h2>
      <?= renderTableZaterdagZondag($zondag4x4) ?>
      <hr>

      <h2>Zondag 2x2 (totaal <?= countTeams($zondag2x2) ?>)</h2>
      <?= renderTableZaterdagZondag($zondag2x2) ?>
      <hr>
      
      <h2>King of the Court (totaal <?= countTeams($king) ?>)</h2>
      <?= renderTableZaterdagZondag($king) ?>
      <hr>
      
      <h2>Bedrijven (totaal <?= countTeams($bedrijven) ?>)</h2>
      <?= renderTableBedrijven($bedrijven) ?>
      <hr>
  
      <h2>Jeugd (totaal <?= count($jeugdInschrijving) ?>)</h2>
      <?= renderTableNinaJeugd($jeugdInschrijving) ?>
      <hr>
  
      <h2>Mini (totaal <?= count($miniInschrijving) ?>)</h2>
      <?= renderTableNinaMini($miniInschrijving) ?>
      <hr>
  </div>
  
  <?php
  
  }

// teller template
// register_activation_hook(__FILE__, 'create_teller_page');
// register_deactivation_hook(__FILE__, 'disable_teller_page');
// add_filter('page_template', 'load_custom_teller_plugin_template');
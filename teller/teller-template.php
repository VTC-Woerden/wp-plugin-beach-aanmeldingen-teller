<?php
/**
 * Template Name: Aanmeldingen page
 */

global $wpdb;

$zaterdag4x4 = $wpdb->get_results("
    SELECT * FROM `wp_mollie_forms_payments` as p 
        JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
        JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
        JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id 
        JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
    WHERE rp.description = 'Zaterdagtoernooi (4x4)' 
        AND p.payment_mode <> 'test' 
        AND (p.payment_status = 'paid' OR p.payment_status = '') 
        AND YEAR(r.created_at) = YEAR(CURDATE())");

$zondag4x4 = $wpdb->get_results("
    SELECT * FROM `wp_mollie_forms_payments` as p 
        JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
        JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
        JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id
        JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
    WHERE rp.description = 'Zondagtoernooi (4x4)' 
        AND p.payment_mode <> 'test' 
        AND (p.payment_status = 'paid' OR p.payment_status = '') 
        AND YEAR(r.created_at) = YEAR(CURDATE())");

$king = $wpdb->get_results("
    SELECT * FROM `wp_mollie_forms_payments` as p 
        JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
        JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
        JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id
        JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
    WHERE rp.description = 'King of the Court (zaterdag)' 
        AND p.payment_mode <> 'test' 
        AND (p.payment_status = 'paid' OR p.payment_status = '') 
        AND YEAR(r.created_at) = YEAR(CURDATE())");

$bedrijven = $wpdb->get_results("
    SELECT * FROM `wp_mollie_forms_payments` as p 
        JOIN `wp_mollie_forms_registrations` as r ON p.registration_id = r.id 
        JOIN `wp_mollie_forms_registration_price_options` as rp ON rp.registration_id = r.id 
        JOIN `wp_mollie_forms_customers` as c ON r.customer_id = c.customer_id
        JOIN `wp_mollie_forms_registration_fields` as rf ON rf.registration_id = r.id AND rf.field = 'Teamnaam'
    WHERE rp.description like '%Deelname Bedrijventoernooi%' 
        AND p.payment_mode <> 'test' 
        AND (p.payment_status = 'paid' OR p.payment_status = '') 
        AND YEAR(r.created_at) = YEAR(CURDATE())");

$submissions = Ninja_Forms()->form(4)->get_subs();

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
    } elseif ($team === 'mini-s' || $team === 'sportspeeltuin') {
        $miniTeams[] = $entry;
    }
}

function renderTable($data) {
    ?>
    <table>
            <tr>
                <th>Registration_id</th>
                <th>Teamnaam</th>
            </tr>
            <?php foreach ($data as $row){ ?>
            <tr>
                <td><?php echo $row->registration_id ?></td>
                <td><?php echo $row->value ?></td>
            </tr>
            <?php } ?>
    </table>
    <?php
}

function renderTableNina($data) {
    ?>
    <table>
            <tr>
                <th>Naam</th>
                <th>Team</th>
            </tr>
            <?php foreach ($data as $row){ ?>
            <tr>
                <td><?php echo $row["voornaam_1731618363543"] ?></td>
                <td><?php echo $row["team_1731618629217"] ?></td>
            </tr>
            <?php } ?>
    </table>
    <?php
}

?>

<link rel="stylesheet" href="<?php echo plugins_url('style.css', __FILE__); ?>" type="text/css" media="all" />

<div class="custom-plugin-content">
    <h2>Zaterdag 4x4 (totaal <?= count($zaterdag4x4) ?>)</h2>
    <?= renderTable($zaterdag4x4) ?>
    <hr>
    
    <h2>Zondag 4x4 (totaal <?= count($zondag4x4) ?>)</h2>
    <?= renderTable($zondag4x4) ?>
    <hr>
    
    <h2>King of the Court (totaal <?= count($king) ?>)</h2>
    <?= renderTable($king) ?>
    <hr>
    
    <h2>Bedrijven (totaal <?= count($bedrijven) ?>)</h2>
    <?= renderTable($bedrijven) ?>
    <hr>

    <h2>Jeugd (totaal <?= count($jeugdTeams) ?>)</h2>
    <?= renderTableNina($jeugdTeams) ?>
    <hr>

    <h2>Mini (totaal <?= count($miniTeams) ?>)</h2>
    <?= renderTableNina($miniTeams) ?>
    <hr>
</div>
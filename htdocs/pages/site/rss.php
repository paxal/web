<?php
/**
 * Fichier Feed RSS site 'AFUP'
 * 
 * @author    Perrick Penet   <perrick@noparking.fr>
 * @author    Olivier Hoareau <olivier@phppro.fr>
 * @copyright 2010 Association Française des Utilisateurs de PHP
 * 
 * @category AFUP
 * @package  AFUP
 * @group    Pages
 */

require_once dirname(__FILE__).'/../../../sources/Afup/Bootstrap/Http.php';
require_once dirname(__FILE__).'/../../../sources/Afup/AFUP_Site.php';

$articles         = new AFUP_Site_Articles($bdd);
$derniersArticles = array();

foreach($articles->chargerDerniersAjouts(20) as $article) {
	$derniersArticles[] = array(
    	'titre'   => $article->titre,
    	'contenu' => $article->contenu,
    	'url'     => $article->route ,
    	'maj'     => date(DATE_RSS,$article->date),
    );
}

$feed = array(
	'title'         => "Le flux RSS de l'AFUP",
	'url'           => 'http://afup.org/',
	'link'          => 'http://afup.org/rss.php',
	'email'         => 'bureau@afup.org',
	'author'        => 'Nicolas Silberman / AFUP',
	'date'          => date(DATE_RSS),
	'lastBuildDate' => 
        isset($derniersArticles[0]['maj']) ?
              $derniersArticles[0]['maj']
            : date('Y-m-d H:i:s', time()),
);

$smarty->assign('billets', $derniersArticles);
$smarty->assign('feed',    $feed);

header('Content-Type: text/xml; charset=UTF-8');
$smarty->display('rss.xml');